<?php
	if ( isset($loggedAccount) ) {
		$roleString = $loggedAccount->roleString;
?>
<ul>
<li>
	<h2><?= $roleString ?> Functions</h2>
	<ul>
	
	<?php if ($roleString == 'Student') { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/session/alist">Sessions List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/student?id=<?= $loggedAccount->studentAisId ?>">My Information</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/student/viewAttendanceStatistics?id=<?= $loggedAccount->studentAisId ?>">My Attendance Statistics</a></li>
	<?php } else if ($roleString == 'Admin') { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/course/alist">Courses List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/session/alist">Sessions List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/session/create">Create New Sessions</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/student/alist">Students List</a></li>
		
		<li><a href="<?= __SITE_CONTEXT ?>/room/">Rooms List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/account/viewaccountlist">Accounts List</a></li>
		
	<?php } else if ($roleString == 'Coordinator') { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/course/alist">Courses List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/session/alist">Sessions List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/session/create">Create New Sessions</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/student/alist">Students List</a></li>
	<?php } else if ($roleString == 'Lecturer') { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/session/alist">Sessions List</a></li>
	<?php } else if ($roleString == 'Tutor') { ?>
		<li><a href="<?= __SITE_CONTEXT ?>/session/alist">Sessions List</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/attendance/viewHyperlinkedRegister">Hyperlinked Registers</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/attendance/viewAbsenceFormsList">Absence Notifications</a></li>
		<li><a href="<?= __SITE_CONTEXT ?>/student/listLazy">Lazy Students List</a></li>
		
	<?php } ?>
	
	</ul>
</li>
</ul>
<?php } ?>
