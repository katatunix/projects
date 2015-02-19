var DATE_SEPARATE = '-';
var CHEAT_SEPA = '♥';

function nl2br(str) {
	return str.replace(/\n/g, '<br />');
}

function addZero(str, len) {
	str += '';
	while (str.length < len) str = '0' + str;
	return str;
}

function getCurrentDateString() {
	var d = new Date();
	return d.getFullYear() + DATE_SEPARATE
		+ addZero(d.getMonth() + 1, 2) + DATE_SEPARATE
		+ addZero(d.getDate(), 2);
}

function checkValidDateString(dateString) {
	var validFormat = /^\d{4}-\d{1,2}-\d{1,2}$/;
	if (!validFormat.test(dateString)) {
		return false;
	}
	var p = dateString.split('-');
	var dateObj = new Date(p[0], p[1] - 1, p[2]);
	return dateObj.getFullYear()	== p[0]
			&& dateObj.getMonth()	== p[1] - 1
			&& dateObj.getDate()	== p[2];
}

function getSelectedMemberIdsListString() {
	var memberIdsList = $('#membersInProjectList').val() || [];
	var memberIdsListString = '';
	var found = false;
	for (var i = 0; i < memberIdsList.length; i++) {
		if (memberIdsList[i] == 0) {
			found = true;
			break;
		}
	}
	
	if (!found) {
		memberIdsListString = memberIdsList.join('-');
	}
	
	return memberIdsListString;
}

function mySubmit() {
	var projectId = $('#projectsListCombobox').val();
	
	if (projectId <= 0) {
		$('#projectId').val('');
		$('#dateString').val('');
		$('#memberIdsList').val('');
		
		$('#myForm').submit();
		return;
	}
	
	$('#projectId').val( projectId );
	
	//if (curYear > 0)
		//$('#dateString').val( curYear + DATE_SEPARATE + curMonth + DATE_SEPARATE + curDay );
	
	$('#dateString').val( $('#datepicker').val() );
	
	$('#memberIdsList').val( getSelectedMemberIdsListString() );
	
	$('#myForm').submit();
}

function showTaskComments(obj) {
	$('#count_task_comments').text( obj.length );
	
	var container = $('#task_comments_container');
	container.empty();
	
	for (var i = 0; i < obj.length; i++)
	{
		container.append('<hr />');
		//container.append('<p>');
		
		var divComment = $('<div>');
		
		var divHeader = $('<div>').append( $('<b>').text(obj[i].authorUsername) ).append(' ' + obj[i].datetime).append(
			$('<div>').css('float', 'right').append(
				obj[i].authorId == curMid ?
					$('<a>').attr('href', 'javascript:deleteTC(' + obj[i].id + ')').text('Delete') :
					''
			)
		);
		
		var divContent = $('<div>').css('margin-left', '20px').css('margin-top', '5px').html( nl2br(obj[i].content) );
		
		divComment.append(divHeader).append(divContent);
		
		
		
		container.append(divComment);
		
	}
	
	
}

function showOverallComments(obj) {
	var container = $('#overall_comments_container');
	container.empty();
	
	$('#count_overall_comments').text( obj.length );
	
	for (var i = 0; i < obj.length; i++)
	{
		container.append('<hr />');
		
		var divComment = $('<div>').addClass('overall_comment');
		
		var divHeader = $('<div>').append( $('<b>').text(obj[i].authorUsername) ).append(' ' + obj[i].datetime).append(
			$('<div>').css('float', 'right').append(
				obj[i].authorId == curMid ?
					$('<a>').attr('href', 'javascript:deleteOC(' + obj[i].id + ')').text('Delete') :
					''
			)
		);
		
		var divContent = $('<div>').addClass('comment_body').html( nl2br(obj[i].content) );
		
		divComment.append(divHeader).append(divContent);
		container.append(divComment);
	}
}

