<?php

namespace gereport\view;

__import('view/View');

class SidebarView extends View
{
	private $projects = array();

	public function show()
	{
		require $this->htmlDir . 'SidebarHtml.php';
	}

	public function addProject($project)
	{
		$this->projects[] = $project;
	}
}
