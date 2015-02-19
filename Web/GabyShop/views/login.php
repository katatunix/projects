<div id="content-container">

<script type="text/javascript">
$(document).ready(function() {
	$('#username').focus();
});

function my_reset() {
	$('#username').val('');
	$('#password').val('');
}
</script>


<div id="content">
	
	<h2><?= $tile_title ?></h2>
	
<?
	if ($message) {
		echo '<p style="color:red">';
		echo $message;
		echo '</p>';
	}
?>
	
	<form method="post" action="" style="font-size:13px">
	
	<table align="left" cellpadding="4">
		<tr>
			<td align="right"><b>Username</b></td>
			<td>
				<input size="30" style="font-size:13px;font-family:Verdana" type="text" name="username" id="username"
					value="<?= $username ?>" />
			</td>
		</tr>
		
		<tr>
			<td align="right"><b>Password</b></td>
			<td><input size="30" style="font-size:13px;font-family:Verdana" type="password" name="password" id="password" /></td>
		</tr>
		
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Login" style="font-size:13px;font-family:Verdana" />
				<input type="button" onclick="my_reset()" value="Reset" style="font-size:13px;font-family:Verdana" />
			</td>
		</tr>
	</table>
	
	</form>

	
</div>

</div>
