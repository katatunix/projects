<?

require_once('includes/utils.php');

__autoload('DataSource');
__autoload('PreparedStatement');

class GlobalDAO {

	private $ds;
	
	public function GlobalDAO($ds) {
		$this->ds = $ds;
	}
	
	public function update($col, $content) {
		$ps = new PreparedStatement('UPDATE global SET ' . $col . ' = ?');
		$ps->setString( 1, $this->ds->escape( $content ) );
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function select($col) {
		$sql = 'SELECT ' . $col . ' FROM global LIMIT 0, 1';
		$rs = $this->ds->execute($sql);
		
		$str = NULL;
		
		if ($row = mysql_fetch_array($rs)) {
			$str = $row[$col];
		}
		
		mysql_free_result($rs);
		
		return $str;
	}
	
	public function incStats() {
		$sql = 'UPDATE global SET count_stats = count_stats + 1';
		return $this->ds->execute( $sql );
	}
	
	public function getStats() {
		$sql = 'SELECT count_stats FROM global LIMIT 0, 1';
		$rs = $this->ds->execute($sql);
		
		$number = 0;
		
		if ($row = mysql_fetch_array($rs)) {
			$number = (int)$row['count_stats'];
		}
		
		mysql_free_result($rs);
		
		return $number;
	}
	
}

?>
