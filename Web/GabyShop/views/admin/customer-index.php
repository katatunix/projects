<script src="<?= __SITE_CONTEXT ?>views/jquery/external/jquery.bgiframe-2.1.2.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.core.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.widget.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.mouse.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.button.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.draggable.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.position.min.js"></script>
<script src="<?= __SITE_CONTEXT ?>views/jquery/ui/jquery.ui.dialog.min.js"></script>

<script>
	var cust_id_del = -1;
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
						"<?= __SITE_CONTEXT ?>admin/customer/delete",
						{ del_cust_id : cust_id_del },
						
						function(data) {
							var t = parseInt( $('#cust_num').text() ) - 1;
							$('#cust_num').text(t);
							
							allowProgbarClose = true;
							$( "#dialog-progbar" ).dialog('close');
							
							$('tr[cust_id=' + cust_id_del + ']').remove();
							$('#myTable').hide();
							$('#myTable').show();
							
							$( "#dialog-message" ).dialog('open');
							
							if ( $('#myTable tr').length <= 1 ) {
								$('#myTable').remove();
							}
							
							cust_id_del = -1;
						}
					);
				},
				"Không": function() {
					$( this ).dialog( "close" );
					cust_id_del = -1;
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
	
	function del_cust(cust_id) {
		cust_id_del = cust_id;
		$( "#dialog-confirm" ).dialog('open');
		return false;
	}
</script>

<div id="dialog-confirm" title="Xóa khách hàng này?" style="display:none">
	<p>Thông tin bị xóa sẽ không thể phục hồi được. Bạn có chắc chắn muốn xóa không?</p>
</div>

<div id="dialog-progbar" title="Xin vui lòng chờ đợi" style="display:none">
	<p>
		<div class="progbar" style="width:100%;height:20px"></div>
	</p>
</div>

<div id="dialog-message" title="Thông báo" style="display:none">
	<p>Đã xóa khách hàng thành công!</p>
</div>


<p>

<form action="" method="get">
<table class="table_no_grid" align="center">
	<tr>
		<td align="right"><b>Mã KH</b></td>
		<td>
			<input value="<?= $_GET['s_cust_id'] ?>" size="8" id="search_cust_id" name="s_cust_id" type="text" />
			<input type="button" value="Clear" onclick="$('#search_cust_id').val('')" />
		</td>
		
		<td align="right"><b>Tuổi</b></td>
		<td>
			<input size="10" name="s_min_age" id="s_min_age"
				value="<?= $s_info['s_min_age'] ?>" type="text" /> đến
			<input size="10" name="s_max_age" id="s_max_age"
				value="<?= $s_info['s_max_age'] ?>" type="text" />
		</td>
	</tr>
	<tr>
		<td align="right"><b>Họ và tên</b></td>
		<td><input value="<?= $s_info['s_full_name'] ?>" id="s_full_name" name="s_full_name" size="25" type="text" /></td>
		
		<td align="right"><b>SL hóa đơn</b></td>
		<td><input size="10" type="text" /> đến <input size="10" type="text" /></td>
	</tr>
	<tr>
		<td align="right"><b>Tỉnh/thành</b></td>
		<td>
			<select id="s_city" name="s_city" style="width:200px">
				<option value="-1">-- Tất cả --</option>
				<option value="0"
					<?= $s_info['s_city'] == '0' ? 'selected' : '' ?>
				>-- Không biết --</option>
<?
				foreach ($cities as $key => $value) {
					$selected = $key == $s_info['s_city'] ? 'selected' : '';
					echo "<option value='$key' $selected>$value</option>";
				}
?>
			</select>
		</td>
		<td align="right"><b>Điểm tích lũy</b></td>
		<td><input size="10" type="text" /> đến <input size="10" type="text" /></td>
	</tr>
	<tr>
		<td align="right"></td>
		<td></td>
		<td align="right"><b>Điện thoại</b></td>
		<td><input value="<?= $s_info['s_phone'] ?>" id="s_phone" name="s_phone" size="25" type="text" /></td>
		
	</tr>
</table>

<br />
<div style="text-align:center">
	<input type="submit" style="font-size:20px" value="Search" />
</div>

<br />
</form>

<?
if ( $list_cust != NULL) {
?>

<?
	if ( $list_cust['cust_num'] == 0) {
?>
	<div style="font-size:20px;color:red;font-weight:bold;text-align:center">KHÔNG TÌM THẤY!</div>
<?
	} else {
?>

<div id="div_cust_num" style="color:green;font-weight:bold">Số lượng khách hàng: <span id="cust_num"><?= $list_cust['cust_num'] ?></span></div>
<br />

<?
	require_once('includes/utils.php');
	echo make_navigation($list_cust['page_num'], $page, $url);
?>

<br />
<table class="table_grid" width="100%" id="myTable" cellspacing="0">
	<thead>
		<tr>
			<th scope="col">Mã KH</th>
			<th scope="col">Họ và tên</th>
			<th scope="col">Ngày sinh</th>
			<th scope="col">Điện thoại</th>
			<th scope="col">Tỉnh/thành</th>
			<th scope="col"></th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
<?
	foreach ($list_cust as $key => $value) {
		if ($key != 'page_num' && $key != 'cust_num') {
	
			echo "<tr cust_id=$key id='cust_$key'>";
				echo "<td align='right'>$key</td>";
				echo "<td><a title='Xem thông tin chi tiết' href='#'>" . $value['full_name'] . "</a></td>";
				echo "<td>" . $value['birthday'] . "</td>";
				echo "<td>" . $value['phone'] . "</td>";
				echo "<td>" . $value['city_name'] . "</td>";
				echo "<td align='center'><a target='_blank' href='" . __SITE_CONTEXT . "admin/customer/update?id=$key'>Sửa</a></td>";
				echo "<td align='center'><a href='#' onclick='return del_cust($key)'>Xóa</a></td>";
			echo '</tr>';
		
		}
		
	}
?>
	</tbody>
</table>
<br />

<?
	echo make_navigation($list_cust['page_num'], $page, $url);
	}
?>
<br />

<?
} else { // if ( $list_cust != NULL)
?>
	<div style="color:green;font-size:20px;text-align:center">Nhấn nút Search để bắt đầu tìm kiếm</div>
	<br />
<?
}
?>
</p>
