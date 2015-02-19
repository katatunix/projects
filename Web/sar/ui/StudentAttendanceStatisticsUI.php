<a href="<?= __SITE_CONTEXT ?>/student?id=<?= $studentAisId ?>">&lt;&lt; Back to Student Information</a>

<?php
	if ($message) {
		echo '<p style="color:red">';
			echo htmlspecialchars($message);
		echo '</p>';
	} else {
?>

<p><b>Student: <?= $student->fullname ?> (<?= $student->id ?>)</b></p>

<table width="100%" border="1" cellpadding="4" cellspacing="0">
	<thead>
		<tr>
			<th>Courses</th>
			<th>Lecture sessions</th>
			<th>Lab sessions</th>
		</tr>
	</thead>
	
	<tbody>
<?php
		foreach ($courses as $course) {
?>
		<tr>
			<td>
				<ul>
					<li><?= htmlspecialchars( $course->name ) ?></li>
					<li>Start date: <?= $course->startDate ?></li>
					<li>Duration: <?= $course->weeks ?> weeks</li>
				</ul>
			</td>
			<td>
				<ul>
					<li>Total: <?= $course->totalLectureSessions ?></li>
					<li style="color: red">Absent: <?= $course->countAbsentLecture ?></li>
					<li style="color: green">Present: <?= $course->countPresentLecture ?></li>
				</ul>
			</td>
			<td>
				<ul>
					<li><b>Group: <?= $course->labGroup ? $course->labGroup : 0 ?></b></li>
					<li>Total: <?= $course->totalLabSessions ?></li>
					<li style="color: red">Absent: <?= $course->countAbsentLab ?></li>
					<li style="color: green">Present: <?= $course->countPresentLab ?></li>
				</ul>
			</td>
		</tr>
<?php
		}
?>
	</tbody>
	
</table>

<?php
	}
?>
