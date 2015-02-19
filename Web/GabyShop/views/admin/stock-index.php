<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>

<script>

var allowProgbarClose = false;

var current_id = -1;

var current_name = '';

var cur_select_id = -1;

$(document).ready(function() {
	
	$( "#dialog-confirm-delete" ).dialog({
		autoOpen: false,
		width: 330,
		modal: true,
		buttons: {
			"Có": function() {
				$( this ).dialog( "close" );
				
				allowProgbarClose = false;
				$( "#dialog-progbar" ).dialog('open');
				
				$.post(
					"<?= __SITE_CONTEXT ?>admin/stock/delete",
					{ id : current_id },
					
					function(data) {
						var el = $('#dialog-message-delete p');
						
						if (data == '0') { // success
							el.text('Đã xóa kho hàng thành công!');
							el.css('color', 'green');
							
							var t = parseInt( $('#stocks_num').text() ) - 1;
							$('#stocks_num').text(t);
							
							$('tr[stock_id=' + current_id + ']').remove();
						} else if (data == '1') { // cannot delete
							el.text('Không thể xóa! Đã có phiếu nhập/chuyển hàng hoặc hóa đơn liên quan tới kho hàng này!');
							el.css('color', 'red');
						} else if (data == '2') { // system error
							el.text('Không thể xóa! Có lỗi xảy ra!');
							el.css('color', 'red');
						}
						
						allowProgbarClose = true;
						$( "#dialog-progbar" ).dialog('close');
						
						$('#myTable').hide();
						$('#myTable').show();
						
						$( "#dialog-message-delete" ).dialog('open');
						
						current_id = -1;
					}
				);
			},
			"Không": function() {
				$( this ).dialog( "close" );
				current_id = -1;
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
	
	$( "#dialog-message-edit" ).dialog({
		modal: true,
		autoOpen: false,
		height: 140,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#dialog-message-insert" ).dialog({
		modal: true,
		autoOpen: false,
		height: 140,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#dialog-message-delete" ).dialog({
		modal: true,
		autoOpen: false,
		height: 180,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#dialog-error" ).dialog({
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


function edit_stock(id) {
	if (cur_select_id > -1) {
		if (cur_select_id == id) return false;
		cancel_edit(cur_select_id);
	}

	var span_id = 'stock_name_span_' + id;
	var edit_zone_id = 'stock_edit_zone_' + id;
	var textbox_id = 'stock_name_textbox_' + id;
	
	var span_el = $('#' + span_id);
	var edit_zone_el = $('#' + edit_zone_id);
	var textbox_el = $('#' + textbox_id);
	
	span_el.css('display', 'none');
	edit_zone_el.css('display', 'block');
	
	textbox_el.val( span_el.text() );
	
	cur_select_id = id;
	
	return false;
}

function cancel_edit(id) {
	var span_id = 'stock_name_span_' + id;
	var edit_zone_id = 'stock_edit_zone_' + id;
	var textbox_id = 'stock_name_textbox_' + id;
	
	var span_el = $('#' + span_id);
	var edit_zone_el = $('#' + edit_zone_id);
	var textbox_el = $('#' + textbox_id);
	
	span_el.css('display', 'block');
	edit_zone_el.css('display', 'none');
	
	textbox_el.val( '' );
	
	cur_select_id = -1;
}

function save_stock(id) {
	var span_id = 'stock_name_span_' + id;
	var edit_zone_id = 'stock_edit_zone_' + id;
	var textbox_id = 'stock_name_textbox_' + id;
	
	var span_el = $('#' + span_id);
	var edit_zone_el = $('#' + edit_zone_id);
	var textbox_el = $('#' + textbox_id);
	
	var new_name = $.trim( textbox_el.val() );
	if (new_name == '') {
		$( "#dialog-error" ).dialog('open');
	} else {
		current_id = id;
		current_name = new_name;
		allowProgbarClose = false;
		
		$( "#dialog-progbar" ).dialog('open');
		
		$.post(
			"<?= __SITE_CONTEXT ?>admin/stock/update",
			{
				id: id,
				name: new_name
			},
			function(data) {
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				$( "#dialog-message-edit" ).dialog('open');
	
				$('#' + 'stock_name_span_' + current_id).text(current_name);
				
				cancel_edit(current_id);
			}
		);
	}
}

function make_row_string(id, name) {
	return "<tr stock_id='" + id + "'>" +
			"<td align='right'>" + id + "</td>" +
			"<td>" +
				"<span id='stock_name_span_" + id + "'>" + name + "</span>" +
				"<span style='display:none' id='stock_edit_zone_" + id + "'>" +
					"<input size='35' type='text' id='stock_name_textbox_" + id + "' />" +
					"<input type='button' value='Lưu' onclick='save_stock(" + id + ")' />" +
					"<input type='button' value='Thôi' onclick='cancel_edit(" + id + ")' />" +
				"</span>" +
			"</td>" +
			"<td></td>" +
			"<td align='right'>0</td>" +
			"<td align='right'>0</td>" +
			"<td align='center'><a href='#' onclick='return edit_stock(" + id + ")'>Sửa</a></td>" +
			"<td align='center'><a href='#' onclick='return del_stock("+ id + ")'>Xóa</a></td>" +
		"</tr>";
}

function add_stock() {
	var new_name = $('#new_stock_name').val();
	new_name = $.trim(new_name);
	
	if (new_name == '') {
		$( "#dialog-error" ).dialog('open');
	} else {
		current_name = new_name;
		allowProgbarClose = false;
		
		$( "#dialog-progbar" ).dialog('open');
		
		$.post(
			"<?= __SITE_CONTEXT ?>admin/stock/insert",
			{
				name: new_name
			},
			function(data) {
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				$('#myTable > tbody:first').append( make_row_string(data, current_name) );
				
				var t = parseInt( $('#stocks_num').text() ) + 1;
				$('#stocks_num').text(t);
				
				$( "#dialog-message-insert" ).dialog('open');
			}
		);
	}
}

function del_stock(id) {
	current_id = id;
	$( "#dialog-confirm-delete" ).dialog('open');
	
	return false;
}

</script>

<div id="dialog-confirm-delete" title="Xóa kho hàng này?" style="display:none">
	<p>Bạn có chắc chắn muốn xóa không?</p>
</div>

<div id="dialog-progbar" title="Xin vui lòng chờ đợi" style="display:none">
	<p>
		<div class="progbar" style="width:100%;height:20px"></div>
	</p>
</div>

<div id="dialog-message-edit" title="Thông báo" style="display:none">
	<p>Đã sửa tên thành công!</p>
</div>

<div id="dialog-message-delete" title="Thông báo" style="display:none">
	<p></p>
</div>

<div id="dialog-message-insert" title="Thông báo" style="display:none">
	<p>Đã thêm thành công!</p>
</div>

<div id="dialog-error" title="Thông báo" style="display:none">
	<p style='color:red'>Tên kho hàng không được bỏ trống!</p>
</div>

<p>

<div style="font-weight:bold;color:green">Số lượng: <span id="stocks_num"><?= count($stocks) ?></span></div>

<br />

<table class="table_grid" width="100%" id="myTable" cellspacing="0">
	<thead>
		<tr>
			<th scope="col" width="50px">Mã</th>
			<th scope="col">Tên</th>
			<th scope="col">Chi tiết</th>
			<th scope="col" width="100px">SL mã SP</th>
			<th scope="col" width="100px">SL SP</th>
			<th scope="col" width="50px"></th>
			<th scope="col" width="50px"></th>
		</tr>
	</thead>
	
	<tbody>
<?
	foreach ($stocks as $key => $value) {
		$details = '';
		$sum_prod = 0;
		$sum_prod_code = 0;
		foreach ($value['items_list'] as $k => $v) {
			if ($v['quantity'] == 0) continue;
			if ($details != '') {
				$details .= ', ';
			}
			$details .= '<a targer="_blank" title="Xem chi tiết sản phẩm" href="' . __SITE_CONTEXT . 'admin/product/detail?id=' . $k . '">' .
							$v['product_code'] . '</a> (' . $v['quantity'] . ')';
			$sum_prod_code++;
			$sum_prod += $v['quantity'];
		}
?>
		<tr stock_id="<?= $key ?>">
			<td align="right"><?= $key ?></td>
			<td>
				<span id='stock_name_span_<?= $key ?>'><?= $value['stock_name'] ?></span>
				<span style="display:none" id='stock_edit_zone_<?= $key ?>'>
					<input size="35" type="text" id='stock_name_textbox_<?= $key ?>' />
					<input type="button" value='Lưu' onclick="save_stock(<?= $key ?>)" />
					<input type="button" value='Thôi' onclick="cancel_edit(<?= $key ?>)" />
				</span>
			</td>
			<td><?= $details ?></td>
			
			<td align="right"><?= $sum_prod_code ?></td>
			<td align="right"><?= $sum_prod ?></td>
			<td align="center"><a href="#" onclick="return edit_stock(<?= $key ?>)">Sửa</a></td>
			<td align="center"><a href="#" onclick="return del_stock(<?= $key ?>)">Xóa</a></td>
		</tr>
<?
	}
?>
	</tbody>
</table>

<br />

<strong>Thêm kho hàng:</strong>
<br /><br />

Tên <input size="40" type="text" name="new_stock_name" id="new_stock_name" />
<input type="button" value="Thêm" onclick="add_stock()" />

<br />

</p>
<br />