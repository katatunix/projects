<a href="<?= __SITE_CONTEXT ?>/account/viewaddstaffaccount" title="Add Staff Account">
	Add New Staff Account
</a><br />

<table style="width: 69%">
	<tr style="font-weight: bold">
		<td>Username</td>
		<td>Fullname</td>
		<td>Role</td>
		<td>Active</td>
		<td> </td>
		<td> </td>
		<td> </td>
	</tr>

<?php
	foreach ($accounts as $i => $account) {
?>
	<tr>
		<td><?= $account->username ?></td>
		<td> <?= $account->fullname ?> 
			 <input type="hidden" name="id" value="<?=$account->id?>"/> </td>
		<td> <?php if($account->role==1){ ?>
			Admin
		<?php } elseif($account->role==2){?>
			Coordinator
			<?php }elseif($account->role==3){?>
			Tutor
			<?php }elseif($account->role==4){?>
			Lecturer
			<?php } ?> </td>
		<?php if($account->isActive){ ?>
		<td><input type="checkbox" name="isActive" checked disabled></input></td>
		<?php }else{ ?>
		<td><input type="checkbox" name="isActive" disabled></input></td>
		<?php } ?>
		<td><a href="<?= __SITE_CONTEXT?>/account/vieweditstaffaccount?id=<?= $account->id ?>" title="Edit Staff Account">Edit</a></td>
		<td><a href="<?= __SITE_CONTEXT?>/account/resetpassword?id=<?= $account->id ?>">Reset Password</a></td>
	</tr>
<?php
	}
?>
</table>
<br />
<?php
	if (isset($_GET['ok'])) {
		$messageok = $_GET['ok'];
		if ($messageok) { ?>
			<ul style="color:green">
				<li>Password has been reset successfully!</li>
			</ul>
<?php			
		} else { ?>
			<ul style="color:red">
				<li>Failed in reset password</li>
			</ul>		
<?php
		}
	}
?>