<?

__autoload('DataSource');
__autoload('PreparedStatement');

class TransferBillDAO {

	private $ds;
	
	public function TransferBillDAO($ds) {
		$this->ds = $ds;
	}
	
	public function checkExisted($stock_id) {
		$ps = new PreparedStatement('SELECT id FROM transfer_bills WHERE source_stock_id = ? OR destination_stock_id = ?');
		$ps->setInt(1, $stock_id);
		$ps->setInt(2, $stock_id);
		
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
