<script type="text/javascript">

	function onSubmit() {
		$('#m').val( $('#year').val() + '-' + $('#month').val() );
	}

	function setCurMonth() {
		var date = new Date();
		$('#year').val(date.getFullYear());
		var m = date.getMonth() + 1;
		if (m < 10) m = '0' + m;
		$('#month').val(m);
	}

</script>

<form action="" method="GET" onsubmit="onSubmit()">

<select name="cid">
	<option <?= $cid == 0 ? 'selected' : '' ?> value="0">-- All courses --</option>
<?php
	foreach ($courses as $course) {
		$isSel = $course->id == $cid ? 'selected' : '';
?>
		<option <?= $isSel ?> value="<?= $course->id ?>"><?= htmlspecialchars($course->name) ?></option>
<?php
	}
?>
</select>

<select name="rid">
	<option <?= $rid == 0 ? 'selected' : '' ?> value="0">-- All rooms --</option>
<?php
	foreach ($rooms as $room) {
		$isSel = $room->id == $rid ? 'selected' : '';
		$rtypeShowing = $room->rtype == 0 ? 'Lec. theater' : 'Lab room';
?>
		<option <?= $isSel ?> value="<?= $room->id ?>"><?= $rtypeShowing . ' : ' . htmlspecialchars($room->name) ?></option>
<?php
	}
?>	
</select>

<p></p>

<?php if ($roleString != 'Lecturer' && $roleString != 'Tutor') { ?>
<select name="stype">
	<option <?= $stype == NULL ? 'selected' : '' ?> value="">-- All types --</option>
	<option <?= $stype == 'lec' ? 'selected' : '' ?> value="lec">Lecture session</option>
	<option <?= $stype == 'lab' ? 'selected' : '' ?> value="lab">Lab session</option>
</select>
<?php } ?>

<?php if ($roleString != 'Lecturer') { ?>
<select name="lg">
<option value="-1">-- All groups --</option>
<?php
	for ($i = 0; $i <= 10; $i++ ) {
		$isSel = $i == $lg ? 'selected' : '';
		echo "<option $isSel value='$i'>Group : $i</option>";
	}
?>
</select>
<?php } ?>

<select name="active">
	<option <?= $active == 2 ? 'selected' : '' ?> value="2">-- All status --</option>
	<option <?= $active == 1 ? 'selected' : '' ?> value="1">Active</option>
	<option <?= $active == 0 ? 'selected' : '' ?> value="0">Deactive</option>
</select>

<select id="year">
<?php
	$cur = getdate();
	$min = $year - 10;
	$max = $year + 10;
	if ($cur['year'] < $min) $min = $cur['year'];
	if ($cur['year'] > $max) $max = $cur['year'];
	for ($i = $min; $i <= $max; $i++ ) {
		$isSel = $i == $year ? 'selected' : '';
		echo "<option $isSel value='$i'>$i</option>";
	}
?>
</select>

<select id="month">
<?php
	for ($i = 1; $i <= 12; $i++ ) {
		$j = $i;
		if ($i < 10) $j = '0' . $j;
		$isSel = $i == $month ? 'selected' : '';
		echo "<option $isSel value='$j'>$j</option>";
	}
?>
</select>

<input type="button" value="Current" onclick="setCurMonth()" />

<p></p>

<p>
	<input type="submit" value="Search" style="font-size: 1.5em" />
</p>

<input type="hidden" name="m" id="m" />

</form>

