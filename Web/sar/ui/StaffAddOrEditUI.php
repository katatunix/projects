<script type="text/javascript">
$(document).ready(function() {
	$('#datepickerSpe').datepicker({
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X'
	});
	$('#datepickerPat_Start').datepicker({
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X'
	});
	$('#datepickerPat_End').datepicker({
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X'
	});
	
	$('input:radio[name=dt]').change(
		function() {
			if ( $(this).is(':checked') && $(this).val() == 'spe' ) {
				enableSpe();
			}
			else if ( $(this).is(':checked') && $(this).val() == 'pat' ) {
				enablePat();
			}
		}
	);
	
	//
	enableSpe();
});

function onSubmit() {
	// make spe and pat
	var el = $('input:radio[name=dt]:checked');
	if (el.val() == 'spe') {
		
	} else {
		
	}
}

</script>

<?php
	if ( isset($message) && $message->list ) {
?>
		<ul style="color: <?= $message->isError ? 'red' : 'green' ?>">
			<?php foreach ($message->list as $mes) { ?>
				<li><?= $mes ?></li>
			<?php } ?>
		</ul>
<?php
	}
?>

<?php
	if ($accounts == NULL) {
?>

	<form method='POST' action="addstaffaccount" onsubmit="onSubmit()">
		<table>
			<tr>
				<td>Username</td>
				<td><input type="text" name="username"/></td>
			</tr>
			<tr>
				<td>Fullname</td>
				<td><input type="text" name="fullname"/></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td>
					<input type="radio" id="genderm" name="gender" value="0" checked="TRUE" />
					<label for="genderm">Male</label>
					<input type="radio" id="genderf" name="gender" value="1" />
					<label for="genderf">Female</label>
				</td>
			</tr>
			<tr>
				<td>Date of Birth</td>
				<td>
					<input type="text" id="datepickerSpe" class="datepickerSpe" name="dob"/>
				</td>
			</tr>
			<tr>
				<td>Role</td>
				<td>
					<select name="role" id="role">
						<option value="1">Admin</option>
						<option value="2">Coordinator</option>
						<option value="3">Lecturer</option>
						<option value="4">Tutor</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Active State</td>
				<td><input type="checkbox" id="isActive" name="isActive" /></td>
			</tr>
			<tr>
				<td><input type="Submit" name="btnSubmit"/></td>
			</tr>
		</table>
	</form>

<?php
} else {
?>
	<form method='POST' action="editstaffaccount" onsubmit="onSubmit()">
		<table>
			<tr><td><input type="hidden" name="id" value="<?= $accounts->id ?>"/></td></tr>
			<tr>
				<td>Username</td>
				<td><input type="text" name="username" value="<?= $accounts->username ?>" disabled="true"/></td>
			</tr>
			<tr>
				<td>Fullname</td>
				<td><input type="text" name="fullname" value="<?= $accounts->fullname ?>"/></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td><?php if($accounts->gender == 0){ ?>
				
					<input type="radio" id="genderm" name="gender" checked="true" disabled/>
					<label for="genderm">Male</label>
					<input type="radio" id="genderf" name="gender" disabled/>
					<label for="genderf">Female</label>
					 <?php } else { ?>
					<input type="radio" id="genderm" name="gender" disabled/>
					<label for="genderm">Male</label>
					<input type="radio" id="genderf" name="gender" checked="true" disabled/>
					<label for="genderf">Female</label>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td>Date of Birth</td>
				<td>
					<input type="text" id="datepickerSpe" class="datepickerSpe" name="dob" value="<?= $accounts->dob ?>"/>
				</td>
			</tr>
			<tr>
				<td>Role</td>
				<td>
					<select name="role" id="role" disabled="true">
						<option value="1">Admin</option>
						<option value="2">Coordinator</option>
						<option value="3">Tutor</option>
						<option value="4">Lecturer</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Active State</td>
					<?php if($accounts->isActive){ ?>
					<td><input type="checkbox" name="isActive" checked></input></td>
					<?php } else { ?>
					<td><input type="checkbox" name="isActive"></input></td>
					<?php }?>	
			</tr>
			<tr>
				<td><input type="Submit" name="btnSubmit"/></td>
				<td></td>
			</tr>
		</table>
	</form>
<?php } ?>