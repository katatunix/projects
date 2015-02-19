<?php

namespace gereport\controller;

use gereport\database\Database;
use gereport\request\Request;
use gereport\session\Session;
use gereport\view\UrlSource;

class Toolbox
{
	/**
	 * @var Database
	 */
	public $database;
	/**
	 * @var Session
	 */
	public $session;
	/**
	 * @var Redirector
	 */
	public $redirector;
	/**
	 * @var Request
	 */
	public $request;
	/**
	 * @var UrlSource
	 */
	public $urlSource;
	/**
	 * @var string
	 */
	public $htmlDir;
}
