<?php

__import('business/AbstractHome');
__import('business/Room');
__import('business/LectureTheater');
__import('business/LabRoom');

class RoomHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new RoomHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function createLectureTheater($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LectureTheater($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	
	private function createLabRoom($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LabRoom($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($roomId) {
		if (!$roomId) return NULL;
		
		if ($obj = $this->findObjectInPool($roomId)) return $obj;
		
		$sql = 'SELECT `name`, `rtype` FROM `room` WHERE `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $roomId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$room = NULL;
		
		if ( $row = mysqli_fetch_array($rs) )
		{
			$room = $row['rtype'] == Room::LEC ?
				$this->createLectureTheater($roomId) :
				$this->createLabRoom($roomId);
			$room->setName($row['name']);
		}
		
		mysqli_free_result($rs);
		
		return $room;
	}
	
	public function findByAll() {
		$sql = 'SELECT `id`, `name`, `rtype` FROM `room` ORDER BY `rtype`';
		$ps = new PreparedStatement($sql);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$list = array();
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$roomId = $row['id'];
			
			$room = $row['rtype'] == Room::LEC ?
				$this->createLectureTheater($roomId) :
				$this->createLabRoom($roomId);
			
			$room->setName( $row['name'] );
			
			$list[] = $room;
		}
		
		mysqli_free_result($rs);
		
		return $list;
	}
	public function addRoom($name,$rtype)
	{		
		$sql = 'Insert Into `room`(`name`,`rtype`) Values (?,?)';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1,$name);
		$ps->setValue(2,$rtype);
		$rs = DataSource::instance()->execute($ps->getSql());
		if($rs)
		{
			return true;
		}
		return false;
	}
	public function editRoom($roomid,$name)
	{
		$sql = 'UPDATE `room` SET `name` = ? WHERE  `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1,$name);
		$ps->setValue(2,$roomid);
		$rs = DataSource::instance()->execute($ps->getSql());
		if($rs){
			return true;
		}
		return false;
	}
	public function removeRoom($roomid)
	{
			$sql = 'Delete From `room` where `id` = ? ';
			$ps = new PreparedStatement($sql);
		$ps->setValue(1,$roomid);
		$rs = DataSource::instance()->execute( $ps->getSql() );
		if($rs)
		{
			return true;
		}
		return false;
	}
	public function findRoomReference($roomid)
	{	
		$sql = 'SELECT * FROM room , session WHERE room.id = ? and session.roomId = room.id';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1,$roomid);
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$num_rows = mysqli_num_rows($rs);
		if($num_rows != 0){
			return FALSE;
		}
		return True;
	}
	public function checkDuplicate($rname)
	{
		$sql = 'SELECT `name`, `rtype` FROM `room` WHERE `name` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $rname);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$num_rows = mysqli_num_rows($rs);
		if($num_rows != 0){
			return FALSE;
		}
		return True;
	}
	
}
