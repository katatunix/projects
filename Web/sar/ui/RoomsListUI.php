<a href="<?= __SITE_CONTEXT?>/room/viewaddroom" title="Add Room">
	Add Room
</a>
<script type="text/javascript">
		function submitRemove(roomid) {
			showConfirmDialog('Confirm', 'Are you sure to remove?', sureRemove, null, roomid);
			}
		
		function sureRemove(data) {
		$('#removeRoom input[name=id]').val(data);
		$('#removeRoom').submit();
		}
	$(document).ready(function(){
		
		initConfirmDialog('confirmDialog');
		
});
</script>
<div id="confirmDialog">
			<p></p>
		</div>
<table style="width: 35%">
	<tr style="font-weight: bold">
		<td>Room Name:</td>
		<td>Room Type:</td>
		<td></td>
	</tr>
<?php
	foreach ($rooms as $i => $room) {
?>
	<?php
			$rtype = $room ->rtype == 0 ? 'Lecture Theatre' : 'Lab Room';//instanceof LectureTheater? 'LectureTheater':'LabRoom';
		?>
		<tr>
			<td><?= $room->name ?></td>
			<td><?= $rtype ?> </td>
			<td><a href="<?= __SITE_CONTEXT?>/room/vieweditroom?id=<?= $room->id?>" title="Edit Room">
			Edit
		</a></td>
		
		<td align="center"><a href="#" onclick="submitRemove(<?= $room->id?>)">Remove</a>
		</td>
		
<?php
	}
?>
</table>
<form id="removeRoom" method="POST" action="<?= __SITE_CONTEXT ?>/room/removeroom">
	<input type="hidden" name="id" value="" />
</form>
