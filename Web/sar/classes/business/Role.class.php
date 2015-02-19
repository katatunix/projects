<?php
class Role {
	const STUDENT		= 0;
	const ADMIN			= 1;
	const COORDINATOR	= 2;
	const LECTURER		= 3;
	const TUTOR			= 4;
	
	private static $NAMES = array(
		'Student',
		'Admin',
		'Coordinator',
		'Lecturer',
		'Tutor'
	);
	
	public static function toString($role) {
		return self::$NAMES[$role];
	}
}
?>
