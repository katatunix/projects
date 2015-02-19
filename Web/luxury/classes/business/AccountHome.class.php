<?php

__import('business/AbstractHome');

__import('business/Account');

class AccountHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new AccountHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function create($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new Account($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($id) {
		if (!$id) return NULL;
		
		if ($obj = $this->findObjectInPool($id)) return $obj;
		
		$ps = new PreparedStatement('SELECT * FROM `account` WHERE `id` = ?');
		$ps->setValue( 1, $id );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $id );
			$obj->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['role']
			);
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}
	
	public function checkLogin($username, $password) {
		if (!$username || !$password) return NULL;
		
		$ps = new PreparedStatement(
			'SELECT * FROM `account` WHERE `username` = ? AND `password` = ?');
		$ps->setValue( 1, $username );
		$ps->setValue( 2, $password );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysqli_fetch_array($rs) ) {
			$obj = $this->create( $row['id'] );
			$obj->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['role']
			);
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}

	public function findAllStaffs($roles) {
		// nghia
		//$ps = new PreparedStatement('SELECT * FROM `account` WHERE `role` = ?');
		//$ps = new PreparedStatement('SELECT * FROM `account` ORDER BY `role`');
		$ps = new PreparedStatement('SELECT * FROM `account` WHERE `role` IN (?)');
		$ps->setValue( 1, $roles );

		$list = array();
		$rs = DataSource::instance()->execute( $ps->getSql() );
		while ( $row = mysqli_fetch_array($rs) ) {
			$obj = $this->create( $row['id'] );
			$obj->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['role']
			);
			$list[] = $obj;
		}

		mysqli_free_result($rs);

		return $list;
	}
	
}

?>
