<?php if ($printableLink) { ?>	

<script type="text/javascript">
$(document).ready(function() {

	$('#datepicker_Start').datepicker({
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X'
	});
	$('#datepicker_End').datepicker({
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: true,
		showButtonPanel: true,
		closeText: 'X'
	});
});

function mySubmit() {
	//$('input[name=cw]').val(1);
	$('#myFrom').submit();
}

function currentWeek() {
	$('input[name=cw]').val(1);
	$('#myFrom').submit();
}

function allDates() {
	$('#datepicker_Start').val('');
	$('#datepicker_End').val('');
	$('#myFrom').submit();
}

</script>

<form action="" method="GET" id="myFrom">

<select name="cid" onchange="mySubmit()">
	<option <?= $cid == 0 ? 'selected' : '' ?> value="0">-- Select a course --</option>
<?php
	foreach ($courses as $c) {
		$isSel = $c->id == $cid ? 'selected' : '';
		echo "<option $isSel value='$c->id'>$c->name</option>";
	}
?>
</select>

<select name="lg" onchange="mySubmit()">
<option value="0">-- Select a group --</option>
<?php
	foreach ($lgs as $g) {
		$isSel = $g == $lg ? 'selected' : '';
		echo "<option $isSel value='$g'>Group : $g</option>";
	}
?>
</select>

<p></p>

From date <input type="text" name="f" id="datepicker_Start" value="<?= $fromDate ?>" size="10" />
to date <input type="text" name="t" id="datepicker_End" value="<?= $toDate ?>" size="10" />
<input type="submit" value="OK" />
<input type="button" value="All dates" onclick="allDates()" />
<input type="button" value="Current week" onclick="currentWeek()" />

<input type="hidden" name="cw" value="" />
</form>

<p></p>
<br />
<?php } //if ($printableLink) { ?>


<?php if ($printableLink) { ?>
	<?php if ($course) { ?>
		<h3 align="center">Course: <?= htmlspecialchars($course->name) ?>.</h3>
		<?php if ($lg) { ?>
			<h3 align="center">Group: <?= $lg ?>.</h3>
		<?php } ?>
		<p align="center">Start date: <?= $course->startDate ?>. Duration: <?= $course->weeks ?> weeks.</p>
	<?php } ?>
<?php } else { ?>
	<?php if ($course) { ?>
		<div align="center" style="font-weight: bold; font-size: 1.2em">
		Course: <?= htmlspecialchars($course->name) ?>.<br />
		<?php if ($lg) { ?>
			Group: <?= $lg ?>.<br />
		<?php } ?>
		Start date: <?= $course->startDate ?>. Duration: <?= $course->weeks ?> weeks.<br />
		</div>
	<?php } ?>
<?php } ?>

<?php if (count($sessions) > 0) {
	foreach ($sessions as $ss) {
		$dt = new DateTime( $ss->startDatetime );
		$ss->startDatetime = $dt->format('m-d H:i');
	}
?>

<?php if ($printableLink) { ?>
	<div style="overflow-x: auto; padding-bottom: 10px">
<?php } else { ?>
	<div style="margin: 10px">
<?php } ?>

<table width="100%" border="1" cellpadding="4" cellspacing="0">
	<tr>
		<td></td>
		<td></td>
		<?php foreach ($sessions as $ss) { ?>
			<td align="center"><?= $ss->startDatetime ?></td>
		<?php } ?>
	</tr>
	
	<?php foreach ($students as $std) { ?>
		<tr>
			<td><?= $std->id ?></td>
			<td style="min-width:200px">
				<?php if ($printableLink) { ?>
					<a href="<?= __SITE_CONTEXT ?>/student/viewFakeAisDetail?id=<?= $std->studentAisId ?>" target="_blank" title="Go to AIS to view this student information"><?= htmlspecialchars( $std->fullname ) ?></a>
				<?php } else { ?>
					<?= htmlspecialchars( $std->fullname ) ?>
				<?php } ?>
			</td>
			<?php foreach ($sessions as $ss) {
				$attStatus = '';
				if (isset($attendances)) {
					$aid = $ss->id . '_' . $std->id;
					if (isset($attendances[$aid])) {
						$pre = $attendances[$aid]->isPresented;
						if ( !is_null($pre) ) {
							$attStatus = $pre ? 'P' : 'A';
						}
					}
				}
			?>
				<td style="min-width: 40px" align="center"><?= $attStatus ?></td>
			<?php } ?>
		</tr>
	<?php } ?>
		
</table>

</div>

<p></p>

<?php if ($printableLink) { ?>
	<div align="right"><a target="_blank" href="<?= $printableLink ?>">Printable version</a></div>
<?php } else { ?>
	<div align="center"><a href="javascript:window.print()">Print</a></div>
<?php } ?>

<?php } ?>
