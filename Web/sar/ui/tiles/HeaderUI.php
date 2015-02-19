<div id="header">
	<div id="logo">
		<h1><a href="<?= __SITE_CONTEXT ?>"><?= __SITE_NAME ?></a></h1>
		<p><?= __SITE_SLOGAN ?></p>
	</div>
	<div id="menu">
		<ul>
			<!--------------------------------------------------------------->
			
			<?php if (isset($loggedAccount)) { ?>
			<li
				<?php if (isset($headerMenuItem) && $headerMenuItem == 'dashboard') { ?>
					class="current_page_item"
				<?php } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>/dashboard">Dashboard</a></li>
			<?php } ?>
			
			<!--------------------------------------------------------------->
			
			<?php if (!isset($loggedAccount)) { ?>
			<li
				<?php if (isset($headerMenuItem) && $headerMenuItem == 'login') { ?>
					class="current_page_item"
				<?php } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>/account/login">Login</a></li>
			<?php } ?>
			
			<!--------------------------------------------------------------->
			
			<?php if (isset($loggedAccount)) { ?>
			<li
				<?php if (isset($headerMenuItem) && $headerMenuItem == 'cpass') { ?>
					class="current_page_item"
				<?php } ?>
			>
			<a href="<?= __SITE_CONTEXT ?>/account/cpass">Change pass</a></li>
			<?php } ?>
			
			<!--------------------------------------------------------------->
			
			<?php if (isset($loggedAccount)) { ?>
			<li><a href="<?= __SITE_CONTEXT ?>/account/logout">Logout</a></li>
			<?php } ?>
			
			<!--------------------------------------------------------------->
			
		</ul>
	</div>
</div>
