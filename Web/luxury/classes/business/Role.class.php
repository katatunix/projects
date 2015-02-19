<?php
class Role {
	const STAFF		= 1;
	const LOC_MAN	= 2;
	const NAT_MAN	= 3;
	
	private static $NAMES = array(
		'Staff',
		'LocMan',
		'NatMan'
	);
	
	public static function toString($v) {
		return self::$NAMES[$v - 1];
	}
}
?>
