<?php
class Category {
	const FOOD		= 1;
	const BEVERAGE	= 2;
	const ROOM		= 3;
	
	private static $NAMES = array(
		'Food',
		'Beverage',
		'Room'
	);
	
	public static function toString($v) {
		return self::$NAMES[$v - 1];
	}
}
?>