function showTaskDetail(taskId) {
	showProgressDialog('Please wait...');
	
	$.getJSON(
		GET_TASK_DETAIL_URL,
		{
			taskId : taskId
		},
		function(json) {
			closeProgressDialog();
			
			$('#taskdetail_taskId').val(json.id);
			$('#taskdetail_title').text(json.title);
			$('#taskdetail_priority').text(json.priority);
			$('#taskdetail_startAt').text(json.createdDate);
			
			$('#taskdetail_desc').html(json.descEscape ? nl2br(json.descEscape) : 'No description');
			
			$('#taskdetail_assigneeUsername').text(json.assigneeUsername || 'N/A');
			$('#taskdetail_authorUsername').text(json.authorUsername);
			
			$('#taskdetail_percentComplete').text(json.percentComplete);
			$('#taskdetail_lastUpdaterUsername').text(json.lastUpdaterUsername);
			$('#taskdetail_lastUpdatedDatetime').text(json.lastUpdatedDatetime);
			
			$('#updatetask_title').val(json.title);
			$('#updatetask_desc').val(json.desc || '');
			$('#updatetask_priority').val(json.priority);
			$('#updatetask_assigneeId').val(json.assigneeId || 0);
			
			$('#updatetask_percentComplete').val(json.percentComplete);
			
			showTaskComments(json.commentsList);
			
			$( "#dialog-task-detail" ).dialog('open');
			
		}
	);
}

function deleteOC(id) {
	showConfirmDialog('Confirm', 'Are you sure to delete this comment?', deleteOverallComment, null, id);
}

function deleteTC(id) {
	showConfirmDialog('Confirm', 'Are you sure to delete this comment?', deleteTaskComment, null, id);
}

function removeMembersFromProject(data) {
	showProgressDialog('Please wait...');
			
	$.post(
		REMOVE_MEMBERS_FROM_PROJECT_URL,
		{
			projectId		: $('#projectsListCombobox').val(),
			memberIdsList	: getSelectedMemberIdsListString()
		},
		function(data) {
			$('#membersInProjectList').val(0);
			mySubmit();
		}
	);
}

function deleteTask(data) {
	showProgressDialog('Please wait...');
	
	$.post(
		DELETE_TASK_URL,
		{
			taskId			: $('#taskdetail_taskId').val()
		},
		function(data) {
			mySubmit();
		}
	);
}

function deleteOverallComment(data) {
	showProgressDialog('Please wait...');
			
	var projectId = $('#projectsListCombobox').val();
	if (projectId <= 0) return;
	
	$.post(
		DEL_OVERALL_COMMENT_URL,
		{
			ocId		: data,
			projectId	: projectId
		},
		function(datajson) {
			closeProgressDialog();
			var obj = $.parseJSON(datajson);
			showOverallComments(obj);
		}
	);
}

function deleteTaskComment(data) {
	showProgressDialog('Please wait...');
	
	$.post(
		DEL_TASK_COMMENT_URL,
		{
			tcId		: data,
			// submit taskId to get comments list after delete
			taskId		: $('#taskdetail_taskId').val()
		},
		function(datajson) {
			closeProgressDialog();
			var obj = $.parseJSON(datajson);
			showTaskComments(obj);
		}
	);
}

function changeDate(isNext) {
	var dateString = $('#datepicker').val();
	if (!checkValidDateString(dateString)) {
		showInfoDialog('Error', 'Wrong date format, please enter yyyy-MM-dd', true);
		return;
	}
	
	var p = dateString.split('-');
	
	var d = new Date(p[0], p[1] - 1, p[2]);
	
	var ms = d.getTime();
	var ONE_DAY_MS = 24 * 60 * 60 * 1000;
	if (isNext) ms += ONE_DAY_MS;
	else ms -= ONE_DAY_MS;
	
	var e = new Date(ms);
	$('#datepicker').val(
		e.getFullYear() + DATE_SEPARATE +
		addZero(e.getMonth() + 1, 2) + DATE_SEPARATE +
		addZero(e.getDate(), 2)
	);
}

