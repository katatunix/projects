<script type="text/javascript">
	$(document).ready(function() {
		$('#username').focus();
	});

	function myReset() {
		$('#username').val('');
		$('#password').val('');
	}
</script>

<div id="content">
	<div id="content2">
		<div class="post">
			<h2 class="title"><?= $title?></h2>
		<?
			if ($message)
			{
		?>
				<p class="meta">
					<span class="author"><?= $message ?></span>
				</p>
		<?
			}
		?>
			
			<div class="entry">
			
				<form method="post" action="">
	
					<table cellpadding="4">
						<tr>
							<td align="right"><b>Username</b></td>
							<td>
								<input size="40" type="text" name="username" id="username" value="<?= $username ?>" />
							</td>
						</tr>
						
						<tr>
							<td align="right"><b>Password</b></td>
							<td><input size="40" type="password" name="password" id="password" /></td>
						</tr>
						
						<tr>
							<td></td>
							<td>
								<input type="submit" value="Login" />
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
