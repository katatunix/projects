<script type="text/javascript" src="<?= __UI_DIR_URL ?>/jquery/jquery.highlight-4.js"></script>

<script type="text/javascript">

function courseChange() {
	if ( $('#cid').val() == 0 ) {
		$('#lg').attr('disabled', 'disabled');
	} else {
		$('#lg').removeAttr('disabled');
	}
}

function genAcc() {
	var el = $('input[name^=cb_gen]');
	var studentAisIdList = '';
	var first = true;
	
	el.each(function() {
		if ( $(this).is(':checked') ) {
			var name = $(this).attr('name').substr('cb_gen'.length + 1);
			
			if (first) {
				first = false;
			} else {
				studentAisIdList += ',';
			}
			studentAisIdList += name;
		}
	});
	
	$('#genAccountForm input[name=studentAisIdList]').val(studentAisIdList);
	if (studentAisIdList == '') {
		showInfoDialog('Error', 'Please select at least one student.', true);
		return;
	}
	$('#genAccountForm').submit();
}

function submitGroups() {
	var el = $('select[id^=_lg]');
	var studentAisIdList = '';
	var groupsList = '';
	var first = true;
	
	el.each(function() {
		var name = $(this).attr('id').substr('_lg'.length + 1);
		
		if (first) {
			first = false;
		} else {
			studentAisIdList += ',';
			groupsList += ',';
		}
		studentAisIdList += name;
		groupsList += $(this).val();
	});
	
	$('#saveGroupForm input[name=studentAisIdList]').val(studentAisIdList);
	$('#saveGroupForm input[name=groupsList]').val(groupsList);
	
	$('#saveGroupForm').submit();
}

function submitRemove() {
	showConfirmDialog('Confirm', 'Are you sure to remove?', sureRemove, null, null);
}

function sureRemove() {
	var el = $('input[name^=cb_rem]');
	var studentAisIdList = '';
	var first = true;
	
	el.each(function() {
		if ( $(this).is(':checked') ) {
			var name = $(this).attr('name').substr('cb_rem'.length + 1);
			
			if (first) {
				first = false;
			} else {
				studentAisIdList += ',';
			}
			studentAisIdList += name;
		}
	});
	
	$('#remTempForm input[name=studentAisIdList]').val(studentAisIdList);
	
	if (studentAisIdList == '') {
		showInfoDialog('Error', 'Please select at least one student.', true);
		return;
	}
	
	$('#remTempForm').submit();
}

<?php if ($cid) { ?>
function openDialogAddTemp() {
	showProgressDialog('Loading students list...', false, null);
	
	$.post(
		"<?= __SITE_CONTEXT ?>/student/getTempList",
		{
			cid: <?= $cid ?>
		},
		function(data) {
			closeProgressDialog();
			
			var dialog = $( "#dialogSelectTempStudents" );
			dialog.empty();
			
			var arr = eval(data);
			if (arr) {
				var table = $('<table>').attr('width', '100%').attr('cellpadding', '4');
				for (var i = 0; i < arr.length; i++) {
					var a = $('<a>').attr('target', '_blank')
						.attr('href', '<?= __SITE_CONTEXT ?>/student?id=' + arr[i].id).text(arr[i].fullname)
						.attr('title', 'View information of this student');
					var tr = $('<tr>').css('background-color', i % 2 ? '#FFFFFF' : '#EEEEEE');
					tr.append( $('<td>').text(arr[i].id).css('text-align', 'right').css('font-weight', 'bold') );
					tr.append( $('<td>').append(a) );
					tr.append( $('<td>').append(
						$('<input>').attr('type', 'checkbox').attr('name', 'cb_add_temp_' + arr[i].id)
					));
					
					table.append(tr);
				}
				
				dialog.append(table);
				
				$( "#dialogSelectTempStudents" ).dialog({
					modal: true,
					width: 350,
					height: 550,
					autoOpen: false,
					buttons: {
						'Submit': function() {
							var el = $('input[name^=cb_add_temp]');
							var studentAisIdList = '';
							var first = true;
							
							el.each(function() {
								if ( $(this).is(':checked') ) {
									var name = $(this).attr('name').substr('cb_add_temp'.length + 1);
									
									if (first) {
										first = false;
									} else {
										studentAisIdList += ',';
									}
									studentAisIdList += name;
								}
							});
							
							$('#addTempForm input[name=studentAisIdList]').val(studentAisIdList);
							if (studentAisIdList == '') {
								showInfoDialog('Error', 'Please select at least one student.', true);
								return;
							}
							$('#addTempForm').submit();
						},
						'Cancel': function() {
							$(this).dialog('close');
						}
					}
				});
			} else {
				dialog.append($('<div>').text('Not found any student to add to this course.').css('color', 'red'));
				
				$( "#dialogSelectTempStudents" ).dialog({
					modal: true,
					width: 350,
					height: 200,
					autoOpen: false,
					buttons: {
						'OK': function() {
							$(this).dialog('close');
						}
					}
				});
			}
			
			dialog.dialog('open');
		}
	);
}
<?php } ?>

