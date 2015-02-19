<?php

class OrderStatus {
	const BOOKED		= 1;
	const PAID	        = 2;
	const CANCELED		= 3;

	private static $NAMES = array(
		'Booked',
		'Paid',
		'Canceled'
	);

	public static function toString($v) {
		return self::$NAMES[$v - 1];
	}

	public static function toEnum($v) {
		$key = array_search($v, self::$NAMES);
		if ($key === false) return false;
		return $key + 1;
	}
}

?>
