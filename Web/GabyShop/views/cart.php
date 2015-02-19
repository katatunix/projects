<script type="text/javascript">

var int_id;

$(document).ready(function() {
	$("form input").keypress(function (e) {
		if (e.which == 0 || e.which == 8) {
			return true;
		}
		if (e.which == 13) {
			return false;
		};
		
		if (e.which < 48 || e.which > 57) {
			return false;
		}
		
		if ($(this).val().length > 2) {
			return false;
		}
	});
	
	int_id = setInterval("count_down()", 1000);
});

function checkout() {
	$('#update_cart').val('2');
	return true;
}

function remove_item(id) {
	$('#updates_' + id).val(0);
	$('#cartform').submit();
	return false;
}

function count_down() {
	var t = parseInt( $('#count_down').text() );
	if (t == 0) {
		clearInterval(int_id);
	} else {
		$('#count_down').text( t - 1 );
	}
}

</script>

<?
	require_once('includes/utils.php');
	$isCartEmpty = $cart->getQuantitySum() == 0 ? TRUE : FALSE;
?>

<div id="content-container">

<div id="collection">
	<div id="left" class="arrow"><!-- --></div>
	<div id="right" class="arrow"><!-- --></div>
<?
	if ($isCartEmpty) {
?>
		<ul class="no-items"><li><p>Giỏ hàng của bạn hiện không có sản phẩm nào.</p></li></ul>
<?
	} else {
		echo '<ul style="height:100%">';
		foreach ($cart_details as $key => $value) {
			$prod_title = $categories_list[$value['category_id']]['name'] . ' / ' .
				$brands_list[$value['brand_id']] . ' / ' . $value['code'];
			$url = __SITE_CONTEXT . 'product/' . $value['seo_url'];
?>
		
			<li class="item">
				<a href="<?= $url ?>" class="cart-item-link">
					<img width="240" height="240"
						src="<?= __SITE_CONTEXT . __UPLOAD_DIR ?><?= get_pic_at($value['pics'], 1) ?>"
						alt="<?= $prod_title ?>" />
				</a>
				<div class="item-meta">
					<div><?= $categories_list[$value['category_id']]['name'] ?></div>
					<div class="brand"><?= $brands_list[$value['brand_id']] ?></div>
					<div class="code"><?= $value['code'] ?></div>
					<p class="price"><?= format_money( $value['price_sell'] ) ?> VND</p>
				</div>
			</li>
<?
		}
		echo '</ul>';
	}
?>
</div>

<div id="content" style="float:left;margin-top:0">
<?
	if ($isCartEmpty) {
?>
<?
	} else {
?>

	<h3>Chi tiết giỏ hàng</h3>
	
	<form action="" method="post" id="cartform" style="margin-top:17px">
		<input type="hidden" name="update_cart" id="update_cart" value="1" />
		<table id="basket">

			<tr>
				<th class="item-title">Sản phẩm</th>
				<th class="item-price">Đơn giá</th>
				<th class="item-qty">S.L</th>
				<th class="item-remove"></th>
			</tr>
<?
			$odd = TRUE;
			$total_money = 0;
			foreach ($cart_details as $key => $value) {
				$url = __SITE_CONTEXT . 'product/' . $value['seo_url'];
?>
				<tr class="<?= $odd ? 'basket-odd' : 'basket-even' ?>">

					<td class="item-title">
						<span style="font-weight:bold"><?= $categories_list[$value['category_id']]['name'] ?> / <?= $brands_list[$value['brand_id']] ?></span><br/>
						<a href="<?= $url ?>" title="Xem sản phẩm"><?= $value['code'] ?></a>
					</td>
					<td class="item-price"><?= format_money( $value['price_sell'] ) ?> VND</td>
					<td class="item-qty">
						<input type="text" size="4" name="updates_<?= $key ?>" id="updates_<?= $key ?>"
							value="<?= $cart->listItem[$key] ?>"/>
					</td>
					<td class="item-remove"><a title="Loại bỏ sản phẩm ra khỏi giỏ hàng" href="#" onclick="return remove_item(<?= $key ?>)" class="btn-remove"></a></td>
				</tr>
<?
				$odd = !$odd;
				$total_money += $value['price_sell'] * $cart->listItem[$key];
			}
?>
		</table>

		<div id="basket-actions">
			<h3>Tổng cộng: <?= format_money( $total_money ) ?> VND</h3>
			
			<div id="submit-action">
				<input onclick="return checkout()" type="image" src="<?= __SITE_CONTEXT ?>views/images/btn-checkout.png" name="checkout" value="Checkout" id="checkout" />
			</div>
			
			<div id="update">
				<p>Nếu bạn thay đổi số lượng, hãy nhấn vào nút Update để cập nhật.</p>

				<input type="image" src="<?= __SITE_CONTEXT ?>views/images/btn-update.gif" id="update-cart" name="update" value="Update" />
			</div>
			
		</div>
	</form>
	<div style="height:135px"></div>

<?
	}
?>
</div>
<?
	if ( isset($checkout) ) {
		if ($checkout > -1) {
?>
	<div class="checkout_success">
		<p>Mã giỏ hàng của bạn là: <?= $checkout ?></p>
		<p>Xin vui lòng gọi điện cho chúng tôi theo số 0987918796 hoặc 0983871412 để đặt hàng.</p>
		<p>Chú ý: mã giỏ hàng sẽ bị xóa sau 24 tiếng.</p>
	</div>
<?
		} else {
?>
	<div class="checkout_fail">
		<p>Vui lòng checkout lại sau <span style="display:inline" id="count_down">10</span> giây nữa!</p>
	</div>
<?
		}
	}
?>
</div>

<script src="<?= __SITE_CONTEXT ?>views/js/slider.js" type="text/javascript"></script>
