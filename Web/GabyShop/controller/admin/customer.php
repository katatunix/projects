<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CityDAO');

class customerController extends BaseController {
	
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
	
	private function checkParamGET($param) {
		return ( isset($_GET[$param]) ) && ( trim($_GET[$param]) != '' );
	}

	public function index() {
		if ( $_SERVER['REQUEST_METHOD'] != 'GET' ) {
			return;
		}
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		$this->registry->template->cities = $cityDAO->findByAll();
		
		$customerDAO = new CustomerDAO( DataSource::getInstance() );
		
		if ( $this->checkParamGET('s_cust_id') ) {
			$this->registry->template->list_cust = $customerDAO->findById_Brief( (int)$_GET['s_cust_id'] );
			$this->registry->template->page = 1;
		
		} else {
			$s_info = array();
			
			if ( $this->checkParamGET('s_full_name') ) {
				$s_info['s_full_name'] = remove_slashes( $_GET['s_full_name'] );
			}
			if ( $this->checkParamGET('s_city') ) {
				if ( $_GET['s_city'] != '-1' ) {
					$s_info['s_city'] = $_GET['s_city'];
				}
			}
			if ( $this->checkParamGET('s_phone') ) {
				$s_info['s_phone'] = remove_slashes( $_GET['s_phone'] );
			}
			if ( $this->checkParamGET('s_min_age') ) {
				$s_info['s_min_age'] = (int)remove_slashes( $_GET['s_min_age'] );
			}
			if ( $this->checkParamGET('s_max_age') ) {
				$s_info['s_max_age'] = (int)remove_slashes( $_GET['s_max_age'] );
			}
			
			$page = 1;
			if ( isset($_GET['page']) ) {
				$page = (int)$_GET['page'];
				if ($page <= 0) $page = 1;
			}
			
			$arr = NULL;
			if ( isset($_GET['s_cust_id']) ) {
				$arr = $customerDAO->findBySearchInfo_Brief( $s_info, $page );
			}
			
			$this->registry->template->list_cust = $arr;
			$this->registry->template->s_info = array(
				's_full_name'	=> htmlspecialchars( $s_info['s_full_name'] ),
				's_city'		=> $s_info['s_city'],
				's_phone'		=> htmlspecialchars( $s_info['s_phone'] ),
				's_min_age'		=> $s_info['s_min_age'],
				's_max_age'		=> $s_info['s_max_age']
			);
			
			$url = $_SERVER['REQUEST_URI'];
			$i = strrpos($url, 'page=');
			
			if ($i) {
				$this->registry->template->url = substr($url, 0, $i + 5);
			} else {
				$this->registry->template->url = $url . '&page=';
			}
			
			$this->registry->template->page = $page > $arr['page_num'] ? $arr['page_num'] : $page;
		}
		
		$this->registry->template->tile_title = 'Danh sách khách hàng';
		$this->registry->template->tile_content = 'admin/customer-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}

	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->insertPost();
		}
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		
		$this->registry->template->cities = $cityDAO->findByAll();
		
		
		
		$this->registry->template->tile_title = 'Thêm khách hàng mới';
		$this->registry->template->tile_content = 'admin/customer-insert.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	private function insertPost() {
		$full_name = trim( remove_slashes( $_POST['full_name'] ) );
		
		$address = trim( remove_slashes( $_POST['address'] ) );
		
		$email = trim( remove_slashes( $_POST['email'] ) );
		
		$birthday = trim( remove_slashes( $_POST['birthday'] ) );
		
		$city_id = (int)$_POST['city_id'];
		
		$note = trim( remove_slashes( $_POST['note'] ) );
		
		$phone = trim( remove_slashes( $_POST['phone'] ) );
		
		$this->registry->template->customer = array(
			'full_name'		=> htmlspecialchars( $full_name ),
			'address'		=> htmlspecialchars( $address ),
			'email'			=> htmlspecialchars( $email ),
			'birthday'		=> htmlspecialchars( $birthday ),
			'city_id'		=> $city_id,
			'note'			=> htmlspecialchars( $note ),
			'phone'			=> htmlspecialchars( $phone )
		);
		
		$messageStr = '';
		
		if ( !isset($full_name) || strlen($full_name) == 0 ) {
			$messageStr .= 'Họ và tên: không được bỏ trống.<br />';
		}
		
		if ( isset($email) && strlen($email) > 0 ) {
			if ( !valid_email($email) ) {
				$messageStr .= 'Email: không hợp lệ.<br />';
			}
		}
		
		if ( isset($birthday) && strlen($birthday) > 0 ) {
			if ( !valid_date_vn($birthday) ) {
				$messageStr .= 'Ngày sinh: không hợp lệ.<br />';
			}
		}
		
