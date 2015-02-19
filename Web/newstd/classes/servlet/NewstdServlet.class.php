<?php

__import('servlet/AbstractServlet');
__import('facade/BasicFacade');
__import('facade/AccountFacade');

class NewstdServlet extends AbstractServlet
{

	public function checkPermission($action)
	{
		$logged = WebUtils::getSESSION(__SKEY) ? true : false;
		switch ($action)
		{
			case 'index' :
			case 'login' :
			case 'logout' :
				return true;

			case 'faq' :

			case 'preEnrolment' :
			case 'enrolment' :
			case 'payYourFees' :
			case 'accommodation' :

			case 'getYourID' :
			case 'getConnected' :
				return $logged;
		}
		return false;
	}

	public function index()
	{
		if (!$this->loggedAccount)
		{
			WebUtils::redirect(__SITE_CONTEXT . '/newstd/login');
			return;
		}

		$this->ui->pageTitle = 'Congrat. and Welcome to UoG';
		$this->ui->pageContent = 'NewstdIndexUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function login()
	{
		if ($this->loggedAccount)
		{
			WebUtils::redirect(__SITE_CONTEXT . '/newstd/index');
			return;
		}

		if (WebUtils::isPOST())
		{
			$username = WebUtils::obtainPOST('username');
			$password = WebUtils::obtainPOST('password');

			BasicFacade::instance()->startTrans();
			$p = AccountFacade::instance()->checkLogin($username, $password);
			$account = isset($p->accounts) ? reset($p->accounts) : NULL;
			BasicFacade::instance()->rollback();
			BasicFacade::instance()->closeDataSource();

			//if ($username == 'test' && $password == 'test')
			if ($account)
			{
				WebUtils::setSESSION(__SKEY, $account);
				WebUtils::redirect(__SITE_CONTEXT . '/newstd/index');
				return;
			}

			$this->ui->message = 'Invalid username or password.';
			$this->ui->username = $username;
		}

		$this->ui->pageTitle = 'Login';
		$this->ui->pageContent = 'LoginUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function logout()
	{
		WebUtils::removeSESSION(__SKEY);
		WebUtils::redirect(__SITE_CONTEXT);
	}

	//=====================================================================================================
	//=====================================================================================================

	public function faq()
	{
		$this->ui->pageTitle = 'FAQ';
		$this->ui->pageContent = 'FAQUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function preEnrolment()
	{
		$this->ui->pageTitle = 'Pre-enrolment';
		$this->ui->pageContent = 'PreEnrolmentUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function enrolment()
	{
		$this->ui->pageTitle = 'Enrolment';
		$this->ui->pageContent = 'EnrolmentUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function payYourFees()
	{
		$this->ui->pageTitle = 'Pay Your Fees';
		$this->ui->pageContent = 'PayYourFeesUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function accommodation()
	{
		$this->ui->pageTitle = 'Accommodation';
		$this->ui->pageContent = 'AccommodationUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function getYourID()
	{
		$this->ui->pageTitle = 'Get Your ID';
		$this->ui->pageContent = 'GetYourIDUI.php';

		$this->ui->show('MainLayout.php');
	}

	public function getConnected()
	{
		$this->ui->pageTitle = 'Get Connected';
		$this->ui->pageContent = 'GetConnectedUI.php';

		$this->ui->show('MainLayout.php');
	}

}

?>
