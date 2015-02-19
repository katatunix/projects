<?php

abstract class AisFakeData {
	public static $fakeCoursesList = array(
		1 => array('name'=>'Mathematic 2013', 'startDate'=>'2013-01-20', 'weeks'=>'15'),
		2 => array('name'=>'Mathematic 2014', 'startDate'=>'2014-05-30', 'weeks'=>'23'),
		3 => array('name'=>'Mathematic 2015', 'startDate'=>'2015-09-17', 'weeks'=>'26')
	);
	
	public static $fakeStudentsList = array(
		69		=> array('fullname'=>'Nghĩa Bùi Văn',		'gender'=>0, 'dob'=>'1987-07-27'),
		313		=> array('fullname'=>'Todd Nguyễn',			'gender'=>0, 'dob'=>'1990-03-31'),
		199		=> array('fullname'=>'Nguyễn Ngọc Cảnh',	'gender'=>0, 'dob'=>'1991-07-18'),
		44		=> array('fullname'=>'Hoàng Kim Ngân',		'gender'=>1, 'dob'=>'1980-08-20'),
		22		=> array('fullname'=>'Lê Văn Việt',			'gender'=>1, 'dob'=>'1980-10-20'),
		25		=> array('fullname'=>'Ly Thuong Kiet',		'gender'=>1, 'dob'=>'1988-10-22'),
		26		=> array('fullname'=>'Van Chung',			'gender'=>1, 'dob'=>'1989-07-23'),
		27		=> array('fullname'=>'Le Thi Hoa',			'gender'=>0, 'dob'=>'1986-12-12'),
		28		=> array('fullname'=>'Nguyen Anh Truong',	'gender'=>1, 'dob'=>'1982-10-16'),
		29		=> array('fullname'=>'Hoang Anh Minh',		'gender'=>1, 'dob'=>'1983-11-02')
	);
	
	public static $fakeRegis = array(
		array('courseId'=>1, 'studentAisId'=>69),
		array('courseId'=>1, 'studentAisId'=>313),
		array('courseId'=>1, 'studentAisId'=>22),
		
		array('courseId'=>2, 'studentAisId'=>69),
		array('courseId'=>2, 'studentAisId'=>313),
		array('courseId'=>2, 'studentAisId'=>44),
		
		array('courseId'=>3, 'studentAisId'=>313),
	);
}

?>
