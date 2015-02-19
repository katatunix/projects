<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('CatDAO');
__autoload('ProductDAO');
__autoload('PromoDAO');

class productController extends BaseController {

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
	
	private function notFound() {
		
		header('Location: ' . __SITE_CONTEXT);
	}

	public function index() {
		if ( empty($_GET['seo_url']) ) {
			$this->notFound();
			return;
		}
		
		$productDAO		= new ProductDAO( DataSource::getInstance() );
		
		$product = $productDAO->findBySeoUrl( $_GET['seo_url'] );
		
		if (!$product) {
			$this->notFound();
			return;
		}
		
		$categoryDAO	= new CatDAO( DataSource::getInstance() );
		$brandDAO		= new BrandDAO( DataSource::getInstance() );
		
		$cat_id = $product['category_id'];
		$brand_id = $product['brand_id'];
	
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$brands_list = $brandDAO->findByAll();
		
		$products_list_in_cat = $productDAO->findByCatId($cat_id);
		$products_list_in_brand = $productDAO->findByBrandId($brand_id);
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$this->registry->template->cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->brands_list = $brands_list;
		
		$this->registry->template->current_cat_id = $cat_id;
		$this->registry->template->current_brand_id = $brand_id;
		
		$this->registry->template->products_list_in_cat = $products_list_in_cat;
		$this->registry->template->products_list_in_brand = $products_list_in_brand;
		$this->registry->template->product = $product;
		$this->registry->template->product_pics = explode_pics($product['pics']);
		
		$this->registry->template->tile_title =
			$categories_list[$cat_id]['name'] . ' / ' . $brands_list[$brand_id] . ' / ' . $product['code'];
		
		$this->registry->template->facebook_description = $product['description'];
		$this->registry->template->facebook_image = __SITE_CONTEXT . __UPLOAD_DIR . get_pic_at($product['pics'], 1);
		
		$this->registry->template->body_class = 'product-template';
		$this->registry->template->tile_content = 'product.php';
		$this->registry->template->tile_footer = 'footer.php';
		
		$this->registry->template->show('layout/user.php');
	}
}

?>