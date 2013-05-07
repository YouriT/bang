<?php
namespace Extend;

class Utilities
{
	public static function generateHash()
	{
		$id = uniqid('profile_', true);
	}
}