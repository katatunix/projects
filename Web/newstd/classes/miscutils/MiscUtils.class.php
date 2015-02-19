<?php

class MiscUtils {
	
	public static function toUpperCaseFirstLetter($str) {
		return strtoupper($str[0]) . substr($str, 1);
	}

	public static function getCurrentDatetime() {
		$obj = new DateTime();
		return $obj->format('Y-m-d G:i');
	}

	public static function splitDatetimeString($str) {
		$obj = DateTime::createFromFormat('Y-m-d G:i', $str);
		return array( $obj->format('Y-m-d'), $obj->format('G'), $obj->format('i') );
	}

	public static function removeSecond($str) {
		$obj = DateTime::createFromFormat('Y-m-d G:i:s', $str);
		return $obj->format('Y-m-d G:i');
	}

	public static function removeTime($str) {
		$obj = DateTime::createFromFormat('Y-m-d G:i', $str);
		return $obj->format('Y-m-d');
	}

	public static function addDays($date, $num) {
		$dt = DateTime::createFromFormat('Y-m-d', $date);
		return $dt->modify($num . ' days')->format('Y-m-d');
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
			if (array_search($val, $list2) !== false) {
				return true;
			}
		}
		return false;
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

	public static function min($a, $b) {
		return $a < $b ? $a : $b;
	}

	public static function max($a, $b) {
		return $a > $b ? $a : $b;
	}

	public static function countDays($fromDate, $toDate) {
		return (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24) + 1;
	}

	public static function isOverlap($a, $b, $c, $d) {
		return !($b < $c || $d < $a);
	}

	public static function loadXmlFile($path) {
		$use_errors = libxml_use_internal_errors(true);
		$xml = simplexml_load_file($path);
		if (!$xml) {
			return false;
		}
		libxml_clear_errors();
		libxml_use_internal_errors($use_errors);
		return $xml;
	}
}

?>
