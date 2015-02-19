<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.button.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.draggable.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.position.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.tabs.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/jquery/ui/jquery.ui.autocomplete.min.js"></script>

<script type="text/javascript">
<?
	$cm = MySession::currentMember();
	$curMid = 0;
	if ($cm) {
		$curMid = $cm->id();
	}
	echo "var curMid = $curMid;";
?>
	var TEMPLATE_SOURCES = [
<?
	$_count = count($templatesList);
	for ($i = 0; $i < $_count; $i++) {
		$_task = $templatesList[$i];
		echo '"' . htmlspecialchars( $_task->title() ) . '♥' . htmlspecialchars( $_task->desc() ) . '"';
		if ($i < $_count - 1) {
			echo ',';
		}
		echo "\r\n";
	}
?>
	];

	var REMOVE_MEMBERS_FROM_PROJECT_URL	= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/removeMembersFromProject";
	var ADD_MEMBER_TO_PROJECT_URL		= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/addMemberToProject";
	var ADD_NEW_TASK_URL				= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/addNewTask";
	var GET_TASK_DETAIL_URL				= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/getTaskDetail";
	var UPDATE_TASK_URL					= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/updateTask";
	var DELETE_TASK_URL					= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/deleteTask";
	var UPDATE_TASK_PC_URL				= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/updateTaskPercentComplete";
	var ADD_OVERALL_COMMENT_URL			= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/addOverallComment";
	var DEL_OVERALL_COMMENT_URL			= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/delOverallComment";
	var ADD_TASK_COMMENT_URL			= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/addTaskComment";
	var DEL_TASK_COMMENT_URL			= "<?= __SITE_CONTEXT . '/' . __DEFAULT_CONTROLLER ?>/delTaskComment";
</script>

<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/js/MyDialog.js"></script>
<script type="text/javascript" src="<?= __VIEW_DIR_URL ?>/js/index.js"></script>

<div id="confirmDialog"><p></p></div>
<div id="progressDialog"><p><div class="progbar"></div></p></div>
<div id="infoDialog"><p></p></div>

<form method="GET" action="" id="myForm">
	<input type="hidden" name="p" id="projectId" />
	<input type="hidden" name="d" id="dateString" />
	<input type="hidden" name="m" id="memberIdsList" />
</form>

