<?php
/**
 * Created by PhpStorm.
 * User: katat_000
 * Date: 2/9/2015
 * Time: 11:13 PM
 */

namespace gereport\tests;


use gereport\utils\StringUtils;

class StringUtilsTest extends \PHPUnit_Framework_TestCase
{
	public function test2DigitNumber()
	{
		$this->assertEquals('17', StringUtils::addZero(17, 2));
	}

	public function test1DigitNumber()
	{
		$this->assertEquals('07', StringUtils::addZero(7, 2));
	}

	public function test3DigitNumber()
	{
		$this->assertEquals('475', StringUtils::addZero(475, 2));
	}
}
