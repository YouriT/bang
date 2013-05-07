<?php
namespace Application\Controller;

use Application\Entity\Picture;

use Application\Entity\User;

use Extend\Action;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AccountController extends Action
{
	/**
	 * @var \Zend\Authentication\AuthenticationService
	 */
	protected $authService;
	protected $storage;
	/**
	 * @var \Facebook
	 */
	private $facebook;
	
	public function getAuthService()
	{
		if (!$this->authService)
			$this->authService = $this->getServiceLocator()->get('AuthService');
	
		return $this->authService;
	}
	
	public function getSessionStorage()
	{
		if (!$this->storage)
			$this->storage = $this->getServiceLocator()->get('AuthStorage');
	
		return $this->storage;
	}
	
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function facebookAction()
    {
    	$config = $this->getServiceLocator()->get('ApplicationConfig');
		$this->facebook = new \Facebook($config['facebook']);
		$user = $this->facebook->getUser();
		if ($user)
		{
			try {
				$user_profile = $this->facebook->api('/me');
				$uEntity = $this->getEntityManager()->getRepository('Application\Entity\User')->findOneBy(array(
					'fbKey' => $user
				));
				if ($uEntity == null || $uEntity->getRole() == 'unregistered')
					// Let's register
					$this->register($uEntity);
				else
				{
					// Let's ident
					$this->getAuthService()->getAdapter()->setIdentityValue($user)->setCredentialValue('');
					$result = $this->getAuthService()->authenticate();
					if ($result->isValid())
					{
						// User is identified
						$result->getIdentity()->setLastLogin(new \DateTime())
							->setFbToken($this->facebook->getAccessToken());
						$this->getEntityManager()->flush();
						$this->getAuthService()->getStorage()->write($result->getIdentity());
						$this->redirect()->toRoute('account');
					}
				}
			} catch (\FacebookApiException $e) {
				$user = null;
				// Une erreur s'est produite !!!
				exit;
			}
		}

		if (!$user)
			$this->redirect()->toUrl(
					$this->facebook->getLoginUrl($params = array('redirect_uri' => 'http://bang.brantrip.com/login'))
			);
    	return false;
    }
    
    public function logoutAction()
    {
    	$this->getSessionStorage()->clear();
    	return false;
    }
    
    private function register($entity)
    {
    	$fbProfile = $this->facebook->api('/me?fields=id,name,username,email,locale,gender,birthday,picture.width(1280)');

    	if ($entity == null)
    	{
	    	$user = new User();
	    	$this->getEntityManager()->persist($user);
    	}
    	else
    		$user = $entity;
    	$user->setRole('user')
    		->setNickname($fbProfile['username'])
    		->setFullName($fbProfile['name'])
    		->setEmail($fbProfile['email'])
    		->setFbKey($this->facebook->getUser())
    		->setLocale($fbProfile['locale'])
    		->setFbToken($this->facebook->getAccessToken());
    	
    	if (isset($fbProfile['gender']))
    		$fbProfile['gender'] == 'male' ? $user->setGender(User::GENDER_MALE) : $user->setGender(User::GENDER_FEMALE);
    	else
    		$user->setGender(User::GENDER_UNKNOWN);
    	
    	if (isset($fbProfile['birthday']))
    		$user->setBirthDate(new \DateTime($fbProfile['birthday']));
    	
    	// Profile picture
    	if (isset($fbProfile['picture']['data']) && !$fbProfile['picture']['data']['is_silhouette'] && $entity == null)
    	{
    		$pName = uniqid('profile_', true);
    		$picExtension = explode('.', $fbProfile['picture']['data']['url']);
    		$picExtension = $picExtension[count($picExtension)-1];
    		file_put_contents('public/pictures/'.$pName.'.'.$picExtension, file_get_contents($fbProfile['picture']['data']['url']));
	    	$pic = new Picture();
	    	$this->getEntityManager()->persist($pic);
	    	$pic->setName($pName.'.'.$picExtension)
	    		->setUser($user);
    	}
    	
    	$this->getEntityManager()->flush();
    	
    	$this->redirect()->toRoute('login');
    }
}
