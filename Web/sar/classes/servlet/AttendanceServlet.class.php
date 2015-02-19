<?php

__import('servlet/AbstractServlet');
__import('facade/BasicFacade');
__import('facade/AttendanceFacade');
__import('facade/SessionFacade');
__import('facade/AccountFacade');
__import('facade/CourseFacade');

__import('webutils/WebUtils');

class AttendanceServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		$bf = BasicFacade::instance();
		switch ($action) {
			case 'enterAttendanceFromPda' :
				return TRUE;
			
			case 'enterAttendance' :
				return $logged && ($bf->isTutor($role) || $bf->isLecturer($role));
			
			case 'index'					:
			case 'viewHyperlinkedRegister'	:
			case 'authorizeAbsenceForm'		:
			case 'viewAbsenceFormsList'		:
				return $logged && $bf->isTutor($role);
			
			case 'createAbsenceForm' :
				return $logged && $bf->isStudent($role);
		}
		return FALSE;
	}

	public function index() {
		WebUtils::redirect(__SITE_CONTEXT . '/attendance/viewHyperlinkedRegister');
	}
	
	public function authorizeAbsenceForm() {
		if (WebUtils::isGET()) return;
		
		$sessionId		= WebUtils::obtainPOST('sessionId');
		$studentAisId	= WebUtils::obtainPOST('studentAisId');
		$isApproved		= WebUtils::obtainPOST('isApproved');
		
		// check the current teacher is the teacher of the session or not
		$account = WebUtils::getSESSION(__SKEY);
		$p = SessionFacade::instance()->findById( $sessionId );
		$session = isset($p->sessions) ? reset($p->sessions) : NULL;
		
		if ($session && $session->teacherId == $account->id) {
			//
			BasicFacade::instance()->startTrans();
			if ( AttendanceFacade::instance()->authorize($sessionId, $studentAisId, $isApproved) )
				BasicFacade::instance()->commit();
			else
				BasicFacade::instance()->rollback();
		}
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/session?id=$sessionId");
	}
	
	public function enterAttendance() {
		if (WebUtils::isGET()) return;
		
		$sessionId			= WebUtils::obtainPOST('sessionId');
		$studentAisIdList	= WebUtils::obtainPOST('studentAisIdList');
		$presentedList		= WebUtils::obtainPOST('presentedList');
		
		// check the current teacher is the teacher of the session or not
		$account = WebUtils::getSESSION(__SKEY);
		$p = SessionFacade::instance()->findById( $sessionId );
		$session = isset($p->sessions) ? reset($p->sessions) : NULL;
		
		if ($session && $session->teacherId == $account->id && $studentAisIdList && $presentedList) {
			//
			BasicFacade::instance()->startTrans();
			$ok = AttendanceFacade::instance()->enterAttendance(
				$sessionId,
				explode(',', $studentAisIdList),
				explode(',', $presentedList)
			);
			
			if ($ok)
				BasicFacade::instance()->commit();
			else
				BasicFacade::instance()->rollback();
		}
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/session?id=$sessionId");
	}
	
	public function createAbsenceForm() {
		if (WebUtils::isGET()) return;
		
		$sessionId		= WebUtils::obtainPOST('sessionId');
		$studentAisId	= WebUtils::obtainPOST('studentAisId');
		$reason			= WebUtils::obtainPOST('reason');
		
		$ok = FALSE;
		
		$account = WebUtils::getSESSION(__SKEY);
		if ( BasicFacade::instance()->isStudent($account->role) ) {
			if ($account->studentAisId == $studentAisId) {
				$ok = TRUE;
			}
		}
		
		if ($ok) {
			BasicFacade::instance()->startTrans();
			$ok = AttendanceFacade::instance()->createAbsenceForm($sessionId, $studentAisId, $reason);
			
			if ($ok)
				BasicFacade::instance()->commit();
			else
				BasicFacade::instance()->rollback();
		}
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/session?id=$sessionId");
	}

	public function viewHyperlinkedRegister() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		//
		$cid		= WebUtils::obtainGET('cid');
		$lg			= WebUtils::obtainGET('lg');
		$fromDate	= WebUtils::obtainGET('f');
		$toDate		= WebUtils::obtainGET('t');
		$cw			= WebUtils::obtainGET('cw');
		$print		= WebUtils::obtainGET('p');
		
		if (!$cid)	$cid = 0;
		if (!$lg)	$lg = 0;
		
		if ($cw) {
			$cur = MiscUtils::getCurrentWeek();
			$fromDate	= $cur[0];
			$toDate		= $cur[1];
		}
		
		BasicFacade::instance()->startTrans();
		
		if (!$cid) {
			$this->ui->sessions			= array();
			$this->ui->course			= NULL;
			$lgs						= array();
			$fromDate	= '';
			$toDate		= '';
		} else {
			//
			$p = CourseFacade::instance()->findById($cid);
			$this->ui->course = $p && isset($p->courses) ? reset($p->courses) : NULL;
			
			$lgs = CourseFacade::instance()->findTrueLabGroupIndices($cid);
			
			//
			if (array_search($lg, $lgs) === FALSE) {
				$lg = 0;
				$fromDate	= '';
				$toDate		= '';
			}
			
			//
			if ($lg) {
				$p = SessionFacade::instance()->findByRegister($account->id, $cid, $lg, $fromDate, $toDate);
				$this->ui->sessions			= $p && isset($p->sessions)			? $p->sessions		: array();
				$this->ui->students			= $p && isset($p->students)			? $p->students		: array();
				$this->ui->registrations	= $p && isset($p->registrations)	? $p->registrations	: array();
				$this->ui->attendances		= $p && isset($p->attendances)		? $p->attendances	: array();
			} else {
				$this->ui->sessions			= array();
				$fromDate	= '';
				$toDate		= '';
			}
		}
		
		$p = CourseFacade::instance()->findByAll();
		$this->ui->courses = $p && isset($p->courses) ? $p->courses : array();
		
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->cid	= $cid;
		$this->ui->lg	= $lg;
		$this->ui->lgs	= $lgs;
		$this->ui->fromDate	= $fromDate;
		$this->ui->toDate	= $toDate;
		
		if ($print) {
			$this->ui->printableLink = NULL;
			
			$this->ui->pageTitle = 'Printable Register';
			$this->ui->pageContent = 'HyperlinkedRegisterUI.php';
			
			$this->ui->show('layout/PrintableLayout.php');
		} else {
			$this->ui->printableLink = WebUtils::getRequestUri() . '&p=1';;
			
			$this->ui->pageTitle = 'Hyperlinked Registers';
			$this->ui->pageContent = 'HyperlinkedRegisterUI.php';
		
			$this->ui->show('layout/MainLayout.php');
		}
	}
	
	public function enterAttendanceFromPda() {
		
		$username	= WebUtils::obtain('u');
		$password	= WebUtils::obtain('p');
		$sessionId	= WebUtils::obtain('sid');
		
		$studentAisIdList	= WebUtils::obtain('std');
		$presentedList		= WebUtils::obtain('pres');
		
		$p = AccountFacade::instance()->checkLogin($username, $password);
		
		if (!isset($p->accounts)) {
			echo 'Wrong username or password.';
			return;
		}
		
		$account = reset($p->accounts);
		if ($account->roleString != 'Lecturer') {
			echo 'Only lecturer can use this function.';
			return;
		}
		
		$p = SessionFacade::instance()->findById( $sessionId );
		$session = isset($p->sessions) ? reset($p->sessions) : NULL;
		
		if (!$session) {
			echo 'Not found the session.';
			return;
		}
		
		if (!$studentAisIdList || !$presentedList) {
			echo 'Please enter attendance information.';
			return;
		}
			
		//
		BasicFacade::instance()->startTrans();
		$ok = AttendanceFacade::instance()->enterAttendance(
			$sessionId,
			explode(',', $studentAisIdList),
			explode(',', $presentedList)
		);
		
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		echo $ok ? 'success' : 'failed';
	}
	
	public function viewAbsenceFormsList() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		//
		
		BasicFacade::instance()->startTrans();
		
		$p = AttendanceFacade::instance()->findByTutorIdAndNewReasonStatus($account->id);
		
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->attendances	= isset($p->attendances)	? $p->attendances	: array();
		$this->ui->sessions		= isset($p->sessions)		? $p->sessions		: array();
		$this->ui->students		= isset($p->students)		? $p->students		: array();
		$this->ui->courses		= isset($p->courses)		? $p->courses		: array();
		
		$this->ui->pageTitle = 'Absence Notifications';
		$this->ui->pageContent = 'AbsenceFormsListUI.php';
	
		$this->ui->show('layout/MainLayout.php');
	}
}

?>
