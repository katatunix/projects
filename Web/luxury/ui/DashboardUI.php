<?php
	$roleName = '';
	switch ($loggedAccount->roleString) {
		case 'Staff':	$roleName = 'Staff';			break;
		case 'LocMan':	$roleName = 'Local Manager';	break;
		case 'NatMan':	$roleName = 'National Manager';	break;
	}
?>
Hello, <?= $loggedAccount->fullname ?> [<?= $roleName ?>]
