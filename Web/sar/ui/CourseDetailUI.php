<a href="<?= __SITE_CONTEXT ?>/course/alist">&lt;&lt; Back to Courses List</a>

<?php
	if (isset($errorMessage)) {
?>
		<div style="color: red"><?= htmlspecialchars($errorMessage) ?></div>
<?php
	} else {
?>

<p>Start date: <?= $course->startDate ?></p>
<p>Weeks number: <?= $course->weeks ?></p>

<ul>
	<li>
		<a target="_blank" href="<?= __SITE_CONTEXT ?>/session/alist?cid=<?= $course->id ?>">View SESSIONS list of this course</a>
	</li>
	<li>
		<a target="_blank" href="<?= __SITE_CONTEXT ?>/student/alist?cid=<?= $course->id ?>">View STUDENTS list of this course</a>
	</li>
</ul>

<?php
	}
?>
