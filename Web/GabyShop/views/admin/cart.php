<? require_once('includes/utils.php'); ?>

<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.datepicker.min.js"></script>

<script>
	var cart_id_del = -1;
	var allowProgbarClose = false;
	
	var isDeleteBatch = false;
	var strBatch = '';
	
	$(document).ready(function() {
		$('#datepicker1').datepicker({
			maxDate: 'Now',
			dateFormat: 'dd/mm/yy'
		});
		
		$('#datepicker2').datepicker({
			maxDate: 'Now',
			dateFormat: 'dd/mm/yy'
		});
		
		$( "#dialog-confirm" ).dialog({
			autoOpen: false,
			width: 330,
			modal: true,
			buttons: {
				"Có": function() {
					$( this ).dialog( "close" );
					
					allowProgbarClose = false;
					$( "#dialog-progbar" ).dialog('open');
					
					if (isDeleteBatch) {
						ajax_del_batch();
					} else {
						ajax_del_single();
					}
				},
				"Không": function() {
					$( this ).dialog( "close" );
					cart_id_del = -1;
					strBatch = '';
				}
			}
		});
		
		$( "#dialog-progbar" ).dialog({
			modal: true,
			autoOpen: false,
			height: 85,
			beforeClose: function(event, ui) {
				return allowProgbarClose;
			}
		});
		
		$( "#dialog-message" ).dialog({
			modal: true,
			autoOpen: false,
			height: 140,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});

	});
	
	function del_cart(cart_id) {
		cart_id_del = cart_id;
		isDeleteBatch = false;
		$( "#dialog-confirm" ).dialog('open');
		return false;
	}
	
	function radio_change() {
		var p = $('input[name="s_type"]:checked').val() == '0' ? '' : 'disabled';
		var q = p == '' ? 'disabled' : '';
		
		$('#cart_id').attr('disabled', p);
		
		$('#datepicker1').attr('disabled', q);
		$('#datepicker2').attr('disabled', q);
		
		$('#button_1_0').attr('disabled', q);
		$('#button_1_1').attr('disabled', q);
		$('#button_2_0').attr('disabled', q);
		$('#button_2_1').attr('disabled', q);
	}
	
	function set_value(index, type) {
		var el = $('#datepicker' + index);
		if (type == 0) {
			el.val('');
		} else {
			var other_el = $('#datepicker' + (index == 1 ? '2' : '1'));
			el.val( other_el.val() );
		}
	}
	
	function delete_batch() {
		isDeleteBatch = true;
		strBatch = '';
		
		$('#myTable tbody tr').each(function() {
			if (strBatch == '')
				strBatch += $(this).attr('cart_id');
			else
				strBatch += ',' + $(this).attr('cart_id');
		});
		
		if (strBatch == '') return;
		
		$( "#dialog-confirm" ).dialog('open');
	}
	
	function ajax_del_single() {
		$.post(
			"<?= __SITE_CONTEXT ?>admin/cart/delete",
			{ del_cart_id : cart_id_del },
			
			function(data) {
				var t = parseInt( $('#cart_num').text() ) - 1;
				$('#cart_num').text(t);
				
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				
				$('tr[cart_id=' + cart_id_del + ']').remove();
				$('#myTable').hide();
				$('#myTable').show();
				
				$( "#dialog-message" ).dialog('open');
				
				if ( $('#myTable tr').length <= 1 ) {
					$('#myTable').remove();
					$('#del_batch_button').remove();
				}
				
				cart_id_del = -1;
			}
		);
	}
	
	function ajax_del_batch() {
		$.post(
			"<?= __SITE_CONTEXT ?>admin/cart/deleteBatch",
			{ str_batch : strBatch },
			
			function(data) {
				$('#cart_num').text('0');
				
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				
				$( "#dialog-message" ).dialog('open');
				
				$('#myTable').remove();
				$('#del_batch_button').remove();
				
				strBatch = '';
			}
		);
	}
	
</script>

<div id="dialog-confirm" title="Xóa giỏ hàng?" style="display:none">
	<p>Thông tin bị xóa sẽ không thể phục hồi được. Bạn có chắc chắn muốn xóa không?</p>
</div>

<div id="dialog-progbar" title="Xin vui lòng chờ đợi" style="display:none">
	<p>
		<div class="progbar" style="width:100%;height:20px"></div>
	</p>
</div>

<div id="dialog-message" title="Thông báo" style="display:none">
	<p>Đã xóa thành công!</p>
