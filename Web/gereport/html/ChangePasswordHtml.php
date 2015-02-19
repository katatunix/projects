<script type="text/javascript">
	$(document).ready(function() {
		$('#oldPassword').focus();
	});
</script>

<h2><?= $this->title ?></h2>

<?php if ($this->message) { ?>
	<p class="<?= $this->isSuccess ? 'infoMessage' : 'errorMessage' ?>">
		<?= $this->message ?>
	</p>
<?php } ?>

<form method="post" action="">
	<table cellspacing="10">
		<tr>
			<td align="right">Current password</td>
			<td><input type="password" name="oldPassword" id="oldPassword" class="memberInfoTextBox" /></td>
		</tr>
		<tr>
			<td align="right">New password</td>
			<td><input type="password" name="newPassword" class="memberInfoTextBox" /></td>
		</tr>
		<tr>
			<td align="right">Confirm password</td>
			<td><input type="password" name="confirmPassword" class="memberInfoTextBox" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Save" /></td>
		</tr>
	</table>
</form>
