<?php

__import('servlet/AbstractServlet');

__import('facade/PObject');
__import('facade/BasicFacade');
__import('facade/ProductFacade');
__import('facade/AccountFacade');
__import('facade/OrderFacade');

__import('webutils/WebUtils');

class LocalReportServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? true : false;

		$bf = BasicFacade::instance();

		switch ($action) {
			case 'index'	:
			case 'room'		:
			case 'foodb'	:
			case 'staff'	:
				return $logged && ( $bf->isLocMan($role) || $bf->isNatMan($role) );
		}
		return false;
	}
	
	public function index() {
		WebUtils::redirect(__SITE_CONTEXT . '/local/localReport/staff');
	}

	public function room() {
		$this->handle(true);
	}
	
	public function foodb() {
		$this->handle(false);
	}

	private function handle($isRoom) {
		if (WebUtils::obtainGET('cw')) {
			$week = MiscUtils::getCurrentWeek();
			$fromDate = $week[0];
			$toDate = $week[1];
		} else {
			$fromDate = WebUtils::obtainGET('fromDate');
			$toDate = WebUtils::obtainGET('toDate');
		}

		BasicFacade::instance()->startTrans();
		$p = ProductFacade::instance()->getProductReport($isRoom, $fromDate, $toDate);
		$this->ui->products = isset($p->products) ? $p->products : array();
		BasicFacade::instance()->rollback();

		$this->ui->isRoom = $isRoom;

		$this->ui->fromDate = $fromDate;
		$this->ui->toDate = $toDate;

		$countDays = $fromDate && $toDate ? MiscUtils::countDays($fromDate, $toDate) : 0;
		$this->ui->countDays = $countDays;

		//
		$this->ui->pageTitle = $isRoom ? 'Local report of room occupancy' : 'Local report of food & beverage sales';
		$this->ui->pageContent = 'local/LocalReportProductUI.php';

		BasicFacade::instance()->closeDataSource();
		$this->ui->show('MainLayout.php');
	}
	
	public function staff() {
		if (WebUtils::obtainGET('cw')) {
			$week = MiscUtils::getCurrentWeek();
			$fromDate = $week[0];
			$toDate = $week[1];
		} else {
			$fromDate = WebUtils::obtainGET('fromDate');
			$toDate = WebUtils::obtainGET('toDate');
		}
		$staffId = WebUtils::obtainGET('sid');

		BasicFacade::instance()->startTrans();

		$p = AccountFacade::instance()->findById($staffId);
		$account = isset($p->accounts) ? reset($p->accounts) : NULL;

		if (!$account) {
			$staffId = 0;
		}

		if ($this->loggedAccount->roleString == 'LocMan') {
			$roles = array(Role::STAFF);
		} else if ($this->loggedAccount->roleString == 'NatMan') {
			$roles = array(Role::STAFF, Role::LOC_MAN);
		} else {
			assert(false);
		}

		$p = AccountFacade::instance()->findAllStaffs($roles);

		$this->ui->staffs = isset($p->accounts) ? $p->accounts : array();

		$p = OrderFacade::instance()->getStaffReport($staffId, $fromDate, $toDate);
		$this->ui->list = $p;

		BasicFacade::instance()->rollback();
		BasicFacade::instance()->closeDataSource();

		$this->ui->sid = $staffId;
		$this->ui->fromDate = $fromDate;
		$this->ui->toDate = $toDate;

		$this->ui->pageTitle = 'Local report of individual staff sales statistics';
		$this->ui->pageContent = 'local/LocalReportStaffUI.php';
		$this->ui->show('MainLayout.php');
	}
	
}
?>
