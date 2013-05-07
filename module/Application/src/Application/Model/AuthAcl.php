<?php
namespace Application\Model;

use Zend\Mvc\MvcEvent;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
     
class AuthAcl extends AbstractPlugin
{
    protected $role;
 
    private function getRole()
    {
        if (!$this->role) {
        	if ($this->getController()->getServiceLocator()->get('AuthService')->hasIdentity())
	            $this->role = $this->getController()->getServiceLocator()->get('AuthService')->getIdentity()->getRole();
        	else
        		$this->role = 'guest';
        }
        return $this->role;
    }
     
    public function doAuthorization(MvcEvent $e)
    {
        $acl = new Acl();
        
        $acl->addRole(new Role('guest'));
        $acl->addRole(new Role('user'),  'guest');
        $acl->addRole(new Role('admin'), 'user');
         
        $acl->addResource(new Resource('application'));
        $acl->addResource(new Resource('account'));
        $acl->addResource(new Resource('deal'));
        $acl->addResource(new Resource('payment'));
         
        $acl->allow('guest', 'application', null);
//         $acl->allow('guest', 'academy', null);
//         $acl->allow('guest', 'deal', 'index');
//         $acl->allow('guest', 'payment', null);
//         $acl->allow('user', 'deal', null);
        
//         $acl->allow('anonymous', 'Login', 'view');
         
//         $acl->allow('user',
//             array('Application'),
//             array('view')
//         );
         
        //admin is child of user, can publish, edit, and view too !
//         $acl->allow('admin',
//             array('Application'),
//             array('publish', 'edit')
//         );
         
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = strtolower(substr($controllerClass, 0, strpos($controllerClass, '\\')));
        $action = strtolower($e->getRouteMatch()->getParam('action'));
        
        if (!$acl->isAllowed($this->getRole(), $namespace, $action)){
            $router = $e->getRouter();
            $url    = $router->assemble(array(), array('name' => 'account'));
         
            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            /* change with header('location: '.$url); if code below not working */
            header('location: '.$url);
            $e->stopPropagation();            
        }
    }
}