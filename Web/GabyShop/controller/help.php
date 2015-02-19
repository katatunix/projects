<?

require_once('includes/utils.php');

__autoload('GlobalDAO');

class helpController extends BaseController {

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
		$content = $globalDAO->select('help');
		
		
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->content = $content;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->is_help_active = TRUE;
		
		$this->registry->template->tile_title = 'Trợ Giúp';
		$this->registry->template->tile_content = 'help.php';
		$this->registry->template->tile_footer = 'footer.php';
		$this->registry->template->show('layout/user.php');
	}

}

?>
