<?php

namespace gereport\utils;

__import('utils/StringUtils');

class DatetimeUtils
{
	/**
	 * @param string $datetime
	 * @param string $date
	 * @return bool
	 */
	public static function isDatetimeInDate($datetime, $date)
	{
		$parts = explode(' ', $datetime);
		if (count($parts) != 2) return false;
		return $parts[0] == $date;
	}

	public static function getCurDate()
	{
		$n = getdate();
		return $n['year'] . '-' . StringUtils::addZero($n['mon'], 2) . '-' . StringUtils::addZero($n['mday'], 2);
	}

	public static function getCurDatetime()
	{
		$n = getdate();
		return $n['year'] . '-' . StringUtils::addZero($n['mon'], 2) . '-' . StringUtils::addZero($n['mday'], 2) . ' '
				. StringUtils::addZero($n['hours'], 2) . ':'
				. StringUtils::addZero($n['minutes'], 2) . ':'
				. StringUtils::addZero($n['seconds'], 2);
	}

}