$(document).ready(function() {
	$('.mark_highlight').highlight('<?= $sk ?>');
	
	$('#cb_checkAll').change(function() {
		var el = $('input[name^=cb_gen]');
		if ( $(this).is(':checked') ) {
			el.attr('checked', 'checked');
		} else {
			el.removeAttr('checked');
		}
	});
	
	<?php if ($cid) { ?>
		initProgressDialog('progressDialog');
		initConfirmDialog('confirmDialog');
	<?php } ?>
	
	initInfoDialog('infoDialog');
	
});

</script>

<form action="" method="GET" onsubmit="">

<select id="cid" name="cid" onchange="courseChange()">
	<option <?= $cid == 0 ? 'selected' : '' ?> value="0">-- All courses --</option>
<?php
	foreach ($courses as $course) {
		$isSel = $course->id == $cid ? 'selected' : '';
		echo "<option $isSel value='$course->id'>$course->name</option>";
	}
?>
</select>

<input type="text" name="sk" placeholder="Search key..." value="<?= $sk ?>" />

<select id="lg" name="lg" <?= $cid == 0 ? 'disabled' : '' ?>>
<option value="-1">-- All groups --</option>
<?php
	for ($i = 0; $i <= 10; $i++ ) {
		$isSel = $i == $lg ? 'selected' : '';
		echo "<option $isSel value='$i'>Group : $i</option>";
	}
?>
</select>

<input type="submit" value="Search" />

</form>

<p></p>
<br />