<?php
	foreach ($list as $session) {
		$date = new DateTime($session->startDatetime);
		$session->y = $date->format('Y');
		$session->m = $date->format('m');
		$session->d = $date->format('d');
		$session->h = $date->format('H:i');
	}
	
	$today = new DateTime();
	$today_Year		= $today->format('Y');
	$today_Month	= $today->format('m');
	$today_Day		= $today->format('d');
	
	$curMonth = $month;
	$curYear = $year;
	
	echo '<div align="center" style="font-size: 1.5em">';
	if ($stype == NULL) $stype = '';
	
	$nextMonth = $curMonth + 1;
	$nextYear = $curYear;
	if ($nextMonth > 12) {
		$nextMonth = 1;
		$nextYear++;
	}
	if ($nextMonth < 10) $nextMonth = '0' . $nextMonth;
	
	$prevMonth = $curMonth - 1;
	$prevYear = $curYear;
	if ($prevMonth < 1) {
		$prevMonth = 12;
		$prevYear--;
	}
	if ($prevMonth < 10) $prevMonth = '0' . $prevMonth;
	
	$url = __SITE_CONTEXT . "/session/alist?cid=$cid&stype=$stype&rid=$rid&active=$active&lg=$lg&m=$prevYear-$prevMonth";
	echo "<a href='$url' title='Previous month'>&lt;&lt;&nbsp;</a> ";
	echo $curYear . '-' . $curMonth;
	$url = __SITE_CONTEXT . "/session/alist?cid=$cid&stype=$stype&rid=$rid&active=$active&lg=$lg&m=$nextYear-$nextMonth";
	echo " <a href='$url' title='Next month'>&nbsp;&gt;&gt;</a>";
	echo '</div><br />';
	
	$date = new DateTime($curYear . '-' . $curMonth . '-01');
	$date->modify('-' . $date->format('w') . ' days');

	echo '<table width="100%" border="1" cellpadding="4" cellspacing="0">';
	
	echo '<tr style="font-weight:bold">';
	$k_weekDays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	for ($i = 0; $i < 7; $i++) {
		echo "<td width='14.28%'>$k_weekDays[$i]</td>";
	}
	echo '</tr>';
	
	$index = 0;
	$keysList = array_keys($list);
	$count = count($list);

	while (TRUE)  {
		echo '<tr>';
		for ($weekDay = 0; $weekDay <= 6; $weekDay++) {
			$y = $date->format('Y');
			$m = $date->format('m');
			$d = $date->format('d');
			$isToday = $y == $today_Year && $m == $today_Month && $d == $today_Day;
			
			if ($isToday) {
				echo '<td valign="top" style="background-color:#cccccc" title="Today">';
			} else {
				echo '<td valign="top">';
			}
			
			if ($m == $curMonth) {
				if ($weekDay == 0) {
					echo '<h2 style="color: red">';
				} else if ($weekDay == 6) {
					echo '<h2 style="color: green">';
				} else {
					echo '<h2>';
				}
				
				echo $date->format('d');
				echo '</h2>';
				
				while ($index < $count
						&& ( $ss = $list[$keysList[$index]] )
						&& $ss->y == $y && $ss->m == $m && $ss->d == $d) {
					
					echo '<div style="font-size: 0.85em';
					if ($ss->stype == 0) {
						echo ';background-color:lightgreen">';
					} else {
						echo '">';
					}
					
					$url = __SITE_CONTEXT . "/session?id=$ss->id";
					$title = "Course ". htmlspecialchars($resCourses[$ss->courseId]->name);
					$pre = $resTeachers[$ss->teacherId]->gender == 0 ? 'Mr. ' : 'Ms. ';
					
					echo "<a href='$url' title='$title'>";
					$stype = $ss->stype == 0 ? 'LEC' : 'LAB';

					echo "● <b>$ss->h</b>, $ss->minutes" . " mins, $stype, " .
							htmlspecialchars($resRooms[$ss->roomId]->name);

					if ($ss->stype == 1) {
						echo ", Group $ss->labGroup";
					}

					echo ", $pre " . htmlspecialchars($resTeachers[$ss->teacherId]->fullname);

					if (!$ss->isActive) echo " [Deactivated]";

					if ( isset($ss->attStatus) && $ss->attStatus > -1) {
						if ($ss->attStatus) echo " [PRESENT]";
						else echo " [ABSENT]";
					}
					echo ".";

					echo "</a>";
					
					echo '</div>';
					$index++;
				}
			}
			
			echo '</td>';
			$date->modify('+1 days');
		}
		echo '</tr>';
		if ( $date->format('m') != $curMonth ) break;
	}
	
	echo '</table>';
	
	echo '<p></p>';
	
	echo "<p align='right'><i>" . count($list) . " sessions found.</i></p>";
?>
