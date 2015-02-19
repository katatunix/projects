<?php

__import('business/AbstractHome');

__import('business/LectureAttendance');
__import('business/LabAttendance');

__import('business/SessionHome');
__import('business/StudentHome');

class AttendanceHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new AttendanceHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function createLectureAttendance($sessionId, $studentAisId) {
		$id = $sessionId . '_' . $studentAisId;
		
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LectureAttendance($sessionId, $studentAisId);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	
	private function createLabAttendance($sessionId, $studentAisId) {
		$id = $sessionId . '_' . $studentAisId;
		
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LabAttendance($sessionId, $studentAisId);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById_LecAtt($sessionId, $studentAisId) {
		if (!$sessionId || !$studentAisId) return NULL;
		
		if ($obj = $this->findObjectInPool($sessionId . '_' . $studentAisId)) return $obj;
		
		$sql = 'SELECT `isPresented` FROM `lectureattendance` WHERE `sessionId` = ? AND `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		$ps->setValue(2, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysqli_fetch_array($rs) ) {
			$obj = $this->createLectureAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented']);
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}
	
	public function findById_LabAtt($sessionId, $studentAisId) {
		if (!$sessionId || !$studentAisId) return NULL;
		
		if ($obj = $this->findObjectInPool($sessionId . '_' . $studentAisId)) return $obj;
		
		$sql = 'SELECT `isPresented`, `reason`, `reasonStatus` FROM `labattendance` 
				WHERE `sessionId` = ? AND `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		$ps->setValue(2, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysqli_fetch_array($rs) ) {
			$obj = $this->createLabAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented'], $row['reason'], $row['reasonStatus']);
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}
	
	public function findBySessionId($sessionId) {
		$list = array();
		
		if (!$sessionId) return $list;
		
		//========================================================================
		$sql = 'SELECT * FROM `lectureattendance` WHERE `sessionId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$studentAisId = $row['studentAisId'];
			
			$obj = $this->createLectureAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented']);
			
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		//========================================================================
		$sql = 'SELECT * FROM `labattendance` WHERE `sessionId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$studentAisId = $row['studentAisId'];
			
			$obj = $this->createLabAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented'], $row['reason'], $row['reasonStatus']);
			
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		//
		return $list;
	}
	
	public function findByStudentAisId($studentAisId) {
		$list = array();
		
		if (!$studentAisId) return $list;
		
		//========================================================================
		$sql = 'SELECT * FROM `lectureattendance` WHERE `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$sessionId = $row['sessionId'];
			
			$obj = $this->createLectureAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented']);
			
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		//========================================================================
		$sql = 'SELECT * FROM `labattendance` WHERE `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$sessionId = $row['sessionId'];
			
			$obj = $this->createLabAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented'], $row['reason'], $row['reasonStatus']);
			
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		//
		return $list;
	}
	
	// Only for LabAttendance
	public function findByTutorIdAndReasonStatus($tutorId, $reasonStatus) {
		$sql = 'SELECT *
				FROM `labattendance` LA, `session` S
				WHERE LA.`reason` IS NOT NULL
					AND LA.`reasonStatus` = ? AND LA.`sessionId` = S.`id` AND S.`teacherId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $reasonStatus);
		$ps->setValue(2, $tutorId);
		
		$list = array();
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$sessionId		= $row['sessionId'];
			$studentAisId	= $row['studentAisId'];
			
			$obj = $this->createLabAttendance($sessionId, $studentAisId);
			$obj->setBasicData($row['isPresented'], $row['reason'], $row['reasonStatus']);
			
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		return $list;
	}
	
	//====================================================================================
	
	public function updateReasonStatus_LabAtt($sessionId, $studentAisId, $value) {
		if ($value != 1 && $value != 2 && $value != 3) return FALSE;
		$session = SessionHome::instance()->findById($sessionId);
		if ( !$session || !$session->isActive() ) return FALSE;
		if ( !StudentHome::instance()->findById($studentAisId) ) return FALSE;
		
		$sql = 'UPDATE `labattendance` SET `reasonStatus` = ? WHERE `sessionId` = ? AND `studentAisId` = ?';
		
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $value);
		$ps->setValue(2, $sessionId);
		$ps->setValue(3, $studentAisId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function updatePresented($attType, $sessionId, $studentAisId, $presented) {
		$session = SessionHome::instance()->findById($sessionId);
		if ( !$session || !$session->isActive() ) return FALSE;
		if ( !StudentHome::instance()->findById($studentAisId) ) return FALSE;
		
		if ($attType == Attendance::LEC) {
			$sql = 'UPDATE `lectureattendance` SET `isPresented` = ? WHERE `sessionId` = ? AND `studentAisId` = ?';
		} else {
			$sql = 'UPDATE `labattendance` SET `isPresented` = ? WHERE `sessionId` = ? AND `studentAisId` = ?';
		}
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $presented ? 1 : 0);
		$ps->setValue(2, $sessionId);
		$ps->setValue(3, $studentAisId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function insertWithPresented($attType, $sessionId, $studentAisId, $presented) {
		$session = SessionHome::instance()->findById($sessionId);
		if ( !$session || !$session->isActive() ) return FALSE;
		if ( !StudentHome::instance()->findById($studentAisId) ) return FALSE;
		
		if ($attType == Attendance::LEC) {
			$sql = 'INSERT `lectureattendance`(`sessionId`, `studentAisId`, `isPresented`) VALUES(?, ?, ?)';
		} else {
			$sql = 'INSERT `labattendance`(`sessionId`, `studentAisId`, `isPresented`) VALUES(?, ?, ?)';
		}
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		$ps->setValue(2, $studentAisId);
		$ps->setValue(3, $presented ? 1 : 0);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}

	public function updateReason_LabAtt($sessionId, $studentAisId, $value) {
		if (!$value) return FALSE;
		
		$session = SessionHome::instance()->findById($sessionId);
		if ( !$session || !$session->isActive() ) return FALSE;
		if ( !StudentHome::instance()->findById($studentAisId) ) return FALSE;
		
		$sql = 'UPDATE `labattendance` SET `reason` = ?, `reasonStatus` = ?
				WHERE `sessionId` = ? AND `studentAisId` = ?';
		
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $value);
		$ps->setValue(2, RStatus::_NEW);
		$ps->setValue(3, $sessionId);
		$ps->setValue(4, $studentAisId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function insertWithReason_LabAtt($sessionId, $studentAisId, $reason) {
		$session = SessionHome::instance()->findById($sessionId);
		if ( !$session || !$session->isActive() ) return FALSE;
		if ( !StudentHome::instance()->findById($studentAisId) ) return FALSE;
		
		$sql = 'INSERT `labattendance`(`sessionId`, `studentAisId`, `reason`) VALUES(?, ?, ?)';
		
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		$ps->setValue(2, $studentAisId);
		$ps->setValue(3, $reason);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function enterAttendance($sessionId, $studentAisIdList, $presentedList) {
		$session = SessionHome::instance()->findById($sessionId);
		
		if (count($studentAisIdList) != count($presentedList)) return FALSE;
		
		$count = count($studentAisIdList);
		$attType = $session instanceof LectureSession ? Attendance::LEC : Attendance::LAB;
		
		$ok = TRUE;
		
		for ($i = 0; $i < $count; $i++) {
			$studentAisId = $studentAisIdList[$i];
			$isPresented = $presentedList[$i];
			
			$attendance = $attType == Attendance::LEC ?
				$this->findById_LecAtt($sessionId, $studentAisId) :
				$this->findById_LabAtt($sessionId, $studentAisId);
			
			if ($attendance) {
				$ok = $ok && $this->updatePresented(
					$attType, $sessionId, $studentAisId, $isPresented
				);
			} else {
				$ok = $ok && $this->insertWithPresented(
					$attType, $sessionId, $studentAisId, $isPresented
				);
			}
			
			if (!$ok) break;
		}
		
		return $ok;
	}
	
	// Only for LabAttendance
	public function authorize($sessionId, $studentAisId, $isApproved) {
		$reasonStatus = $isApproved ? RStatus::_APPROVED : RStatus::_DENIED;
		return $this->updateReasonStatus_LabAtt($sessionId, $studentAisId, $reasonStatus);
	}
	
	// Only for LabAttendance
	public function createAbsenceForm($sessionId, $studentAisId, $reason) {
		$la = $this->findById_LabAtt($sessionId, $studentAisId);
		if ($la)
			return $this->updateReason_LabAtt($sessionId, $studentAisId, $reason);
		else
			return $this->insertWithReason_LabAtt($sessionId, $studentAisId, $reason);
	}
	
}

?>
