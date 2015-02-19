<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/AttendanceHome');
__import('business/SessionHome');
__import('business/RStatus');

class AttendanceFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new AttendanceFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	// Only for LabAttendance
	public function authorize($sessionId, $studentAisId, $isApproved) {
		return AttendanceHome::instance()->authorize($sessionId, $studentAisId, $isApproved);
	}
	
	public function enterAttendance($sessionId, $studentAisIdList, $presentedList) {
		return AttendanceHome::instance()->enterAttendance($sessionId, $studentAisIdList, $presentedList);
	}
	
	public function createAbsenceForm($sessionId, $studentAisId, $reason) {
		return AttendanceHome::instance()->createAbsenceForm($sessionId, $studentAisId, $reason);
	}

	public function findByTutorIdAndNewReasonStatus($tutorId) {
		$list = AttendanceHome::instance()->findByTutorIdAndReasonStatus($tutorId, RStatus::_NEW);
		
		$cv = new PObjectConverter();
		
		foreach ($list as $att) {
			$att->clearAllExternals();
			
			$att->loadSession()->clearAllExternals();
			$att->getSession()->loadCourse()->clearAllExternals();
			
			$att->loadStudentAis()->clearAllExternals();
			
			//
			$cv->convertAttendance($att);
		}
		
		return $cv->getResult();
	}
}

?>
