<?

__include_file('utils.php');

class indexController extends abstractController
{
	const DATE_SEPARATE		= '-';
	const TIME_SEPARATE		= ':';
	const ID_SEPARATE		= '-';
	
	// 0: guest
	// 1: normal
	// 2: admin
	// 3: mod
	public function getPermissionDefine()
	{
		return array(
			__DEFAULT_ACTION			=> array(0, 1, 2, 3),
			'removeMembersFromProject'	=> array(0, 1, 2, 3),
			'addMemberToProject'		=> array(0, 1, 2, 3),
			'addNewTask'				=> array(0, 1, 2, 3),
			'getTaskDetail'				=> array(0, 1, 2, 3),
			'updateTask'				=> array(0, 1, 2, 3),
			'deleteTask'				=> array(0, 1, 2, 3),
			'updateTaskPercentComplete'	=> array(0, 1, 2, 3),
			'addOverallComment'			=> array(0, 1, 2, 3),
			'delOverallComment'			=> array(0, 1, 2, 3),
			'addTaskComment'			=> array(0, 1, 2, 3),
			'delTaskComment'			=> array(0, 1, 2, 3),
			'logout'					=> array(0, 1, 2, 3)
		);
	}

	public function index()
	{
		$projectsList = ProjectDAO::fetchByAll();
		
		$project = NULL;
		if ( $projectId = checkGetParameter('p') )
		{
			$project = ProjectDAO::fetchById( $projectId );
		}
		
		if ( $project )
		{
			/*$curDay = 0;
			$curMonth = 0;
			$curYear = 0;*/
			
			$dateString = checkGetParameter('d');
			if ( $dateString )
			{
				try
				{
					new DateTime($dateString);
				}
				catch (Exception $ex)
				{
					$dateString = NULL;
				}
			}
			
			if ( $dateString )
			{
				$parts = explode(self::DATE_SEPARATE, $dateString);
				if ( count($parts) == 3 )
				{
					/*$curYear = $parts[0];
					$curMonth = $parts[1];
					$curDay = $parts[2];*/
				}
				else
				{
					$dateString = NULL;
				}
			}
			
			//
			$memberIdsList = NULL;
			if ( $memberIdsListString = checkGetParameter('m') )
			{
				$memberIdsList = explode(self::ID_SEPARATE, $memberIdsListString);
			}
		
			$fullMembersList = MemberDAO::fetchByAll();
			$busyMembersList = $project->membersList();
			$freeMembersList = array();
			foreach ($fullMembersList as $k => $v)
			{
				if ( ! in_array( $v, $busyMembersList ) )
				{
					$freeMembersList[] = $v;
				}
			}
			
			//
			$tasksList = TaskDAO::fetchByDailyAssignment( $project->id(), $dateString, $memberIdsList );
			$templatesList = TaskDAO::fetchByDailyAssignment( 1, NULL, NULL ); // Cheat, magic number
			
			//
			$overallCommentsList = DateCommentDAO::fetchByProject( $project->id() );
			
			//
			//$this->registry->template->curDay = $curDay;
			//$this->registry->template->curMonth = $curMonth;
			//$this->registry->template->curYear = $curYear;
			$this->registry->template->curDateString = $dateString ? $dateString : '';
			
			$this->registry->template->tasksList = $tasksList;
			$this->registry->template->templatesList = $templatesList;
			
			$this->registry->template->overallCommentsList = $overallCommentsList;
			
			$this->registry->template->memberIdsList = $memberIdsList;
			$this->registry->template->freeMembersList = $freeMembersList;
		}
		
		$this->registry->template->project = $project;
		$this->registry->template->projectsList = $projectsList;
		
		//
		$this->registry->template->title = $project ? $project->name() : 'Welcome';
		$this->registry->template->tileContent = 'index.php';
		$this->registry->template->_name = 'home';
		$this->registry->template->show('layout/user.php');
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function removeMembersFromProject()
	{
		$memberIdsListString = checkPostParameter('memberIdsList');
		$projectId = checkPostParameter('projectId');
		
		if ( $projectId )
		{
			$memberIdsList = empty($memberIdsListString) ? array() : explode(self::ID_SEPARATE, $memberIdsListString);
			ProjectDAO::deleteMembersList( $projectId, $memberIdsList );
		}
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function addMemberToProject()
	{
		$memberId = checkPostParameter('memberId');
		$projectId = checkPostParameter('projectId');
		if ($memberId && $projectId)
		{
			ProjectDAO::insertMember($projectId, $memberId);
		}
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function addNewTask()
	{
		$projectId = checkPostParameter('projectId');
		if ( ! TaskBean::checkValidProjectId($projectId) )
		{
			// todo
			// error
			return;
		}
		
		$title = checkPostParameter('title');
		if ( ! TaskBean::checkValidTitle($title) )
		{
			// todo
			// error
			return;
		}
		
		
		$desc = checkPostParameter('desc');
		if ( ! TaskBean::checkValidDesc($desc) )
		{
			// todo
			// error
			return;
		}
		
		$priority = checkPostParameter('priority');
		if ( ! TaskBean::checkValidPriority($priority) )
		{
			// todo
			// error
			return;
		}
		
		$assigneeId = checkPostParameter('assigneeId');
		if ( ! TaskBean::checkValidAssigneeId($assigneeId) )
		{
			$assigneeId = NULL;
		}
		if ( $assigneeId && ! MemberDAO::fetchById($assigneeId) )
		{
			$assigneeId = NULL;
		}
		
		/*$createdDate = checkPostParameter('createdDate');
		if ( ! TaskBean::checkValidCreatedDate($createdDate) )
		{
			// todo
			// error
			return;
		}*/
		$createdDate = getCurrentDatetimeString(self::DATE_SEPARATE, self::TIME_SEPARATE);
		
		// sure MySession::currentMember() is not NULL
		$authorId = MySession::currentMember()->id();
		
		$percentComplete = '0';
		$lastUpdaterId = $authorId;
		$lastUpdatedDatetime = $createdDate;
		
		TaskDAO::insert(	$title, $desc, $priority, $authorId, $assigneeId, $createdDate,
							$percentComplete, $lastUpdaterId, $lastUpdatedDatetime, $projectId);
	}

	//=======================================================================================================
	//	AJAX request
	//
	//	Input:
	//		GET(taskId)
	//
	//	Return:
	//		Detail of the task in JSON format
	//=======================================================================================================
	public function getTaskDetail()
	{
		$taskId = checkGetParameter('taskId');
		$taskObj = TaskDAO::fetchById($taskId);
		
		$arr = $taskObj->toArray();
		$arr['descEscape']			= htmlspecialchars($arr['desc']);
		$arr['authorUsername']		= $taskObj->author()->username();
		$arr['assigneeUsername']	= $taskObj->assignee() ? $taskObj->assignee()->username() : NULL;
		$arr['lastUpdaterUsername']	= $taskObj->lastUpdater()->username();
		
		$tmp = array();
		foreach ($taskObj->commentsList() as $k => $comment)
		{
			$commentArr = $comment->toArray(TRUE);
			$commentArr['authorUsername'] = $comment->author()->username();
			$tmp[] = $commentArr;
		}
		
		$arr['commentsList'] = $tmp;
		
		echo json_encode( $arr );
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function updateTask()
	{
		$taskId = checkPostParameter('taskId');
		if ($taskId == NULL)
		{
			// todo
			// error
			return;
		}
		
		$title = checkPostParameter('title');
		if ( ! TaskBean::checkValidTitle($title) )
		{
			// todo
			// error
			return;
		}
		
		
		$desc = checkPostParameter('desc');
		if ( ! TaskBean::checkValidDesc($desc) )
		{
			// todo
			// error
			return;
		}
		
		$priority = checkPostParameter('priority');
		if ( ! TaskBean::checkValidPriority($priority) )
		{
			// todo
			// error
			return;
		}
		
		$assigneeId = checkPostParameter('assigneeId');
		if ( ! TaskBean::checkValidAssigneeId($assigneeId) )
		{
			$assigneeId = NULL;
		}
		if ( $assigneeId && ! MemberDAO::fetchById($assigneeId) )
		{
			$assigneeId = NULL;
		}
		
		TaskDAO::update($taskId, $title, $desc, $priority, $assigneeId);
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function deleteTask()
	{
		$taskId = checkPostParameter('taskId');
		if ($taskId == NULL)
		{
			// todo
			// error
			return;
		}
		echo TaskDAO::delete($taskId);
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function updateTaskPercentComplete()
	{
		$taskId = checkPostParameter('taskId');
		$percentComplete = checkPostParameter('percentComplete');
		
		// sure MySession::currentMember() is not NULL
		$lastUpdaterId = MySession::currentMember()->id();
		$lastUpdatedDatetime = getCurrentDatetimeString(self::DATE_SEPARATE, self::TIME_SEPARATE);
		
		TaskDAO::updatePercentComplete($taskId, $percentComplete, $lastUpdaterId, $lastUpdatedDatetime);
	}
	
	//=======================================================================================================
	// AJAX request
	// Insert a new overall comment to database
	//
	// Input:
	// 			+ POST(content)		: content of the new comment
	//			+ POST(projectId)	: id of the project
	//
	// Return: a list (in JSON format) of overall comments which belong to this date and this project
	//=======================================================================================================
	public function addOverallComment()
	{
		$content = checkPostParameter('content');
		$projectId = checkPostParameter('projectId');
		
		// sure MySession::currentMember() is not NULL
		$authorId = MySession::currentMember()->id();
		
		$datetime = getCurrentDatetimeString(self::DATE_SEPARATE, self::TIME_SEPARATE);
		
		DateCommentDAO::insert($content, $authorId, $datetime, $projectId);
		
		echo $this->makeJsonOverallComments($projectId);
	}
	
	public function delOverallComment()
	{
		$projectId = checkPostParameter('projectId');
		$ocId = checkPostParameter('ocId');
		DateCommentDAO::delete($ocId);
		
		echo $this->makeJsonOverallComments($projectId);
	}
	
	private function makeJsonOverallComments($projectId)
	{
		$list = DateCommentDAO::fetchByProject($projectId);
		$arr = array();
		foreach ($list as $k => $comment)
		{
			$commentArr = $comment->toArray(TRUE);
			$commentArr['authorUsername'] = htmlspecialchars( $comment->author()->username() );
			$arr[] = $commentArr;
		}
		
		return json_encode($arr);
	}
	
	//=======================================================================================================
	// AJAX request
	//=======================================================================================================
	public function addTaskComment()
	{
		$content = checkPostParameter('content');
		$taskId = checkPostParameter('taskId');
		
		// sure MySession::currentMember() is not NULL
		$authorId = MySession::currentMember()->id();
		
		$datetime = getCurrentDatetimeString(self::DATE_SEPARATE, self::TIME_SEPARATE);
		
		TaskCommentDAO::insert($content, $taskId, $authorId, $datetime);
		
		echo $this->makeJsonTaskComments($taskId);
	}


	public function delTaskComment()
	{
		$tcId = checkPostParameter('tcId');
		$taskId = checkPostParameter('taskId');
		
		TaskCommentDAO::delete($tcId);
		
		echo $this->makeJsonTaskComments($taskId);
	}
	
	private function makeJsonTaskComments($taskId)
	{
		$list = TaskCommentDAO::fetchByTaskId($taskId);
		$arr = array();
		foreach ($list as $k => $comment)
		{
			$commentArr = $comment->toArray(TRUE);
			$commentArr['authorUsername'] = $comment->author()->username();
			
			$arr[] = $commentArr;
		}
		
		return json_encode($arr);
	}
	
	//=======================================================================================================
	//
	//=======================================================================================================
	public function logout()
	{
		MySession::deleteCurrentMember();
		header('LOCATION: ' . __SITE_CONTEXT);
	}
}

?>
