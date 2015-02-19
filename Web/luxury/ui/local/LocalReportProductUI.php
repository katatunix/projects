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

<?php
	$hasOccupancyRate = $isRoom && $countDays > 0;

	if ($countDays > 0) {
?>
		<i><?= $countDays ?> day(s)</i>
		<br /><br />
<?php
	}
?>

<table border="1" cellpadding="4" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><?= $isRoom ? 'Room name' : 'Food or beverage name' ?></th>
			<th><?= $isRoom ? 'Price/day' : 'Unit price' ?></th>
			<th><?= $isRoom ? 'Occupancy days' : 'Quantity of sell items' ?></th>
<?php
			if ($hasOccupancyRate) {
?>
				<th>Occupancy rate</th>
<?php
			}
?>
			<th>Sales</th>
		</tr>
	</thead>

	<tbody>
<?php
	$totalQty = 0;
	$totalSales = 0;
	foreach ($products as $prod) {
		$totalQty += $prod->paidQty;
		$totalSales += $prod->paidQty * $prod->unitPrice;
?>
		<tr>
			<td><?= htmlspecialchars( $prod->name ) ?></td>
			<td align="right"><?= $prod->unitPrice ?> $</td>
			<td align="right"><?= $prod->paidQty ?></td>
<?php
			if ($hasOccupancyRate) {
?>
				<td align="right"><?= sprintf('%.2f', $prod->paidQty / $countDays * 100) ?> %</td>
<?php
			}
?>
			<td align="right"><?= $prod->paidQty * $prod->unitPrice ?> $</td>
		</tr>
<?php
	}
?>
	</tbody>

	<tfoot>
	<tr>
		<th>Total</th>
		<th></th>
		<th align="right"><?= $totalQty ?></th>
<?php
		if ($hasOccupancyRate) {
?>
			<th align="right"><?= sprintf('%.2f', $totalQty / ($countDays * count($products)) * 100) ?> %</th>
<?php
		}
?>
		<th align="right"><?= $totalSales ?> $</th>
	</tr>
	</tfoot>
</table>
