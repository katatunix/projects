<?php

class MiscUtils {
	
	public static function toUpperCaseFirstLetter($str) {
		return strtoupper($str[0]) . substr($str, 1);
	}
	
	public static function isContentString($value) {
		return is_string($value) && strlen( trim($value) ) > 0;
	}
	
	public static function isIntInStringFormat($value) {
		return is_string($value) && is_numeric($value);
	}
	
	public static function isPositiveIntInStringFormat($value) {
		return is_string($value) && is_numeric($value) && (int)$value > 0;
	}
	
	public static function checkValidEmail($email)
	{
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}
	
	public static function parseYearMonthString($str) { // yyyy-MM
		$p = explode('-', $str);
		if (count($p) != 2) return NULL;
		if (!self::isPositiveIntInStringFormat($p[0]))  return NULL;
		if (!self::isPositiveIntInStringFormat($p[1]))  return NULL;
		
		if ($p[1] < 1 || $p[1] > 12) return NULL;
		return $p;
	}
	
	public static function getCurrentYearMonth() {
		$n = getdate();
		return array($n['year'], $n['mon'] < 10 ? '0' . $n['mon'] : $n['mon']);
	}
	
	public static function getCurrentWeek() {
		$result = array();
		
		$dt = new DateTime();
		while (TRUE) {
			$w = $dt->format('w');
			if ($w == '1') break;
			$dt->modify('-1 days');
		}
		$result[] = $dt->format('Y-m-d');
		
		$dt = new DateTime();
		while (TRUE) {
			$w = $dt->format('w');
			if ($w == '0') break;
			$dt->modify('+1 days');
		}
		$result[] = $dt->format('Y-m-d');
		
		return $result;
	}
	
	public static function hasSameAnyElement($list1, $list2) {
		foreach ($list1 as $val) {
			if (array_search($val, $list2) !== FALSE) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	public static function sort($list, $att) {
		$arr = array();
		foreach ($list as $obj) {
			$arr[] = $obj;
		}
		$n = count($arr);
		for ($i = 0; $i < $n - 1; $i++) {
			for ($j = $i + 1; $j < $n; $j++) {
				if ($arr[$i]->$att > $arr[$j]->$att) {
					$tmp = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp;
				}
			}
		}
		unset($list);
		for ($i = 0; $i < $n; $i++) {
			$obj = $arr[$i];
			$list[$obj->id] = $obj;
		}
		
		return $list;
	}
}

?>
