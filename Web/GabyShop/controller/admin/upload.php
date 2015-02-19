<?

__autoload('ProductDAO');

class uploadController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(0, 1, 2),
			'cleanup'			=> array(1, 2)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$url = basename( $_FILES['async_upload']['name'] );
		$url = $this->makeValidFileName( $url );
		
		$uploadfile = __UPLOAD_DIR . $url;
		move_uploaded_file( $_FILES['async_upload']['tmp_name'], $uploadfile );
		echo $url;
	}
	
	public function cleanup() {
		$productDAO = new ProductDAO( DataSource::getInstance() );
		$file_names = $productDAO->findAllPics();
		
		
		$c = clean_upload_images($file_names);
		
		$this->registry->template->deleted_files = $c;
		
		$this->registry->template->tile_title = 'Dọn dẹp thư mục upload';
		$this->registry->template->tile_content = 'admin/upload-cleanup.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	private function makeValidFileName($file_name) {
		$fbase = str_replace( ' ', '_', trim($file_name) );
		
		$pos = strrpos( $fbase, '.' );
		
		if ($pos) {
			$fbase2 = substr($fbase, 0, $pos);
			$ext = substr($fbase, $pos);
		} else {
			$fbase2 = $fbase;
			$ext = '';
		}
		
		$i = 1;
		$upload_path = __SITE_PATH . '/' . __UPLOAD_DIR;
		$fname = $fbase2;
		while ( file_exists($upload_path . $fname . $ext) ) {
			$fname = $fbase2 . '_' . $i;
			$i++;
		}
		
		return $fname . $ext;
	}
	
}

?>