<table width="100%" border="1" cellpadding="4" cellspacing="0">
	<thead>
		<tr>
			<th>Ais Id</th>
			<th>Full name</th>
			<th>Gender</th>
			<th>DOB</th>
			<th>SAR account</th>
			<?php if ($cid) { ?>
				<th>Regis. status</th>
				<th>Group</th>
				<th>Remove</th>
			<?php } ?>
		</tr>
	</thead>
	
	<tbody>
		<?php foreach ($students as $std) {
				if ($cid) {
					$regis = $registrations[$cid . '_' . $std->id];
				}
				
				$sarAccount = '';
				foreach ($accounts as $acc) {
					if ($acc->studentAisId == $std->id) {
						$sarAccount = $acc->username;
						break;
					}
				}
		?>
			<tr>
				<td class="mark_highlight" align="right"><?= $std->id ?></td>
				<td class="mark_highlight">
					<a target="_blank" href="<?= __SITE_CONTEXT ?>/student?id=<?= $std->id ?>" title="View information of this student">
						<?= htmlspecialchars($std->fullname) ?>
					</a>
				</td>
				<td align="right"><?= $std->genderString ?></td>
				<td align="right" class="mark_highlight"><?= $std->dob ?></td>
				<td align="right">
					<?php if ($sarAccount) { ?>
						<span>
					<?php } else {?>
						<span style="color:red">
					<?php } ?>
							<?= $sarAccount ? $sarAccount : 'not have' ?>
						</span>
					<?php if (!$sarAccount) { ?>
						<input type="checkbox" name="cb_gen_<?= $std->id ?>" title="Select to generate account" />
					<?php } ?>
				</td>
				
				<?php if ($cid) { ?>
					<td align="right">
						<?= $regis->isTemp ? 'Temp' : 'Official' ?>
					</td>
					<td align="center">
						<select id="_lg_<?= $std->id ?>">
							<?php
								for ($i = 0; $i <= 10; $i++ ) {
									$isSel = $i == $regis->labGroup ? 'selected' : '';
									echo "<option $isSel value='$i'>$i</option>";
								}
							?>
						</select>
					</td>
					<td align="center">
						<?php if ($regis->isTemp) { ?>
							<input type="checkbox" name="cb_rem_<?= $std->id ?>" title="Select to remove this student" />
						<?php } ?>
					</td>
			<?php } ?>
			</tr>
		<?php } ?>
		
		<?php if (count($students) > 0) { ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td align="right">
				<label><input type="checkbox" id="cb_checkAll" >Check all</label>
				<input type="button" value="Gen. acc." onclick="genAcc()" />
			</td>
			<?php if ($cid) { ?>
				<td></td>
				<td align="center"><input type="button" value="Save" onclick="submitGroups()" /></td>
				<td align="center"><input type="button" value="Remove" onclick="submitRemove()" /></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php if ( $cid ) { ?>
	<p></p>
	<form method="POST" action="<?= __SITE_CONTEXT ?>/student/randomlyAssign">
		<input type="submit" value="Randomly assign"  />
		 none-grouped students with maximum students number in a group is
		<select name="maxnum">
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
		</select>
		
		<input type="hidden" name="cid" value="<?= $cid ?>" />
		<input type="hidden" name="sk" value="<?= $sk ?>" />
		<input type="hidden" name="lg" value="<?= $lg ?>" />
	</form>
	
	<p></p>
	<input type="button" value="Add temporary" onclick="openDialogAddTemp()" /> students to this course.
	
	<div id="dialogSelectTempStudents" title="Add temporary students">
		
	</div>
	
	<div id="progressDialog"><p><div class="progbar"></div></p></div>
	<div id="confirmDialog"><p></p></div>
<?php } ?>

<div id="infoDialog"><p></p></div>

<p></p>
<p align="right"><i><?= count($students) ?> students.</i></p>

<form id="genAccountForm" method="POST" action="<?= __SITE_CONTEXT ?>/student/genAccount">
	<input type="hidden" name="studentAisIdList" />
	<input type="hidden" name="cid" value="<?= $cid ?>" />
	<input type="hidden" name="sk" value="<?= $sk ?>" />
	<input type="hidden" name="lg" value="<?= $lg ?>" />
</form>

<form id="saveGroupForm" method="POST" action="<?= __SITE_CONTEXT ?>/student/assignGroups">
	<input type="hidden" name="studentAisIdList" />
	<input type="hidden" name="groupsList" />
	<input type="hidden" name="cid" value="<?= $cid ?>" />
	<input type="hidden" name="sk" value="<?= $sk ?>" />
	<input type="hidden" name="lg" value="<?= $lg ?>" />
</form>

<form id="addTempForm" method="POST" action="<?= __SITE_CONTEXT ?>/student/addTemp">
	<input type="hidden" name="studentAisIdList" />
	<input type="hidden" name="cid" value="<?= $cid ?>" />
	<input type="hidden" name="sk" value="<?= $sk ?>" />
	<input type="hidden" name="lg" value="<?= $lg ?>" />
</form>

<form id="remTempForm" method="POST" action="<?= __SITE_CONTEXT ?>/student/removeTemp">
	<input type="hidden" name="studentAisIdList" />
	<input type="hidden" name="cid" value="<?= $cid ?>" />
	<input type="hidden" name="sk" value="<?= $sk ?>" />
	<input type="hidden" name="lg" value="<?= $lg ?>" />
</form>