		$messageStr = trim($messageStr);
		
		if ( $messageStr != '' ) {
			$this->registry->template->message = array(
				'type'		=> 'error',
				'value'		=> $messageStr
			);
		} else {
			$customerDAO = new CustomerDAO( DataSource::getInstance() );
			
			$last_id = $customerDAO->insert(
				$full_name, convert_date_to_us($birthday),
				$address, $email, $city_id, $note, $phone
			);
			
			if ( $last_id > 0 ) {
				$this->registry->template->message = array(
					'type'		=> 'info',
					'value'		=> 'Thêm khách hàng thành công với mã là ' . $last_id .
						' <a href="' . __SITE_CONTEXT . 'admin/customer/update?id=' . $last_id . '">[Sửa]</a>'
				);
				$this->registry->template->customer = NULL;
			} else {
				$this->registry->template->message = array(
					'type'		=> 'error',
					'value'		=> 'Có lỗi xảy ra.'
				);
			}
		}
	}
	
	public function update() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->updatePost();
		} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$this->updateGet();
		}
		
		$cityDAO = new CityDAO( DataSource::getInstance() );
		
		$this->registry->template->cities = $cityDAO->findByAll();
		
		

		$this->registry->template->tile_title = 'Sửa thông tin khách hàng';
		$this->registry->template->tile_content = 'admin/customer-update.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	private function updateGet() {
		if ( !isset($_GET['id']) ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại khách hàng này!'
			);
			return;
		}
		
		$id = (int)$_GET['id'];
		if ($id == 0) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại khách hàng này!'
			);
			return;
		}
		
		$customerDAO = new CustomerDAO( DataSource::getInstance() );
		$customer = $customerDAO->findById($id);
		if ( !$customer ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại khách hàng này!'
			);
			return;
		}
		
		$this->registry->template->customer = $customer;
		$this->registry->template->customer_backup = $customer;
	}
	
	private function updatePost() {
		$id = (int)$_POST['id'];
		
		$full_name = trim( remove_slashes( $_POST['full_name'] ) );
		
		$address = trim( remove_slashes( $_POST['address'] ) );
		
		$email = trim( remove_slashes( $_POST['email'] ) );
		
		$birthday = trim( remove_slashes( $_POST['birthday'] ) );
		
		$city_id = (int)$_POST['city_id'];
		
		$note = trim( remove_slashes( $_POST['note'] ) );
		
		$phone = trim( remove_slashes( $_POST['phone'] ) );
		
		$this->registry->template->customer = array(
			'id'			=> $id,
			'full_name'		=> htmlspecialchars( $full_name ),
			'address'		=> htmlspecialchars( $address ),
			'email'			=> htmlspecialchars( $email ),
			'birthday'		=> htmlspecialchars( $birthday ),
			'city_id'		=> $city_id,
			'note'			=> htmlspecialchars( $note ),
			'phone'			=> htmlspecialchars( $phone )
		);
		
		$messageStr = '';
		
		if ( !isset($full_name) || strlen($full_name) == 0 ) {
			$messageStr .= 'Họ và tên: không được bỏ trống.<br />';
		}
		
		if ( isset($email) && strlen($email) > 0 ) {
			if ( !valid_email($email) ) {
				$messageStr .= 'Email: không hợp lệ.<br />';
			}
		}
		
		if ( isset($birthday) && strlen($birthday) > 0 ) {
			if ( !valid_date_vn($birthday) ) {
				$messageStr .= 'Ngày sinh: không hợp lệ.<br />';
			}
		}
		
		$messageStr = trim($messageStr);
		
		$customerDAO = new CustomerDAO( DataSource::getInstance() );
		
		if ( $messageStr != '' ) {
			$this->registry->template->message = array(
				'type'		=> 'error',
				'value'		=> $messageStr
			);
		} else {
			$result = $customerDAO->update(
				$id, $full_name, convert_date_to_us($birthday), $address, $email, $city_id, $note, $phone);
			
			if ( $result ) {
				$this->registry->template->message = array(
					'type'		=> 'info',
					'value'		=> 'Lưu thành công!'
				);
			} else {
				$this->registry->template->message = array(
					'type'		=> 'error',
					'value'		=> 'Có lỗi xảy ra.'
				);
			}
		}
		
		$tmp = $customerDAO->findById($id);
		$this->registry->template->customer_backup = $tmp;
		if ( !$tmp ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại khách hàng này!'
			);
		}
	}
	
	public function delete() {
		$customerDAO = new CustomerDAO( DataSource::getInstance() );
		$customerDAO->delete( (int)$_POST['del_cust_id'] );
		
		echo '1';
	}

}

?>
