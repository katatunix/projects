<?php

namespace gereport\domainproxy;

__import('database/Database');

use gereport\database\Database;

abstract class Proxy
{
	protected  $id;

	/**
	 * @var Database
	 */
	protected  $database;

	public function __construct($id, $database)
	{
		$this->id = $id;
		$this->database = $database;
	}
}
