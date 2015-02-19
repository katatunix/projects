<?php

namespace gereport\view;

class Error403View extends View
{
	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);
		$this->setTitle('Error 403');
	}

	public function show()
	{
		require $this->htmlDir . 'Error403Html.php';
	}
}
