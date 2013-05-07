<?php
namespace Application\Model;

use Zend\Mail\Transport\SmtpOptions;

use Zend\Mail\Transport\Smtp;

use Zend\Mail\Message;

class Email extends Message
{
	public function __construct($model)
	{
		$this->addFrom("no-reply@befasty.com","Instatrading")
			->setEncoding('UTF-8');
	}
	
	public function send()
	{
		if ($this->isValid())
		{
			$transport = new Smtp();
			return $transport->setOptions(new SmtpOptions(array(
				'host' => 'in.mailjet.com',
				'port' => '587',
				'connection_class' => 'login',
				'connection_config' => array(
					'username' => '9c317ebb7129193e21d80bac6ad3621c',
					'password' => 'cb0d42f79ed926b5fee2ad244c754fd4',
					'ssl' => 'tls'
				)
			)))->send($this);
		}	
	}
}