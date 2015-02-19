<div id="about">
	<h2>Hello,
		<?php
			if ( isset($loggedAccount) ) {
				echo htmlspecialchars($loggedAccount->username);
			} else {
				echo 'guest';
			}
		?>
	</h2>
</div>
