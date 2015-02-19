<?php
/**
 * Created by PhpStorm.
 * User: katat_000
 * Date: 2/9/2015
 * Time: 11:16 PM
 */

namespace gereport\tests;


use gereport\utils\DatetimeUtils;

class DatetimeUtilsTest extends \PHPUnit_Framework_TestCase
{
	public function testIsDatetimeInDate()
	{
		$this->assertTrue(DatetimeUtils::isDatetimeInDate('2004-07-19 23:59', '2004-07-19'));
		$this->assertFalse(DatetimeUtils::isDatetimeInDate('2004-07-19 23:59', '2004-07-29'));
	}
}
