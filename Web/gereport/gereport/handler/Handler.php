<?php

namespace gereport\handler;

use gereport\controller\Toolbox;

abstract class Handler
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
	 * @return void
	 */
	public abstract function handle();
}
