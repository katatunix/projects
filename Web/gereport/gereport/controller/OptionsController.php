<?php

namespace gereport\controller;

use gereport\view\Error403View;
use gereport\view\OptionsView;

__import('controller/controller');

class OptionsController extends Controller
{
	/**
	 * @var OptionsView
	 */
	private $optionsView;

	public function __construct($optionsView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->optionsView = $optionsView;
	}

	public function process()
	{
		if (!$this->toolbox->session->isLogged())
		{
			return new Error403View($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		}
		$this->optionsView->setTitle('Options');
		return $this->optionsView;
	}
}
