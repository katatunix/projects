<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('PromoDAO');

class promoController extends BaseController {

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
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		
		$this->registry->template->list_promo = $promoDAO->findByAll();
		
		$this->registry->template->tile_title = 'Danh sách chương trình khuyến mãi';
		$this->registry->template->tile_content = 'admin/promo-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->updatePost();
		} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$this->updateGet();
		}
		
		$this->registry->template->tile_title = 'Sửa chương trình khuyến mãi';
		$this->registry->template->tile_content = 'admin/promo-update.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function updatePost() {
		$id = (int)$_POST['promo_id'];
		
		$seo_url = trim( remove_slashes($_POST['seo_url']) );
		$subject = trim( remove_slashes($_POST['subject']) );
		$promo_date = trim( remove_slashes($_POST['promo_date']) );
		$content = trim( remove_slashes($_POST['content']) );
		
		$this->registry->template->promo = array(
			'id'				=> $id,
			'seo_url'			=> htmlspecialchars( $seo_url ),
			'subject'			=> htmlspecialchars( $subject ),
			'promo_date'		=> htmlspecialchars( $promo_date ),
			'content'			=> $content
		);
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		
		$message = '';
		
		if ( strlen($seo_url) == 0 ) {
			$message .= 'SEO URL: không được bỏ trống.<br />';
		} else if ( !check_seo_url($seo_url) ) {
			$message .= 'SEO URL: không hợp lệ.<br />';
		} else if ( $promoDAO->isExistedSeoUrl_Except($seo_url, $id) ) {
			$message .= 'SEO URL: đã tồn tại.<br />';
		}
		
		if ( strlen($subject) == 0 ) {
			$message .= 'Chủ đề: không được bỏ trống.<br />';
		}
		
		if ( strlen($promo_date) == 0 ) {
			$message .= 'Ngày tạo: không được bỏ trống.<br />';
		} else if ( !valid_date_vn($promo_date) ) {
			$message .= 'Ngày tạo: không hợp lệ.<br />';
		}
		
		if ( strlen($content) == 0 ) {
			$message .= 'Nội dung chương trình: không được bỏ trống.<br />';
		}
		
		if ( strlen($message) != 0 ) {
			$this->registry->template->message = array(
				'type'			=> 'error',
				'value'			=> $message
			);
		} else {
			$update_result = $promoDAO->update($seo_url, $subject, convert_date_to_us($promo_date), $content, $id);
			if ($update_result) {
				$this->registry->template->message = array(
					'type'			=> 'info',
					'value'			=> 'Đã sửa chương trình khuyến mãi thành công.'
				);
			} else {
				$this->registry->template->message = array(
					'type'		=> 'error',
					'value'		=> 'Có lỗi xảy ra.'
				);
			}
		}
		
		$tmp = $promoDAO->findById($id);
		if ($tmp == NULL) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại chương trình khuyến mãi này!'
			);
		} else {
			$this->registry->template->promo_backup = $tmp;
		}
	}
	
	public function updateGet() {
		if ( !isset($_GET['id']) ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại chương trình khuyến mãi này!'
			);
			return;
		}
		
		$id = (int)$_GET['id'];
		if ($id == 0) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại chương trình khuyến mãi này!'
			);
			return;
		}
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$promo = $promoDAO->findById($id);
		if ( $promo == NULL ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại chương trình khuyến mãi này!'
			);
			return;
		}
		
		$this->registry->template->promo = $promo;
		$this->registry->template->promo_backup = $promo;
	}
	
	public function insert() {
		$cur_date = get_cur_date('/');
		$this->registry->template->cur_date = $cur_date;
		
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$this->registry->template->promo = array(
				'promo_date'		=> $cur_date
			);
		} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->insertPost();
		}
		
		$this->registry->template->tile_title = 'Tạo chương trình khuyến mãi';
		$this->registry->template->tile_content = 'admin/promo-insert.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	private function insertPost() {
		$seo_url = trim( remove_slashes($_POST['seo_url']) );
		$subject = trim( remove_slashes($_POST['subject']) );
		$promo_date = trim( remove_slashes($_POST['promo_date']) );
		$content = trim( remove_slashes($_POST['content']) );
		
		$this->registry->template->promo = array(
			'seo_url'			=> htmlspecialchars( $seo_url ),
			'subject'			=> htmlspecialchars( $subject ),
			'promo_date'		=> htmlspecialchars( $promo_date ),
			'content'			=> $content
		);
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		
		$message = '';
		
		if ( strlen($seo_url) == 0 ) {
			$message .= 'SEO URL: không được bỏ trống.<br />';
		} else if ( !check_seo_url($seo_url) ) {
			$message .= 'SEO URL: không hợp lệ.<br />';
		} else if ( $promoDAO->isExistedSeoUrl($seo_url) ) {
			$message .= 'SEO URL: đã tồn tại.<br />';
		}
		
		if ( strlen($subject) == 0 ) {
			$message .= 'Chủ đề: không được bỏ trống.<br />';
		}
		
		if ( strlen($promo_date) == 0 ) {
			$message .= 'Ngày tạo: không được bỏ trống.<br />';
		} else if ( !valid_date_vn($promo_date) ) {
			$message .= 'Ngày tạo: không hợp lệ.<br />';
		}
		
		if ( strlen($content) == 0 ) {
			$message .= 'Nội dung chương trình: không được bỏ trống.<br />';
		}
		
		if ( strlen($message) != 0 ) {
			$this->registry->template->message = array(
				'type'			=> 'error',
				'value'			=> $message
			);
		} else {
			$last_id = $promoDAO->insert($seo_url, $subject, convert_date_to_us($promo_date), $content);
			if ($last_id > -1) {
				$this->registry->template->message = array(
					'type'			=> 'info',
					'value'			=> 'Đã thêm chương trình khuyến mãi thành công. ' .
						' <a href="' . __SITE_CONTEXT . 'admin/promo/update?id=' . $last_id . '">[Sửa]</a>'
				);
				$this->registry->template->promo = array(
					'promo_date'		=> get_cur_date('/')
				);
			} else {
				$this->registry->template->message = array(
					'type'		=> 'error',
					'value'		=> 'Có lỗi xảy ra.'
				);
			}
		}
		
	}
	
	public function delete() {
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		
		if ( $promoDAO->delete( (int)$_POST['del_promo_id'] ) ) {
			echo '0';
		} else {
			echo '1';
		}
	}

}

?>