<div id="content">
	<div id="content2">
		<div class="post">
		<?
			if ( ! $project )
			{
		?>
			<h2 class="title">Welcome</h2>
			<div class="entry">
				Please select a project on the right sidebar.
			</div>
		<?
			}
			else
			{
		?>
			<h2 class="title"><?= htmlspecialchars( $project->name() ) ?></h2>
			
			<p class="meta">
				<span class="author">Producer: <?= $project->producer() ? htmlspecialchars( $project->producer()->username() ) : 'N/A' ?></span>
				<span class="date">Lead DEV: <?= $project->leadDev() ? htmlspecialchars( $project->leadDev()->username() ) : 'N/A' ?></span>
				<span class="links">Lead QA: <?= $project->leadQA() ? htmlspecialchars( $project->leadQA()->username() ) : 'N/A' ?></span>
			</p>
			
			<div class="entry">
				
				
				<div id="tabs">
					<ul>
						<li><a href="#tabs-1">Tasks list</a></li>
						<li><a href="#tabs-2">Overall comments</a></li>
						<?if (isAdmin() || isMod()) {?>
						<li><a href="#tabs-3">Add new task</a></li>
						<?}?>
					</ul>
					
					<div id="tabs-1">
						<table>
							<tr>
								<td>Date filter</td>
								<td><input type="button" id="button_prevDay" value="&lt;" /></td>
								<td><input type="text" id="datepicker"
										style="font-size: 1.3em; font-weight: bold" size="10"
										value="<?= $curDateString ?>" /></td>
								<td><input type="button" id="button_nextDay" value="&gt;" /></td>
								<td><input type="button" id="button_allDays" value="All days" /></td>
								<td><input type="button" id="button_today" value="Today" /></td>
								<td><input type="button" id="button_apply" style="font-size:1.5em" value="Apply" /></td>
							</tr>	
						</table>
						
						<p></p>
						
						<?
							if ( count($tasksList) == 0 )
							{
						?>
							<p><h3 align="center">No task found!</h3></p>
						<?
							}
							else
							{
						?>
							<table width="100%" border="1" cellpadding="4" cellspacing="0">
								<thead>
									<tr>
										<!--<th width="50px" align="center">Task ID</th>-->
										<th>Title</th>
										<th align="center">Priority</th>
										<th>Assigned to</th>
										<th>Start at</th>
										<th>Complete</th>
										<th>Last updated</th>
									</tr>
								</thead>
								<tbody>
									<?
									foreach ($tasksList as $k => $task)
									{
									?>
									<tr>
										<!--<td align="center"><?= $task->id() ?></td>-->
										<td><a href="javascript:showTaskDetail(<?=$task->id()?>)" title="View detail & comments"><?= htmlspecialchars( $task->title() )?></a></td>
										<td align="center"><?= $task->priority() ?></td>
										<td><?= $task->assignee() ? htmlspecialchars( $task->assignee()->username() ) : 'N/A'?></td>
										<td align="center"><?= $task->createdDate() ?></td>
										<td>
											<b style="<?= $task->percentComplete() == '100' ? 'font-size:1.3em' : '' ?>">
												<?= $task->percentComplete() ?> %
											</b>
										</td>
										<td>
											<?= $task->lastUpdatedDatetime() ?>
											<br />
											<i></b><?= htmlspecialchars( $task->lastUpdater()->username() ) ?></i>
										</td>
									</tr>
									<?
									}
									?>
								</tbody>
							</table>
						<?
							}
						?>
					</div>
					
					<div id="tabs-2">
						<div><strong><i>
							<span id="count_overall_comments"><?= count($overallCommentsList) ?></span> comment(s)
						</i></strong></div>
							<div id="overall_comments_container">
							<?
							foreach ($overallCommentsList as $k => $v)
							{
							?>
								<hr />
								<div class="overall_comment">
									<div>
										<strong><?= htmlspecialchars( $v->author()->username() ) ?></strong>
										on
										<?= $v->datetime() ?>
										<div style="float:right">
											<?if ( $cm && $cm->id() == $v->authorId() ) {?>
											<a href="javascript:deleteOC(<?= $v->id() ?>)">Delete</a>
											<?}?>
										</div>
									</div>
									<div class="comment_body"><?= nl2br( htmlspecialchars($v->content()) ) ?></div>
								</div>
							<?
							}
							?>
							</div>
							
						<? if ( !isGuest() ) {?>
						<hr />
						
						<div><strong><i>Post a comment</i></strong></div>
						<table width="100%" cellpadding="10">
							<tr>
								<td><textarea id="overall_comment" style="width:100%; height:100px"></textarea></td>
								<td width="100"><input type="button" id="buttonPostOverallComment" value="Post" style="width:100%; height:100px; font-size:2em" /></td>
							</tr>
						</table>
						<? } ?>
					</div>
					
					<?if (isAdmin() || isMod()) {?>
					<div id="tabs-3">
						<table border="0" cellpadding="5" width="100%">
							<tr>
								<td align="right" width="100">Title</td>
								<td><input type="text" style="width:100%" id="newtask_title" /></td>
							</tr>
							<tr>
								<td valign="top" align="right">Description</td>
								<td><textarea id="newtask_desc" style="width:100%; height:200px"></textarea></td>
							</tr>
							<tr>
								<td align="right">Priority</td>
								<td><select id="newtask_priority" style="width:50px">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select></td>
							</tr>
							<tr>
								<td align="right">Assigned to</td>
								<td>
									<select id="newtask_assigneeId">
										<option value="0">-- None --</option>
									<?
										foreach ($project->membersList() as $k => $m)
										{
									?>
											<option value="<?= $m->id() ?>"><?= htmlspecialchars( $m->username() ) ?></option>
									<?
										}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td align="center"><input type="button" id="buttonAddNewTask" value="Add"
										style="font-size:1.5em; width:120px" /></td>
							</tr>
						</table>
					</div>
					<?}?>
				</div>
			</div>
		<?
			}
		?>
		</div>
		
		
		<? include __VIEW_DIR_PATH . '/tiles/about.php'; ?>
	</div>
</div>
<!-- end #content -->

<div id="sidebar">
	<ul>
		
		<li>
			<h2>Project</h2>
			<p align="center">
			<select id="projectsListCombobox">
				<option value="0">-- select a project --</option>
			<?
				foreach ($projectsList as $i => $p)
				{
					$isSelected = ( $project && $p->id() == $project->id() ) ? ' selected ' : ' ';
			?>
					<option <?= $isSelected ?> value="<?= $p->id() ?>"><?= htmlspecialchars( $p->name() ) ?></option>
			<?
				}
			?>
			</select>
			</p>
		</li>
		
	<?
		if ($project)
		{
	?>
		<li>
			<h2>Working members in this project</h2>
			<p align="center">
				<select id="membersInProjectList" multiple="true" style="width: 100%; height: 200px; margin-bottom: 10px">
					<option <?= $memberIdsList ? '' : 'selected' ?> value="0">-- all -- </option>
					<?
					foreach ($project->membersList() as $k => $m)
					{
						$isSelected = FALSE;
						if ( $memberIdsList )
						{
							$isSelected = in_array( $m->id(), $memberIdsList ) ? ' selected ' : ' ';
						}
					?>
						<option <?= $isSelected ?> value="<?= $m->id() ?>"><?= htmlspecialchars( $m->username() ) ?></option>
					<?
					}
					?>
				</select>
				
				<input type="button" style="width: 80px" value="Filter" id="buttonFilter"
					title="Show only tasks which were assigned to these selected members" />
					<?if (isAdmin() || isMod()) {?>
				<input type="button" style="width: 80px" value="Remove" id="buttonRemoveMembers"
					title="Remove these selected members from this project" />
					<?}?>
				
			</p>
		</li>
		<?if (isAdmin() || isMod()) {?>
		<li>
			<h2>Add member to this project</h2>
			<p align="center">
				<select id="freeMembersListComboBox" style="width: 100%; margin-bottom: 10px">
				<option value="0">-- select a member --</option>
				<?
					foreach ($freeMembersList as $k => $v)
					{
				?>
						<option value="<?= $v->id() ?>"><?= htmlspecialchars( $v->username() ) ?></option>
				<?
					}
				?>
				</select>
				
				<input type="button" style="width: 80px" value="Add" id="buttonAddMembers" />
			</p>
		</li>
		<?}?>
	<?
		}
	?>
	</ul>
