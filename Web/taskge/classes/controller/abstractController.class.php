<?

abstract class abstractController extends BaseController {
	
	// Implement from BaseController
	public function checkPermission($action)
	{
		// 0: guest
		// 1: normal
		// 2: admin
		// 3: mod
		
		$group_id = 0;
		if ( $cm = MySession::currentMember() )
		{
			$group_id = $cm->groupId();
		}
		
		$permission_define = $this->getPermissionDefine();
		
		if ( ! $permission_define[$action] ) return FALSE;
		
		return in_array($group_id, $permission_define[$action]) ? TRUE: FALSE;
	}
	
	abstract public function getPermissionDefine();
	
	abstract public function index();
	
}

?>
