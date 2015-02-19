<?
	$error_not_found = FALSE;
	if ( $message )
		if ( $message['type'] == 'error_not_found' )
			$error_not_found = TRUE;
?>

<?
	if ($error_not_found) {
		echo '<p style="color:red">' . $message['value'] . '</p>';
	} else {
?>

<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.sortable.min.js"></script>

<script type="text/javascript" src="<?= __SITE_CONTEXT ?>views/admin/js/swf_upload.js"></script>
<script type="text/javascript" src="<?= __SITE_CONTEXT ?>views/admin/js/swf_handlers.js"></script>

<script>

var g_pics = '<?= $product["pics"]?>';
var g_pics_bk = '<?= $product_backup["pics"]?>';

var prod_obj = {
	code:			'<?= addslashes($product_backup['code']) ?>',
	brand_id:		<?= isset( $product_backup['brand_id'] ) ? $product_backup['brand_id'] : 0 ?>,
	category_id:	<?= isset( $product_backup['category_id'] ) ? $product_backup['category_id'] : 0 ?>,
	price_fonds:	'<?= addslashes($product_backup['price_fonds']) ?>',
	price_sell:		'<?= addslashes($product_backup['price_sell']) ?>',
	seo_url:		'<?= $product_backup['seo_url'] ?>'
};

function my_reset() {
	$('#code').val(prod_obj.code);
	$('#category_id').val(prod_obj.category_id);
	$('#brand_id').val(prod_obj.brand_id);
	$('#price_fonds').val(prod_obj.price_fonds);
	$('#price_sell').val(prod_obj.price_sell);
	$('#seo_url').val(prod_obj.seo_url);
	
	$('#description').val( $('#description_bk').val() );
	
	$('#media-items').empty();
	showItemsList( g_pics_bk );
}

function my_submit() {
	if (getLength() != 4) {
		$( "#dialog-message" ).dialog('open');
		return false;
	}
	$('#pics').val( getListFiles() );
	return true;
}

$(document).ready(function() {
	$('#code').focus();
	
	if (g_pics != '') {
		showItemsList( g_pics );
	}
	
	$( "#dialog-message" ).dialog({
		modal: true,
		autoOpen: false,
		height: 150,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$('#media-items').sortable();
	$('#media-items').disableSelection();
});
</script>

<div id="dialog-message" title="Thông báo" style="display:none">
	<p style="color:red">Phải chọn đúng 4 file hình ảnh!</p>
</div>

<div id="popup" style="display:none;position:absolute;z-index:9999">
	<img border="2" />
</div>

<p>

<strong>Những ô có dấu (*) là bắt buộc</strong>
<br />

<?
	if ( $message ) {
		if ( $message['type'] == 'error' ) {
			echo '<p style="color:red">';
		} else {
			echo '<p style="color:green">';
		}
		echo $message['value'];
		echo '</p>';
	}
?>

<form method="post" action="">

<table class="table_no_grid" align="center">
<tbody>

<tr>
	<td align="right">Mã sản phẩm (*)</td>
	<td>
		<input id="code" name="code" value="<?= $product['code'] ?>" type="text" style="width:400px" />
	</td>
</tr>

<tr>
	<td align="right">Chủng loại</td>
	<td>
	<select name="category_id" id="category_id">
		<option value="0">-- Không biết --</option>
<?
		foreach ($cats as $key => $value) {
			$selected = $product['category_id'] == $key ? 'selected' : '';
			echo "<option value='$key' $selected>$value</option>";
		}
?>
	</select>
	</td>
</tr>

<tr>
	<td align="right">Nhãn hiệu</td>
	<td>
	<select name="brand_id" id="brand_id">
		<option value="0">-- Không biết --</option>
<?
		foreach ($brands as $key => $value) {
			$selected = $product['brand_id'] == $key ? 'selected' : '';
			echo "<option value='$key' $selected>$value</option>";
		}
?>
	</select>
	</td>
</tr>

<tr>
	<td align="right">SEO URL (*)</td>
	<td>
		<input id="seo_url" name="seo_url" value="<?= $product['seo_url'] ?>" type="text" style="width:400px" />
	</td>
</tr>

<tr>
	<td align="right">Giá vốn (*)</td>
	<td>
		<input id="price_fonds" name="price_fonds" value="<?= $product['price_fonds'] ?>" type="text" size="10" /> (VND)
	</td>
</tr>
<tr>
	<td align="right">Giá bán (*)</td>
	<td>
		<input id="price_sell" name="price_sell" value="<?= $product['price_sell'] ?>" type="text" size="10" /> (VND)
	</td>
</tr>
<tr>
	<td align="right">Mô tả sản phẩm</td>
	<td>
		<textarea id="description" name="description" style="width:400px;height:100px"><?= $product['description'] ?></textarea>
		<textarea id="description_bk" style="display:none"><?= addslashes($product_backup['description']) ?></textarea>
	</td>
</tr>

<tr>
	<td></td>
	<td>
		<table id='table-flash'>
			<tr>
				<td><div id="flash-browse-button"></div></td>
				<td style="font-size:11px;font-weight:bold;line-height:17px">Chỉ chọn {.jpg, .png, .gif, .bmp, .jpeg}<br />
				Size < 2MB, theo thứ tự từ nhỏ đến lớn</td>
			</tr>
		</table>
		<div id="media-items">

		</div>
	</td>
</tr>

<tr>
	<td></td>
	<td align="center">
		<input id="save-button" style="font-size:20px" type="submit" value="Lưu lại" onclick="return my_submit()" />
		<input id="reset-button" style="font-size:20px" type="button" value="Reset" onclick="my_reset()" />
	</td>
</tr>
	
</tbody>
</table>
<input type="hidden" name="pics" id="pics" />
<input type="hidden" name="id" value="<?= $product['id'] ?>" />
</form>

<br />
</p>

<script>
var swfu = new SWFUpload({
	button_text: '<span class="button">Chọn file ảnh</span>',
	button_text_style: '.button {text-align:center; font-weight:bold; font-family:Verdana}',
	button_height: "24",
	button_width: "132",
	button_text_top_padding : 3,
	button_cursor : SWFUpload.CURSOR.HAND,
	button_image_url: '<?= __SITE_CONTEXT ?>views/admin/images/swf_upload.png',
	button_placeholder_id: "flash-browse-button",
	upload_url : "<?= __SITE_CONTEXT ?>admin/upload/",
	flash_url : "<?= __SITE_CONTEXT ?>views/admin/flash/swfupload.swf",
	file_post_name: "async_upload",
	file_types: "*.jpg; *.png; *.gif; *.bmp; *.jpeg",
	file_size_limit : "2 MB",
	file_types_description: "Web Image Files",
	post_params : { },
	file_queued_handler : fileQueued,
	upload_progress_handler : uploadProgress,
	upload_success_handler : uploadSuccess,
	file_dialog_complete_handler : fileDialogComplete,
	debug: false,
	button_window_mode:SWFUpload.WINDOW_MODE.TRANSPARENT
});
</script>

<?
	} // $error_not_found
?>
