<?php

namespace gereport\view;

__import('view/View');

class MainLayoutView extends View
{
	private $bannerView;
	private $footerView;
	private $sidebarView;
	private $contentView;

	public function __construct($request, $urlSource, $htmlDir)
	{
		parent::__construct($request, $urlSource, $htmlDir);
	}

	public function setBannerView($view)
	{
		$this->bannerView = $view;
		return $this;
	}

	public function setFooterView($view)
	{
		$this->footerView = $view;
		return $this;
	}

	public function setSidebarView($view)
	{
		$this->sidebarView = $view;
		return $this;
	}

	public function setContentView($view)
	{
		$this->contentView = $view;
		return $this;
	}

	public function show()
	{
		require $this->htmlDir . 'MainLayoutHtml.php';
	}
}
