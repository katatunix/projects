<?php	if ($message) {
		echo '<p style="color:red">';
			echo htmlspecialchars($message);
		echo '</p>';
	} else {
?>

<h2>Fake AIS Student Information</h2>

<ul>
	<li>Full name: <?= htmlspecialchars($student->fullname) ?></li>
	<li>Birthday: <?= $student->dob ?></li>
	<li>Gender: <?= $student->genderString ?></li>
	<li>AIS Id: <?= $student->studentAisId ?></li>
</ul>

<?php	} ?>
