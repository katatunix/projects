<?php

class RStatus {
	const _NEW = 1;
	const _APPROVED = 2;
	const _DENIED = 3;
	
	private static $arr = array(
		'New',
		'Approved',
		'Denied'
	);
	
	public static function toString($stt) {
		return self::$arr[$stt - 1];
	}
}

?>
