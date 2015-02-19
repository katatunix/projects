<?

require_once('includes/utils.php');

__autoload('CatDAO');
__autoload('PromoDAO');

class error404Controller extends BaseController {

	// 0: guest
	// 1: admin
	// 2: mod
	public function getPermissionDefine() {
		return array(
			'index'				=> array(0, 1, 2)
		);
	}
	
	public function isAllowCountStats() {
		return FALSE;
	}

	public function index() {
		$categoryDAO = new CatDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->tile_title = 'Không tồn tại trang này';
		$this->registry->template->tile_content = 'error404.php';
		$this->registry->template->tile_footer = 'footer.php';
		
		$this->registry->template->tile_content = 'error404.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
