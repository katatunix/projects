<script type="text/javascript">
	$(document).ready(function() {
		myReset();
		$('#currentPassword').focus();
	});

	function myReset() {
		$('#currentPassword').val('');
		$('#newPassword').val('');
		$('#newPasswordConfirm').val('');
	}
</script>

<?php
	if ( isset($message) )
	{
?>
		<ul style="color: <?= $message->isError ? 'red' : 'green' ?>">
			<?php foreach($message->list as $msg) { ?>
				<li><?= htmlspecialchars($msg) ?></li>
			<?php } ?>
		</ul>
<?php
	}
?>

<form method="POST" action="">
	<table cellpadding="4">
		<tr>
			<td align="right"><b>Current password</b></td>
			<td><input type="password" name="currentPassword" id="currentPassword" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>New password</b></td>
			<td><input type="password" name="newPassword" id="newPassword" size="30" /></td>
		</tr>
		<tr>
			<td align="right"><b>Confirm</b></td>
			<td><input type="password" name="newPasswordConfirm" id="newPasswordConfirm" size="30" /></td>
		</tr>
		<tr>
			<td></td>
			<td align="center">
				<input type="submit" value="Change" />
				<input type="button" value="Clear" onclick="myReset()" />
			</td>
		</tr>
	</table>
</form>
