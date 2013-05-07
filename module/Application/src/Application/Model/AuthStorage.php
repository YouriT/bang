<?php

namespace Application\Model;

use Zend\Authentication\Storage\Session;

/**
 * Authenticator.
 *
 * @author Youri
 */
class AuthStorage extends Session
{
	/**
	 * Remember me.
	 *
	 * @param boolean $rememberMe	RememberMe
	 * @param integer $time	Time to retain auth
	 *
	 */
	public function setRememberMe($rememberMe = 0, $time = 1209600)
	{
		if ($rememberMe == 1)
		{
			$this->session->getManager()->rememberMe($time);
		}
	}
	
	/**
	 * Forgot auth
	 */
	public function forgotMe()
	{
		$this->session->getManager()->forgetMe();
	}
}