<script type="text/javascript">
	$(document).ready(function() {
		$('#currentPassword').focus();
	});

	function myReset() {
		$('#currentPassword').val('');
		$('#newPassword').val('');
		$('#confirmNewPassword').val('');
	}
</script>

<div id="content">
	<div id="content2">
		<div class="post">
			<h2 class="title"><?= $title?></h2>
			
			<?if ($messageType) {?>
				<p class="meta">
					<?if ($messageType == 1) {
						foreach ($message as $k => $v) {?>
							<span class="author"><?= $v ?></span>
						<?}?>
						
					<?} else if ($messageType == 2) {
						foreach ($message as $k => $v) {?>
							<span class="date"><?= $v ?></span>
						<?}?>
					<?}?>
				</p>
			<?}?>
			
			<div class="entry">
				<form method="post" action="">
	
					<table cellpadding="4">
						<tr>
							<td align="right"><b>Current password</b></td>
							<td>
								<input size="40" type="password" name="currentPassword" id="currentPassword" />
							</td>
						</tr>
						
						<tr>
							<td align="right"><b>New password</b></td>
							<td><input size="40" type="password" name="newPassword" id="newPassword" /></td>
						</tr>
						
						<tr>
							<td align="right"><b>Confirm new password</b></td>
							<td><input size="40" type="password" name="confirmNewPassword" id="confirmNewPassword" /></td>
						</tr>
						
						<tr>
							<td></td>
							<td>
								<input type="submit" value="Change" />
								<input type="button" onclick="myReset()" value="Reset" />
							</td>
						</tr>
					</table>
				
				</form>
			</div>
		</div>
		
		<? include __VIEW_DIR_PATH . '/tiles/about.php'; ?>
	</div>
</div>
<!-- end #content -->

<div id="sidebar">
	<ul>
		<? include __VIEW_DIR_PATH . '/tiles/gameloftlogo.php'; ?>
	</ul>
</div>
<!-- end #sidebar -->
