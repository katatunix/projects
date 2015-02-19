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
	
	public function findById($accountId) {
		if (!$accountId) return NULL;
		
		if ($obj = $this->findObjectInPool($accountId)) return $obj;
		
		$ps = new PreparedStatement(
			'SELECT * FROM `account` WHERE `id` = ?'
		);
		$ps->setValue( 1, $accountId );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$account = NULL;
		
		if ( $row = mysqli_fetch_array($rs) )
		{
			$account = $this->create( $accountId );
			$account->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['gender'],
				$row['dob'],
				$row['role'],
				$row['studentAisId'],
				$row['isActive'] == 1
			);
		}
		
		mysqli_free_result($rs);
		
		return $account;
	}
	
	public function findByStudentAisId($studentAisId) {
		if (!$studentAisId) return NULL;
		
		$ps = new PreparedStatement(
			'SELECT * FROM `account`
			WHERE `role` = 0 AND `studentAisId` IS NOT NULL AND `studentAisId` = ?'
		);
		$ps->setValue( 1, $studentAisId );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$account = NULL;
		
		if ( $row = mysqli_fetch_array($rs) )
		{
			$account = $this->create( $row['id'] );
			$account->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['gender'],
				$row['dob'],
				$row['role'],
				$row['studentAisId'],
				$row['isActive'] == 1
			);
		}
		
		mysqli_free_result($rs);
		
		return $account;
	}
	
	// $rolesArray is NULL means all
	public function findByRoles($rolesArray) {
		$sql = 'SELECT * FROM `account`';
		if (!is_null($rolesArray)) {
			$sql .= ' WHERE `role` IN (?)';
		}
		$sql .= ' ORDER BY `role`, `username`';
		
		$ps = new PreparedStatement($sql);
		
		if (!is_null($rolesArray)) {
			$ps->setValue(1, $rolesArray);
		}
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$list = array();
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$account = $this->create($row['id']);
			$account->setBasicData(
				$row['username'],
				$row['fullname'],
				$row['gender'],
				$row['dob'],
				$row['role'],
				$row['studentAisId'],
				$row['isActive'] == 1
			);
			
			$list[] = $account;
		}
		
		mysqli_free_result($rs);
		
		return $list;
	}
	
	public function checkLogin($username, $password) {
		if (!$username || !$password) return NULL;
		
		$ps = new PreparedStatement(
			'SELECT * FROM `account` WHERE `username` = ? AND `password` = ?');
		$ps->setValue( 1, $username );
		$ps->setValue( 2, $password );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$account = NULL;
		
		if ( $row = mysqli_fetch_array($rs) ) {
			if ($row['isActive'] == 1) {
				$account = $this->create($row['id']);
				$account->setBasicData(
					$row['username'],
					$row['fullname'],
					$row['gender'],
					$row['dob'],
					$row['role'],
					$row['studentAisId'],
					$row['isActive'] == 1
				);
			}
		}
		
		mysqli_free_result($rs);
		
		return $account;
	}
	
	public function changePassword($accountId, $newPassword) {
		if (!$accountId || !$newPassword) return FALSE;
		
		$ps = new PreparedStatement('UPDATE `account` SET `password` = ? WHERE id = ?');
		$ps->setValue( 1, $newPassword );
		$ps->setValue( 2, $accountId );
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	
	public function checkPassword($accountId, $password) {
		if (!$accountId || !$password) return FALSE;
		
		$ps = new PreparedStatement('SELECT `id` FROM `account` WHERE `id` = ? AND `password` = ?');
		$ps->setValue( 1, $accountId );
		$ps->setValue( 2, $password );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$ok = mysqli_fetch_array($rs) ? TRUE : FALSE;
		
		mysqli_free_result($rs);
		return $ok;
	}
	
	public function addStaffAccount($username, $fullname, $gender, $dob, $role, $isActive){
		$sql = 'Insert Into `account` (`username`, `password`, `fullname`, `gender`, `dob`, `role`, `isActive`) values(?,?,?,?,?,?,?)';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $username);
		$ps->setValue(2, '123456');
		$ps->setValue(3, $fullname);
		$ps->setValue(4, $gender);
		$ps->setValue(5, $dob);
		$ps->setValue(6, $role);
		$ps->setValue(7, $isActive);
		
		$rs = DataSource::instance()->execute($ps->getSql());
		if($rs)
		{
			return true;
		}
		return false;
	}
	
	public function editStaffAccount($id, $fullname, $gender, $dob, $isActive){
		$sql = 'Update `account` Set `fullname` = ?,
					                 
					                 `dob` = ?,
					                 `isActive` = ?
				Where `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $fullname);
		//$ps->setValue(2, $gender);
		$ps->setValue(2, $dob);
		$ps->setValue(3, $isActive);
		$ps->setValue(4, $id);
		
		$rs = DataSource::instance()->execute($ps->getSql());
		if($rs){
			return true;
		} return false;
	}
	public function resetPassword($accountId) {
		if (!$accountId) return FALSE;
		
		$ps = new PreparedStatement('UPDATE `account` SET `password` = ? WHERE id = ?');
		$ps->setValue( 1, '123456');
		$ps->setValue( 2, $accountId );
		
		return DataSource::instance()->execute( $ps->getSql() );
	}
	public function activeDeactive($id, $isActive){
		$sql = 'Update `account` Set `isActive` = ? Where `id` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $isActive);
		$ps->setValue(2, $id);
		$rs = DataSource::instance()->execute($ps->getSql());
		if($rs){
			return true;
		} return false;
	}
	
	// Katatunix
	public function genAccount($studentAisIdList) {
		$ok = TRUE;
		
		foreach ($studentAisIdList as $studentAisId) if ($studentAisId) {
			//
			if ( $this->findByStudentAisId($studentAisId) ) {
				// account is generated already
			} else if (StudentHome::instance()->findById($studentAisId)) {
				// insert
				$sql = 'INSERT `account`(`username`, `password`, `studentAisId`, `role`)
							VALUES(?, ?, ?, ?)';
				$ps = new PreparedStatement($sql);
				$ps->setValue(1, 'student'. $studentAisId);
				$ps->setValue(2, __DEFAULT_PASS);
				$ps->setValue(3, $studentAisId);
				$ps->setValue(4, Role::STUDENT);
				
				$ok = $ok && DataSource::instance()->execute($ps->getSql());
			} else {
				$ok = FALSE;
			}
			if (!$ok) return FALSE;
		}
		
		return $ok;
	}
	
	public function checkDuplicateUsername($username){
		$sql = 'Select `username` from `account` Where `username` = ?';
		$ps = new PreparedStatement($sql);
		$ps->setValue(1, $username);
		$rs = DataSource::instance()->execute( $ps->getSql() );
		$num_rows = mysqli_num_rows($rs);
		if($num_rows != 0){
			return FALSE;
		}
		return True;
	}
	
}

?>
