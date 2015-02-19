<?

class error777Controller extends abstractController {

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
		$this->registry->template->title = 'Access denied';
		$this->registry->template->tileContent = 'error777.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
