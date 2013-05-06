<?php
namespace Application\Controller;

use Extend\Action;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends Action
{
    public function indexAction()
    {
    	$res = $this->getEntityManager()->getRepository('Application\Entity\User')->findAll();
    	foreach ($res as $r)
    	{
    		echo $r->getNickName();
    	}
        return new ViewModel();
    }
}
