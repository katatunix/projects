<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CityDAO');

class cityController extends BaseController {

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
		$cityDAO = new CityDAO( DataSource::getInstance() );
		
		$this->registry->template->cities = $cityDAO->findByAll_Show();
		
		
		$this->registry->template->tile_title = 'Danh sách tỉnh/thành';
		$this->registry->template->tile_content = 'admin/city-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		$name = remove_slashes( $_POST['name'] );
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		$cityDAO->update($id, $name);
		
		
		
		echo '1';
	}
	
	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$name = remove_slashes( $_POST['name'] );
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		$last_id = $cityDAO->insert($name);
		
		
		
		echo $last_id;
	}
	
	public function delete() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		$cityDAO->delete($id);
		
		
		
		echo '1';
	}

}

?>
