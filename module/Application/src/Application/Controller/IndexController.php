<?php
namespace Application\Controller;

use Extend\Action;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends Action
{
    public function indexAction()
    {
        return new ViewModel();
    }
}