</div>

<?
	if ($message) {
		if ($message['type'] == 'error') {
			echo '<p style="color:red">';
		} else {
			echo '<p style="color:green">';
		}
		echo $message['value'];
		echo '</p>';
	}
?>

<p>
	<form method="post" action="">
		<label>
			<input type="radio" name="s_type" value="0" <?= isset($cart_id) ? 'checked' : '' ?> onchange="radio_change()" />
			<b>Mã giỏ hàng</b>
		</label>
		<input type="text" id="cart_id" name="cart_id" value="<?= $cart_id ?>" <?= isset($cart_id) ? '' : 'disabled' ?> />
		
		<br /><br />
		
		<label>
			<input type="radio" name="s_type" value="1" <?= isset($fromDate) ? 'checked' : '' ?> onchange="radio_change()" />
			<b>Giới hạn ngày</b>
		</label>
<?
		$eee = isset($fromDate) ? '' : 'disabled'
?>
		<div style="margin-top:5px;margin-left:100px">
			<table class="table_no_grid">
				<tr>
					<td align="right">FROM</td>
					<td><input type="text" id="datepicker1" name="fromDate" size="10" value="<?= $fromDate ?>" <?= $eee ?> /></td>
					<td>
						<input id="button_1_0" type="button" value="No limit" onclick="set_value(1, 0)" <?= $eee ?> />
						<input id="button_1_1" type="button" value="Same as TO" onclick="set_value(1, 1)" style="width:120px;text-align:left" <?= $eee ?> />
					</td>
				</tr>
				<tr>
					<td align="right">TO</td>
					<td><input type="text" id="datepicker2" name="toDate" size="10" value="<?= $toDate ?>" <?= $eee ?> /></td>
					<td>
						<input id="button_2_0" type="button" value="No limit" onclick="set_value(2, 0)" <?= $eee ?> />
						<input id="button_2_1" type="button" value="Same as FROM" onclick="set_value(2, 1)" style="width:120px;text-align:left" <?= $eee ?> />
					</td>
				</tr>
			</table>
		</div>
		
		<p><input type="submit" value="Search" /></p>
	</form>
	
<?
	if ( !$carts || count($carts) == 0 ) {
?>
	<div style="color:red;font-size:20px">Không có giỏ hàng nào!</div>
<?
	} else {
?>
	
	<div style="font-size:18px;color:green">Số lượng giỏ hàng tìm thấy: <span id="cart_num"><?= count($carts) ?></span></div>
	<br />
	<table class="table_grid" width="100%" id="myTable" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" width="90">Mã giỏ hàng</th>
				<th scope="col" width="145">Thời gian</th>
				<th scope="col">Chi tiết giỏ hàng</th>
				<th scope="col" width="70">SL mã SP</th>
				<th scope="col" width="70">SL SP</th>
				<th scope="col" width="100">Tổng số tiền</th>
				<th scope="col" width="40"></th>
			</tr>
		</thead>
		<tbody>
<?
		foreach ($carts as $key => $value) {
			$dt = $cart_details[$key];
			$detail_text = '';
			$total_quantity = 0;
			$total_money = 0;
			foreach ($dt as $k => $v) {
				if ($detail_text != '') {
					$detail_text .= ', ';
				}
				$detail_text .= '<a targer="_blank" title="Xem chi tiết sản phẩm" href="' . __SITE_CONTEXT . 'admin/product/detail?id=' . $k . '">' .
						$v['code'] . '</a> (' . $v['quantity'] . ')';
				
				$total_quantity += $v['quantity'];
				$total_money += $v['price_sell'] * $v['quantity'];
			}
?>
		<tr cart_id='<?= $key ?>'>
			<td align="right"><?= $key ?></td>
			<td align="right"><?= convert_datetime_to_vn( $value ) ?></td>
			<td><?= $detail_text ?></td>
			<td align="right"><?= count($dt) ?></td>
			<td align="right"><?= $total_quantity ?></td>
			<td align="right"><?= format_money( $total_money ) ?></td>
			<td align="center"><a href="#" onclick="return del_cart(<?= $key ?>)">Xóa</a></td>
		</tr>
<?
	}
?>
		</tbody>
	</table>
	
	<p id="del_batch_button" style="text-align:right"><input onclick="delete_batch()" type="submit" value="Xóa hết danh sách này" /></p>
<?
	}
?>
</p>
