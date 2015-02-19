<?php

__import('business/AbstractHome');
__import('business/Course');

__import('business/AisFakeData');

class CourseHome extends AbstractHome {
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new CourseHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function create($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new Course($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($courseId) {
		if (!$courseId) return NULL;
		
		if ($obj = $this->findObjectInPool($courseId)) return $obj;
		
		if ( ! isset(AisFakeData::$fakeCoursesList[$courseId]) ) return NULL;
		
		$course = $this->create($courseId);
		
		$fake = AisFakeData::$fakeCoursesList[$courseId];
		$course->setBasicData( $fake['name'], $fake['startDate'], $fake['weeks'] );
		
		return $course;
	}
	
	public function findByAll() {
		$list = array();
		
		foreach (AisFakeData::$fakeCoursesList as $id => $fake) {
			$course = $this->create($id);
			$course->setBasicData( $fake['name'], $fake['startDate'], $fake['weeks'] );
			
			$list[] = $course;
		}
		
		return $list;
	}
	
	public function findTrueLabGroupIndices($courseId) {
		$course = $this->findById($courseId);
		if (!$course) return array();
		
		$course->clearRegistrations(); // clear old data
		$registrations = $course->loadRegistrations(); // load full data of Registrations
		
		$lgs = array();
		foreach ($registrations as $regis) {
			if ($g = $regis->getLabGroup()) {
				if ( array_search($g, $lgs) === FALSE ) {
					$lgs[] = $g;
				}
			}
		}
		asort($lgs);
		return $lgs;
	}
	
	public function findStudentAisIds($courseId) {// in all lab groups, include zero
		$course = $this->findById($courseId);
		if (!$course) return array();
		
		$course->clearRegistrations(); // clear old data
		$registrations = $course->loadRegistrations(); // load full data of Registrations
		
		$ids = array();
		foreach ($registrations as $regis) {
			$ids[] = $regis->getStudentAisId();
		}
		return $ids;
	}
	
	public function findStudentAisIdsInLabGroup($courseId, $g) {
		$course = $this->findById($courseId);
		if (!$course) return array();
		
		$course->clearRegistrations(); // clear old data
		$registrations = $course->loadRegistrations(); // load full data of Registrations
		
		$ids = array();
		foreach ($registrations as $regis) {
			if ($regis->getLabGroup() == $g) {
				$ids[] = $regis->getStudentAisId();
			}
		}
		return $ids;
	}
}
