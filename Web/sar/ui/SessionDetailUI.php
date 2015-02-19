<script>

$(document).ready(function() {
	$('#cb_checkAll').change(function() {
		var el = $('input[name^=cb_attend]');
		if ( $(this).is(':checked') ) {
			el.attr('checked', 'checked');
		} else {
			el.removeAttr('checked');
		}
	});
	
	$( "#dialogComposeReason" ).dialog({
		modal: true,
		width: 500,
		height: 300,
		resizable: true,
		autoOpen: false,
		buttons: {
			'Submit': function() {
				$(this).dialog('close');
				$('#updateReasonForm').submit();
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		},
		open: function() {
			//$('body').addClass('noscroll');
		},
		close: function() {
			//$('body').removeClass('noscroll');
		}
	});
		
	$('#removeLink').click(function() {
		showConfirmDialog('Confirm', 'Are you sure to remove this session?', sureDelete, null, null);
		return false;
	});
	
	$('#activeLink').click(function() {
		var isActive = <?= $session->isActive ?>;
		var str = isActive ? 'deactivate' : 'activate';
		showConfirmDialog('Confirm', 'Are you sure to ' + str + ' this session?', sureActivate, null, null);
		return false;
	});
	
	initConfirmDialog('confirmDialog');
});

function authorize(approved, studentAisId) {
	$('#authorizeForm input[name=studentAisId]').val(studentAisId);
	$('#authorizeForm input[name=isApproved]').val(approved);
	
	$('#authorizeForm').submit();
}

function submitAttend() {
	var el = $('input[name^=cb_attend]');
	var studentAisIdList = '';
	var presentedList = '';
	var first = true;
	
	el.each(function() {
		var name = $(this).attr('name').substr('cb_attend'.length + 1);
		if (first) {
			first = false;
		} else {
			studentAisIdList += ',';
			presentedList += ',';
		}
		studentAisIdList += name;
		presentedList += $(this).is(':checked') ? '1' : '0';
		
	});
	
	$('#enterAttendanceForm input[name=studentAisIdList]').val(studentAisIdList);
	$('#enterAttendanceForm input[name=presentedList]').val(presentedList);
	
	$('#enterAttendanceForm').submit();	
}

function openDialogComposeReason() {
	$( "#dialogComposeReason" ).dialog('open');
}

function sureDelete(data) {
	$('#removeSessionForm').submit();
}

function sureActivate(data) {
	$('#activateSessionForm').submit();
}

</script>

<a href="<?= $backLink ?>">&lt;&lt; Back to Sessions List</a>
<br /><br />

<?php
	if ($message) {
		if ($isSuccess) {
			echo '<span style="color:green">';
		} else {
			echo '<span style="color:red">';
		}
		echo htmlspecialchars($message);
		echo '</span><br />';
	}

	if ($isFoundSession) {
?>
<b>Course:</b> <?= htmlspecialchars($course->name) ?> / <?= $course->startDate ?> / <?= $course->weeks ?> weeks.
<br />

<b>Session type:</b> <?= $session->stype == 0 ? 'Lecture' : 'Lab' ?>.
<b>Room:</b> <?= htmlspecialchars($room->name) ?>.
<br />

<b>Teacher:</b> <?= $teacher->gender == 0 ? 'Mr' : 'Ms' ?>. <?= htmlspecialchars($teacher->fullname) ?>.

<?php if ($session->stype == 1) { ?>
<b>Lab group:</b> <?= $session->labGroup ?>.
<?php } ?>
<br />

<b>Start datetime:</b> <?= $session->startDatetime ?>.
<b>Duration:</b> <?= $session->minutes ?> minutes.
<br />

<b>Status:</b> <?= $session->isActive ? 'Activated' : 'Deactivated' ?>.
<br />

<?php if ($roleString == 'Admin' || $roleString == 'Coordinator') { ?>
<a href="<?= __SITE_CONTEXT ?>/session/edit?id=<?= $session->id ?>">[Edit]</a>
<a href="#" id="removeLink">[Remove]</a>
<a href="#" id="activeLink">[<?= $session->isActive ? 'Deactivated' : 'Activate' ?>]</a>
<?php } ?>

<center><h3><?= count( $students ) ?> students</h3></center>
<br />

<table width="100%" border="1" cellpadding="4" cellspacing="0">
	<thead>
		<tr>
			<th>AIS Id</th>
			<th>Full name</th>
			<th>DOB</th>
			<th>Gender</th>
			<th>Regis. stt</th>
			<th width="170px">Attendance</th>
			<?php if ($session->stype == 1) { ?>
			<th width="170px">Reason</th>
			<?php } ?>
		</tr>
	</thead>
	
	<tbody>
	
	<?php foreach ($students as $std)  {
		$attId = $session->id . '_' . $std->studentAisId;
		$attStatus = -1; // not set
		$att = NULL;
		if ( isset($attendances[$attId]) ) {
			$att = $attendances[$attId];
			if ( isset($att->hidden) ) {
				$attStatus = -2; // hidden
			} else if (is_null($att->isPresented)) {
				$attStatus = -1; // not set
			} else {
				$attStatus = $att->isPresented ? 1 : 0;
			}
		}
		if ($roleString == 'Student' && $std->studentAisId != $loggedStudentAisId) {
			$attStatus = -2; // hidden
		}
		//
		$regisId = $course->id . '_' . $std->studentAisId;
		$regisStatus = -1; // out
		if (isset($registrations[$regisId])) {
			// 0 = temp; 1 = official;
			$regisStatus = $registrations[$regisId]->isTemp ? 0 : 1;
		}
	?>
	<tr valign="top">
		<td><?= $std->id ?></td>
		<td>
			<a target="_blank" href="<?= __SITE_CONTEXT ?>/student?id=<?= $std->id ?>" title="View information of this student">
				<?= htmlspecialchars($std->fullname) ?>
			</a>
		</td>
		<td><?= $std->dob ?></td>
		<td><?= $std->genderString ?></td>
		<td>
			<?php if ($regisStatus == -1) {?>
				Out
			<?php } ?>
			<?php if ($regisStatus == 0) {?>
				Temp
			<?php } ?>
			<?php if ($regisStatus == 1) {?>
				Official
			<?php } ?>
		</td>
		
		<td>
			<?php if ($session->isActive && ($roleString == 'Lecturer' || $roleString == 'Tutor')) { ?>
				<input type="checkbox" name="cb_attend_<?= $std->id ?>" <?= $attStatus == 1 ? 'checked' : '' ?> />
			<?php } ?>
			
			<?php if ($attStatus == -1) {?>
				Not set
			<?php } ?>
			<?php if ($attStatus == 0) {?>
				<span style="color:red">Absent</span>
			<?php } ?>
			<?php if ($attStatus == 1) {?>
				<span style="color:green">Presented</span>
			<?php } ?>
		</td>
		
		<?php if ($session->stype == 1) { ?>
		<td>
		<?php if ($attStatus != -2 && $att && isset($att->reason)) { ?>
			<div style="font-weight: bold">
				<?= $att->reasonStatusString ?>
				
				<?php if ($session->isActive && $roleString == 'Tutor') { ?>
					<?php if ($att->reasonStatusString != 'Approved') { ?>
						<input type="button" value="Appr." onclick="authorize(1, <?= $std->studentAisId ?>)" />
					<?php } ?>
					<?php if ($att->reasonStatusString != 'Denied') { ?>
						<input type="button" value="Deny" onclick="authorize(0, <?= $std->studentAisId ?>)" />
					<?php } ?>
				<?php } ?>
			</div>
			
			<div style="margin-top: 5px; font-size: 0.85em">
				<?= htmlspecialchars($att->reason) ?>
			</div>	
		<?php } ?>
		
		<?php if ($attStatus != -2 && $session->isActive && $roleString == 'Student') { ?>
			<div align="right" style="margin-top:5px">
				<input type="button" value="Update" onclick="openDialogComposeReason()" />
			</div>
		<?php } ?>
		</td>
		<?php } ?>
		
	</tr>
	<?php } ?>
	
	<?php if (($roleString == 'Lecturer' || $roleString == 'Tutor') && $session->isActive && count( $students ) > 0) { ?>
		<tr>
		<td></td> <td></td> <td></td> <td></td> <td></td>
		<td>
			<label><input type="checkbox" id="cb_checkAll" />Check all</label>
			<input type="button" value="Submit" onclick="submitAttend()" style="font-weight: bold" />
		</td>
		<?php if ($session->stype == 1) { ?>
		<td></td>
		</tr>
		<?php } ?>
	<?php } ?>
	
	</tbody>
</table>

<?php if ($session->isActive && $roleString == 'Tutor') { ?>
	<form id="authorizeForm" action="<?= __SITE_CONTEXT ?>/attendance/authorizeAbsenceForm" method="POST">
		<input type="hidden" name="sessionId" value="<?= $session->id ?>" />
		<input type="hidden" name="studentAisId" />
		<input type="hidden" name="isApproved" />
	</form>
<?php } ?>

<?php if ($session->isActive && ($roleString == 'Lecturer' || $roleString == 'Tutor')) { ?>
	<form id="enterAttendanceForm" action="<?= __SITE_CONTEXT ?>/attendance/enterAttendance" method="POST">
		<input type="hidden" name="sessionId" value="<?= $session->id ?>" />
		<input type="hidden" name="studentAisIdList" />
		<input type="hidden" name="presentedList" />
	</form>
<?php } ?>

<?php if ($session->isActive && $roleString == 'Student') { ?>
	<div id="dialogComposeReason" title="Update Reason">
	The reason length must be less than 256 characters.
	<p></p>

	<form id="updateReasonForm" action="<?= __SITE_CONTEXT ?>/attendance/createAbsenceForm" method="POST">
		<input type="hidden" name="sessionId" value="<?= $session->id ?>" />
		<input type="hidden" name="studentAisId" value="<?= $loggedStudentAisId ?>" />
		<textarea name="reason" style="resize:none; width: 99%; height: 145px"></textarea>
	</form>
	</div>
<?php } ?>

<?php if ($roleString == 'Admin' || $roleString == 'Coordinator') { ?>
	<form id="removeSessionForm" action="<?= __SITE_CONTEXT ?>/session/remove" method="POST">
		<input type="hidden" name="sessionId" value="<?= $session->id ?>" />
	</form>
	
	<form id="activateSessionForm" action="<?= __SITE_CONTEXT ?>/session/activeDeactive" method="POST">
		<input type="hidden" name="sessionId" value="<?= $session->id ?>" />
		<input type="hidden" name="isActive" value="<?= $session->isActive ? '0' : '1' ?>" />
	</form>
	
	<div id="confirmDialog"><p></p></div>
<?php } ?>

<?php }//if ($isFoundSession) ?>
