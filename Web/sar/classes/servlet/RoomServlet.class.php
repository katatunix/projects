<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');
__import('facade/RoomFacade');

__import('webutils/WebUtils');

__import('facade/PObject');

class RoomServlet extends AbstractServlet 
{
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		$role = -1;
		if ($account) $role = $account->role;
		switch ($action) {
			case 'index'	:  return BasicFacade::instance()->isAdmin($role);
			case 'viewaddroom' : return BasicFacade::instance()->isAdmin($role);
			case 'addroom' : return BasicFacade::instance()->isAdmin($role);
			case 'vieweditroom' : return BasicFacade::instance()->isAdmin($role);
			case 'editroom' : return BasicFacade::instance()->isAdmin($role);
			case 'removeroom' : return BasicFacade::instance()->isAdmin($role);
			//case 'findList'		: return BasicFacade::instance()->isAdmin($role);
		}
		return FALSE;
	}

	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$list = RoomFacade::instance()->findByAll();
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->rooms = $list->rooms;//======================
		
		$this->ui->pageTitle = 'Rooms List';
		$this->ui->pageContent = 'RoomsListUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	public function addroom()
		{
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		if (WebUtils::isPOST()) {
			$message = new PObject();
			$message->isError = TRUE;
			$message->list = array();
			
			BasicFacade::instance()->startTrans();			
			$name		= WebUtils::obtainPOST('name');
			$rtype			= WebUtils::obtainPOST('rtype');
			if (!$name || !trim($name)) {
				$message->list[] = 'Current name must not be blank.';
			}
			if(!RoomFacade::instance()->checkDuplicate($name)){
				$message->list[] = 'The current Room Name has been existed on the system.Please choose another';
			}
			if (count($message->list) == 0) { // OK
				if (RoomFacade::instance()->addRoom($name,$rtype)) {
					$message->list[] = 'Room add successfully.';
					$message->isError = FALSE;
				} else {
					$message->list[] = 'Something went wrong,room add fail.';
				}
			}
		//$result = RoomFacade::instance()->addRoom($name,$rtype);
		if ( isset($message) && count($message->list) > 0 ) {
			$this->ui->message = $message;
		}
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		$this->ui->rooms = NULL;
		$this->ui->pageTitle = 'Add Room';
		$this->ui->pageContent = 'RoomAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');
		}
	}
	public function viewaddroom(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		$this->ui->pageTitle = 'Add Room';
		$this->ui->pageContent = 'RoomAddOrEditUI.php';
		$this->ui->rooms = NULL;
		
		$this->ui->show('layout/MainLayout.php');
	}
	public function vieweditroom(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		if (WebUtils::isPOST()) return;
		
		$id = WebUtils::obtainGET('id');
		
		BasicFacade::instance()->startTrans();
		$room = RoomFacade::instance()->findById($id);
		BasicFacade::instance()->commit();
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->rooms = reset( $room->rooms );//======================
		
		$this->ui->pageTitle = 'Edit Room';
		$this->ui->pageContent = 'RoomAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	public function editroom(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		if (WebUtils::isPOST()) {
			$message = new PObject();
			$message->isError = TRUE;
			$message->list = array();
				$id = WebUtils::obtainPOST('id');		
			$name		= WebUtils::obtainPOST('name');
			BasicFacade::instance()->startTrans();
			if (!$name || !trim($name)) {
				$message->list[] = 'Current name must not be blank.';
			}
			if(!RoomFacade::instance()->checkDuplicate($name)){
				$message->list[] = 'The current Room Name has been existed on the system.Please choose another';
			}
			if (count($message->list) == 0) { // OK
				if (RoomFacade::instance()->editRoom($id,$name)) {
					$message->isError = FALSE;
					BasicFacade::instance()->commit();
					BasicFacade::instance()->closeDataSource();
					$this->index();
				} else {
					$message->list[] = 'Something went wrong,room edit fail.';
				}
			}
		//$result = RoomFacade::instance()->addRoom($name,$rtype);
		if ( isset($message) && count($message->list) > 0 ) {
			$this->ui->message = $message;
			$room = RoomFacade::instance()->findById($id);
			$this->ui->rooms = reset( $room->rooms );
			$this->ui->pageTitle = 'Edit Room';
			$this->ui->pageContent = 'RoomAddOrEditUI.php';
		
			$this->ui->show('layout/MainLayout.php');
		}
			
		//$result = RoomFacade::instance()->editRoom($id,$name);
		
		/*$this->ui->rooms = NULL;
		$this->ui->pageTitle = 'Edit Room';
		$this->ui->pageContent = 'RoomAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');*/
		}
	}
	public function removeroom(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		if (WebUtils::isPOST()){
				$id = WebUtils::obtainPOST('id');		
			BasicFacade::instance()->startTrans();
		$result = RoomFacade::instance()->removeRoom($id);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		$this->index();
		}
		
	}
}

?>
