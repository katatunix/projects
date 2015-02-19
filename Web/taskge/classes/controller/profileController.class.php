<?
__include_file('utils.php');

class profileController extends abstractController
{
	// 0: guest
	// 1: normal
	// 2: admin
	// 3: mod
	public function getPermissionDefine()
	{
		return array(
			__DEFAULT_ACTION				=> array(1, 2, 3),
			'cpass'							=> array(1, 2, 3)
		);
	}

	public function index()
	{
		$this->registry->template->title = 'Profile';
		$this->registry->template->tileContent = 'profile.php';
		
		$this->registry->template->show('layout/user.php');
	}
	
	public function cpass()
	{
		$cm = MySession::currentMember(); // sure not NULL
		
		// 0: none
		// 1: error
		// 2: info
		$messageType = 0;
		$message = array();
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$currentPassword = checkPostParameter('currentPassword');
			
			if ( $cm->password() != $currentPassword )
			{
				$messageType = 1;
				$message[] = MemberBean::MSG_WRONG_CURRENT_PASSWORD;
			}
			else
			{
				$newPassword = checkPostParameter('newPassword');
				$confirmNewPassword = checkPostParameter('confirmNewPassword');
				if ($newPassword != $confirmNewPassword)
				{
					$messageType = 1;
					$message[] = MemberBean::MSG_PASSWORD_NOT_MATCHED;
				}
				else if ($newPassword == '')
				{
					$messageType = 1;
					$message[] = 'New password can not be blank';
				}
				else
				{
					if ( MemberDAO::updatePassword( $cm->id(), $newPassword ) )
					{
						$messageType = 2;
						$message[] = 'Change password successfully';
					}
					else
					{
						$messageType = 1;
						$message[] = 'Something went wrong';
					}
				}
			}
		}
		else if ($_SERVER['REQUEST_METHOD'] == 'GET')
		{
			
		}
		
		$this->registry->template->message = $message;
		$this->registry->template->messageType = $messageType;
		
		$this->registry->template->title = 'Change password';
		$this->registry->template->tileContent = 'cpass.php';
		
		$this->registry->template->_name = 'cpass';
		
		$this->registry->template->show('layout/user.php');
	}

}

?>
