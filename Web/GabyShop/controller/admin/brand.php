<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('BrandDAO');

class brandController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(1, 2),
			'insert'			=> array(1, 2),
			'update'			=> array(1),
			'delete'			=> array(1)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		
		$this->registry->template->brands = $brandDAO->findByAll_Show();
		
		
		$this->registry->template->tile_title = 'Danh sách nhãn hiệu';
		$this->registry->template->tile_content = 'admin/brand-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		$name = remove_slashes( $_POST['name'] );
		
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		$brandDAO->update($id, $name);
		
		
		
		echo '1';
	}
	
	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$name = remove_slashes( $_POST['name'] );
		
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		$last_id = $brandDAO->insert($name);
		
		
		
		echo $last_id;
	}
	
	public function delete() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		$brandDAO->delete($id);
		
		
		
		echo '1';
	}

}

?>
