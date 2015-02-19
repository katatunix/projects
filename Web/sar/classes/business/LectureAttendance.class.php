<?php

__import('business/Attendance');

class LectureAttendance extends Attendance {
	
	public function __construct($sessionId, $studentAisId) {
		parent::__construct($sessionId, $studentAisId);
	}
	
}

?>
