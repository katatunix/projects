<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>

<script>

var promo_id_del = -1;
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
					"<?= __SITE_CONTEXT ?>admin/promo/delete",
					{ del_promo_id : promo_id_del },
					
					function(data) {
						var el = $('#dialog-message p');
						
						if (data == '0') { // success
							el.text('Đã xóa chương trình khuyến mãi thành công!');
							el.css('color', 'green');
							
							var t = parseInt( $('#promo_num').text() ) - 1;
							$('#promo_num').text(t);
							
							$('tr[promo_id=' + promo_id_del + ']').remove();
						} else if (data == '1') { // system error
							el.text('Không thể xóa! Có lỗi xảy ra!');
							el.css('color', 'red');
						}
						
						allowProgbarClose = true;
						$( "#dialog-progbar" ).dialog('close');
						
						$('#myTable').hide();
						$('#myTable').show();
						
						$( "#dialog-message" ).dialog('open');
						
						if ( $('#myTable tr').length <= 1 ) {
							$('#myTable').remove();
						}
												
						promo_id_del = -1;
					}
				);
			},
			"Không": function() {
				$( this ).dialog( "close" );
				promo_id_del = -1;
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

function del_promo(promo_id) {
	promo_id_del = promo_id;
	$( "#dialog-confirm" ).dialog('open');
	return false;
}
</script>

<div id="dialog-confirm" title="Xóa chương trình khuyến mãi này?" style="display:none">
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
	
<?
	if (count($list_promo) == 0) {
?>
	<div style="font-size:20px;color:red;font-weight:bold;text-align:center">KHÔNG TÌM THẤY!</div>
<?
	} else {
?>
	<div style="font-size:18px;color:green">Số lượng chương trình khuyến mãi tìm thấy: <span id="promo_num"><?= count($list_promo) ?></span></div>
	<br />
	
	<table class="table_grid" width="100%" id="myTable" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">Ngày tạo</th>
			<th scope="col">Chủ đề</th>
			<th scope="col">SEO URL</th>
			<th scope="col" width="50"></th>
			<th scope="col" width="50"></th>
		</tr>
	</thead>
	
	<tbody>
<?
	
	foreach ($list_promo as $key => $value) {
?>
	<tr promo_id="<?= $key ?>">
		<td align="right"><?= $value['promo_date'] ?></td>
		<td><?= $value['subject'] ?></td>
		<td><?= $value['seo_url'] ?></td>
		<td align="center"><a target="_blank" href="<?= __SITE_CONTEXT . 'admin/promo/update?id=' . $key ?>">Sửa</a></td>
		<td align="center"><a href="#" onclick="return del_promo(<?= $key ?>)">Xóa</a></td>
	</tr>
<?	
	}
	
?>
	</tbody>
	</table>
<?
	}
?>
	<br />
</p>
