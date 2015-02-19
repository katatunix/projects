<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CartDAO');

class cartController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(1, 2),
			'delete'			=> array(1),
			'deleteBatch'		=> array(1)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			
			$fromDate = get_cur_date('/');
			$toDate = $fromDate;
		
		} else if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			
			if ( isset( $_POST['cart_id'] ) ) {
				$cart_id = $_POST['cart_id'];
				if ( ((int)$cart_id) <= 0 ) {
					$message = 'Mã giỏ hàng không hợp lệ';
					$this->registry->template->message = array(
						'type'		=> 'error',
						'value'		=> $message
					);
				} else {
					$message = '1';
				}
			} else {
				$fromDate = trim( remove_slashes( $_POST['fromDate'] ) );
				$toDate = trim( remove_slashes( $_POST['toDate'] ) );
				
				$message = '';
				if ( $fromDate != '' ) {
					if ( !valid_date_vn($fromDate) ) {
						$message .= 'Ngày bắt đầu không hợp lệ<br />';
					}
				}
				if ( $toDate != '' ) {
					if ( !valid_date_vn($toDate) ) {
						$message .= 'Ngày kết thúc không hợp lệ<br />';
					}
				}
				
				if ($message != '') {
					$this->registry->template->message = array(
						'type'		=> 'error',
						'value'		=> $message
					);
				}
			}
			
		}
		
		if ( empty($message) ) {
			$t1 = $fromDate	== '' ? '01/01/2000' : $fromDate;
			$t2 = $toDate	== '' ? '01/01/2050' : $toDate;
			
			$t1 = convert_date_to_us($t1)	. ' 00:00:00';
			$t2 = convert_date_to_us($t2)	. ' 23:59:59';
			
			$cartDAO = new CartDAO( DataSource::getInstance() );
			
			$carts = $cartDAO->findByDateRange($t1, $t2);
			
			$cart_details = array();
			foreach ($carts as $key => $value) {
				$cart_details[$key] = $cartDAO->findDetail($key);
			}
			
			
		} else if ($message == '1') { // search by cart id
			$cartDAO = new CartDAO( DataSource::getInstance() );
			
			$cart = $cartDAO->findById( (int)$cart_id );
			
			$carts = array();
			$cart_details = array();
			if ($cart) {
				$carts[$cart_id] = $cart['checkout_datetime'];
				$cart_details[$cart_id] = $cartDAO->findDetail($cart_id);
			}
			
			
		}
		
		$this->registry->template->cart_id = $cart_id;
		$this->registry->template->fromDate = $fromDate;
		$this->registry->template->toDate = $toDate;
		
		$this->registry->template->carts = $carts;
		$this->registry->template->cart_details = $cart_details;
		
		$this->registry->template->tile_title = 'Danh sách giỏ hàng';
		$this->registry->template->tile_content = 'admin/cart.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function delete() {
		$cartDAO = new CartDAO( DataSource::getInstance() );
		$cartDAO->delete( (int)$_POST['del_cart_id'] );
		
		echo '1';
	}
	
	public function deleteBatch() {
		$strBatch = remove_slashes( $_POST['str_batch'] );
		$cartDAO = new CartDAO( DataSource::getInstance() );
		$cartDAO->deleteBatch( $strBatch );
		
		echo '1';
	}

}

?>
