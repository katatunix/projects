<?php
	if ($message) {
		echo '<p style="color:red">';
			echo htmlspecialchars($message);
		echo '</p>';
	} else {
?>

<ul>
	<li>Full name: <?= htmlspecialchars($student->fullname) ?></li>
	<li>Birthday: <?= $student->dob ?></li>
	<li>Gender: <?= $student->genderString ?></li>
	<li>AIS Id: <?= $student->studentAisId ?></li>
	
	<?php if ($isShowStatisticsLink) { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/student/viewAttendanceStatistics?id=<?= $student->studentAisId ?>">View attendance statistics</a></li>
	<?php } ?>
	
	<li><a target="_blank" href="<?= __SITE_CONTEXT ?>/student/viewFakeAisDetail?id=<?= $student->studentAisId ?>">View information on AIS website</a></li>
	
</ul>

<?php
	}
?>
