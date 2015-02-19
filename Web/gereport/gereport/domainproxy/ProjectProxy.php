<?php

namespace gereport\domainproxy;

__import('domain/Project');
__import('domainproxy/Proxy');

use gereport\domain\Project;

class ProjectProxy extends Proxy implements Project
{
	public function __construct($id, $database)
	{
		parent::__construct($id, $database);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->database->findProject($this->id)['name'];
	}
}
