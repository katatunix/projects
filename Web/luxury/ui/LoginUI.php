<script type="text/javascript">
	$(document).ready(function() {
		$('#username').focus();
	});

	function myReset() {
		$('#username').val('');
		$('#password').val('');
	}
</script>

<?php
	if ( isset($message) && $message )
	{
?>
		<p style="color: red"><?= htmlspecialchars($message) ?>	</p>
<?php
	}
?>

<form action="" method="POST">
<table cellpadding="4">
	<tr>
		<td align="right"><b>Username</b></td>
		<td><input type="text" name="username" id="username" size="30"
			value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" /></td>
	</tr>
	<tr>
		<td align="right"><b>Password</b></td>
		<td><input type="password" name="password" id="password" size="30" /></td>
	</tr>
	<tr>
		<td></td>
		<td align="center">
			<input type="submit" value="Login" />
			<input type="button" value="Clear" onclick="myReset()" />
		</td>
	</tr>
</table>
</form>
