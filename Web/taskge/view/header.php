<div id="header">
	<div id="logo">
		<h1><a href="<?= __SITE_CONTEXT ?>"><?= __SITE_NAME ?></a></h1>
		<p><?= __SITE_SLOGAN ?></p>
	</div>
	<div id="menu">
		<ul>
			<li
				<? if ($_name == 'home') { ?>
					class="current_page_item"
				<? } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>">Home</a></li>
			
			<? if (MySession::currentMember()) { ?>
			<li
				<? if ($_name == 'cpass') { ?>
					class="current_page_item"
				<? } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>/profile/cpass">Change password</a></li>
			
			<li><a href="<?= __SITE_CONTEXT ?>/index/logout">Logout</a></li>
			<?} else {?>
			<li
				<? if ($_name == 'login') { ?>
					class="current_page_item"
				<? } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>/login">Login</a></li>
			<?}?>
		</ul>
	</div>
</div>
