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
	<b>Staff member</b>
	<select name="sid">
		<option value="0" <?= $sid == 0 ? 'selected' : '' ?> >-- Select a staff --</option>
<?php
		foreach ($staffs as $st) {
?>
			<option value="<?= $st->id ?>" <?= $sid == $st->id ? 'selected' : '' ?> >
				<?= $st->username ?> - <?= $st->fullname ?>
			</option>
<?php
		}
?>
	</select>
	<br /><br />

	From date <input type="text" id="fromDate" name="fromDate" size="10" value="<?= $fromDate ? $fromDate : '' ?>" />
	to date <input type="text" id="toDate" name="toDate" size="10" value="<?= $toDate ? $toDate : '' ?>" />
	<input type="submit" value="OK" />
</form>

<br />

<table border="1" cellpadding="4" width="100%" cellspacing="0">
	<thead>
		<tr>
			<th>Date</th>
			<th>Quantity of paid orders</th>
			<th>Room sales</th>
			<th>Food & beverage sales</th>
			<th>Total sales</th>
		</tr>
	</thead>
	
	<tbody>
<?php
		$totalRoomSales = 0;
		$totalFoodbSales = 0;
		foreach ($list as $obj) {
			$totalRoomSales += $obj->roomSales;
			$totalFoodbSales += $obj->foodbSales;
?>
		<tr>
			<td><?= $obj->date ?></td>
			<td align="right"><?= $obj->ordersCount ?></td>
			<td align="right"><?= $obj->roomSales ?> $</td>
			<td align="right"><?= $obj->foodbSales ?> $</td>
			<td align="right"><?= $obj->roomSales + $obj->foodbSales ?> $</td>
		</tr>
<?php
		}
?>
	</tbody>
	
	<tfoot>
		<tr>
			<th>Total</th>
			<th align="right"></th>
			<th align="right"><?= $totalRoomSales ?> $</th>
			<th align="right"><?= $totalFoodbSales ?> $</th>
			<th align="right"><?= $totalRoomSales + $totalFoodbSales ?> $</th>
		</tr>
	</tfoot>
</table>
