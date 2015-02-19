<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');
__import('facade/CourseFacade');

class CourseServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		$bf = BasicFacade::instance();
		switch ($action) {
			case 'index'	: 
			case 'alist'	: return $logged && ($bf->isCoordinator($role) || $bf->isAdmin($role));
		}
		return FALSE;
	}

	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		if (WebUtils::isPOST()) return;
		
		$id = WebUtils::obtainGET('id');
		
		BasicFacade::instance()->startTrans();
		$p = CourseFacade::instance()->findById($id);
		BasicFacade::instance()->commit();
		
		$course = $p && isset($p->courses) ? reset($p->courses) : NULL;
		
		if ($course) {
			$this->ui->course = $course;
			$this->ui->pageTitle = 'Course ' . $course->name;
		} else {
			$this->ui->errorMessage = 'Could not found the course.';
			$this->ui->pageTitle = 'Course [Unknown]';
		}
		
		$this->ui->pageContent = 'CourseDetailUI.php';
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function alist() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		BasicFacade::instance()->startTrans();
		$p = CourseFacade::instance()->findByAll();
		BasicFacade::instance()->commit();
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->courses = $p->courses;//======================
		
		$this->ui->pageTitle = 'Courses List';
		$this->ui->pageContent = 'CoursesListUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}

}

?>
