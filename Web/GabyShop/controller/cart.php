<?

require_once('includes/utils.php');

__autoload('CartDAO');
__autoload('PromoDAO');

class cartController extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(0, 1, 2)
		);
	}
	
	public function isAllowCountStats() {
		return TRUE;
	}

	public function index() {
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$this->handlePost();
		}
		
		$productDAO		= new ProductDAO( DataSource::getInstance() );
		$categoryDAO	= new CatDAO( DataSource::getInstance() );
		$brandDAO		= new BrandDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		$brands_list = $brandDAO->findByAll();
		
		$cart = getCart();
		
		$cart_details = array();
		
		foreach ($cart->listItem as $key => $value) {
			$cart_details[$key] = $productDAO->findById($key);
		}
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->brands_list = $brands_list;
		
		$this->registry->template->cart = $cart;
		$this->registry->template->cart_details = $cart_details;
		$this->registry->template->is_cart_active = TRUE;
		
		$this->registry->template->tile_title = 'Giỏ Hàng';
		$this->registry->template->body_class = 'cart-template';
		
		$this->registry->template->tile_content = 'cart.php';
		$this->registry->template->tile_footer = 'footer.php';
		$this->registry->template->show('layout/user.php');
	}
	
	private function handlePost() {
		if ( isset($_POST['update_cart']) ) {
			
			// update the cart
			$cart = getCart();
			foreach ($cart->listItem as $key => $value) {
				$quantity = (int)$_POST['updates_' . $key];
				$cart->removeItem($key);
				if ($quantity > 0) {
					$cart->addItem($key, $quantity);
				}
			}
			
			// checkout
			if ($_POST['update_cart'] == '2') {
				if ( $cart->checkout() ) {
					$cartDAO = new CartDAO( DataSource::getInstance() );
					$cart_id = $cartDAO->insert( $cart->listItem );
					
					$this->registry->template->checkout = $cart_id;
				} else { // FAILED
					$this->registry->template->checkout = -1;
				}
			}
		
		} else { // add to cart
			
			$id = (int)$_POST['id'];
			if ($id <= 0) return;
			getCart()->addItem($id, 1);
			
		}
	}

}

?>
