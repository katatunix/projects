<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>

<script>

var prod_id_del = -1;
var allowProgbarClose = false;

$(document).ready(function() {
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		width: 330,
		modal: true,
		buttons: {
			"Có": function() {
				$( this ).dialog( "close" );
				
				allowProgbarClose = false;
				$( "#dialog-progbar" ).dialog('open');
				
				$.post(
					"<?= __SITE_CONTEXT ?>admin/product/delete",
					{ del_prod_id : prod_id_del },
					
					function(data) {
						var el = $('#dialog-message p');
						
						if (data == '0') { // success
							el.text('Đã xóa sản phẩm thành công!');
							el.css('color', 'green');
							
							var t = parseInt( $('#prod_num').text() ) - 1;
							$('#prod_num').text(t);
							
							$('div[prod_id=' + prod_id_del + ']').remove();
							
						} else if (data == '1') { // cannot delete
							el.text('Không thể xóa! Sản phẩm này đã có hóa đơn hoặc phiếu nhập/chuyển hàng!');
							el.css('color', 'red');
						
						} else if (data == '2') { // system error
							el.text('Không thể xóa! Có lỗi xảy ra!');
							el.css('color', 'red');
						}
						
						allowProgbarClose = true;
						$( "#dialog-progbar" ).dialog('close');
						
						$('#myTable').hide();
						$('#myTable').show();
						
						$( "#dialog-message" ).dialog('open');
												
						prod_id_del = -1;
					}
				);
			},
			"Không": function() {
				$( this ).dialog( "close" );
				prod_id_del = -1;
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
		height: 160,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});

});

function del_prod(prod_id) {
	prod_id_del = prod_id;
	$( "#dialog-confirm" ).dialog('open');
	return false;
}
</script>

<div id="dialog-confirm" title="Xóa sản phẩm này?" style="display:none">
	<p>Thông tin bị xóa sẽ không thể phục hồi được. Bạn có chắc chắn muốn xóa không?</p>
</div>

<div id="dialog-progbar" title="Xin vui lòng chờ đợi" style="display:none">
	<p>
		<div class="progbar" style="width:100%;height:20px"></div>
	</p>
</div>

<div id="dialog-message" title="Thông báo" style="display:none">
	<p></p>
</div>

<div id="popup" style="display:none;position:absolute;z-index:9999">
	<img width="240" height="240" border="2" />
</div>

<p>
	<form method="post" action="">
	<table class="table_no_grid" align="center">
		<tr>
			<td align="right"><b>Mã SP</b></td>
			<td>
				<input type="text" name="s_product_code" id="s_product_code" value="<?= $s_product_code ?>" style="width:185px" />
				<input type="button" value="Clear" onclick="$('#s_product_code').val('')" />
			</td>
		</tr>
		<tr>
			<td align="right"><b>Chủng loại</b></td>
			<td>
				<select name="s_category_id" style="width:250px">
					<option value="-1">-- Tất cả --</option>
					<option value="0"
						<?= $s_category_id == '0' ? 'selected' : '' ?>>
						-- Không biết --</option>
<?
				foreach ($list_category as $key => $value) {
					$sl = $key == $s_category_id ? 'selected' : '';
?>
					<option value="<?= $key ?>" <?= $sl ?>><?= $value ?></option>
<?
				}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right"><b>Nhãn hiệu</b></td>
			<td>
				<select name="s_brand_id" style="width:250px">
					<option value="-1">-- Tất cả --</option>
					<option value="0"
						<?= $s_brand_id == '0' ? 'selected' : '' ?>>
						-- Không biết --</option>
<?
				foreach ($list_brand as $key => $value) {
					$sl = $key == $s_brand_id ? 'selected' : '';
?>
					<option value="<?= $key ?>" <?= $sl ?>><?= $value ?></option>
<?
				}
?>
				</select>
			</td>
		</tr>
	</table>
	<div style="margin:10px 0px 15px 0px;text-align:center"><input type="submit" value="Search" style="font-size:20px"></div>
	</form>
	
<?
	if (count($list_prod) == 0) {
?>
	<div style="font-size:20px;color:red;font-weight:bold;text-align:center">KHÔNG TÌM THẤY!</div>
<?
	} else {
?>
	<div style="font-size:18px;color:green">Số lượng sản phẩm tìm thấy: <span id="prod_num"><?= count($list_prod) ?></span></div>
	<br />
	<table class="table_grid" width="100%" id="myTable" cellspacing="0">
	<tbody>
<?
	$odd = TRUE;
	foreach ($list_prod as $key => $value) {
		if ($odd) {
			echo "<tr>";
		}
?>		
		<td>
			<div prod_id="<?= $key ?>">
				<div style="float:left; height:100px">
					<a title="Xem chi tiết" href="#"><img border="0" width="100" height="100" src="<?= __SITE_CONTEXT . __UPLOAD_DIR . $value['pic'] ?>" /></a>
				</div>
				<div style="float:left; padding:10px">
					<strong><?= $value['code'] ?></strong> /
					<?= $value['category_name'] ? $value['category_name'] : '[Không biết]' ?> /
					<?= $value['brand_name'] ? $value['brand_name'] : '[Không biết]' ?>
					<br />
					
					<strong>Giá vốn: </strong><?= format_money( $value['price_fonds'] ) ?> VND<br />
					<strong>Giá bán: </strong><?= format_money( $value['price_sell'] ) ?> VND<br />
					<strong>SL trong kho: </strong><?= format_money( $value['my_sum'] ) ?><br>
					
				</div>
				<div style="float:right; margin:80px 5px 0 0">
					<a target="_blank" href="<?= __SITE_CONTEXT . 'admin/product/update?id=' . $key ?>">Sửa</a>
					|
					<a href="#" onclick="return del_prod(<?= $key ?>)">Xóa</a>
				</div>
			</div>
		</td>
<?
		if (!$odd) {
			echo '</tr>';
		}
		$odd = !$odd;
	}
	
	if (!$odd) {
		echo '</tr>';
	}
?>
	</tbody>
	</table>
<?
	}
?>
	<br />
</p>
