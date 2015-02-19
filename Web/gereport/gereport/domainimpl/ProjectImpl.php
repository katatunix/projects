<?php

namespace gereport\domainimpl;

__import('domain/Project');

use gereport\domain\Project;

class ProjectImpl implements Project
{
	private $id;
	private $name;

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}
}
