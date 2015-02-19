<?php
	if (count($registrations) == 0) {
?>
		<p style="color: green">No lazy students.</p>
<?php
	} else {
		echo '<ul>';
		foreach ($registrations as $regis) {
?>
			<li>
			<a target="_blank" href="<?= __SITE_CONTEXT ?>/student?id=<?= $regis->studentAisId ?>" title="View information of this student">
				Student: <?= htmlspecialchars( $students[$regis->studentAisId]->fullname ) ?>
			</a>
			<div style="margin-left: 20px">
				Course: <?= $courses[$regis->courseId]->name ?>.<br />
				Group: <?= $regis->labGroup ?>.<br />
				Absent: <?= $regis->absentCount ?> / <?= $regis->totalCount ?>
					(<?= sprintf('%.2f', $regis->absentCount / $regis->totalCount * 100) ?>%).
			</div>
			</li>
<?php
		}
		echo '</ul>';
	}
?>
