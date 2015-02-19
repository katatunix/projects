<div class="post">
	<h2><?= htmlspecialchars($pageTitle) ?></h2>
	<div class="clear-float"></div>

<?php
	if ( isset($message) && $message )
	{
?>
		<br />
		<p style="color: red"><?= htmlspecialchars($message) ?>	</p>
<?php
	}
?>
	<br />
	<form action="" method="post">
		Username<br>
		<input type="text" class="text" name="username" value="<?= isset($username) ? $username : ''?>"><br>
		Password<br>
		<input type="password" class="text" name="password"><br>
		<input type="submit" value="Log in" class="submit">
	</form>
</div>
