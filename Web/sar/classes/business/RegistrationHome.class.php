<?php

__import('business/AbstractHome');

__import('business/Course');
__import('business/Student');
__import('business/Registration');
__import('business/AisFakeData');
__import('business/AccountHome');
__import('business/SessionHome');

class RegistrationHome extends AbstractHome {
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new RegistrationHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function create($courseId, $studentAisId) {
		$id = $courseId . '_' . $studentAisId;
		
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new Registration($courseId, $studentAisId);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($courseId, $studentAisId) {
		if (!$courseId || !$studentAisId) return NULL;
		
		if ($obj = $this->findObjectInPool($courseId . '_' . $studentAisId)) return $obj;
		
		$obj = NULL;
		
		foreach (AisFakeData::$fakeRegis as $regis) {
			if ($regis['courseId'] == $courseId && $regis['studentAisId'] == $studentAisId) {
				$obj = $this->create($courseId, $studentAisId);
				$obj->setTemp(FALSE);
				break;
			}
		}
		
		// Find lab group
		$sql = 'SELECT `labGroup` FROM `registration` WHERE `courseId` = ? AND `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $courseId);
		$ps->setValue(2, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		if ( $row = mysqli_fetch_array($rs) ) {
			if (!$obj) {
				$obj = $this->create($courseId, $studentAisId);
				$obj->setTemp(TRUE);
			}
			$obj->setLabGroup($row['labGroup']);
			
		} else {
			if (!$obj) {
				return NULL;
			}
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}
	
	/**
	* 
	* @param int $courseId
	* @param int $studentAisId
	* @param int $labGroup		-1: all; -2: greater than zero;
	* @param int $isTemp		-1: all; 0: false; 1: true;
	* 
	* @return array of Registration
	*/
	public function findByFilter($courseId = 0, $studentAisId = 0, $labGroup = -1, $isTemp = -1) {
		$list = array();
		
		foreach (AisFakeData::$fakeRegis as $regis) {
			$obj = $this->create($regis['courseId'], $regis['studentAisId']);
			$obj->setTemp(FALSE);
			$list[ $obj->getId() ] = $obj;
		}
		
		//
		$sql = 'SELECT * FROM `registration` WHERE TRUE';
		$ps = new PreparedStatement(NULL);
		
		$index = 1;
		
		if ($courseId > 0) {
			$sql .= ' AND `courseId` = ?';
			$ps->setValue($index++, $courseId);
		}
		
		if ($studentAisId > 0) {
			$sql .= ' AND `studentAisId` = ?';
			$ps->setValue($index++, $studentAisId);
		}
		
		if ($labGroup != -1) {
			if ($labGroup == 0) {
				$sql .= ' AND (`labGroup` IS NULL OR `labGroup` = 0)';
			} else if ($labGroup > 0) {
				$sql .= ' AND (`labGroup` IS NOT NULL AND `labGroup` = ?)';
				$ps->setValue($index++, $labGroup);
			} else { // $labGroup == -2
				$sql .= ' AND (`labGroup` IS NOT NULL AND `labGroup` > 0)';
			}
		}
		
		$ps->setSql($sql);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		while ( $row = mysqli_fetch_array($rs) ) {
			//
			$id = $row['courseId'] . '_' . $row['studentAisId'];
			
			if (!isset($list[$id])) {
				$obj = $this->create($row['courseId'], $row['studentAisId']);
				$obj->setTemp(TRUE);
				
				$list[$id] = $obj;
			}
			$list[$id]->setLabGroup($row['labGroup']);
		}
		
		mysqli_free_result($rs);
		
		//
		foreach ($list as $id => $obj) {
			$c1 = $c2 = $c3 = $c4 = TRUE;
			if ($courseId > 0) {
				$c1 = $obj->getCourseId() == $courseId;
			}
			if ($studentAisId > 0) {
				$c2 = $obj->getStudentAisId() == $studentAisId;
			}
			if ($labGroup > -1) {
				$c3 = $obj->getLabGroup() == $labGroup;
			}
			if ($isTemp > -1) {
				$c4 = $obj->isTemp() == $isTemp;
			}
			
			if ($c1 && $c2 && $c3 && $c4) {
				
			} else {
				unset( $list[$id] );
			}
		}
		
		return $list;
	}
	
	public function findByLazy($tutorId) {
		$account = AccountHome::instance()->findById($tutorId);
		if (!$account || $account->getRole() != Role::TUTOR) return NULL;
		
		$sessions = SessionHome::instance()->findByFilter(
			$tutorId, 0/*courseId*/, Session::LAB, NULL/*year*/, NULL/*month*/,
			0/*roomId*/, 1/*active*/, -1/*labGroup*/, NULL/*fromDate*/, NULL/*toDate*/);
			
		$markCourse			= array();
		$lazyRegistrations	= array();
		$absentCountArr		= array();
		$totalCountArr		= array();
		
		foreach ($sessions as $session) {
			$courseId = $session->getCourseId();
			$labGroup = $session->getLabGroup();
			
			if ( $labGroup > 0 && !isset($markCourse[$courseId . '_' . $labGroup]) ) {
				$markCourse[$courseId . '_' . $labGroup] = 1;
				//
				$course = $session->loadCourse();
				
				// solve for this course and labGroup
				
				$course->clearRegistrations(); // clear old data
				// load regis with labGroup > 0
				//$registrations = $course->loadRegistrations(-2/*labGroup*/);
				$registrations = $course->loadRegistrations($labGroup);
				
				//foreach ($lgs as $labGroup)
				{
					//
					$ssl = SessionHome::instance()->findByFilter(
						0/*accountId*/, $courseId, Session::LAB, NULL/*year*/, NULL/*month*/,
						0/*roomId*/, 1/*active*/, $labGroup, NULL/*fromDate*/, NULL/*toDate*/);
					
					
					$totalSessionsNumber = count($ssl);
					if ($totalSessionsNumber == 0) continue;
					
					foreach ($ssl as $ss) {
						$ss->clearAttendances();
						$ss->loadAttendances();
					}
					
					foreach ($registrations as $regis) {
						//if ($regis->getLabGroup() == $labGroup)
						{
							$studentAisId = $regis->getStudentAisId();
							// check for this student $studentAisId
							$absentCount = 0;
							foreach ($ssl as $ss) {
								// is this student absent in this session $ss
								foreach ($ss->getAttendances() as $a) {
									if ( $a->getStudentAisId() == $studentAisId ) {
										$pres = $a->isPresented();
										if ( !is_null($pres) && $pres == 0 ) {
											$absentCount++;
										}
										break;
									}
								}
							}
							//
							if ($absentCount / $totalSessionsNumber > 0.3) {
								$lazyRegistrations[] = $regis;
								$absentCountArr[$regis->getId()] = $absentCount;
								$totalCountArr[$regis->getId()] = $totalSessionsNumber;
							}
						}
					}
				}
				
				
			}
		}
		
		//
		return array($lazyRegistrations, $absentCountArr, $totalCountArr);
	}
	
	public function assign($courseId, $studentAisId, $labGroup) {
		if (!$courseId || !$studentAisId) return FALSE;
		
		$sql = 'SELECT `courseId` FROM `registration`
					WHERE `courseId` = ? AND `studentAisId` = ?';
		
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $courseId);
		$ps->setValue(2, $studentAisId);
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$found = mysqli_fetch_array($rs) ? TRUE : FALSE;
		mysqli_free_result($rs);
		
		if ($found) {
			$sql = 'UPDATE `registration` SET `labGroup` = ?
							WHERE `courseId` = ? AND `studentAisId` = ?';
			$ps->setSql($sql);
			$ps->setValue(1, $labGroup);
			$ps->setValue(2, $courseId);
			$ps->setValue(3, $studentAisId);
		} else {
			$sql = 'INSERT `registration`(`courseId`, `studentAisId`, `labGroup`)
							VALUES(?, ?, ?)';
			$ps->setSql($sql);
			$ps->setValue(1, $courseId);
			$ps->setValue(2, $studentAisId);
			$ps->setValue(3, $labGroup);
		}
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function assignGroups($studentAisIdList, $groupsList, $courseId) {
		$ok = TRUE;
		
		$count = count($studentAisIdList);
		for ($i = 0; $i < $count; $i++) {
			$studentAisId 	= $studentAisIdList[$i];
			$labGroup		= $groupsList[$i];
			
			$regis = $this->findById($courseId, $studentAisId);
			if ($regis) {
				$ok = $ok && $this->assign($courseId, $studentAisId, $labGroup);
			}
		}
		
		return $ok;
	}
	
	public function assignGroupsRandomly($courseId, $maxnum) {
		$ok = TRUE;
		
		// get all regis of this course
		$registrations = $this->findByFilter($courseId);
		
		$noneGroupedStudents = array();
		$trueGroups = array();
		
		foreach ($registrations as $regis) {
			$lg = $regis->getLabGroup();
			if ( !$lg ) {
				$noneGroupedStudents[] = $regis->getStudentAisId();
			} else {
				if (isset($trueGroups[$lg])) {
					$trueGroups[$lg]++;
				} else {
					$trueGroups[$lg] = 1;
				}
			}
		}
		
		$used = array();
		
		foreach ($trueGroups as $g => $num) {
			$used[$g] = 1;
			
			if ($num < $maxnum) {
				$slotNumber = $maxnum - $num;
				
				while (count($noneGroupedStudents) > 0 && $slotNumber > 0) {
					$studentAisId = array_pop($noneGroupedStudents);
					$ok = $ok && $this->assign($courseId, $studentAisId, $g);
					
					$slotNumber--;
				}
			}
		}
		
		$g = 1;
		
		while (count($noneGroupedStudents) > 0) {
			
			while (isset($used[$g])) $g++;
			$used[$g] = 1;
			
			$slotNumber = $maxnum;
				
			while (count($noneGroupedStudents) > 0 && $slotNumber > 0) {
				$studentAisId = array_pop($noneGroupedStudents);
				$ok = $ok && $this->assign($courseId, $studentAisId, $g);
				
				$slotNumber--;
			}
		}
		
		return $ok;
	}
	
	public function addTemp($cid, $studentAisIdList) {
		$ok = TRUE;
		
		foreach ($studentAisIdList as $studentAisId) {
			$ok = $ok && $this->assign($cid, $studentAisId, 0);
		}
		
		return $ok;
	}
	
	public function removeTemp($cid, $studentAisIdList) {
		$ok = TRUE;
		
		foreach ($studentAisIdList as $studentAisId) {
			$ok = $ok && $this->remove($cid, $studentAisId);
		}
		
		return $ok;
	}
	
	public function remove($courseId, $studentAisId) {
		$regis = $this->findById($courseId, $studentAisId);
		if (!$regis || !$regis->isTemp()) return FALSE;
		
		$sql = 'DELETE FROM `registration` WHERE `courseId` = ? AND `studentAisId` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $courseId);
		$ps->setValue(2, $studentAisId);
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
}
