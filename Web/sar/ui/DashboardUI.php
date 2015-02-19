<script type="text/javascript">

/*
$(document).ready(function() {
	initConfirmDialog('confirmDialog');
	showConfirmDialog('Confirm', 'Are you sure to delete?', sureDelete, notSure, 'helllo');
});

function sureDelete(data) {
	console.log('YES ' + data);
	
}

function notSure(data) {
	console.log('NO ' + data);
}
*/

</script>

<!--<div id="confirmDialog"><p></p></div>-->

<?php
	$obj = $student ? $student : $account;
?>

<ul>
	<li><?= isset($obj->fullname) ? htmlspecialchars($obj->fullname) : 'Unknown full name' ?></li>
	<li>Birthday: <?= isset($obj->dob) ? $obj->dob : 'Unknown' ?></li>
	<li>Gender: <?= isset($obj->genderString) ? $obj->genderString : 'Unknown' ?></li>
	<li>Role: <?= $account->roleString ?></li>
	
	<?php if ($student) { ?>
		<li>AIS Id: <?= $account->studentAisId ?></li>
	<?php } ?>
</ul>
