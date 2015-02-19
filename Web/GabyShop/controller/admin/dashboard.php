<?

__autoload('GlobalDAO');

class dashboardController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(1, 2),
			'logout'			=> array(1, 2)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$globalDAO = new GlobalDAO( DataSource::getInstance() );
		$this->registry->template->count_stats = $globalDAO->getStats();
		$this->registry->template->tile_title = 'Trang quản trị';
		$this->registry->template->tile_content = 'admin/dashboard.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function logout() {
		unset( $_SESSION['member'] );
		header('LOCATION: ' . __SITE_CONTEXT);
	}
	
}

?>
