<?php

namespace gereport\session;

class Session
{
	const NO_MEMBER_ID = 0;

	private $key;

	public function __construct($key)
	{
		$this->key = $key;
	}

	public function isLogged()
	{
		return $this->getLoggedMemberId() > self::NO_MEMBER_ID;
	}

	public function getLoggedMemberId()
	{
		if (!isset($_SESSION[$this->key])) return self::NO_MEMBER_ID;
		$id = $_SESSION[$this->key];
		return $id ? $id : self::NO_MEMBER_ID;
	}

	public function setLoggedMemberId($memberId)
	{
		$_SESSION[$this->key] = $memberId;
	}

	public function clearLogged()
	{
		unset($_SESSION[$this->key]);
	}
}
