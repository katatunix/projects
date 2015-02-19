<ul>
<?php
	foreach ($courses as $course) {
?>

<li>
	<a href="<?= __SITE_CONTEXT ?>/course?id=<?= $course->id ?>" title="View detail of this course">
		<?= htmlspecialchars($course->name) ?> |
		<?= $course->startDate ?> |
		<?= $course->weeks ?> weeks
	</a>
</li>
<?php
	}
?>
</ul>
