<?php
	/*
	Expected variables:
		$isEdit: boolean
		$isRoom: boolean

		$products: array of PObject

		$customer: string
		$consumedDate: string
		$consumedHour: string
		$consumedMinute: string
		$statusString: string

		$pidList: array of int
		$qtyList: array of int

		$message: string
		if $message != NULL then need
			$isErrorMessage: boolean

		if $isEdit == true then need:
			$orderId: int
			$canEditOrder: boolean

		if $isEdit == false and $message != NULL and $isErrorMessage == false then need:
			$newOrderId: int

	*/
?>

<?php
	if ($message) {
		if ($isErrorMessage) {
			echo '<p style="color:red">';
		} else {
			echo '<p style="color:green">';
		}
		echo htmlspecialchars($message);
		if (!$isErrorMessage && !$isEdit) {
			$editUrl = __SITE_CONTEXT . "/local/order/";
			$editUrl .= $isRoom ? 'editRoom' : 'editFoodb';
			$editUrl .= "?id=$newOrderId";

			echo " <a href='$editUrl'>Edit the order</a>";
		}
		echo '</p>';
	}

	if (!$isEdit || $canEditOrder) {
?>

<script type="text/javascript">
	var g_products = [
<?php
		$first = true;
		foreach ($products as $prod) {
			if (!$first) {
				echo ",";
			} else {
				$first = false;
			}
			echo "{";
			echo "id:$prod->id,";
			echo "name:'" . htmlspecialchars($prod->name) . "',";
			echo "unitPrice:$prod->unitPrice";
			echo "}";
		}
?>
	];

	var g_items = [

<?php
		if ($pidList && $qtyList && count($pidList) == count($qtyList)) {
			$count = count($pidList);
			$first = true;
			for ($i = 0; $i < $count; $i++) {
				if (!$first) {
					echo ",";
				} else {
					$first = false;
				}
				echo "{";
				echo "pid:$pidList[$i],";
				echo "qty:$qtyList[$i]";
				echo "}";
			}
		}
?>
	];

	$(document).ready(function() {
		$('#consumedDate').datepicker({
			dateFormat: 'yy-mm-dd',
			showWeek: true,
			showOtherMonths: true,
			showButtonPanel: true,
			closeText: 'X'
		});

		$('#products').change(function () {
			if ($(this).val() == 0) {
				$('#addButton').attr('disabled', 'disabled');
			} else {
				$('#addButton').removeAttr('disabled');
			}
		});

		$('#addButton').click(function() {
			appendRowItem( $('#products').val(), 1 );
		});

		$('#addButton').attr('disabled', 'disabled');

		//
		var statusString = '<?= $statusString ?>';
		if (statusString) {
			$('input[name=statusString][value=' + statusString + ']').attr('checked', 'checked');
		}

		//
		for (var i = 0; i < g_items.length; i++) {
			appendRowItem(g_items[i].pid, g_items[i].qty);
		}
		makeTotalPrice();

		//
		$('#createOrderForm').submit(function() {
			var pidList = '';
			var qtyList = '';
			var first = true;
			$('#tableItems tbody tr[id^=prod_]').each(function() {
				var pid = $(this).attr('id').substr('prod_'.length);
				var qty = $(this).find('.pQty').val();
				if (!first) {
					pidList += ',';
					qtyList += ',';
				} else {
					first = false;
				}
				pidList += pid;
				qtyList += qty;
			});
			$('input[name=pidList]').val(pidList);
			$('input[name=qtyList]').val(qtyList);
		});
	});

	function appendRowItem(pid, qty) {
		//
		var prod = findProductById(pid);
		if (!prod) return;

		//
		var tr = null;
		$('#tableItems tbody tr[id^=prod_]').each(function() {
			var _pid = $(this).attr('id').substr('prod_'.length);
			if (_pid == pid) {
				tr = $(this);
				return false; // break
			}
		});

		//
		if (!tr) {
			tr = $('<tr id="prod_' + prod.id + '">');

			tr.append( $('<td>').text(prod.name) );
			tr.append( $('<td>').html('<input class="pQty" type="text" size="2" value="' + qty + '" ' +
				'onkeydown="onQtyKeyDown(event, ' + prod.id + ')" ' +
				'onblur="onQtyBlur(' + prod.id + ')" />') );
			tr.append( $('<td>').text(prod.unitPrice + ' $') );
			tr.append( $('<td class="pPrice">').text((prod.unitPrice * qty) + ' $') );
			tr.append( $('<td>').html('<input type="button" value="X" onclick="removeRowItem(' + prod.id +
				')" title="Remove this item" />') );

			$('#tableItems tbody').append(tr);

		} else {

			var qtyTxt = tr.find('.pQty');
			var priceTd = tr.find('.pPrice');

			var _qty = qtyTxt.val();
			if (_qty != parseInt(_qty)) {
				_qty = 1;
			}
			var newQty = parseInt(_qty) + qty;
			qtyTxt.val(newQty);
			priceTd.text((newQty * prod.unitPrice) + ' $');
		}

		//
		makeTotalPrice();
	}

	function removeRowItem(pid) {
		$('#prod_' + pid).remove();
		makeTotalPrice();
	}

	function onQtyKeyDown(evt, pid) {
		var keypressed = evt.keyCode || evt.which;
		if (keypressed == 13) {
			evt.preventDefault();
			onQtyBlur(pid);
		}
	}

	function onQtyBlur(pid) {
		var tr = $('#prod_' + pid);

		//
		var qtyTxt = tr.find('.pQty');
		var qty = qtyTxt.val();
		if (qty != parseInt(qty) || qty <= 0) {
			qty = 1;
			qtyTxt.val(qty);
		} else {
			qty = parseInt(qty);
		}

		//
		var prod = findProductById(pid);
		var priceTd = tr.find('.pPrice');
		priceTd.text(qty * prod.unitPrice + ' $');

		//
		makeTotalPrice();
	}

	function makeTotalPrice() {
		var total = 0.0;
		$('#tableItems tbody tr[id^=prod_]').each(function() {
			var pid = $(this).attr('id').substr('prod_'.length);
			var prod = findProductById(pid);
			if (prod) {
				var qty = $(this).find('.pQty').val();
				total += qty * prod.unitPrice;
			}
		});
		$('#totalPrice').text(total);
	}

	function findProductById(pid) {
		var prod = null;
		for (var i = 0; i < g_products.length; i++) {
			if (g_products[i].id == pid) {
				prod = g_products[i];
				break;
			}
		}
		return prod;
	}
</script>

<form action="" method="post" id="createOrderForm">

	<b>Customer</b><br />
	<input type="text" name="customer" style="width: 100%" value="<?= $customer ? htmlspecialchars($customer) : '' ?>" />
	<br /><br />

	<b>Consumed datetime</b><br />
	<input type="text" id="consumedDate" name="consumedDate"
	       value="<?= $consumedDate ? $consumedDate : '' ?>" />&nbsp;&nbsp;
	<input type="text" id="consumedHour" name="consumedHour"
	       value="<?= $consumedHour ? $consumedHour : '' ?>" size="2" /> :
	<input type="text" id="consumedMinute" name="consumedMinute"
	       value="<?= $consumedMinute ? $consumedMinute : '' ?>" size="2" />
	<span style="font-size: .85em">(leave blank to use current datetime)</span>
	<br /><br />

<?php
	if ($isEdit) {
?>
		<label><input type="radio" name="statusString" value="Booked">Booked</label>
		<label><input type="radio" name="statusString" value="Paid">Paid</label>
		<label><input type="radio" name="statusString" value="Canceled">Canceled</label>
<?php
	} else {
?>
		<label><input type="radio" name="statusString" value="Booked">Booked</label>
		<label><input type="radio" name="statusString" value="Paid">Paid</label>
<?php
	}
?>
	<br /><br />

	<select id="products">
		<option value="0">
			<?= $isRoom ? '-- Select a room --' : '-- Select a food or beverage --' ?>
		</option>
<?php
		foreach ($products as $prod) {
?>
			<option value="<?= $prod->id ?>">
				<?= htmlspecialchars($prod->name) . ' (' . $prod->unitPrice . ' $)' ?>
			</option>
<?php
		}
?>
	</select>
	<input id="addButton" type="button" value="+" title="Add to order" />
	<br /><br />

	<table id="tableItems" border="1" cellpadding="4" cellspacing="0">
		<thead>
		<tr>
			<th><?= $isRoom ? 'Room name' : 'Food or beverage name' ?></th>
			<th><?= $isRoom ? 'Days quantity' : 'Items quantity' ?></th>
			<th><?= $isRoom ? 'Price/day' : 'Unit price' ?></th>
			<th>Price</th>
			<th>#</th>
		</tr>
		</thead>

		<tbody></tbody>

	</table>
	<br />

	<b>Total price: <span id="totalPrice">0</span> $</b>

	<br /><br />
	<input type="submit" value="<?= $isEdit ? 'Update' : 'Create' ?>" style="font-size: 1.5em" />

	<input type="hidden" name="pidList" />
	<input type="hidden" name="qtyList" />

<?php
	if ($isEdit) {
?>
	<input type="hidden" name="orderId" value="<?= $orderId ?>" />
<?php
	}
?>

</form>

<?php
	}
?>
