<?php

namespace gereport\transaction;

use gereport\database\Database;

abstract class Transaction
{
	/**
	 * @var Database
	 */
	protected $database;

	public function __construct($database)
	{
		$this->database = $database;
	}

	public abstract function  execute();
}