function applyAutoCompleteTask(sltTitle, sltDesc) {
	sltTitle.autocomplete({
		source: TEMPLATE_SOURCES,
		minLength: 0,
		open: function() {
			sltTitle.autocomplete("widget").width( sltTitle.width() )
			sltTitle.autocomplete("widget").css('max-height', '400px');
			sltTitle.autocomplete("widget").css('overflow-y', 'auto');
		},
		select: function( event, ui ) {
			$(sltDesc).val(ui.item.label);
		}
	});
		
	sltTitle.click(function() {
		sltTitle.autocomplete( "search", "" );
	});
	
	$.ui.autocomplete.prototype._renderItem = function(ul, item) {
		var _title = item.label.split(CHEAT_SEPA)[0];
		var _desc = item.label.split(CHEAT_SEPA)[1];
		if (_desc.trim() == '') {
			_desc = '// No description';
		}
		
		var _a = $( "<a>" );
		
		var _div_title = $( "<div>" ).css('font-weight', 'bold').addClass('ellipsis').text( _title );
		var _div_desc = $( "<div>" ).css('font-style', 'italic').addClass('ellipsis').text( _desc );
		
		_a.append(_div_title).append(_div_desc);
		
		return $( "<li>" )
		    .data(
				"item.autocomplete",
				{
					value: item.label.split(CHEAT_SEPA)[0],
					label: item.label.split(CHEAT_SEPA)[1]
				}
			)
			.append( _a )
		    .appendTo( ul );
	}
}

//==========================================================================================================
//==========================================================================================================

