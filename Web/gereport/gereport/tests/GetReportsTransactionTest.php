<?php

namespace gereport\tests;

__import('database/MockDatabase');
__import('transaction/GetReportsTransaction');
__import('domainproxy/ReportProxy');

use gereport\database\MockDatabase;
use gereport\transaction\AddReportTransaction;
use gereport\transaction\GetReportsTransaction;

class GetReportsTransactionTest extends \PHPUnit_Framework_TestCase
{
	public function testNormal()
	{
		$database = new MockDatabase();

		$memberId = 1;
		$projectId = 1;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-27', '2015-02-27 17:30', 'Fix bugs', $database);
		$transaction->execute();

		//----------------------------------------------------------------------------
		$projectId = 1;
		$date = '2015-02-27';
		$transaction = new GetReportsTransaction($projectId, $date, 0, $database);
		$transaction->execute();
		$reports = $transaction->getReports();

		$this->assertEquals('Asphalt 8', $transaction->getProjectName());
		$this->assertEquals($date, $transaction->getDate());

		$this->assertEquals(1, count($reports));
		$this->assertEquals('Fix bugs', $reports[0]['content']);
		$this->assertEquals('2015-02-27 17:30', $reports[0]['datetimeAdd']);
		$this->assertEquals('nghia.buivan', $reports[0]['memberUsername']);
		$this->assertEquals(false, $reports[0]['isPast']);
		$this->assertEquals(2, count($transaction->getNotReportedMembers()));

		//----------------------------------------------------------------------------
		$projectId = 1;
		$date = '2015-02-28';
		$transaction = new GetReportsTransaction($projectId, $date, 0, $database);
		$transaction->execute();
		$reports = $transaction->getReports();

		$this->assertEquals(0, count($reports));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testNotFoundProject()
	{
		$database = new MockDatabase();

		$memberId = 1;
		$projectId = 1;
		$transaction = new AddReportTransaction($memberId, $projectId,
			'2015-02-27', '2015-02-27 17:30', 'Fix bugs', $database);
		$transaction->execute();

		//----------------------------------------------------------------------------
		$projectId = 100;
		$date = '2015-02-27';
		$transaction = new GetReportsTransaction($projectId, $date, 0, $database);
		$transaction->execute();
	}

}
