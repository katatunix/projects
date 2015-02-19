<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CatDAO');

class categoryController extends BaseController {

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
		$catDAO = new CatDAO( DataSource::getInstance() );
		
		$this->registry->template->cats = $catDAO->findByAll_Show();
		
		
		$this->registry->template->tile_title = 'Danh sách chủng loại';
		$this->registry->template->tile_content = 'admin/category-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		$name = remove_slashes( $_POST['name'] );
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$catDAO->update($id, $name);
		
		
		
		echo '1';
	}
	
	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$name = remove_slashes( $_POST['name'] );
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$last_id = $catDAO->insert($name);
		
		
		
		echo $last_id;
	}
	
	public function delete() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$catDAO->delete($id);
		
		
		
		echo '1';
	}

}

?>