</div>
<!-- end #sidebar -->

<!------------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------->
<div id="dialog-task-detail" title="Task detail & comments">
<div id="detail_tabs">
	<ul>
		<li><a href="#detail_tabs_1">View</a></li>
		<?if (isAdmin() || isMod()) {?>
		<li><a href="#detail_tabs_2">Edit</a></li>
		<?}?>
	</ul>
	<div id="detail_tabs_1">
		<input type="hidden" id="taskdetail_taskId" />
		<span id="taskdetail_title" style="font-size: 1.5em"></span>
		<hr />

		<div id="taskdetail_desc"></div>
		<hr />
		<div><strong>Priority:</strong> <span id="taskdetail_priority"></span></div>
		<div><strong>Start at:</strong> <span id="taskdetail_startAt"></span></div>
		<div><strong>Author:</strong> <span id="taskdetail_authorUsername"></span></div>
		<div><strong>Assigned to:</strong> <span id="taskdetail_assigneeUsername"></span></div>
		
		<div>
			<strong id="taskdetail_percentComplete"></strong> % completed - last updated by
			<strong id="taskdetail_lastUpdaterUsername"></strong>&nbsp;at
			<strong id="taskdetail_lastUpdatedDatetime"></strong>&nbsp;
			<?if (!isGuest()) {?>
			<select id="updatetask_percentComplete">
				<option value="0">0 %</option>
				<option value="10">10 %</option>
				<option value="20">20 %</option>
				<option value="30">30 %</option>
				<option value="40">40 %</option>
				<option value="50">50 %</option>
				<option value="60">60 %</option>
				<option value="70">70 %</option>
				<option value="80">80 %</option>
				<option value="90">90 %</option>
				<option value="100">100 %</option>
			</select>
			<input id="buttonUpdatePercentComplete" type="button" value="Update" />
			<?}?>
		</div>
		
		<hr />
		<strong><i><span id="count_task_comments"></span>&nbsp;comment(s)</i></strong>
		<div id="task_comments_container" style="margin-left:40px;margin-bottom:10px">
		
		</div>
		
		<? if ( !isGuest() ) {?>
		<div><strong><i>Post a comment</i></strong></div>
		<table width="100%" cellpadding="10">
			<tr>
				<td><textarea id="task_comment" style="width:100%; height:100px"></textarea></td>
				<td width="100"><input type="button" id="buttonPostTaskComment" value="Post" style="width:100%; height:100px; font-size:2em" /></td>
			</tr>
		</table>
		<? } ?>
		
	</div>
	<?if (isAdmin() || isMod()) {?>
	<div id="detail_tabs_2">
		<table border="0" cellpadding="5" width="100%">
			<tr>
				<td align="right" width="100">Title</td>
				<td><input type="text" style="width:100%" id="updatetask_title" /></td>
			</tr>
			<tr>
				<td valign="top" align="right">Description</td>
				<td><textarea id="updatetask_desc" style="width:100%; height:200px"></textarea></td>
			</tr>
			<tr>
				<td align="right">Priority</td>
				<td><select id="updatetask_priority" style="width:50px">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select></td>
			</tr>
			<tr>
				<td align="right">Assigned to</td>
				<td>
					<select id="updatetask_assigneeId">
						<option value="0">-- None --</option>
					<?
						foreach ($project->membersList() as $k => $m) {
					?>
							<option value="<?= $m->id() ?>"><?= htmlspecialchars( $m->username() ) ?></option>
					<?
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td align="center">
					<input type="button" id="buttonUpdateTask" value="Save" />
					<input type="button" id="buttonDeleteTask" value="Delete" />
				</td>
			</tr>
		</table>
	</div>
	<?}?>
</div>
</div>
