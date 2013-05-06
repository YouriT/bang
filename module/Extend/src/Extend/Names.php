<?php
namespace Extend;

use Zend\Mvc\Router\RouteMatch;

use Zend\View\Helper\AbstractHelper;

class Names extends AbstractHelper
{
	private $router;
	
	public function setRouter(RouteMatch $r)
	{
		$this->router = $r;
	}
	
	public function __invoke($type)
	{
		return $this->router->getParam($type);
	}
}