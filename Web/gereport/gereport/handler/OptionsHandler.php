<?php

namespace gereport\handler;

__import('handler/MainLayoutHandler');
__import('controller/OptionsController');
__import('view/OptionsView');
__import('view/Error403View');

use gereport\controller\OptionsController;
use gereport\view\Error403View;
use gereport\view\OptionsView;

class OptionsHandler extends MainLayoutHandler
{
	public function getContentView()
	{
		$optionsView = new OptionsView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		return (new OptionsController($optionsView, $this->toolbox))->process();

	}
}
