<?

require_once('includes/utils.php');

__autoload('CatDAO');
__autoload('PromoDAO');
__autoload('GlobalDAO');

abstract class BaseController {

	protected $registry;

	function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function findGroupId() {
		// 0: guest
		// 1: admin
		// 2: mod
		if ( !isset($_SESSION['member']) ) return 0;
		$group_id = $_SESSION['member']['group_id'];
		if ( !isset($group_id) ) return 0;
		return $group_id;
	}
	
	public function checkPermission($action) {
		$group_id = $this->findGroupId();
		
		$permission_define = $this->getPermissionDefine();
		
		if ( !$permission_define[$action] ) return FALSE;
		
		if ( in_array($group_id, $permission_define[$action]) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function countStats() {
		if ( $this->isAllowCountStats() ) {
			$globalDAO = new GlobalDAO( DataSource::getInstance() );
			$globalDAO->incStats();
		}
	}
	
	public function showError777() {
		$categoryDAO = new CatDAO( DataSource::getInstance() );
		
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->tile_title = 'Không có quyền truy cập';
		$this->registry->template->tile_content = 'error777.php';
		$this->registry->template->tile_footer = 'footer.php';
		
		$this->registry->template->show('layout/user.php');
	}
	
	abstract public function index();
	
	abstract public function getPermissionDefine();
	abstract public function isAllowCountStats();

}

?>
