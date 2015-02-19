<?php

__import('servlet/AbstractServlet');

__import('facade/PObject');
__import('facade/BasicFacade');
__import('facade/RoomFacade');
__import('facade/CourseFacade');
__import('facade/SessionFacade');
__import('facade/AccountFacade');

__import('webutils/WebUtils');

class SessionServlet extends AbstractServlet {
	private static $NOT_FOUND_ERROR = 'Not found the session.';
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		$bf = BasicFacade::instance();
		switch ($action) {
			case 'index'			:
			case 'alist'			:
				return $logged;
			case 'create'			:
			case 'edit'				:
			case 'remove'			:
			case 'activeDeactive'	:
				return $logged && ($bf->isCoordinator($role) || $bf->isAdmin($role));
		}
		return FALSE;
	}
	
	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$isFoundSession = TRUE;
		$message = NULL;
		$isSuccess = TRUE;
		
		if ($rm = WebUtils::obtainGET('rm')) {
			if ($rm == 1) {
				$message = 'The session has been removed successully.';
				$isSuccess = TRUE;
				$isFoundSession = FALSE;
			} else {
				$message = 'Could not remove the session because there are attendances belong to it.';
				$isSuccess = FALSE;
				$isFoundSession = TRUE;
			}
		}
		
		if ($isFoundSession) {
			//
			$sessionId = WebUtils::obtainGET('id');
			
			BasicFacade::instance()->startTrans();
			$p = SessionFacade::instance()->findDetail($account->id, $sessionId);
			BasicFacade::instance()->commit();
			
			$session = $p && isset($p->sessions) ? reset($p->sessions) : NULL;
			
			if (!$session) {
				$isFoundSession = FALSE;
				$isSuccess = FALSE;
				$message = self::$NOT_FOUND_ERROR;
			}
			
			//
			$this->ui->loggedStudentAisId = AccountHome::instance()->findById($account->id)->getStudentAisId();
			
			$this->ui->session			= $session;
			$this->ui->course			= isset($p->courses)	? reset($p->courses)	: NULL;
			$this->ui->room				= isset($p->rooms)		? reset($p->rooms)		: NULL;
			$this->ui->teacher			= isset($p->accounts)	? reset($p->accounts)	: NULL;
			
			$this->ui->students			= isset($p->students)		? $p->students		: array();
			$this->ui->attendances		= isset($p->attendances)	? $p->attendances	: array();
			$this->ui->registrations	= isset($p->registrations)	? $p->registrations	: array();
			
			//
			BasicFacade::instance()->closeDataSource();
		}
		
		//
		$this->ui->isFoundSession	= $isFoundSession;
		$this->ui->message			= $message;
		$this->ui->isSuccess		= $isSuccess;
		$this->ui->roleString		= $account->roleString;
		
		//
		$uri = WebUtils::getSESSION(__SKEY_SSLIST);
		$this->ui->backLink			= $uri ? $uri : __SITE_CONTEXT . "/session/alist";
			
		//
		$this->ui->pageTitle = 'Session Detail';
		$this->ui->pageContent = 'SessionDetailUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function alist() {
		if (WebUtils::isPOST()) return;
		
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$cid = WebUtils::obtainGET('cid');
		$stypeString = WebUtils::obtainGET('stype');
		$stype = NULL;
		if ($stypeString) {
			$stype = $stypeString == 'lec' ? 0 : 1;
		}
		
		$year	= NULL;
		$month	= NULL;
		if ($tmp = WebUtils::obtainGET('m')) {
			if ($tmp = MiscUtils::parseYearMonthString($tmp)) {
				$year	= $tmp[0];
				$month	= $tmp[1];
			}
		}
		if (!$year) {
			$tmp = MiscUtils::getCurrentYearMonth();
			$year	= $tmp[0];
			$month	= $tmp[1];
		}
		
		$rid = WebUtils::obtainGET('rid');
		$active = WebUtils::obtainGET('active');
		$active = is_null($active) ? 2 : (int)$active;
		
		$lg = WebUtils::obtainGET('lg');
		if (is_null($lg)) $lg = -1;
		
		//
		BasicFacade::instance()->startTrans();
		$p = SessionFacade::instance()->findByFilter(
			$account->id, $cid, $stype, $year, $month, $rid, $active, $lg, NULL, NULL,
			TRUE);
		BasicFacade::instance()->commit();
		
		//
		$this->ui->list				= isset($p->sessions)	? $p->sessions	: array();
		$this->ui->resCourses		= isset($p->courses)	? $p->courses	: array();
		$this->ui->resRooms			= isset($p->rooms)		? $p->rooms		: array();
		$this->ui->resTeachers		= isset($p->accounts)	? $p->accounts	: array();
		
		$p = CourseFacade::instance()->findByAll();
		$this->ui->courses	= isset($p->courses) ? $p->courses : array();
		
		$p = RoomFacade::instance()->findByAll();
		$this->ui->rooms	= isset($p->rooms) ? $p->rooms : array();
		
		$this->ui->cid		= $cid;
		$this->ui->stype	= $stypeString;
		$this->ui->year		= $year;
		$this->ui->month	= $month;
		$this->ui->rid		= $rid;
		$this->ui->active	= $active;
		$this->ui->lg		= $lg;
		
		$this->ui->roleString = $account->roleString;
		
		//
		$this->ui->pageTitle = 'Sessions List';
		$this->ui->pageContent = 'SessionsListUI.php';
		
		BasicFacade::instance()->closeDataSource();
		
		//
		$uri = WebUtils::getRequestUri();
		WebUtils::setSESSION(__SKEY_SSLIST, $uri);
		
		$this->ui->show('layout/MainLayout.php');
	}

	public function create() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		//
		$isSuccess = FALSE;
		$message = NULL;
		
		//
		$cid			= NULL;
		$stypeString	= NULL;
		$mins			= NULL;
		$spe			= NULL;
		$pat			= NULL;
		$rid			= NULL;
		$tid			= NULL;
		$g				= NULL;
		
		if (WebUtils::isPOST()) {
			$cid			= WebUtils::obtainPOST('cid');
			
			$stypeString	= WebUtils::obtainPOST('stype');
			$stype = NULL;
			if ($stypeString) {
				$stype = $stypeString == 'lec' ? 0 : 1;
			}
			
			$mins		= WebUtils::obtainPOST('mins');
			
			$spe		= WebUtils::obtainPOST('spe');
			if (!$spe) {
				$pat	= WebUtils::obtainPOST('pat');
			}
			
			$rid		= WebUtils::obtainPOST('rid');
			$tid		= WebUtils::obtainPOST('tid');
			$g			= WebUtils::obtainPOST('g');
			
			//
			BasicFacade::instance()->startTrans();
			$result = SessionFacade::instance()->insert($cid, $stype, $mins, $spe, $pat, $rid, $tid, $g);
			$isSuccess	= $result[0];
			$message	= $result[1];
			if ($isSuccess) {
				BasicFacade::instance()->commit();
			} else {
				BasicFacade::instance()->rollback();
			}
			
		} else {
			// GET method
			// Nothing to do
		}
		
		$p = CourseFacade::instance()->findByAll();
		$this->ui->courses	= isset($p->courses) ? $p->courses : array();
		
		$p = RoomFacade::instance()->findByAll();
		$this->ui->rooms	= isset($p->rooms) ? $p->rooms : array();
		
		$p = AccountFacade::instance()->findListTeachers();
		$this->ui->teachers	= isset($p->accounts) ? $p->accounts : array();
		
		$this->ui->cid		= $cid;
		$this->ui->stype	= $stypeString;
		$this->ui->mins		= $mins;
		$this->ui->spe		= $spe;
		$this->ui->pat		= $pat;
		$this->ui->rid		= $rid;
		$this->ui->tid		= $tid;
		$this->ui->g		= $g;
		
		//
		$this->ui->isEdit = FALSE;
		
		$this->ui->isSuccess = $isSuccess;
		$this->ui->message = $message;
		
		$this->ui->pageTitle = 'Create New Sessions';
		$this->ui->pageContent = 'SessionAddOrEditUI.php';
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->show('layout/MainLayout.php');
	}

	public function edit() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		//
		$isSuccess = FALSE;
		$message = NULL;
		$isFoundSession = TRUE;
		
		//
		$cid			= NULL;
		$stypeString	= NULL;
		$mins			= NULL;
		$spe			= NULL;
		$rid			= NULL;
		$tid			= NULL;
		$g				= NULL;
		
		BasicFacade::instance()->startTrans();
		
		if (WebUtils::isPOST()) {
			$sessionId		= WebUtils::obtainPOST('sid');
			
			$cid			= WebUtils::obtainPOST('cid');
			
			$stypeString	= WebUtils::obtainPOST('stype');
			$stype = NULL;
			if ($stypeString) {
				$stype = $stypeString == 'lec' ? 0 : 1;
			}
			
			$mins		= WebUtils::obtainPOST('mins');
			$spe		= WebUtils::obtainPOST('spe');
			$rid		= WebUtils::obtainPOST('rid');
			$tid		= WebUtils::obtainPOST('tid');
			$g			= WebUtils::obtainPOST('g');
			
			//
			$result = SessionFacade::instance()->update($sessionId, $cid, $stype, $mins, $spe, $rid, $tid, $g);
			$isSuccess	= $result[0];
			$message	= $result[1];
			if ($isSuccess) {
				$isFoundSession	= TRUE;
			} else if ($message == 'NF') {
				$isFoundSession	= FALSE;
				$message		= self::$NOT_FOUND_ERROR;
			} else {
				$isFoundSession	= TRUE;
			}
			
		} else {
			// GET method
			$sessionId = WebUtils::obtainGET('id');
			$p = SessionFacade::instance()->findById($sessionId, TRUE);
			$session = isset($p->sessions) ? reset($p->sessions) : NULL;
			
			if ($session) {
				$cid		= $session->courseId;
				$stype		= $session->stype;
				$mins		= $session->minutes;
				$spe		= $session->startDatetime;
				$rid		= $session->roomId;
				$tid		= $session->teacherId;
				
				if ($stype == 0) {
					$stypeString = 'lec';
				} else {
					$stypeString = 'lab';
					$g		= $session->labGroup;
				}
				
				$isFoundSession	= TRUE;
				$isSuccess		= TRUE;
				$message		= NULL;
			} else {
				$isFoundSession	= FALSE;
				$isSuccess		= FALSE;
				$message		= self::$NOT_FOUND_ERROR;
			}
		}
	
		if ($isFoundSession) {
			$p = CourseFacade::instance()->findByAll();
			$this->ui->courses	= isset($p->courses) ? $p->courses : array();
		
			$p = RoomFacade::instance()->findByAll();
			$this->ui->rooms	= isset($p->rooms) ? $p->rooms : array();
		
			$p = AccountFacade::instance()->findListTeachers();
			$this->ui->teachers	= isset($p->accounts) ? $p->accounts : array();
			
			$this->ui->sid		= $sessionId;
			
			$this->ui->cid		= $cid;
			$this->ui->stype	= $stypeString;
			$this->ui->mins		= $mins;
			$this->ui->spe		= $spe;
			$this->ui->rid		= $rid;
			$this->ui->tid		= $tid;
			$this->ui->g		= $g;
		}
		
		//
		$this->ui->isEdit = TRUE;
		
		$this->ui->isFoundSession	= $isFoundSession;
		$this->ui->isSuccess		= $isSuccess;
		$this->ui->message			= $message;
		
		$this->ui->pageTitle = 'Edit Session';
		$this->ui->pageContent = 'SessionAddOrEditUI.php';
		
		if ($isSuccess) {
			BasicFacade::instance()->commit();
		} else {
			BasicFacade::instance()->rollback();
		}
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->show('layout/MainLayout.php');
	}

	public function remove() {
		if (WebUtils::isGET()) return;
		
		$sessionId		= WebUtils::obtainPOST('sessionId');
		
		BasicFacade::instance()->startTrans();
		$ok = SessionFacade::instance()->delete($sessionId);
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		$ok = $ok ? '1' : '2';
		
		WebUtils::redirect(__SITE_CONTEXT . "/session?id=$sessionId&rm=$ok");
	}
	
	public function activeDeactive() {
		if (WebUtils::isGET()) return;
		
		$sessionId		= WebUtils::obtainPOST('sessionId');
		$isActive		= WebUtils::obtainPOST('isActive');
		
		BasicFacade::instance()->startTrans();
		$ok = SessionFacade::instance()->updateActive($sessionId, $isActive);
		if ($ok)
			BasicFacade::instance()->commit();
		else
			BasicFacade::instance()->rollback();
		
		BasicFacade::instance()->closeDataSource();
		
		WebUtils::redirect(__SITE_CONTEXT . "/session?id=$sessionId");
	}
	
}
