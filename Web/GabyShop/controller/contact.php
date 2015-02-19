<?

require_once('includes/utils.php');

__autoload('GlobalDAO');
__autoload('PromoDAO');

class contactController extends BaseController {

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
		$content = $globalDAO->select('contact');
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->content = $content;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->is_contact_active = TRUE;
		
		$this->registry->template->facebook_description = 'Ms. Phương Thanh - 0987918796 - pttran87@gmail.com | Ms. Thùy Linh - 0983871412 - ptlinh1412@yahoo.com.vn';
		
		$this->registry->template->tile_title = 'Liên Hệ';
		$this->registry->template->tile_content = 'contact.php';
		$this->registry->template->tile_footer = 'footer.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
