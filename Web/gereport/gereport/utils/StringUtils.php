<?php

namespace gereport\utils;

class StringUtils
{
	public static function addZero($number, $length)
	{
		$number = (string)$number;
		$zeroCount = $length - strlen($number);
		for (; $zeroCount > 0; $zeroCount--)
		{
			$number = '0' . $number;
		}
		return $number;
	}
}
