<?php
namespace Extend;

use Zend\Http\Request;

use Zend\View\Helper\AbstractHelper;

class XmlHttpRequest extends AbstractHelper
{
	private $isXmlHttpRequest;
	
	public function setXml(Request $req)
	{
		$this->isXmlHttpRequest = $req->isXmlHttpRequest();
	}
	
	public function __invoke()
	{
		return $this->isXmlHttpRequest;
	}
}