<?php

__import('servlet/AbstractServlet');

__import('facade/PObject');
__import('facade/BasicFacade');
__import('facade/RegistrationFacade');
__import('facade/StudentFacade');
__import('facade/CourseFacade');

__import('webutils/WebUtils');

class StudentServlet extends AbstractServlet {
	
	private static $NOT_FOUND_STUDENT_ERROR = 'Not found the student.';
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		$bf = BasicFacade::instance();
		
		switch ($action) {
			case 'index'						:
			case 'viewFakeAisDetail'			:
			case 'viewAttendanceStatistics'		:
				return $logged;
				
			case 'listLazy'	:
				return $logged && $bf->isTutor($role);
				
			case 'alist'			:
			case 'genAccount'		:
			case 'assignGroups'		:
			case 'randomlyAssign'	:
			case 'getTempList'		:
			case 'addTemp'			:
			case 'removeTemp'		:
				return $logged && ( $bf->isCoordinator($role) || $bf->isAdmin($role) );
		}
		return FALSE;
	}
	
	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$sid = WebUtils::obtainGET('id');
		
		$isShowStatisticsLink = TRUE;
		if ( BasicFacade::instance()->isStudent($account->role) ) {
			if ($sid != $account->studentAisId) {
				$isShowStatisticsLink = FALSE;
			}
		}
		
		BasicFacade::instance()->startTrans();
		$p = StudentFacade::instance()->findById($sid);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
	
		$student = $p && isset($p->students) ? reset($p->students) : NULL;
		
		if ($student) {
			$message = NULL;
		} else {
			$message = self::$NOT_FOUND_STUDENT_ERROR;
		}
		
		$this->ui->student = $student;
		$this->ui->message = $message;
		$this->ui->isShowStatisticsLink = $isShowStatisticsLink;
		
		$this->ui->pageTitle = 'Student Information';
		$this->ui->pageContent = 'StudentDetailUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function listLazy() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		BasicFacade::instance()->startTrans();
		$p = RegistrationFacade::instance()->findByLazy($account->id);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
	
		$this->ui->registrations	= $p && isset($p->registrations)	? $p->registrations	: array();
		$this->ui->courses			= $p && isset($p->courses)			? $p->courses		: array();
		$this->ui->students			= $p && isset($p->students)			? $p->students		: array();
		
		$this->ui->pageTitle = 'Lazy Students List';
		$this->ui->pageContent = 'LazyStudentsListUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function viewFakeAisDetail() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$sid = WebUtils::obtainGET('id');
		
		BasicFacade::instance()->startTrans();
		$p = StudentFacade::instance()->findById($sid);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
	
		$student = $p && isset($p->students) ? reset($p->students) : NULL;
		
		if ($student) {
			$message = NULL;
		} else {
			$message = self::$NOT_FOUND_STUDENT_ERROR;
		}
		
		$this->ui->student = $student;
		$this->ui->message = $message;
		
		$this->ui->pageTitle = 'Student Information';
		$this->ui->pageContent = 'FakeStudentAisUI.php';
		
		$this->ui->show('layout/FakeAisLayout.php');
	}
	
	public function viewAttendanceStatistics() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$sid = WebUtils::obtainGET('id');
		
		$message = NULL;
		
		if ( BasicFacade::instance()->isStudent($account->role) ) {
			if ($sid != $account->studentAisId) {
				$message = 'You cannot see attendance statistics of other students.';
			}
		}
		
		if (!$message) {
			BasicFacade::instance()->startTrans();
			$p = StudentFacade::instance()->findStudentAttStatistics($sid);
			if ($p) {
				$this->ui->courses = isset($p->courses) ? $p->courses : array();
			} else {
				$message = self::$NOT_FOUND_STUDENT_ERROR;
			}
			
			$p = StudentFacade::instance()->findById($sid);
			if ($p) {
				$this->ui->student = isset($p->students) ? reset($p->students) : NULL;
			}
			
			BasicFacade::instance()->commit();
			BasicFacade::instance()->closeDataSource();
		}
		
		$this->ui->studentAisId = $sid;
		$this->ui->message = $message;
		$this->ui->pageTitle = 'Student Attendance Statistics';
		$this->ui->pageContent = 'StudentAttendanceStatisticsUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function alist() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$cid	= WebUtils::obtainGET('cid');
		$sk		= WebUtils::obtainGET('sk');
		$lg		= WebUtils::obtainGET('lg');
		if (is_null($lg)) $lg = -1;
		
		BasicFacade::instance()->startTrans();
		
		$p = StudentFacade::instance()->findByFilter($cid, $sk, $lg);
		$this->ui->students			= isset($p->students)		? $p->students		: array();
		$this->ui->registrations	= isset($p->registrations)	? $p->registrations	: array();
		$this->ui->accounts			= isset($p->accounts)		? $p->accounts		: array();
		
		$p = CourseFacade::instance()->findByAll();
		$this->ui->courses			= isset($p->courses) ? $p->courses : array();
		
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->cid	= $cid;
		$this->ui->sk	= $sk;
		$this->ui->lg	= $lg;
		
		$this->ui->pageTitle = 'Students List';
		$this->ui->pageContent = 'StudentsListUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function genAccount() {
		if (WebUtils::isGET()) return;
		
		$studentAisIdList = WebUtils::obtainPOST('studentAisIdList');
		$cid	= WebUtils::obtainPOST('cid');
		$sk		= WebUtils::obtainPOST('sk');
		$lg		= WebUtils::obtainPOST('lg');
		
		BasicFacade::instance()->startTrans();
		$ok = StudentFacade::instance()->genAccount( explode(',', $studentAisIdList) );
			
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/student/alist?cid=$cid&sk=$sk&lg=$lg");
	}
	
	public function assignGroups() {
		if (WebUtils::isGET()) return;
		
		$studentAisIdList	= WebUtils::obtainPOST('studentAisIdList');
		$groupsList			= WebUtils::obtainPOST('groupsList');
		$cid	= WebUtils::obtainPOST('cid');
		$sk		= WebUtils::obtainPOST('sk');
		$lg		= WebUtils::obtainPOST('lg');
		
		BasicFacade::instance()->startTrans();
		$ok = StudentFacade::instance()->assignGroups(
			explode(',', $studentAisIdList),
			explode(',', $groupsList),
			$cid
		);
			
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/student/alist?cid=$cid&sk=$sk&lg=$lg");
	}
	
	public function randomlyAssign() {
		if (WebUtils::isGET()) return;
		
		$maxnum	= WebUtils::obtainPOST('maxnum');
		
		$cid	= WebUtils::obtainPOST('cid');
		$sk		= WebUtils::obtainPOST('sk');
		$lg		= WebUtils::obtainPOST('lg');
		
		BasicFacade::instance()->startTrans();
		$ok = StudentFacade::instance()->randomlyAssign($cid, $maxnum);
			
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/student/alist?cid=$cid&sk=$sk&lg=$lg");
	}
	
	public function getTempList() {
		// AJAX
		if (WebUtils::isGET()) return;
		
		$cid = WebUtils::obtainPOST('cid');
		
		BasicFacade::instance()->startTrans();
		$p = StudentFacade::instance()->getFreeStudentsWithCourse($cid);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();	
		
		if (isset($p->students)) {
			$arr = array();
			foreach ($p->students as $std) {
				$arr[] = array('id' => $std->id, 'fullname' => $std->fullname);
			}
			echo json_encode($arr);
		} else {
			echo '';
		}
	}
	
	public function addTemp() {
		if (WebUtils::isGET()) return;
		
		$studentAisIdList	= WebUtils::obtainPOST('studentAisIdList');
		$cid	= WebUtils::obtainPOST('cid');
		$sk		= WebUtils::obtainPOST('sk');
		$lg		= WebUtils::obtainPOST('lg');
		
		BasicFacade::instance()->startTrans();
		$ok = StudentFacade::instance()->addTemp(
			$cid,
			explode(',', $studentAisIdList)
		);
		
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/student/alist?cid=$cid&sk=$sk&lg=$lg");
	}
	
	public function removeTemp() {
		if (WebUtils::isGET()) return;
		
		$studentAisIdList	= WebUtils::obtainPOST('studentAisIdList');
		$cid	= WebUtils::obtainPOST('cid');
		$sk		= WebUtils::obtainPOST('sk');
		$lg		= WebUtils::obtainPOST('lg');
		
		BasicFacade::instance()->startTrans();
		$ok = StudentFacade::instance()->removeTemp(
			$cid,
			explode(',', $studentAisIdList)
		);
			
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/student/alist?cid=$cid&sk=$sk&lg=$lg");
	}
}

?>
