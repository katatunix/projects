<?

require_once 'includes/utils.php';

__autoload('DataSource');
__autoload('MemberDAO');
__autoload('PromoDAO');

class loginController extends BaseController {

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
		echo 'zzzzzzzzzzz 1';
		if ( $this->findGroupId() > 0 ) {
			header('Location: ' . __SITE_CONTEXT . 'admin/dashboard/');
			return;
		}
	
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$username = remove_slashes( $_POST['username'] );
			$password = remove_slashes( $_POST['password'] );
			
			$memberDAO = new MemberDAO( DataSource::getInstance() );
			$group_id = $memberDAO->checkLogin($username, $password);
			
			if ($group_id == 0) { // login failed
				$message = 'Sai username hoặc password!';
				$this->registry->template->message = $message;
				$this->registry->template->username = $username;
			} else {
				$_SESSION['member'] = array(
					'group_id'		=> $group_id
				);
				
				header('Location: ' . __SITE_CONTEXT . 'admin/dashboard/');
				return;
			}
		}
		
		$categoryDAO	= new CatDAO( DataSource::getInstance() );
		$categories_list = $categoryDAO->findByAll_Navigation();
		
		$promoDAO = new PromoDAO( DataSource::getInstance() );
		$this->registry->template->promo_seo_url_newest = $promoDAO->findNewestSeoUrl();
		
		$cart = getCart();
		
		$this->registry->template->categories_list = $categories_list;
		$this->registry->template->cart = $cart;
		
		$this->registry->template->tile_title = 'Login';
		$this->registry->template->body_class = 'page-template';
		
		$this->registry->template->tile_content = 'login.php';
		$this->registry->template->tile_footer = 'footer.php';
		
		$this->registry->template->show('layout/user.php');
	}

}

?>
