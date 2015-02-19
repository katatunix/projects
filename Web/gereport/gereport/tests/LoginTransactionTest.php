<?php

namespace gereport\tests;

__import('transaction/LoginTransaction');
__import('database/MockDatabase');

use gereport\database\MockDatabase;
use gereport\transaction\LoginTransaction;

class LoginTransactionTest extends \PHPUnit_Framework_TestCase
{
	public function testSuccess()
	{
		$database = new MockDatabase();
		$transaction = new LoginTransaction('nghia.buivan', '1234567', $database);
		$transaction->execute();
		$this->assertTrue($transaction->getLoggedMemberId() > 0);
	}

	public function testFailedUsername()
	{
		$database = new MockDatabase();
		$transaction = new LoginTransaction('katatunix', '1234567', $database);
		$transaction->execute();
		$this->assertFalse($transaction->getLoggedMemberId() > 0);
	}

	public function testFailedPassword()
	{
		$database = new MockDatabase();
		$transaction = new LoginTransaction('nghia.buivan', '12345678', $database);
		$transaction->execute();
		$this->assertFalse($transaction->getLoggedMemberId() > 0);
	}

}
