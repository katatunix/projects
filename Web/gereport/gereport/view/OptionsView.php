<?php

namespace gereport\view;

__import('view/View');

class OptionsView extends View
{
	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);
	}

	public function show()
	{
		require $this->htmlDir . 'OptionsHtml.php';
	}
}
