<?php

namespace gereport\view;

__import('request/Request');

use gereport\request\Request;

abstract class View
{
	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var UrlSource
	 */
	protected $urlSource;

	protected $htmlDir;

	protected $title;

	public function __construct($request, $urlSource, $htmlDir)
	{
		$this->request = $request;
		$this->urlSource = $urlSource;
		$this->htmlDir = $htmlDir;
	}

	public function isPostMethod()
	{
		return $this->request->isPostMethod();
	}

	public function setTitle($val)
	{
		$this->title = $val;
	}

	public abstract function show();
}
