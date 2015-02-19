<?
__include_file('utils.php');

class testController extends abstractController
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
		$this->registry->template->title = 'Test';
		$this->registry->template->tileContent = 'test.php';
		$this->registry->template->_name = 'test';
		
		$this->registry->template->show('layout/user.php');
	}
}

?>
