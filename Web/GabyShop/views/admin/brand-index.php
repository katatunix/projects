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
					"<?= __SITE_CONTEXT ?>admin/brand/delete",
					{ id : current_id },
					
					function(data) {
						var t = parseInt( $('#brands_num').text() ) - 1;
						$('#brands_num').text(t);
						
						allowProgbarClose = true;
						$( "#dialog-progbar" ).dialog('close');
						
						$('tr[brand_id=' + current_id + ']').remove();
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
		height: 140,
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


function edit_brand(id) {
	if (cur_select_id > -1) {
		if (cur_select_id == id) return false;
		cancel_edit(cur_select_id);
	}

	var span_id = 'brand_name_span_' + id;
	var edit_zone_id = 'brand_edit_zone_' + id;
	var textbox_id = 'brand_name_textbox_' + id;
	
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
	var span_id = 'brand_name_span_' + id;
	var edit_zone_id = 'brand_edit_zone_' + id;
	var textbox_id = 'brand_name_textbox_' + id;
	
	var span_el = $('#' + span_id);
	var edit_zone_el = $('#' + edit_zone_id);
	var textbox_el = $('#' + textbox_id);
	
	span_el.css('display', 'block');
	edit_zone_el.css('display', 'none');
	
	textbox_el.val( '' );
	
	cur_select_id = -1;
}

function save_brand(id) {
	var span_id = 'brand_name_span_' + id;
	var edit_zone_id = 'brand_edit_zone_' + id;
	var textbox_id = 'brand_name_textbox_' + id;
	
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
			"<?= __SITE_CONTEXT ?>admin/brand/update",
			{
				id: id,
				name: new_name
			},
			function(data) {
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				$( "#dialog-message-edit" ).dialog('open');
	
				$('#' + 'brand_name_span_' + current_id).text(current_name);
				
				cancel_edit(current_id);
			}
		);
	}
}

function make_row_string(id, name) {
	return "<tr brand_id='" + id + "'>" +
			"<td>" + id + "</td>" +
			"<td>" +
				"<span id='brand_name_span_" + id + "'>" + name + "</span>" +
				"<span style='display:none' id='brand_edit_zone_" + id + "'>" +
					"<input size='35' type='text' id='brand_name_textbox_" + id + "' />" +
					"<input type='button' value='Lưu' onclick='save_brand(" + id + ")' />" +
					"<input type='button' value='Thôi' onclick='cancel_edit(" + id + ")' />" +
				"</span>" +
			"</td>" +
			"<td align='center'>0</td>" +
			"<td align='center'><a href='#' onclick='return edit_brand(" + id + ")'>Sửa</a></td>" +
			"<td align='center'><a href='#' onclick='return del_brand("+ id + ")'>Xóa</a></td>" +
		"</tr>";
}

function add_brand() {
	var new_name = $('#new_brand_name').val();
	new_name = $.trim(new_name);
	
	if (new_name == '') {
		$( "#dialog-error" ).dialog('open');
	} else {
		current_name = new_name;
		allowProgbarClose = false;
		
		$( "#dialog-progbar" ).dialog('open');
		
		$.post(
			"<?= __SITE_CONTEXT ?>admin/brand/insert",
			{
				name: new_name
			},
			function(data) {
				allowProgbarClose = true;
				$( "#dialog-progbar" ).dialog('close');
				$('#myTable > tbody:first').append( make_row_string(data, current_name) );
				
				var t = parseInt( $('#brands_num').text() ) + 1;
				$('#brands_num').text(t);
				
				$( "#dialog-message-insert" ).dialog('open');
			}
		);
	}
}

function del_brand(id) {
	current_id = id;
	$( "#dialog-confirm-delete" ).dialog('open');
	
	return false;
}

</script>

<div id="dialog-confirm-delete" title="Xóa nhãn hiệu này?" style="display:none">
	<p>Tất cả khách hàng ở nhãn hiệu này (nếu có) sẽ được chuyển sang nhãn hiệu “Không biết”.
		Bạn có chắc chắn muốn xóa không?</p>
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
	<p>Đã xóa thành công!</p>
</div>

<div id="dialog-message-insert" title="Thông báo" style="display:none">
	<p>Đã thêm thành công!</p>
</div>

<div id="dialog-error" title="Thông báo" style="display:none">
	<p style='color:red'>Tên nhãn hiệu không được bỏ trống!</p>
</div>

<p>

<div style="font-weight:bold;color:green">Số lượng: <span id="brands_num"><?= count($brands) ?></span></div>

<br />

<table class="table_grid" width="100%" id="myTable" cellspacing="0">
	<thead>
		<tr>
			<th scope="col" width="50px">Mã</th>
			<th scope="col">Tên</th>
			<th scope="col" width="50px">SLSP</th>
			<th scope="col" width="50px"></th>
			<th scope="col" width="50px"></th>
		</tr>
	</thead>
	
	<tbody>
<?
	foreach ($brands as $key => $value) {
?>
		<tr brand_id="<?= $key ?>">
			<td><?= $key ?></td>
			<td>
				<span id='brand_name_span_<?= $key ?>'><?= $value['name'] ?></span>
				<span style="display:none" id='brand_edit_zone_<?= $key ?>'>
					<input size="35" type="text" id='brand_name_textbox_<?= $key ?>' />
					<input type="button" value='Lưu' onclick="save_brand(<?= $key ?>)" />
					<input type="button" value='Thôi' onclick="cancel_edit(<?= $key ?>)" />
				</span>
			</td>
			<td align="center"><?= $value['prod_count'] ?></td>
			<td align="center"><a href="#" onclick="return edit_brand(<?= $key ?>)">Sửa</a></td>
			<td align="center"><a href="#" onclick="return del_brand(<?= $key ?>)">Xóa</a></td>
		</tr>
<?
	}
?>
	</tbody>
</table>

<br />

<strong>Thêm nhãn hiệu:</strong>
<br /><br />

Tên <input size="40" type="text" name="new_brand_name" id="new_brand_name" />
<input type="button" value="Thêm" onclick="add_brand()" />

<br />

</p>
<br />