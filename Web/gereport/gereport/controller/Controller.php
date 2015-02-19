<?php

namespace gereport\controller;

/**
 * A controller gets inputs from a view, executes transactions,
 * and then return either the same view or another view stored outputs.
 * In some cases, a controller might redirect to another URL by using the $redirector.
 */

use gereport\view\View;

abstract class Controller
{
	/**
	 * @var Toolbox
	 */
	protected $toolbox;

	public function __construct($toolbox)
	{
		$this->toolbox = $toolbox;
	}

	/**
	 * @return View
	 */
	public abstract function process();
}
