<?php if (!$isEdit || ($isEdit && $isFoundSession)) { ?>

<script type="text/javascript">

var DIS = 'disabled';

var g_spe = '<?= $spe ?>';
var g_pat = '<?= isset($pat) ? $pat : "" ?>';

function enableSpe() {
	$('#datepickerPat_Start').attr(DIS, DIS);
	$('#datepickerPat_End').attr(DIS, DIS);
	$('#hourPat').attr(DIS, DIS);
	$('#minutePat').attr(DIS, DIS);
	for (var i = 1; i <= 7; i++) {
		$('#cb' + i).attr(DIS, DIS);
	}
	
	$('#datepickerSpe').removeAttr(DIS);
	$('#hourSpe').removeAttr(DIS);
	$('#minuteSpe').removeAttr(DIS);
}

function enablePat() {
	$('#datepickerPat_Start').removeAttr(DIS);
	$('#datepickerPat_End').removeAttr(DIS);
	$('#hourPat').removeAttr(DIS);
	$('#minutePat').removeAttr(DIS);
	for (var i = 1; i <= 7; i++) {
		$('#cb' + i).removeAttr(DIS);
	}
	
	$('#datepickerSpe').attr(DIS, DIS);
	$('#hourSpe').attr(DIS, DIS);
	$('#minuteSpe').attr(DIS, DIS);
}

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
	<?php if (!$isEdit) { ?>
	if (g_pat) {
		$('input:radio[name=dt]')[1].checked = true;
		enablePat();
		
		var p = g_pat.split(' ');
		if (p[2]) {
			$('#datepickerPat_Start').val(p[2]);
		}
		if (p[3]) {
			$('#datepickerPat_End').val(p[3]);
		}
		if (p[1]) {
			var weekDays = p[1].split(',');
			for (var i = 0; i < weekDays.length; i++) {
				$('#cb' + weekDays[i]).attr('checked', true);
			}
		}
		if (p[0]) {
			p = p[0].split(':');
			if (p[0]) {
				$('#hourPat').val(p[0]);
			}
			if (p[1]) {
				$('#minutePat').val(p[1]);
			}
		}
		
	}
	else
	<?php } ?>
	{
		$('input:radio[name=dt]')[0].checked = true;
		enableSpe();
		
		var p = g_spe.split(' ');
		$('#datepickerSpe').val(p[0]);
		
		if (p[1]) {
			p = p[1].split(':');
			if (p[0]) {
				$('#hourSpe').val(p[0]);
			}
			if (p[1]) {
				$('#minuteSpe').val(p[1]);
			}
		}
	}
	//
});

function onSubmit() {
	// make spe and pat
	var el = $('input:radio[name=dt]:checked');
	
	if (el.val() == 'spe')
	{
		var spe = $('#datepickerSpe').val() + ' ' + $('#hourSpe').val() + ':' + $('#minuteSpe').val();
		$('#hiddenSpe').val(spe);
		
	}
	<?php if (!$isEdit) { ?>
	else
	{
		var pat = $('#hourPat').val() + ':' + $('#minutePat').val() + ' ';
		var isFirst = true;
		for (var i = 1; i <= 7; i++) {
			var cb = $('#cb' + i);
			if (cb.is(':checked')) {
				if (isFirst) {
					isFirst = false;
				} else {
					pat += ',';
				}
				pat += i;
			}
		}
		
		pat += ' ' + $('#datepickerPat_Start').val() + ' ' + $('#datepickerPat_End').val();
		
		$('#hiddenPat').val(pat);
	}
	<?php } ?>
}

</script>

<?php } ?>

<?php if ($isEdit) { ?>
	<a href="<?= __SITE_CONTEXT . "/session?id=$sid" ?>">&lt;&lt; Back to Session Detail</a>
	<br />
<?php } ?>

