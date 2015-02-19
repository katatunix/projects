<?php

namespace gereport\controller;

__import('controller/controller');
__import('transaction/GetUsernameTransaction');

use gereport\view\BannerView;
use gereport\transaction\GetUsernameTransaction;

class BannerController extends Controller
{
	/**
	 * @var BannerView
	 */
	private $bannerView;

	public function __construct($bannerView, $toolbox)
	{
		parent::__construct($toolbox);
		$this->bannerView = $bannerView;
	}

	public function process()
	{
		if ($this->toolbox->session->isLogged())
		{
			$tx = new GetUsernameTransaction($this->toolbox->session->getLoggedMemberId(), $this->toolbox->database);
			try
			{
				$tx->execute();
			}
			catch (\Exception $ex)
			{
				// WTF: logged, but the member id is not found in database??? ===> Logout
				$this->toolbox->redirector->toLogout();
			}
			$this->bannerView->setUsername($tx->getMemberUsername());
		}
		return $this->bannerView;
	}
}
