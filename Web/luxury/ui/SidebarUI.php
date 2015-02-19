<div id="sidebar-bgtop"></div>
<div id="sidebar-content">
	<div id="sidebar-bgbtm">
	<ul>

<?php
		if (!$loggedAccount) {
?>
			<li>
				<h2>Hi guest</h2>
				<ul>
					<li><a href="<?= __SITE_CONTEXT ?>/account/login">Please login</a></li>
				</ul>
			</li>
<?php
		}

		if ($loggedAccount) {
?>
			<li>
				<h2>Staff</h2>
				<ul>
					<li><a href="<?= __SITE_CONTEXT ?>/local/order/createRoom">Create room order</a></li>
					<li><a href="<?= __SITE_CONTEXT ?>/local/order/createFoodb">Create food & beverage order</a></li>
					<li><a href="<?= __SITE_CONTEXT ?>/local/order/index?cw=1">My orders list</a></li>
				</ul>
			</li>
<?php
		}

		if ($loggedAccount && ($loggedAccount->roleString == 'LocMan' || $loggedAccount->roleString == 'NatMan')) {
?>
			<li>
				<h2>Local Manager</h2>
				<ul>
					<li><a href="<?= __SITE_CONTEXT ?>/local/localReport/room?cw=1">Report of room occupancy</a></li>
					<li><a href="<?= __SITE_CONTEXT ?>/local/localReport/foodb?cw=1">Report of food & beverage sales</a></li>
					<li><a href="<?= __SITE_CONTEXT ?>/local/localReport/staff?cw=1">Individual staff sales statistics</a></li>
				</ul>
			</li>
<?php
		}

		if ($loggedAccount && $loggedAccount->roleString == 'NatMan') {
?>
			<li>
				<h2>National Manager</h2>
				<ul>
					<li><a href="<?= __SITE_CONTEXT ?>/national/nationalReport/room?cw=1">National report of room occupancy</a></li>
					<li><a href="<?= __SITE_CONTEXT ?>/national/nationalReport/foodb?cw=1">National report of food & beverage sales</a></li>
				</ul>
			</li>
<?php
		}
?>
	</ul>
</div>
</div>
