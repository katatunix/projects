<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/CourseHome');

class CourseFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new CourseFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findByAll() {
		$courses = CourseHome::instance()->findByAll();
		
		$cv = new PObjectConverter();
		
		foreach ($courses as $course) {
			$course->clearAllExternals();
			$cv->convertCourse($course);
		}
		
		return $cv->getResult();
	}
	
	public function findById($courseId) {
		$course = CourseHome::instance()->findById($courseId);
		if (!$course) return NULL;
		
		$course->clearAllExternals();
		
		$cv = new PObjectConverter();
		$cv->convertCourse($course);
		return $cv->getResult();
	}
	
	public function findTrueLabGroupIndices($courseId) {
		return CourseHome::instance()->findTrueLabGroupIndices($courseId);
	}
}

?>
