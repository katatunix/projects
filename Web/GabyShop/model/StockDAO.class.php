<?

__autoload('DataSource');
__autoload('PreparedStatement');

__autoload('ExportBillDAO');
__autoload('ImportBillDAO');
__autoload('TransferBillDAO');

class StockDAO {

	private $ds;
	
	public function StockDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findByAll() {
		$sql = "SELECT * FROM stocks ORDER BY id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = htmlspecialchars( $row['name'] );
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function update($id, $name) {
		$ps = new PreparedStatement( "UPDATE stocks SET name = ? WHERE id = ?" );
		$ps->setString(1, $this->ds->escape($name) );
		$ps->setInt(2, $id);
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function findByAll_Show() {
		$sql =	"SELECT P.id AS product_id, P.code AS product_code, S.id AS stock_id, S.name AS stock_name, " .
				"( " .
					"(SELECT IFNULL(SUM(IBI.amount), 0) " .
						"FROM import_bill_items AS IBI INNER JOIN import_bills AS IB ON IBI.import_bill_id = IB.id " .
						"WHERE IBI.product_id = P.id AND IB.stock_id = S.id ".
					") - " .
					"(SELECT IFNULL(SUM(EBI.amount), 0) " .
						"FROM export_bill_items AS EBI INNER JOIN export_bills AS EB ON EBI.export_bill_id = EB.id " .
						"WHERE EBI.product_id = P.id AND EB.stock_id = S.id " .
					") + " .
					"(SELECT IFNULL(SUM(TBI.amount), 0) " .
						"FROM transfer_bill_items AS TBI INNER JOIN transfer_bills AS TB ON TBI.transfer_bill_id = TB.id " .
						"WHERE TBI.product_id = P.id AND TB.destination_stock_id = S.id " .
					") - " .
					"(SELECT IFNULL(SUM(TBI.amount), 0) " .
						"FROM transfer_bill_items AS TBI INNER JOIN transfer_bills AS TB ON TBI.transfer_bill_id = TB.id " .
						"WHERE TBI.product_id = P.id AND TB.source_stock_id = S.id " .
					") " .
				") AS quantity " .

				"FROM products AS P INNER JOIN stocks AS S";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$quantity = (int)$row['quantity'];
			
			$stock_id = (int)$row['stock_id'];
			$product_id = (int)$row['product_id'];
			
			if ( array_key_exists( $stock_id, $arr ) ) {
				if ( array_key_exists($product_id, $arr[$stock_id]['items_list']) ) {
					$arr[$stock_id]['items_list'][$product_id]['quantity'] += $quantity;
				} else {
					$arr[$stock_id]['items_list'][$product_id] = array(
						'product_code'			=> htmlspecialchars( $row['product_code'] ),
						'quantity'				=> $quantity
					);
				}
			} else {
				$arr[$stock_id] = array(
					'stock_name'			=> htmlspecialchars( $row['stock_name'] ),
					'items_list'			=> array()
				);
				
				$arr[$stock_id]['items_list'][$product_id] = array(
					'product_code'			=> htmlspecialchars( $row['product_code'] ),
					'quantity'				=> $quantity
				);
			}
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function insert($name) {
		$ps = new PreparedStatement( 'INSERT stocks(name) VALUES(?)' );
		$ps->setString( 1, $this->ds->escape($name) );
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function delete($id) {
		if ( $this->checkExistedInBills($id) ) {
			return 1;
		}
	
		$ps = new PreparedStatement( "DELETE FROM stocks WHERE id = ?" );
		$ps->setInt(1, $id);
		
		return $this->ds->execute( $ps->getSql() ) ? 0 : 2;
	}
	
	public function checkExistedInBills($stock_id) {
		$exportBillDAO = new ExportBillDAO($this->ds);
		if ( $exportBillDAO->checkExisted($stock_id) ) return TRUE;
		
		$importBillDAO = new ImportBillDAO($this->ds);
		if ( $importBillDAO->checkExisted($stock_id) ) return TRUE;
		
		$transferBillDAO = new TransferBillDAO($this->ds);
		if ( $transferBillDAO->checkExisted($stock_id) ) return TRUE;
		
		return FALSE;
	}
	
}

?>