<?php
	if ($message) {
		if ($isSuccess) {
			echo '<p style="color:green">';
		} else {
			echo '<p style="color:red">';
		}
		echo htmlspecialchars($message);
		echo '</p>';
	}
	
	if (!$isEdit || ($isEdit && $isFoundSession)) {
?>

<form action="" method="POST" onsubmit="onSubmit()">

<table cellpadding="5">
	<tr>
		<td>
		<select name="stype">
			<option <?= $stype == NULL ? 'selected' : '' ?> value="">-- Select a type --</option>
			<option <?= $stype == 'lec' ? 'selected' : '' ?> value="lec">Lecture session</option>
			<option <?= $stype == 'lab' ? 'selected' : '' ?> value="lab">Lab session</option>
		</select>
		</td>
		
		<td>
			<select name="cid">
				<option <?= $cid == 0 ? 'selected' : '' ?> value="0">-- Select a course --</option>
			<?php
				foreach ($courses as $course) {
					$isSel = $course->id == $cid ? 'selected' : '';
					echo "<option $isSel value='$course->id'>";
					echo htmlspecialchars($course->name);
					echo "</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<select name="rid">
				<option <?= $rid == 0 ? 'selected' : '' ?> value="0">-- Select a room --</option>
			<?php
				foreach ($rooms as $room) {
					$isSel = $room->id == $rid ? 'selected' : '';
					$rtypeShowing = $room->rtype == 0 ? 'Lec. theater' : 'Lab room';
					
					echo "<option $isSel value='$room->id'>$rtypeShowing : ";
					echo htmlspecialchars($room->name);
					echo "</option>";
				}
			?>	
			</select>
		</td>
		<td>
			<select name="tid">
				<option <?= $tid == 0 ? 'selected' : '' ?> value="0">-- Select a teacher --</option>
			<?php
				foreach ($teachers as $teacher) {
					$isSel = $teacher->id == $tid ? 'selected' : '';
					echo "<option $isSel value='$teacher->id'>$teacher->roleString : ";
					echo htmlspecialchars($teacher->fullname);
					echo "</option>";
				}
			?>	
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Duration minutes
			<select name="mins">
			<?php
				for ($i = 30; $i <= 360; $i++ ) {
					$isSel = $i == $mins ? 'selected' : '';
					echo "<option $isSel value='$i'>$i</option>";
				}
			?>
			</select>
		</td>
		<td>
			Lab group
			<select name="g">
			<?php
				for ($i = 0; $i <= 10; $i++ ) {
					$isSel = $i == $g ? 'selected' : '';
					echo "<option $isSel value='$i'>$i</option>";
				}
			?>
			</select> <i>[ will be ignored for lecture session ]</i>
		</td>
	</tr>
</table>

<p></p>
<label><input type="radio" name="dt" value="spe" checked />Specific datetime</label>
<div style="margin-left: 50px; margin-top: 10px">
	<input type="text" id="datepickerSpe" value="" size="10" />
	<select id="hourSpe">
	<?php
		for ($i = 0; $i <= 23; $i++ ) {
			//$isSel = $i == $mins ? 'selected' : '';
			$isSel = '';
			echo "<option $isSel value='$i'>$i</option>";
		}
	?>
	</select>
	:
	<select id="minuteSpe">
	<?php
		for ($i = 0; $i <= 59; $i++ ) {
			//$isSel = $i == $mins ? 'selected' : '';
			$isSel = '';
			echo "<option $isSel value='$i'>$i</option>";
		}
	?>
	</select>
</div>

<?php if (!$isEdit) { ?>

<p></p>
<label><input type="radio" name="dt" value="pat" />Pattern datetime</label>
<div style="margin-left: 50px; margin-top: 10px">
	<select id="hourPat">
	<?php
		for ($i = 0; $i <= 23; $i++ ) {
			//$isSel = $i == $mins ? 'selected' : '';
			$isSel = '';
			echo "<option $isSel value='$i'>$i</option>";
		}
	?>
	</select>
	:
	<select id="minutePat">
	<?php
		for ($i = 0; $i <= 59; $i++ ) {
			//$isSel = $i == $mins ? 'selected' : '';
			$isSel = '';
			echo "<option $isSel value='$i'>$i</option>";
		}
	?>
	</select>
	
	<label><input type="checkbox" id="cb1" />Sun</label>
	<label><input type="checkbox" id="cb2" />Mon</label>
	<label><input type="checkbox" id="cb3" />Tue</label>
	<label><input type="checkbox" id="cb4" />Wed</label>
	<label><input type="checkbox" id="cb5" />Thu</label>
	<label><input type="checkbox" id="cb6" />Fri</label>
	<label><input type="checkbox" id="cb7" />Sat</label>

	<p></p>
	From date
	<input type="text" id="datepickerPat_Start" value="" size="10" />
	to date
	<input type="text" id="datepickerPat_End" value="" size="10" />
</div>
<!--Pattern datetime-->
<?php } ?>

<p></p>

<?php if ($isEdit) { ?>
	<input type="submit" value="Update" style="font-size: 1.5em" />
	<input type="hidden" name="sid" value="<?= $sid ?>" />
<?php } else { ?>
	<input type="submit" value="Create" style="font-size: 1.5em" />
<?php } ?>

<input type="hidden" name="spe" id="hiddenSpe" />
<input type="hidden" name="pat" id="hiddenPat" />



</form>

<?php } ?>
