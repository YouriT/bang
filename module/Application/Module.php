<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Entity\User;

use Application\Model\AuthStorage;

use Zend\Authentication\AuthenticationService;

use DoctrineModule\Authentication\Adapter\ObjectRepository;

use Extend\Names;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('names', function($sm) use ($e) {
        	$v = new Names();
        	$v->setRouter($e->getRouteMatch());
        	return $v;
        });
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
    }
	
	public function loadConfiguration(MvcEvent $e)
	{
		$application   = $e->getApplication();
		$sm            = $application->getServiceManager();
		$sharedManager = $application->getEventManager()->getSharedManager();
		 
		$router = $sm->get('router');
		$request = $sm->get('request');
		 
		$matchedRoute = $router->match($request);
		if (null !== $matchedRoute) {
			$sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
				function($e) use ($sm) {
					$sm->get('ControllerPluginManager')->get('AuthAcl')
						->doAuthorization($e);
				},2
			);
		}
	}

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
			'factories' => array(
				'AuthStorage' => function ($sm) {
					return new AuthStorage('Bang');
				},
				'AuthService' => function ($sm) {
					$this->em = $sm->get('doctrine.entitymanager.orm_default');
					$authAdapter = new ObjectRepository(array(
						'objectManager' => $this->em,
						'objectRepository' => $this->em->getRepository('Application\Entity\User'),
						'identityClass' => 'Application\Entity\User',
						'identityProperty' => 'fbKey',
						'credentialProperty' => 'fbKey',
						'credentialCallable' => function ($identity, $cred)
						{
							//var_dump($identity,$cred);
							return $identity->getFbKey();
						}
					));
					$authService = new AuthenticationService();
					$authService->setAdapter($authAdapter)->setStorage($sm->get('AuthStorage'));
					return $authService;
				}
			)
		);
    }
}
