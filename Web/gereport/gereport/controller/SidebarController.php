<?php

namespace gereport\controller;

__import('controller/controller');
__import('transaction/GetProjectsTransaction');
__import('view/SidebarView');

use gereport\transaction\GetProjectsTransaction;
use gereport\view\SidebarView;

class SidebarController extends Controller
{
	/**
	 * @var SidebarView
	 */
	private $sidebarView;

	public function __construct($sidebarView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->sidebarView = $sidebarView;
	}

	public function process()
	{
		$tx = new GetProjectsTransaction($this->toolbox->database);
		$tx->execute();

		foreach ($tx->getProjects() as $project)
		{
			$this->sidebarView->addProject($project);
		}

		return $this->sidebarView;
	}
}
