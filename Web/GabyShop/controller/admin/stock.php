<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('StockDAO');

class stockController extends BaseController {

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
		$stockDAO = new StockDAO( DataSource::getInstance() );
		
		$this->registry->template->stocks = $stockDAO->findByAll_Show();
		
		
		$this->registry->template->tile_title = 'Danh sách kho hàng';
		$this->registry->template->tile_content = 'admin/stock-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$id = (int)$_POST['id'];
		$name = remove_slashes( $_POST['name'] );
		
		$stockDAO = new StockDAO( DataSource::getInstance() );
		$stockDAO->update($id, $name);
		
		echo '1';
	}
	
	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' ) {
			echo '0';
			return;
		}
		
		$name = remove_slashes( $_POST['name'] );
		
		$stockDAO = new StockDAO( DataSource::getInstance() );
		$last_id = $stockDAO->insert($name);
		
		echo $last_id;
	}
	
	public function delete() {
		if ( !isset($_POST['id']) ) {
			echo '2';
			return;
		}
		
		$id = (int)$_POST['id'];
		
		if ($id <= 0) {
			echo '2';
			return;
		}
		
		$stockDAO = new StockDAO( DataSource::getInstance() );
		$ret = $stockDAO->delete($id);
		echo (string)$ret;
	}

}

?>
