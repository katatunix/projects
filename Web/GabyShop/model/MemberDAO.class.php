<?

__autoload('DataSource');
__autoload('PreparedStatement');

class MemberDAO {

	private $ds;
	
	public function MemberDAO($ds) {
		$this->ds = $ds;
	}
	
	// return 0 if check failed
	// else return group_id: 1(admin) or 2(mod)
	public function checkLogin($username, $password) {
		$ps = new PreparedStatement('SELECT group_id FROM members WHERE username = ? AND password = ?');
		$ps->setString(1, $username);
		$ps->setString(2, $password);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$ret = 0;
		if ($row = mysql_fetch_array($rs)) {
			$ret = (int) $row['group_id'];
		}
		
		mysql_free_result($rs);
		return $ret;
	}
	
}

?>
