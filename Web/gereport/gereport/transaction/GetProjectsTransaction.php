<?php

namespace gereport\transaction;

__import('transaction/Transaction');
__import('domainproxy/ProjectProxy');

use gereport\domainproxy\ProjectProxy;

class GetProjectsTransaction extends Transaction
{
	private $projects;

	public function __construct($database)
	{
		parent::__construct($database);
	}

	public function execute()
	{
		$this->projects = array();
		foreach ($this->database->findProjects() as $projectId)
		{
			$project = new ProjectProxy($projectId, $this->database);
			$this->projects[] = array('id' => $projectId, 'name' => $project->getName());
		}
	}

	public function getProjects()
	{
		return $this->projects;
	}
}
