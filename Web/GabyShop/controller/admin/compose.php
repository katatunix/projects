<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('GlobalDAO');

class composeController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'about'				=> array(1),
			'contact'			=> array(1)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$this->about();
	}
	
	public function about() {
		$this->handle('about');
	}
	
	public function contact() {
		$this->handle('contact');
	}
	
	private function handle($col) {
		$globalDAO = new GlobalDAO( DataSource::getInstance() );
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$content = trim ( remove_slashes( $_POST['content'] ) );
			
			if ( $globalDAO->update($col, $content) ) {
				$message = array(
					'type'		=> 'info',
					'value'		=> 'Lưu thành công.',
				);
			}
			else {
				$message = array(
					'type'		=> 'error',
					'value'		=> 'Có lỗi xảy ra!',
				);
			}
			$this->registry->template->message = $message;
			
			$this->registry->template->content = $content;
			
			$tmp = $globalDAO->select($col);
			$this->registry->template->content_backup = $tmp;
		
		} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			
			$tmp = $globalDAO->select($col);
			$this->registry->template->content = $tmp;
			$this->registry->template->content_backup = $tmp;
		}
		
		
		
		if			($col == 'about')		$s = '“Giới thiệu”';
		else if		($col == 'contact')		$s = '“Liên hệ”';
		
		$this->registry->template->tile_title = 'Soạn thảo trang ' . $s;
		
		$this->registry->template->tile_content = 'admin/compose.php';
		$this->registry->template->show('admin/layout/admin.php');
	}

}

?>
