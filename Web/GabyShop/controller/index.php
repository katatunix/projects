<?

require_once('includes/utils.php');
__autoload('PromoDAO');
__autoload('CatDAO');

class IndexController extends BaseController {

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
		$categoryDAO	= new CatDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		$this->registry->template->promo_subject_newest = $promoDAO->findNewestSubject();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->cart = $cart;
			
		$this->registry->template->tile_title = __SITE_SLOGAN;
		$this->registry->template->body_class = 'index-template';
		$this->registry->template->body_id = 'index-page';
		$this->registry->template->tile_content = 'index.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
