<?php

__import('business/AbstractHome');
__import('business/CourseHome');
__import('business/AccountHome');
__import('business/RoomHome');
__import('business/StudentHome');

__import('business/Session');
__import('business/LabSession');
__import('business/LectureSession');
__import('business/Role');

__import('business/LectureTheater');
__import('business/LabRoom');

__import('miscutils/MiscUtils');

class SessionHome extends AbstractHome {
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new SessionHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function createLectureSession($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LectureSession($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	
	private function createLabSession($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new LabSession($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($id) {
		if (!$id) return NULL;
		
		if ($obj = $this->findObjectInPool($id)) return $obj;
		
		$sql = 'SELECT * FROM `session` WHERE `id` = ?';
		
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $id);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		if ($row = mysqli_fetch_array($rs)) {
			$obj = $row['stype'] == Session::LEC ?
				$this->createLectureSession($id) :
				$this->createLabSession($id);
			
			$obj->setBasicData(
				$row['startDatetime'],
				$row['minutes'],
				$row['isActive'],
				$row['courseId'],
				$row['roomId'],
				$row['teacherId']
			);
			if ($row['stype'] == Session::LAB) {
				$obj->setLabGroup($row['labGroup']);
			}
		}
		mysqli_free_result($rs);
		
		return $obj;
	}
	
	public function findByFilter($accountId, $courseId, $sessionType, $year, $month, $roomId, $active, $lg,
			$fromDate = NULL, $toDate = NULL) {
		
		$account = NULL;
		if ($accountId) {
			$account = AccountHome::instance()->findById($accountId);
			if ($account) {
				$role = $account->getRole();
			}
		}
		
		$ps = new PreparedStatement(NULL);
		
		$sql = 'SELECT S.* FROM `session` S';
		$index = 1;
		
		if ($account && $role == Role::STUDENT) {
			$sql .=
			' WHERE
			(
				( S.`stype` = 0 AND S.`courseId` IN (?) )
				OR
				(
					S.`stype` = 1 AND S.`labGroup` IS NOT NULL AND S.`labGroup` > 0
					AND ? IN (
						SELECT RE.`studentAisId`
						FROM `registration` RE
						WHERE RE.`courseId` = S.`courseId`
							AND RE.`labGroup` IS NOT NULL
							AND RE.`labGroup` > 0
							AND RE.`labGroup` = S.`labGroup`
					)
				)
			)';
			
			$studentAisId = $account->getStudentAisId();
			// sure $studentAisId is not NULL because $role == Role::STUDENT, but just careful
			if (!$studentAisId) return array();
			
			$cidsList = StudentHome::instance()->findCourseIds($studentAisId);
			// $cidsList can be an empty array
			
			$ps->setValue($index++, $cidsList);
			
			$ps->setValue($index++, $studentAisId);
			
		} else if ($account && ($role == Role::LECTURER || $role == Role::TUTOR)) {
			$sql .= ' WHERE S.`teacherId` = ?';
			$ps->setValue($index++, $accountId);
			
			$sessionType = $role == Role::LECTURER ? Session::LEC : Session::LAB; // force
			
		} else {
			$sql .= ' WHERE TRUE';
		}
		
		if ($courseId) {
			$sql .= ' AND S.`courseId` = ?';
			$ps->setValue($index++, $courseId);
		}
		
		if (!is_null($sessionType)) {
			$sql .= ' AND S.`stype` = ?';
			$ps->setValue($index++, $sessionType);
		}
		
		if ($year && $month) {
			$sql .= ' AND YEAR(S.`startDatetime`) = ? AND MONTH(S.`startDatetime`) = ?';
		
			$ps->setValue($index++, $year);
			$ps->setValue($index++, $month);
		} else {
			if ($fromDate) {
				$sql .= ' AND ? <= S.`startDatetime`';
				$ps->setValue($index++, $fromDate);
			}
			if ($toDate) {
				$sql .= ' AND S.`startDatetime` <= ?';
				$ps->setValue($index++, $toDate);
			}
		}
		
		if ($roomId) {
			$sql .= ' AND S.`roomId` = ?';
			$ps->setValue($index++, $roomId);
		}
		
		if ( $active == 0 || $active == 1 ) {
			$sql .= ' AND S.`isActive` = ?';
			$ps->setValue($index++, $active);
		}
		
		if ( !is_null($lg) && $lg > -1 ) {
			if ($lg == 0) {
				$sql .= ' AND (S.`stype` = ? OR S.`labGroup` IS NULL OR S.`labGroup` = 0)';
				$ps->setValue($index++, Session::LEC);
			} else {
				$sql .= ' AND (S.`stype` = ? OR S.`labGroup` = ?)';
				$ps->setValue($index++, Session::LEC);
				$ps->setValue($index++, $lg);
			}
		}
		
		$sql .= ' ORDER BY S.`startDatetime` ';
		
		$ps->setSql($sql);
		
		//==============================================================================
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$list = array();
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$sessionId = $row['id'];
			
			$session = $row['stype'] == Session::LEC ?
				$this->createLectureSession($sessionId) :
				$this->createLabSession($sessionId);
			
			$session->setBasicData(
				$row['startDatetime'],
				$row['minutes'],
				$row['isActive'],
				$row['courseId'],
				$row['roomId'],
				$row['teacherId']
			);
			if ($row['stype'] == Session::LAB) {
				$session->setLabGroup($row['labGroup']);
			}
			
			$list[] = $session;
		}
		
		mysqli_free_result($rs);
		
		return $list;
	}

	public function insert($courseId, $sessionType, $minutes, $specificDatetime, $patternDatetime,
				$roomId, $teacherId, $labGroup) {
		
		return $this->insertOrUpdate(NULL, $courseId, $sessionType, $minutes, $specificDatetime, $patternDatetime,
				$roomId, $teacherId, $labGroup);
	}
	
	public function update(
				$sessionId, // note
				$courseId, $sessionType, $minutes, $specificDatetime,
				$roomId, $teacherId, $labGroup) {
		 
		 if (!$sessionId) return FALSE;
		 
		 return $this->insertOrUpdate($sessionId, $courseId, $sessionType, $minutes,
		 		$specificDatetime, NULL,
				$roomId, $teacherId, $labGroup);
	}
	
	private function isRoomFreeAtTime($roomId, $startDatetime, $minutes, $exceptSessionId) {
		$sql = 'SELECT S.`id`
				FROM `session` S
				WHERE S.`isActive` = 1 AND S.`roomId` = ? AND NOT (
					DATE_ADD(?, INTERVAL ? MINUTE) <= S.`startDatetime` OR
					DATE_ADD(S.`startDatetime`, INTERVAL S.`minutes` MINUTE) <= ?
				)';
		if ($exceptSessionId) {
			$sql .= ' AND S.`id` <> ?';
		}
		$ps = new PreparedStatement($sql);
		
		$ps->setValue(1, $roomId);
		$ps->setValue(2, $startDatetime);
		$ps->setValue(3, $minutes);
		$ps->setValue(4, $startDatetime);
		if ($exceptSessionId) {
			$ps->setValue(5, $exceptSessionId);
		}
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$ok = mysqli_fetch_array($rs) ? FALSE : TRUE;
		mysqli_free_result($rs);
		
		return $ok;
	}
	
	private function isTeacherFreeAtTime($teacherId, $startDatetime, $minutes, $exceptSessionId) {
		$sql = 'SELECT S.`id`
				FROM `session` S
				WHERE S.`isActive` = 1 AND S.`teacherId` = ? AND NOT (
					DATE_ADD(?, INTERVAL ? MINUTE) <= S.`startDatetime` OR
					DATE_ADD(S.`startDatetime`, INTERVAL S.`minutes` MINUTE) <= ?
				)';
		if ($exceptSessionId) {
			$sql .= ' AND S.`id` <> ?';
		}
		$ps = new PreparedStatement($sql);
		
		$ps->setValue(1, $teacherId);
		$ps->setValue(2, $startDatetime);
		$ps->setValue(3, $minutes);
		$ps->setValue(4, $startDatetime);
		if ($exceptSessionId) {
			$ps->setValue(5, $exceptSessionId);
		}
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$ok = mysqli_fetch_array($rs) ? FALSE : TRUE;
		mysqli_free_result($rs);
		
		return $ok;
	}
	
	private function areStudentsFreeAtTime($courseId, $sessionType, $labGroup, $startDatetime, $minutes, $exceptSessionId) {
		if ($sessionType == Session::LAB && !$labGroup) return TRUE;
		
		$affectedStudentsList = $sessionType == Session::LEC ?
			CourseHome::instance()->findStudentAisIds($courseId) :
			CourseHome::instance()->findStudentAisIdsInLabGroup($courseId, $labGroup);
		
		if (count($affectedStudentsList) == 0) return TRUE;
		
		$sql = 'SELECT S.`courseId`, S.`stype`, S.`labGroup`
				FROM `session` S
				WHERE S.`isActive` = 1 AND NOT (
					DATE_ADD(?, INTERVAL ? MINUTE) <= S.startDatetime OR
					DATE_ADD(S.startDatetime, INTERVAL S.minutes MINUTE) <= ?
				)';
		if ($exceptSessionId) {
			$sql .= ' AND S.`id` <> ?';
		}
		$ps = new PreparedStatement($sql);
		
		$ps->setValue(1, $startDatetime);
		$ps->setValue(2, $minutes);
		$ps->setValue(3, $startDatetime);
		if ($exceptSessionId) {
			$ps->setValue(4, $exceptSessionId);
		}
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$ok = TRUE;
		
		while ( $row = mysqli_fetch_array($rs) ) {
			
			$cid = $row['courseId'];
			$checkedList = NULL;
			
			if ($row['stype'] == Session::LEC) {
				$checkedList = CourseHome::instance()->findStudentAisIds($cid);
			} else if ($row['labGroup']) {
				$checkedList = CourseHome::instance()->findStudentAisIdsInLabGroup($cid, $row['labGroup']);
			}
			
			if ($checkedList) {
				if (MiscUtils::hasSameAnyElement($affectedStudentsList, $checkedList)) {
					// Oh no, these students are busy at the time
					$ok = FALSE;
					break;
				}
			}
		}
		
		mysqli_free_result($rs);
		
		return $ok;
	}

	private function insertOrUpdate(
				$sessionId, // note: use it to know insert or update
				$courseId, $sessionType, $minutes, $specificDatetime, $patternDatetime,
				$roomId, $teacherId, $labGroup) {
					
		$result = array();
		$result[0] = FALSE;
					
		$isEdit = $sessionId ? TRUE : FALSE;
		if ($isEdit) {
			$session = $this->findById($sessionId);
			if (!$session) {
				$result[1] = 'NF'; // Not Found
				return $result;
			}
		}
		
		if (is_null($sessionType) || ($sessionType != Session::LEC && $sessionType != Session::LAB)) {
			$result[1] = 'Session type must be entered as lab or lecture.'; return $result;
		}
		
		if ($isEdit) {
			// check for the change of $sessionType
			$needToCheck =	( $session instanceof LectureSession	&& $sessionType == Session::LAB )
						||	( $session instanceof LabSession		&& $sessionType == Session::LEC );
			if ($needToCheck) {
				if ( $session->loadAttendances() ) {
					$result[1] = 'Could not change the session type because there are attendances for this session.';
					return $result;
				}
			}
		}
		
		if (!$courseId) {
			$result[1] = 'Course must be entered.'; return $result;
		} else {
			if ( !CourseHome::instance()->findById($courseId) ) {
				$result[1] = 'The course is not existent.'; return $result;
			}
		}
		
		// Room
		if (!$roomId) {
			$result[1] = 'Room must be entered.'; return $result;
		}
		
		$room = RoomHome::instance()->findById($roomId);
		if (!$room) {
			$result[1] = 'The room is not existent.'; return $result;
		}
		if ( $sessionType == Session::LEC && !($room instanceof LectureTheater) ) {
			$result[1] = 'The room must be a lecture theater.'; return $result;
		}
		if ( $sessionType == Session::LAB && !($room instanceof LabRoom) ) {
			$result[1] = 'The room must be a lab room.'; return $result;
		}
		
		// Teacher
		if (!$teacherId) {
			$result[1] = 'Teacher must be entered.'; return $result;
		}
		$teacher = AccountHome::instance()->findById($teacherId);
		if (!$teacher) {
			$result[1] = 'The teacher is not existent.'; return $result;
		}
		if ($sessionType == Session::LEC && $teacher->getRole() != Role::LECTURER) {
			$result[1] = 'The teacher must be an lecturer.'; return $result;
		}
		if ($sessionType == Session::LAB && $teacher->getRole() != Role::TUTOR) {
			$result[1] = 'The teacher must be an tutor.'; return $result;
		}
		
		// Minutes
		if (!$minutes || $minutes < 30 || $minutes > 360 ) {
			$result[1] = 'Minutes must be entered in range 30 to 360.'; return $result;
		}
		
		// Datetime
		if (!$specificDatetime && !$patternDatetime) {
			$result[1] = 'Datetime must be entered.'; return $result;
		}
		
		$listDt = array();
		
		$SPECIFIC_DATETIME_ERROR = 'Specific datetime must be entered in the format yyyy-MM-dd HH:mm.';
		$PATTERN_DATETIME_ERROR = 'Pattern datetime must be entered in the format e.g. 14:30 1,3,5,7 2014-08-17 2014-10-30.';
		
		if ($specificDatetime) {
			$p = explode(' ', $specificDatetime);
			if (count($p) != 2 || !$p[0] || !$p[1]) {
				$result[1] = $SPECIFIC_DATETIME_ERROR; return $result;
			}
			try {
				$dt = new DateTime($specificDatetime);
			} catch (Exception $ex) {
				$result[1] = $SPECIFIC_DATETIME_ERROR; return $result;
			}
			$listDt[] = $specificDatetime;
			
		} else if ($patternDatetime) {
			// 14:30 1,3,5,7 2014-08-17 2014-10-30
			$p = explode(' ', $patternDatetime);
			if ( count($p) != 4 ) {
				$result[1] = $PATTERN_DATETIME_ERROR; return $result;
			}
			
			for ($i = 0; $i < 4; $i++) {
				if (!$p[$i]) {
					$result[1] = $PATTERN_DATETIME_ERROR; return $result;
				}
			}
			
			// TODO: check format more
			
			$weekDays	= explode(',', $p[1]);
			
			try {
				$startDt	= new DateTime($p[2]);
				$endDt		= new DateTime($p[3]);
			} catch (Exception $ex) {
				$result[1] = $PATTERN_DATETIME_ERROR; return $result;
			}
			
			if ($startDt > $endDt) {
				$result[1] = 'Pattern datetime: start date must be less or equal than end date.'; return $result;
			}
			
			while ($startDt <= $endDt) {
				$w = $startDt->format('w') + 1;
				if ( array_search($w, $weekDays) !== FALSE ) {
					$listDt[] = $startDt->format('Y-m-d') . ' ' . $p[0];
				}
				$startDt->modify('+1 days');
			}
		}
		
		if (count($listDt) == 0) {
			$result[1] = 'Pattern datetime contains no session.'; return $result;
		}
		$MAX_SES = 100;
		if (count($listDt) > $MAX_SES) {
			$result[1] = 'Could not create more than ' . $MAX_SES . ' sessions at once time.';
			return $result;
		}
		
		foreach ($listDt as $dt) {
			if (!$this->isRoomFreeAtTime($roomId, $dt, $minutes, $isEdit ? $sessionId : NULL)) {
				$result[1] = 'The room is not free at that time.'; return $result;
			}
			if (!$this->isTeacherFreeAtTime($teacherId, $dt, $minutes, $isEdit ? $sessionId : NULL)) {
				$result[1] = 'The teacher is not free at that time.'; return $result;
			}
			if (!$this->areStudentsFreeAtTime($courseId, $sessionType, $labGroup, $dt, $minutes, $isEdit ? $sessionId : NULL)) {
				$result[1] = 'Some students are not free at that time.'; return $result;
			}
		}
		
		//==================================================================================
		// Now insert/update database
		if ($isEdit) {
			$sql = 'UPDATE `session` SET `startDatetime` = ?, `minutes` = ?, `stype` = ?,
						`roomId` = ?, `teacherId` = ?, `courseId` = ?, `labGroup` = ?
					WHERE `id` = ?';
			$ps = new PreparedStatement($sql);
			$ps->setValue(1, $listDt[0]);
			$ps->setValue(2, $minutes);
			$ps->setValue(3, $sessionType);
			$ps->setValue(4, $roomId);
			$ps->setValue(5, $teacherId);
			$ps->setValue(6, $courseId);
			$ps->setValue(7, $labGroup);
			$ps->setValue(8, $sessionId);
			
			//echo $ps->getSql();
			
			if ( !DataSource::instance()->execute( $ps->getSql() ) ) {
				$result[1] = 'Something went wrong, could not update the session.'; return $result;
			}
		} else {
			$sql = 'INSERT `session`(`startDatetime`, `minutes`, `stype`, `roomId`, `teacherId`, `courseId`, `labGroup`)
						VALUES(?, ?, ?, ?, ?, ?, ?)';
			$ps = new PreparedStatement($sql);
			
			foreach ($listDt as $dt) {
				$ps->setValue(1, $dt);
				$ps->setValue(2, $minutes);
				$ps->setValue(3, $sessionType);
				$ps->setValue(4, $roomId);
				$ps->setValue(5, $teacherId);
				$ps->setValue(6, $courseId);
				$ps->setValue(7, $labGroup);
				
				//echo $ps->getSql();
				
				if ( !DataSource::instance()->execute( $ps->getSql() ) ) {
					$result[1] = 'Something went wrong, could not insert new sessions.'; return $result;
				}
			}
		}
		
		// SUCCESS
		$result[0] = TRUE;
		if ($isEdit) {
			$result[1] = 'The session has been updated successfully.';
		} else {
			$result[1] = count($listDt) . ' sessions have been created successfully.';
		}
		return $result;
	}
	
	//==============================================================================

	public function delete($sessionId) {
		$session = $this->findById($sessionId);
		if (!$session) return FALSE;
		
		// Check Attendance
		$session->clearAttendances();
		if ( $session->loadAttendances() ) return FALSE;
		
		//
		$sql = 'DELETE FROM `session` WHERE `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $sessionId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	/**
	 * 
	 * @param int $sessionId
	 * @param int $isActive
	 * 
	 * @return boolean
	 */
	public function updateActive($sessionId, $isActive) {
		$session = $this->findById($sessionId);
		if (!$session) return FALSE;
		
		$sql = 'UPDATE `session` SET `isActive` = ? WHERE `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $isActive);
		$ps->setValue(2, $sessionId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
}
?>
