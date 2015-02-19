<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/RegistrationHome');

class RegistrationFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new RegistrationFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findByLazy($tutorId) {
		$p = RegistrationHome::instance()->findByLazy($tutorId);
		
		if (!$p) return NULL;
		
		$lazyRegistrations	= $p[0];
		$absentCountArr		= $p[1];
		$totalCountArr		= $p[2];
		
		$cv = new PObjectConverter();
		
		foreach ($lazyRegistrations as $regis) {
			$regis->clearAllExternals();
			
			$regis->loadCourse()->clearAllExternals();
			$regis->loadStudentAis()->clearAllExternals();
			
			$cv->convertRegistration($regis);
		}
		
		$res = $cv->getResult();
		
		if (isset($res->registrations)) {
			foreach ($res->registrations as $r) {
				$r->absentCount	= $absentCountArr[$r->id];
				$r->totalCount	= $totalCountArr[$r->id];
			}
		}
		
		return $res;
	}
	
}
