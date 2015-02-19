<?

__autoload('DataSource');
__autoload('PreparedStatement');

class ExportBillDAO {

	private $ds;
	
	public function ExportBillDAO($ds) {
		$this->ds = $ds;
	}
	
	public function checkExisted($stock_id) {
		$ps = new PreparedStatement('SELECT id FROM export_bills WHERE stock_id = ?');
		$ps->setInt(1, $stock_id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		$ret = FALSE;
		if ( mysql_fetch_array($rs) ) {
			$ret = TRUE;
		}
		mysql_free_result($rs);
		
		return $ret;
	}
	
}

?>
