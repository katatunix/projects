<?php

namespace gereport\controller;

__import('controller/controller');

class FooterController extends Controller
{
	private $footerView;

	public function __construct($footerView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->footerView = $footerView;
	}

	public function process()
	{
		return $this->footerView;
	}
}
