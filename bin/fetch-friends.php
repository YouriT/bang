<?php
use Application\Entity\Picture;

use Application\Entity\User;

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Application;

ini_set('display_errors', true);
chdir(__DIR__);

$previousDir = '.';

while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());

    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php": ' .
            'is DoctrineModule in a subdir of your application skeleton?'
        );
    }

    $previousDir = $dir;
    chdir($dir);
}

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

$application = Application::init(include 'config/application.config.php');
/* @var $em \Doctrine\ORM\EntityManager */
$em = $application->getServiceManager()->get('doctrine.entitymanager.orm_default');

$users = $em->getRepository('Application\Entity\User')->friendsToFetch();
$config = $application->getServiceManager()->get('ApplicationConfig');
/* @var $u \Application\Entity\User */
foreach ($users as $u)
{
	$fb = new \Facebook($config['facebook']);
	$fb->setAccessToken($u->getFbToken());
	
	$res = $fb->api('/'.$u->getFbKey().'?fields=friends.limit(10).fields(picture.width(1280),name,username,birthday,gender)');
	
	if (!isset($res['friends']['data']))
		return;
	
	$fbKeys = array();
	foreach ($res['friends']['data'] as $v)
	{
		if ($em->getRepository('Application\Entity\User')->findOneBy(array('fbKey'=>$v['id'])) == null)
		{
			$user = new User();
	    	$em->persist($user);
	    	$user->setRole('unregistered')
	    		->setNickname($v['username'])
	    		->setFullName($v['name'])
	    		->setFbKey($v['id']);
	    	$u->addFriend($user);
	    	
	    	if (isset($v['gender']))
	    		$v['gender'] == 'male' ? $user->setGender(User::GENDER_MALE) : $user->setGender(User::GENDER_FEMALE);
	    	else
	    		$user->setGender(User::GENDER_UNKNOWN);
	    	
	    	if (isset($v['birthday']))
	    		$user->setBirthDate(new \DateTime($v['birthday']));
	    	
	    	// Profile picture
	    	if (isset($v['picture']['data']) && !$v['picture']['data']['is_silhouette'])
	    	{
	    		$pName = uniqid('profile_', true);
	    		$picExtension = explode('.', $v['picture']['data']['url']);
	    		$picExtension = $picExtension[count($picExtension)-1];
	    		file_put_contents('public/pictures/'.$pName.'.'.$picExtension, file_get_contents($v['picture']['data']['url']));
		    	$pic = new Picture();
		    	$em->persist($pic);
		    	$pic->setName($pName.'.'.$picExtension)
		    		->setUser($user);
	    	}
		}
	}
	$u->setLastFbUpdate(new \DateTime());
	$em->flush();
}