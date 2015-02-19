<?
__include_file('utils.php');

class loginController extends abstractController
{
	// 0: guest
	// 1: normal
	// 2: admin
	// 3: mod
	public function getPermissionDefine()
	{
		return array(
			__DEFAULT_ACTION				=> array(0, 1, 2, 3)
		);
	}

	public function index()
	{
		MySession::deleteCurrentMember();
		
		$message = NULL;
		$username = '';
		
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' )
		{
			$username = checkPostParameter('username');
			$password = checkPostParameter('password');
			
			$foundMember = NULL;
			if ( !is_null($username) && !is_null($password) )
			{
				$foundMember = MemberDAO::fetchByLogin($username, $password);
			}
			
			if ($foundMember)
			{
				MySession::setCurrentMember($foundMember);
				header('LOCATION: ' . __SITE_CONTEXT);
				return;
			}
			else
			{
				$message = MemberBean::MSG_MEMBER_NOT_FOUND;
			}
		}
		
		if ($message)
		{
			$message = htmlspecialchars( $message );
		}
		
		$this->registry->template->message = $message;
		$this->registry->template->username = htmlspecialchars( $username );
		
		$this->registry->template->title = 'Login';
		$this->registry->template->tileContent = 'login.php';
		
		$this->registry->template->_name = 'login';
		
		$this->registry->template->show('layout/user.php');
	}

}

?>
