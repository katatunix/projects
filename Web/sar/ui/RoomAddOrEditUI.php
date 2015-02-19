<?php
	if ( isset($message) )
	{
?>
		<ul style="color: <?= $message->isError ? 'red' : 'green' ?>">
			<?php foreach($message->list as $mes) { ?>
				<li><?= $mes ?></li>
			<?php } ?>
		</ul>
<?php
	}
?>

<?php if($rooms == NULL) { ?>

<form method="POST" action="addroom">
	<table>
		<tr>
			<td>Room Name:</td>
			<td><input  type="text" name="name"/></td>
		</tr>
		<tr>
			<td>Room Type:</td>
			<td><select name="rtype">
				<option value="0">Lecture Theatre</option>
				<option value="1">Lab Room</option>				
			</select></td>
		</tr>
		<tr>
			<td><input type="submit" value="Add"/></td>
			<td></td>
		</tr>
	</table>
</form>

<?php } else { ?>

	<form method="POST" action="editroom">
	<table>
		<tr>
			<td>Room Name:</td>
			<td><input  type="text" name="name" value="<?= htmlspecialchars( $rooms->name ) ?>"/></td>
		</tr>
		<tr>
			<td>Room Type:</td>
			<td><select name="rtype" disabled="true">
			<?php if ($rooms->rtype == 0) { ?>
				<option value="0" selected="true">Lecture Theatre</option>
				<option value="1">Lab Room</option>	
			<?php } else { ?>
				<option value="0">Lecture Theatre</option>
				<option value="1" selected="true">Lab Room</option>	
			<?php } ?>
			</select>
			<input  type="hidden" name="id" value="<?= $rooms->id ?>"/>
			</td>
		</tr>
		<tr>
			<td><input type="submit" value="Edit"/></td>
			<td></td>
		</tr>
	</table>
</form>

<?php } ?>
