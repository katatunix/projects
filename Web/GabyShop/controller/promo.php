<?

require_once('includes/utils.php');

__autoload('PromoDAO');

class promoController extends BaseController {

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
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		
		$promo = $promoDAO->findBySeoUrl( $_GET['seo_url'] );
		
		if (!$promo) {
			$this->notFound();
			return;
		}
		
		$categoryDAO = new CatDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$this->registry->template->is_promo_active = TRUE;
		
		$this->registry->template->promo = $promo;
		$this->registry->template->related_promos = $promoDAO->findNewestList();
		
		$this->registry->template->tile_title = $promo['subject'];
		$this->registry->template->tile_content = 'promo.php';
		$this->registry->template->tile_footer = 'footer.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
