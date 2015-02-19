<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.datepicker.min.js"></script>

<script>
	$(document).ready(function() {
		$("#datepicker").datepicker({
			maxDate: 'Now',
			dateFormat: 'dd/mm/yy',
			defaultDate: '19/09/1987'
		});
		
		$('#full_name').focus();
	});
	
	var cust_obj = {
		full_name:		'<?= addslashes($customer_backup['full_name']) ?>',
		address:		'<?= addslashes($customer_backup['address']) ?>',
		email:			'<?= addslashes($customer_backup['email']) ?>',
		birthday:		'<?= addslashes($customer_backup['birthday']) ?>',
		city_id:		<?= isset( $customer_backup['city_id'] ) ? $customer_backup['city_id'] : 0 ?>,
		phone:			'<?= addslashes($customer_backup['phone']) ?>'
	};
	
	function my_reset() {
		$('#full_name').val(cust_obj.full_name);
		$('#address').val(cust_obj.address);
		$('#email').val(cust_obj.email);
		$('#datepicker').val(cust_obj.birthday);
		$('#city_id').val(cust_obj.city_id);
		$('#note').val( $('#note_hidden').val() );
		$('#phone').val(cust_obj.phone);
	}
	
</script>

<?
	$error_not_found = FALSE;
	if ( $message ) {
		if ( $message['type'] == 'error_not_found' ) {
			$error_not_found = TRUE;
			echo '<p style="color:red">';
			echo $message['value'];
			echo '</p>';
		} else {
			if ( $message['type'] == 'error' ) {
				echo '<p style="color:red">';
			} else {
				echo '<p style="color:green">';
			}
			echo $message['value'];
			echo '</p>';
		}
	}
?>

<?
	if ( !$error_not_found ) {
?>

<p>
<form action="" method="post">
	<table align="center" class="table_no_grid">
		<tr>
			<td align="right"><b>Mã khách hàng</b></td>
			<td><input size="60" type="text" readonly name="id" value="<?= $customer['id'] ?>" /></td>
		</tr>
		<tr>
			<td align="right"><b>Họ và tên</b></td>
			<td>
				<input size="60" type="text" name="full_name" id="full_name"
					value="<?= $customer['full_name'] ?>"
				/>
			</td>
		</tr>
		<tr>
			<td align="right">Ngày sinh</td>
			<td>
				<input type="text" id="datepicker" name="birthday"
					value="<?= $customer['birthday'] ?>"
				/> [dd/mm/yyyy]
			</td>
		</tr>
		<tr>
			<td align="right">Điện thoại</td>
			<td>
				<input type="text" id="phone" name="phone"
					value="<?= $customer['phone'] ?>"
				/>
			</td>
		</tr>
		<tr>
			<td align="right">Địa chỉ</td>
			<td>
				<input size="60" type="text" name="address" id="address"
					value="<?= $customer['address'] ?>"
				/>
			</td>
		</tr>
		<tr>
			<td align="right">Tỉnh / thành phố</td>
			<td>
				<select name="city_id" id="city_id">
					<option value="0">-- Không biết --</option>
<?
					foreach ($cities as $key => $value) {
						$selected = $key == $customer['city_id'] ? 'selected' : '';
						echo "<option value='$key' $selected>$value</option>";
					}
?>				
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Email</td>
			<td>
				<input size="60" type="text" name="email" id="email"
					value="<?= $customer['email'] ?>"
				/>
			</td>
		</tr>
		<tr>
			<td align="right">Ghi chú</td>
			<td>
				<textarea id="note" name="note" cols="50" rows="5"><?= $customer['note'] ?></textarea>
				<textarea id="note_hidden" style="display:none"><?= $customer_backup['note'] ?></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td align="center">
				<input style="font-size:20px" type="submit" value="Lưu lại" />
				<input style="font-size:20px" type="button" value="Reset" onclick="my_reset()" />
			</td>
		</tr>
	</table>
	<br />
</form>

</p>

<?
	}
?>
