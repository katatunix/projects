<div id="logo">
	<h1><a href="<?= __SITE_CONTEXT ?>"><?= __SITE_NAME ?></a></h1>
	<p><?= $resortInfo->name ?> [<?= $resortInfo->isNational ? 'National' : 'Local' ?> Resort]</p>
</div>
<!-- end #logo -->
<div id="menu">
	<ul>
<?php
			if ($loggedAccount) {
?>
				<li><a href="<?= __SITE_CONTEXT ?>/dashboard/index">Dashboard</a></li>
				<li><a href="<?= __SITE_CONTEXT ?>/account/logout">Logout</a></li>
<?php
			} else {
?>
				<li><a href="<?= __SITE_CONTEXT ?>/account/login">Login</a></li>
<?php
			}
?>
		
	</ul>
</div>
<!-- end #menu -->
