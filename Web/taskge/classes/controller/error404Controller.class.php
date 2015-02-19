<?

class error404Controller extends abstractController {

	// 0: guest
	// 1: normal
	// 2: admin
	// 3: mod
	public function getPermissionDefine() {
		return array(
			__DEFAULT_ACTION				=> array(0, 1, 2, 3)
		);
	}

	public function index() {
		$this->registry->template->title = 'This page is not found';
		$this->registry->template->tileContent = 'error404.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
