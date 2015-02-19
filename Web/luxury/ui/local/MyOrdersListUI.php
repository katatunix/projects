<script type="text/javascript">
	$(document).ready(function() {
		$('#fromDate, #toDate').datepicker({
			dateFormat: 'yy-mm-dd',
			showWeek: true,
			showOtherMonths: true,
			showButtonPanel: true,
			closeText: 'X'
		});
	});
</script>

<form action="" method="get">
	From date <input type="text" id="fromDate" name="fromDate" size="10" value="<?= $fromDate ? $fromDate : '' ?>" />
	to date <input type="text" id="toDate" name="toDate" size="10" value="<?= $toDate ? $toDate : '' ?>" />
	<input type="submit" value="OK" />
</form>

<br />

<i><?= count($orders) ?> order(s)</i>

<br />
<br />
<?php
	if (count($orders) > 0) {
?>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
<?php
	foreach ($orders as $order) {
		$total = 0;
		$isRoom = true;
		foreach ($order->orderItemIds as $itemId) {
			$item = $orderItems[$itemId];
			$prod = $products[$item->productId];
			$total += $item->quantity * $prod->unitPrice;
			if ($prod->categoryString != 'Room') {
				$isRoom = false;
			}
		}
?>
	<tr>
		<td>
			<ul>
				<li><b>Created at:</b> <?= $order->createdDatetime ?></li>
				<li><b>Customer:</b> <?= $order->customer ? $order->customer : 'guest' ?></li>
				<li><b>Consumed at:</b> <?= $order->consumedDatetime ?></li>
				<li><b>Status:</b> <?= $order->statusString ?></li>
				<li><b>Items:</b> total price <?= $total ?> $
					<ul>
<?php
					foreach ($order->orderItemIds as $itemId) {
						$item = $orderItems[$itemId];
						$prod = $products[$item->productId];
?>
						<li><?= $prod->name ?>, qty: <?= $item->quantity ?>, price: <?= $item->quantity * $prod->unitPrice ?> $</li>
<?php
					}
?>
					</ul>
				</li>
			</ul>
<?php
			if ($order->statusString != 'Paid') {
?>
				<div style="text-align:center">
					<a href="<?= __SITE_CONTEXT ?>/local/order/<?= $isRoom ? 'editRoom' : 'editFoodb' ?>?id=<?= $order->id ?>">
						Edit
					</a>
				</div>
<?php
			}
?>
		</td>
	</tr>
<?php
	}
?>
</table>

<?php
	}
?>
