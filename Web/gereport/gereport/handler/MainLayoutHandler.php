<?php

namespace gereport\handler;

__import('view/BannerView');
__import('view/FooterView');
__import('view/SidebarView');
__import('view/MainLayoutView');

__import('controller/BannerController');
__import('controller/FooterController');
__import('controller/SidebarController');

use gereport\controller\BannerController;
use gereport\controller\FooterController;
use gereport\controller\SidebarController;

use gereport\view\View;
use gereport\view\BannerView;
use gereport\view\FooterView;
use gereport\view\SidebarView;
use gereport\view\MainLayoutView;

abstract class MainLayoutHandler extends Handler
{
	public function handle()
	{
		$bannerView = new BannerView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		$bannerView = (new BannerController($bannerView, $this->toolbox))->process();

		$footerView = new FooterView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		$footerView = (new FooterController($footerView, $this->toolbox))->process();

		$sidebarView = new SidebarView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir);
		$sidebarView = (new SidebarController($sidebarView, $this->toolbox))->process();

		(new MainLayoutView($this->toolbox->request, $this->toolbox->urlSource, $this->toolbox->htmlDir))
			->setBannerView($bannerView)
			->setFooterView($footerView)
			->setSidebarView($sidebarView)
			->setContentView($this->getContentView())
			->show();
	}

	/**
	 * @return View
	 */
	public abstract function getContentView();
}
