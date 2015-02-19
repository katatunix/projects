<?php
	if ( count($attendances) == 0 ) {
?>
	<p style="color: green">No notifications.</p>
<?php
	} else {
		echo '<ul>';
		foreach ($attendances as $att) {
?>
			<li>
			<a target="_blank" href="<?= __SITE_CONTEXT ?>/session?id=<?= $att->sessionId ?>" title="View information of this student">
				Student: <?= htmlspecialchars( $students[$att->studentAisId]->fullname ) ?> /
				Session: <?= $sessions[$att->sessionId]->startDatetime ?> /
				Course: <?= htmlspecialchars( $courses[$sessions[$att->sessionId]->courseId]->name ) ?>
			</a>
			<div style="margin-left: 20px"><?= htmlspecialchars( $att->reason ) ?></div>
				
			</li>
<?php
		}
		echo '</ul>';
	}
?>
