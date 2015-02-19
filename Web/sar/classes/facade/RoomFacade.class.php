<?php
__import('business/RoomHome');

__import('facade/PObject');
__import('facade/PObjectConverter');

class RoomFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new RoomFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	public function findById($roomId) {
		$room = RoomHome::instance()->findById($roomId);
		if (!$room) return NULL;
		
		$room->clearAllExternals();
		
		$cv = new PObjectConverter();
		$cv->convertRoom($room);
		return $cv->getResult();
	}
	
	public function findByAll() {
		$rooms = RoomHome::instance()->findByAll();
		
		$cv = new PObjectConverter();
		
		foreach($rooms as $room) {
			$room->clearAllExternals();
			$cv->convertRoom($room);
		}
		
		return $cv->getResult();
	}
	
	public function addRoom($id,$name)
	{
		$result = RoomHome::instance()->addRoom($id,$name);
		return $result;
	}
	
	public function editRoom($id,$name)
	{
		$result = RoomHome::instance()->editRoom($id,$name);
		return $result;
	}
	public function removeRoom($id){
		$findReference = RoomHome::instance()->findRoomReference($id);
		if($findReference == TRUE){
			$result = RoomHome::instance()->removeRoom($id);
			return $result;
		}
		return FALSE;
	}
	public function checkDuplicate($rname){
		$checkDuplicate = RoomHome::instance()->checkDuplicate($rname);
		return $checkDuplicate;
	}
}

?>
