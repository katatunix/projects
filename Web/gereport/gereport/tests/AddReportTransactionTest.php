<?php

namespace gereport\tests;

__import('database/MockDatabase');
__import('transaction/AddReportTransaction');
__import('domainproxy/ReportProxy');

use gereport\database\MockDatabase;
use gereport\transaction\AddReportTransaction;
use gereport\domainproxy\ReportProxy;

class AddReportTransactionTest extends \PHPUnit_Framework_TestCase
{
	public function testNormal()
	{
		$database = new MockDatabase();
		$memberId = 1;
		$projectId = 1;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-28', '2015-02-27 17:30', 'Fix bugs', $database);
		$transaction->execute();

		$reportId = $database->getLastInsertedId();
		$this->assertTrue($database->hasReport($reportId));

		$report = new ReportProxy($reportId, $database);
		$this->assertEquals('Fix bugs', $report->getContent());
		//$this->assertEquals('2015-02-28', $report->getDateFor());
	}

	/**
	 * @expectedException \Exception
	 */
	public function testNotFoundMember()
	{
		$database = new MockDatabase();
		$memberId = 10;
		$projectId = 1;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-27', '2015-02-27', 'Fix bugs', $database);
		$transaction->execute();
	}

	/**
	 * @expectedException \Exception
	 */
	public function testNotFoundProject()
	{
		$database = new MockDatabase();
		$memberId = 1;
		$projectId = 10;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-27', '2015-02-27 00:30', 'Fix bugs', $database);
		$transaction->execute();
	}

	/**
	 * @expectedException \Exception
	 */
	public function testNotWorkingInProject()
	{
		$database = new MockDatabase();
		$memberId = 1;
		$projectId = 4;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-27', '2015-02-27 05:05', 'Fix bugs', $database);
		$transaction->execute();
	}
}
