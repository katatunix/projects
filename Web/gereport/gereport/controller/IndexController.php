<?php

namespace gereport\controller;

use gereport\view\IndexView;

__import('controller/controller');

class IndexController extends Controller
{
	/**
	 * @var IndexView
	 */
	private $indexView;

	public function __construct($indexView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->indexView = $indexView;
	}

	public function process()
	{
		$this->indexView->setTitle('Welcome');
		return $this->indexView;
	}
}
