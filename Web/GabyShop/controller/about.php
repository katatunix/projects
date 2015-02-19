<?

require_once('includes/utils.php');

__autoload('GlobalDAO');
__autoload('PromoDAO');

class aboutController extends BaseController {

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
		$globalDAO		= new GlobalDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		$content = $globalDAO->select('about');
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->content = $content;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->is_about_active = TRUE;
		
		$this->registry->template->tile_title = 'Giới Thiệu';
		$this->registry->template->body_class = 'page-template';
		
		$this->registry->template->tile_content = 'about.php';
		$this->registry->template->tile_footer = 'footer.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
