<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.sortable.min.js"></script>

<script type="text/javascript">

var allowProgbarClose = false;

$(document).ready(function() {
	$( "#sortable_grid" ).sortable();
	$( "#sortable_grid" ).disableSelection();
	
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
	
function cat_selected() {
	$('#myform').submit();
}

function save_lick() {
	var list_id_str = '';
	$('#sortable_grid li').each(function() {
		if (list_id_str != '') {
			list_id_str += ',';
		}
		list_id_str += $(this).attr('prod_id');
	});
	
	allowProgbarClose = false;
	$( "#dialog-progbar" ).dialog('open');
	
	$.post(
		"<?= __SITE_CONTEXT ?>admin/product/sortSave",
		{ listIdSort : list_id_str },
		
		function(data) {
			allowProgbarClose = true;
			$( "#dialog-progbar" ).dialog('close');
			$( "#dialog-message" ).dialog('open');
		}
	);
}
</script>

<div id="dialog-progbar" title="Xin vui lòng chờ đợi" style="display:none">
	<p>
		<div class="progbar" style="width:100%;height:20px"></div>
	</p>
</div>

<div id="dialog-message" title="Thông báo" style="display:none">
	<p>Đã lưu lại thứ tự thành công!</p>
</div>

<br />

<form method="post" action="" id="myform">
	<table class="table_no_grid">
		<tr>
			<td><strong>Chủng loại</strong></td>
			<td>
				<select name="cat_id" onchange="cat_selected()">
					<option value="0">-- Chọn một chủng loại để sắp xếp --</option>
<?
					foreach ($cats as $key => $value) {
?>
					<option value="<?= $key ?>" <?= $cur_cat_id == $key ? 'selected' : '' ?>>
						<?= $value ?>
					</option>
<?
					}
?>
				</select>
			</td>
		</tr>
	</table>
</form>

<br />

<?
if ( count($list_prod) > 0 ) {
?>

<p>Nhấn chuột vào sản phẩm và kéo thả đến vị trí mong muốn.
Các sản phẩm sẽ được sắp xếp theo thứ tự từ trái qua phải, từ trên xuống dưới.</p>

<ul id="sortable_grid">
<?
	foreach ($list_prod as $key => $value) {
?>
	<li class="ui-state-default" prod_id="<?= $key ?>">
		<img title="<?= $value['code'] ?>" width="100" height="100" src="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= get_pic_at($value['pics'], 0) ?>" alt="<?= $product_title ?>" />
	</li>
<?
	}
?>
</ul>

<div style="clear:both;padding:30px;text-align:center">
<input type="button" value="Lưu lại" style="font-size:20px" onclick="save_lick()" />
</div>

<?
} // count
?>
