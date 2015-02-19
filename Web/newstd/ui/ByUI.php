<?php
	if ($loggedAccount)
	{
?>
You are logged as <?= $loggedAccount->fullname ?> - <a href="<?= __SITE_CONTEXT ?>/newstd/logout">Logout</a>
<?php
	}
?>