$(document).ready(function() {
	initConfirmDialog('confirmDialog');
	initProgressDialog('progressDialog');
	initInfoDialog('infoDialog');
	
	$('#tabs').tabs();
	$('#detail_tabs').tabs();
	
	$('#datepicker').datepicker({
		//dateFormat: 'DD, MM d, yy',
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X',
		
		onSelect: function(dateText, inst) {
			//var dateObj = $(this).datepicker('getDate');
			//curDay = dateObj.getDate();
			//curMonth = dateObj.getMonth() + 1;
			//curYear = dateObj.getFullYear();
			
			//mySubmit();
		}
	});

	$('#projectsListCombobox').change(function() {
		var projectId = $('#projectsListCombobox').val();
	
		$('#projectId').val( projectId > 0 ? projectId : '' );
		$('#dateString').val(projectId > 0 ? getCurrentDateString() : '');
		$('#memberIdsList').val('');
		
		$('#myForm').submit();
	});
	
	//========================================================================================
	// BUTTON
	//========================================================================================
	$('#buttonFilter').click(function() {
		mySubmit();
	});
	
	$('#button_allDays').click(function() {
		$('#datepicker').val('');
	});
	
	$('#button_today').click(function() {
		$('#datepicker').val(getCurrentDateString());
	});
	
	$('#button_apply').click(function() {
		var dateString = $('#datepicker').val();
		if (dateString != '' && !checkValidDateString(dateString)) {
			showInfoDialog('Error', 'Wrong date format, please enter yyyy-MM-dd', true);
			return;
		}
		mySubmit();
	});
	
	$('#button_nextDay').click(function() {
		changeDate(true);
	});
	
	$('#button_prevDay').click(function() {
		changeDate(false);
	});
	
	$('#buttonRemoveMembers').click(function() {
		showConfirmDialog('Confirm', 'Are you sure to remove these members from this project?', removeMembersFromProject);
	});
	
	$('#buttonAddMembers').click(function() {
		var memberId = $('#freeMembersListComboBox').val();
		if (memberId <= 0) return;
		var projectId = $('#projectsListCombobox').val();
		if (projectId <= 0) return;
		
		showProgressDialog('Please wait...');
		
		$.post(
			ADD_MEMBER_TO_PROJECT_URL,
			{
				projectId		: projectId,
				memberId		: memberId
			},
			function(data) {
				$('#membersInProjectList').val(0);
				mySubmit();
			}
		);
	});
	
	$('#buttonAddNewTask').click(function() {
		var projectId = $('#projectsListCombobox').val();
		if (projectId <= 0) return;
		
		var title = $.trim( $('#newtask_title').val() );
		if (title.length == 0)
		{
			showInfoDialog('Error', 'Title can not be blank!', true);
			return;
		}
		
		$(this).attr('disabled', true);
		$(this).val('Adding...');
		
		$.post(
			ADD_NEW_TASK_URL,
			{
				projectId		: projectId
				,title			: title
				,desc			: $.trim( $('#newtask_desc').val() )
				,priority		: $('#newtask_priority').val()
				,assigneeId		: $('#newtask_assigneeId').val()
				//,createdDate	: curYear + DATE_SEPARATE + curMonth + DATE_SEPARATE + curDay
			},
			function(data) {
				$('#buttonAddNewTask').attr('disabled', false);
				$('#buttonAddNewTask').val('Add');
				
				showInfoDialog('Info', 'The new task has been added successfully!');
			}
		);
	});
	
	$('#buttonUpdateTask').click(function() {
		var taskId = $('#taskdetail_taskId').val();
		
		var title = $.trim( $('#updatetask_title').val() );
		if (title.length == 0)
		{
			showInfoDialog('Error', 'Title can not be blank!', true);
			return;
		}
		
		$(this).attr('disabled', true);
		$(this).val('Saving...');
		
		$.post(
			UPDATE_TASK_URL,
			{
				taskId			: taskId,
				title			: title,
				desc			: $.trim( $('#updatetask_desc').val() ),
				priority		: $('#updatetask_priority').val(),
				assigneeId		: $('#updatetask_assigneeId').val()
			},
			function(data) {
				$('#buttonUpdateTask').attr('disabled', false);
				$('#buttonUpdateTask').val('Save');
				
				showInfoDialog('Info', 'The task has been saved successfully!');
			}
		);
	});
	
	$('#buttonDeleteTask').click(function() {
		showConfirmDialog('Confirm', 'Are you sure to delete this task?', deleteTask);
	});
	
	$('#buttonUpdatePercentComplete').click(function() {
		var taskId = $('#taskdetail_taskId').val();
		var newPC = $('#updatetask_percentComplete').val();
		
		$(this).attr('disabled', true);
		$(this).val('Updating...');
		
		$.post(
			UPDATE_TASK_PC_URL,
			{
				taskId			: taskId,
				percentComplete	: newPC
			},
			function(data)
			{
				$('#buttonUpdatePercentComplete').attr('disabled', false);
				$('#buttonUpdatePercentComplete').val('Update');
			}
		);
	});
	
	$('#buttonPostOverallComment').click(function() {
		var projectId = $('#projectsListCombobox').val();
		if (projectId <= 0) return;
		
		var content = $.trim( $('#overall_comment').val() );
		if (content.length == 0)
		{
			showInfoDialog('Error', 'Content of comment can not be blank!', true);
			return;
		}
		
		$(this).attr('disabled', true);
		$(this).val('...');
		
		$.post(
			ADD_OVERALL_COMMENT_URL,
			{
				projectId			: projectId,
				content				: content
			},
			function(data)
			{
				$('#buttonPostOverallComment').attr('disabled', false);
				$('#buttonPostOverallComment').val('Post');
				
				var obj = $.parseJSON(data);
				
				showOverallComments(obj);
				
				$('#overall_comment').val('');
			}
		);
	});
	
	$('#buttonPostTaskComment').click(function() {
		var taskId = $('#taskdetail_taskId').val();
		
		var content = $.trim( $('#task_comment').val() );
		if (content.length == 0)
		{
			showInfoDialog('Error', 'Content of comment can not be blank!', true);
			return;
		}
		
		$(this).attr('disabled', true);
		$(this).val('...');
		
		$.post(
			ADD_TASK_COMMENT_URL,
			{
				taskId				: taskId,
				content				: content
			},
			function(data)
			{
				var obj = $.parseJSON(data);
				
				$('#buttonPostTaskComment').attr('disabled', false);
				$('#buttonPostTaskComment').val('Post');
				
				showTaskComments(obj);
				
				$('#task_comment').val('');
			}
		);
	});
	
	//========================================================================================
	// DIALOG
	//========================================================================================
	$( "#dialog-task-detail" ).dialog({
		modal: true,
		width: 800,
		height: 570,
		resizable: true,
		autoOpen: false,
		buttons: {
			'Close': function() {
				$(this).dialog('close');
			},
			'Close and Refresh': function() {
				$(this).dialog('close');
				mySubmit();
			}
		},
		open: function() {
			$('body').addClass('noscroll');
		},
		close: function() {
			$('body').removeClass('noscroll');
			$('#updatetask_title').autocomplete( "close" );
		}
	});
	
	//========================================================================================
	// AUTOCOMPLETE widget for adding new task
	//========================================================================================
	applyAutoCompleteTask( $('#newtask_title'), $('#newtask_desc') );
	applyAutoCompleteTask( $('#updatetask_title'), $('#updatetask_desc') );
	
});
