<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CatDAO');
__autoload('BrandDAO');
__autoload('ProductDAO');

class productController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(1, 2),
			'insert'			=> array(1, 2),
			'update'			=> array(1),
			'delete'			=> array(1),
			'sort'				=> array(1),
			'sortSave'			=> array(1)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$catDAO = new CatDAO( DataSource::getInstance() );
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		$productDAO = new ProductDAO( DataSource::getInstance() );
		
		$this->registry->template->list_category = $catDAO->findByAll();
		$this->registry->template->list_brand = $brandDAO->findByAll();
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$t = remove_slashes( trim( $_POST['s_product_code'] ) );
			if ( $t != '' ) {
				$this->registry->template->s_product_code = $t;
				$this->registry->template->list_prod = $productDAO->findByCode_Manage( $t );
			} else {
				$info = array();
				if ( $_POST['s_category_id'] != '-1' ) {
					$info['category_id'] = $_POST['s_category_id'];
				}
				if ( $_POST['s_brand_id'] != '-1' ) {
					$info['brand_id'] = $_POST['s_brand_id'];
				}
				
				$this->registry->template->list_prod = $productDAO->findByInfo_Manage( $info );
				
				$this->registry->template->s_category_id = $_POST['s_category_id'];
				$this->registry->template->s_brand_id = $_POST['s_brand_id'];
			}
		
		} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$this->registry->template->list_prod = $productDAO->findByAll_Manage();
		}
		
		
		
		$this->registry->template->tile_title = 'Danh sách sản phẩm';
		$this->registry->template->tile_content = 'admin/product-index.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function insert() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->insertPost();
		}
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		
		$this->registry->template->cats = $catDAO->findByAll();
		$this->registry->template->brands = $brandDAO->findByAll();
		
		
	
		$this->registry->template->tile_title = 'Thêm sản phẩm mới';
		$this->registry->template->tile_content = 'admin/product-insert.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function insertPost() {
		$code = trim( remove_slashes( $_POST['code'] ) );
		
		$category_id = (int)$_POST['category_id'];
		$brand_id = (int)$_POST['brand_id'];
		
		$price_fonds = trim( remove_slashes( $_POST['price_fonds'] ) );
		
		$price_sell = trim( remove_slashes( $_POST['price_sell'] ) );
		$price_sell = trim( remove_slashes( $_POST['price_sell'] ) );
		
		$description = trim( remove_slashes( $_POST['description'] ) );
		
		$pics = trim( remove_slashes( $_POST['pics'] ) );
		
		$seo_url = trim( remove_slashes( $_POST['seo_url'] ) );
		
		$this->registry->template->product = array(
			'code'				=> htmlspecialchars( $code ),
			'category_id'		=> $category_id,
			'brand_id'			=> $brand_id,
			'price_fonds'		=> htmlspecialchars( $price_fonds ),
			'price_sell'		=> htmlspecialchars( $price_sell ),
			'description'		=> htmlspecialchars( $description ),
			'pics'				=> $pics,
			'seo_url'			=> $seo_url
		);
		
		$messageStr = '';
		
		$productDAO = new ProductDAO( DataSource::getInstance() );
		
		if ( !isset($code) || strlen($code) == 0 ) {
			$messageStr .= 'Mã sản phẩm: không được bỏ trống.<br />';
		} else if ( $productDAO->isExistedCode($code) ) {
			$messageStr .= 'Mã sản phẩm: đã tồn tại.<br />';
		}
		
		if ( !isset($price_fonds) || strlen($price_fonds) == 0 ) {
			$messageStr .= 'Giá vốn: không được bỏ trống.<br />';
		} else if ( (int)$price_fonds <= 0 ) {
			$messageStr .= 'Giá vốn: phải là số nguyên dương.<br />';
		} else {
			$price_fonds_int = (int)$price_fonds;
		}
		
		if ( !isset($price_sell) || strlen($price_sell) == 0 ) {
			$messageStr .= 'Giá bán: không được bỏ trống.<br />';
		} else if ( (int)$price_sell <= 0 ) {
			$messageStr .= 'Giá bán: phải là số nguyên dương.<br />';
		} else {
			$price_sell_int = (int)$price_sell;
		}
		
		if ( isset($price_fonds_int) && isset($price_sell_int) ) {
			if ($price_fonds_int > $price_sell_int) {
				$messageStr .= 'Giá bán: không được thấp hơn giá vốn.<br />';
			}
		}
		
		if ( strlen($seo_url) == 0 ) {
			$messageStr .= 'SEO URL: không được bỏ trống.<br />';
		} else if ( !check_seo_url($seo_url) ) {
			$messageStr .= 'SEO URL: không hợp lệ.<br />';
		} else if ( $productDAO->isExistedSeoUrl($seo_url) ) {
			$messageStr .= 'SEO URL: đã tồn tại.<br />';
		}
		
		$messageStr = trim($messageStr);
		
		if ( $messageStr != '' ) {
			$this->registry->template->message = array(
				'type'		=> 'error',
				'value'		=> $messageStr
			);
		} else {
			$last_id = $productDAO->insert($code, $brand_id, $category_id, $price_fonds,
					$price_sell, $description, $pics, $seo_url);
			if ( $last_id > -1 ) {
				$this->registry->template->message = array(
					'type'		=> 'info',
					'value'		=> 'Thêm sản phẩm thành công. ' .
						' <a href="' . __SITE_CONTEXT . 'admin/product/update?id=' . $last_id . '">[Sửa]</a>'
				);
				$this->registry->template->product = NULL;
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
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$brandDAO = new BrandDAO( DataSource::getInstance() );
		
		$this->registry->template->cats = $catDAO->findByAll();
		$this->registry->template->brands = $brandDAO->findByAll();
		
		$this->registry->template->tile_title = 'Sửa thông tin sản phẩm';
		$this->registry->template->tile_content = 'admin/product-update.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	public function updatePost() {
		$id = (int)$_POST['id'];
		
		$code = trim( remove_slashes( $_POST['code'] ) );
		
		$brand_id = (int)$_POST['brand_id'];
		
		$category_id = (int)$_POST['category_id'];
		
		$price_fonds = trim( remove_slashes( $_POST['price_fonds'] ) );
		
		$price_sell = trim( remove_slashes( $_POST['price_sell'] ) );
		
		$description = trim( remove_slashes( $_POST['description'] ) );
		
		$pics = trim( remove_slashes( $_POST['pics'] ) );
		
		$seo_url = trim( remove_slashes( $_POST['seo_url'] ) );
		
		$this->registry->template->product = array(
			'id'				=> $id,
			'code'				=> htmlspecialchars( $code ),
			'category_id'		=> $category_id,
			'brand_id'			=> $brand_id,
			'price_fonds'		=> htmlspecialchars( $price_fonds ),
			'price_sell'		=> htmlspecialchars( $price_sell ),
			'description'		=> htmlspecialchars( $description ),
			'pics'				=> $pics,
			'seo_url'			=> htmlspecialchars( $seo_url )
		);
		
		$messageStr = '';
		
		$productDAO = new ProductDAO( DataSource::getInstance() );
		
		if ( !isset($code) || strlen($code) == 0 ) {
			$messageStr .= 'Mã sản phẩm: không được bỏ trống.<br />';
		} else if ( $productDAO->isExistedCode_Except($code, $id) ) {
			$messageStr .= 'Mã sản phẩm: đã tồn tại.<br />';
		}
		
		if ( !isset($price_fonds) || strlen($price_fonds) == 0 ) {
			$messageStr .= 'Giá vốn: không được bỏ trống.<br />';
		} else if ( (int)$price_fonds <= 0 ) {
			$messageStr .= 'Giá vốn: phải là số nguyên dương.<br />';
		} else {
			$price_fonds_int = (int)$price_fonds;
		}
		
		if ( !isset($price_sell) || strlen($price_sell) == 0 ) {
			$messageStr .= 'Giá bán: không được bỏ trống.<br />';
		} else if ( (int)$price_sell <= 0 ) {
			$messageStr .= 'Giá bán: phải là số nguyên dương.<br />';
		} else {
			$price_sell_int = (int)$price_sell;
		}
		
		if ( isset($price_fonds_int) && isset($price_sell_int) ) {
			if ($price_fonds_int > $price_sell_int) {
				$messageStr .= 'Giá bán: không được thấp hơn giá vốn.<br />';
			}
		}
		
		if ( strlen($seo_url) == 0 ) {
			$messageStr .= 'SEO URL: không được bỏ trống.<br />';
		} else if ( !check_seo_url($seo_url) ) {
			$messageStr .= 'SEO URL: không hợp lệ.<br />';
		} else if ( $productDAO->isExistedSeoUrl_Except($seo_url, $id) ) {
			$messageStr .= 'SEO URL: đã tồn tại.<br />';
		}
		
		$messageStr = trim($messageStr);
		
		if ( $messageStr != '' ) {
			$this->registry->template->message = array(
				'type'		=> 'error',
				'value'		=> $messageStr
			);
		} else {
			$result = $productDAO->update($id, $code, $brand_id, $category_id,
					$price_fonds, $price_sell, $description, $pics, $seo_url);
			
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
		
		$tmp = $productDAO->findById($id);
		$this->registry->template->product_backup = $tmp;
		if ( !$tmp ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại sản phẩm này!'
			);
		}
	}
	
	public function updateGet() {
		if ( !isset($_GET['id']) ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại sản phẩm này!'
			);
			return;
		}
		
		$id = (int)$_GET['id'];
		if ($id == 0) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại sản phẩm này!'
			);
			return;
		}
		
		$productDAO = new ProductDAO( DataSource::getInstance() );
		$product = $productDAO->findById($id);
		if ( !$product ) {
			$this->registry->template->message = array(
				'type'		=> 'error_not_found',
				'value'		=> 'Không tồn tại sản phẩm này!'
			);
			return;
		}
		
		$this->registry->template->product = $product;
		$this->registry->template->product_backup = $product;
	}


	// return 0: success
	// return 1: cannot delete
	// return 2: system error
	public function delete() {
		if ( !isset($_POST['del_prod_id']) ) {
			echo '2';
			return;
		}
		
		$prod_id = (int)$_POST['del_prod_id'];
		if ($prod_id <= 0) {
			echo '2';
			return;
		}
		
		$productDAO = new ProductDAO( DataSource::getInstance() );
		$result = $productDAO->delete( $prod_id );
		
		echo (string)$result;
	}
	
	public function sort() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->sortPost();
		} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			$this->sortGet();
		}
		
		$catDAO = new CatDAO( DataSource::getInstance() );
		$this->registry->template->cats = $catDAO->findByAll();
		
		$this->registry->template->tile_title = 'Sắp xếp vị trí sản phẩm';
		$this->registry->template->tile_content = 'admin/product-sort.php';
		$this->registry->template->show('admin/layout/admin.php');
	}
	
	private function sortPost() {
		$cat_id = (int)$_POST['cat_id'];
		$this->registry->template->cur_cat_id = $cat_id;
		
		$productDAO = new ProductDAO( DataSource::getInstance() );
		
		$list_prod = $productDAO->findByCatId($cat_id);
		$this->registry->template->list_prod = $list_prod;
	}
	
	private function sortGet() {
		
	}
	
	public function sortSave() {
		$list_id_str = $_POST['listIdSort'];
		$productDAO = new ProductDAO( DataSource::getInstance() );
		if ( $productDAO->resort($list_id_str) )
			echo '0';
		else
			echo '1';
	}
	
}

?>
