<?php
/**
 * Created by PhpStorm.
 * User: katat_000
 * Date: 2/12/2015
 * Time: 2:13 PM
 */

namespace gereport\tests;

__import('database/MySqlDatabase');

use gereport\database\MySqlDatabase;

class MySqlDatabaseTest extends \PHPUnit_Framework_TestCase
{
	const HOST = 'localhost';
	const USERNAME = 'root';
	const PASSWORD = '';
	const DBNAME = 'gereporttest';

	public function init()
	{
		$link = new \mysqli(self::HOST, self::USERNAME, self::PASSWORD, self::DBNAME);
		if ($link->connect_errno) $this->assertTrue(false);

		if (!$link->query('TRUNCATE TABLE `memberproject`')) $this->assertTrue(false);
		if (!$link->query('TRUNCATE TABLE `report`')) $this->assertTrue(false);
		if (!$link->query('TRUNCATE TABLE `member`')) $this->assertTrue(false);
		if (!$link->query('TRUNCATE TABLE `project`')) $this->assertTrue(false);

		$link->close();

		$database = new MySqlDatabase(self::HOST, self::USERNAME, self::PASSWORD, self::DBNAME);
		$this->assertTrue($database->isConnected());

		return $database;
	}

	public function testHasMember()
	{
		$database = $this->init();

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$id = $database->getLastInsertedId();

		$this->assertTrue($database->hasMember($id));
		$this->assertFalse($database->hasMember($id + 1));
	}

	public function testFindMember()
	{
		$database = $this->init();
		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$id = $database->getLastInsertedId();

		$member = $database->findMember($id);
		$this->assertEquals('nghia.buivan', $member['username']);
		$this->assertEquals('gameloft', $member['password']);
		$this->assertEquals(0, $member['group']);
	}

	public function testFindMemberByLogin()
	{
		$database = $this->init();
		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$database->insertMember('thanh.tranphuong', 'gameloft', 0);
		$id = $database->getLastInsertedId();

		$this->assertEquals($id, 2);

		$this->assertEquals($id, $database->findMemberByLogin('thanh.tranphuong', 'gameloft'));
		$this->assertEquals(0, $database->findMemberByLogin('nghia.buivan', 'gameloft_'));
	}

	public function testFindNotReportedMembers()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$a8Id = $database->getLastInsertedId();
		$database->insertProject('Uno & Friends');
		$database->insertProject('Order & Chaos');

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$nghiaId = $database->getLastInsertedId();
		$database->insertMember('thanh.tranphuong', 'gameloft', 1);
		$thanhId = $database->getLastInsertedId();

		$date = '2015-02-13';

		$this->assertEquals(0, count($database->findNotReportedMembers($a8Id, $date)));

		$database->addMemberToProject($nghiaId, $a8Id);
		$this->assertEquals(1, count($database->findNotReportedMembers($a8Id, $date)));

		$database->addMemberToProject($thanhId, $a8Id);
		$this->assertEquals(2, count($database->findNotReportedMembers($a8Id, $date)));

		$database->insertReport($thanhId, $a8Id, $date, $date . ' 14:30:00', 'Fix bugs!');
		$this->assertEquals(1, count($database->findNotReportedMembers($a8Id, $date)));

		$database->insertReport($nghiaId, $a8Id, $date, $date . ' 19:30:00', 'Relax!!!');
		$this->assertEquals(0, count($database->findNotReportedMembers($a8Id, $date)));
	}

	public function testUpdateMemberPassword()
	{
		$database = $this->init();

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$id = $database->getLastInsertedId();

		$this->assertEquals('gameloft', $database->findMember($id)['password']);

		$database->updateMemberPassword($id, 'toilatoi');
		$this->assertEquals('toilatoi', $database->findMember($id)['password']);
	}

	public function testIsMemberHasPassword()
	{
		$database = $this->init();

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$id = $database->getLastInsertedId();

		$this->assertTrue($database->isMemberHasPassword($id, 'gameloft'));
		$this->assertFalse($database->isMemberHasPassword($id, 'gameloft_'));
	}

	//==========================================================================================

	public function testHasProject()
	{
		$database = $this->init();
		$database->insertProject('Asphalt 8');
		$id = $database->getLastInsertedId();

		$this->assertTrue($database->hasProject($id));
		$this->assertFalse($database->hasProject($id + 1));
	}

	public function testFindProject()
	{
		$database = $this->init();
		$database->insertProject('Asphalt 8');
		$id = $database->getLastInsertedId();

		$this->assertEquals('Asphalt 8', $database->findProject($id)['name']);
	}

	public function testFindProjects()
	{
		$database = $this->init();
		$database->insertProject('Asphalt 1');
		$database->insertProject('Uno & Friends');
		$database->insertProject('Order & Chaos');
		$id = $database->getLastInsertedId();

		$ids = $database->findProjects();

		$this->assertEquals($id, count($ids));
	}

	//==========================================================================================

	public function testIsMemberWorkingForProject()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$a8Id = $database->getLastInsertedId();

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$nghiaId = $database->getLastInsertedId();
		$database->insertMember('thanh.tranphuong', 'gameloft', 1);
		$thanhId = $database->getLastInsertedId();

		$database->addMemberToProject($nghiaId, $a8Id);
		$this->assertTrue($database->isMemberWorkingForProject($nghiaId, $a8Id));
		$this->assertFalse($database->isMemberWorkingForProject($thanhId, $a8Id));

		$database->addMemberToProject($thanhId, $a8Id);
		$this->assertTrue($database->isMemberWorkingForProject($thanhId, $a8Id));
	}

	public function testHasReport()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$database->insertProject('Uno & Friends');
		$database->insertProject('Order & Chaos');

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$database->insertMember('thanh.tranphuong', 'gameloft', 1);

		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:56:00', 'Relaxing');
		$id = $database->getLastInsertedId();

		$this->assertTrue($database->hasReport($id));
	}

	public function testFindReport()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$database->insertProject('Uno & Friends');
		$database->insertProject('Order & Chaos');

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$database->insertMember('thanh.tranphuong', 'gameloft', 1);

		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:56:00', 'Relaxing');
		$id = $database->getLastInsertedId();
		$report = $database->findReport($id);

		$this->assertEquals('Relaxing', $report['content']);
		$this->assertEquals('2015-02-12', $report['dateFor']);
		$this->assertEquals('2015-02-12 23:56:00', $report['datetimeAdd']);
	}

	public function testDeleteReport()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$database->insertProject('Uno & Friends');
		$database->insertProject('Order & Chaos');

		$database->insertMember('nghia.buivan', 'gameloft', 0);
		$database->insertMember('thanh.tranphuong', 'gameloft', 1);

		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:56:00', 'Relaxing');
		$id = $database->getLastInsertedId();

		$this->assertTrue($database->hasReport($id));

		$database->deleteReport($id);
		$this->assertFalse($database->hasReport($id));
	}

	public function testFindReportsByProjectAndDate()
	{
		$database = $this->init();

		$database->insertProject('Asphalt 1');
		$database->insertMember('nghia.buivan', 'gameloft', 0);

		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:56:00', 'Relaxing1');
		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:57:00', 'Relaxing2');
		$database->insertReport(1, 1, '2015-02-12', '2015-02-12 23:58:00', 'Relaxing3');
		$database->insertReport(1, 1, '2015-02-13', '2015-02-12 23:59:00', 'Relaxing4');


		$ids = $database->findReportsByProjectAndDate(1, '2015-02-12');

		$this->assertEquals(3, count($ids));
	}
}
