<?

require_once('includes/utils.php');

__autoload('DataSource');
__autoload('PreparedStatement');
__autoload('CartItemDAO');

class CartDAO {

	private $ds;
	
	public function CartDAO($ds) {
		$this->ds = $ds;
	}
	
	public function insert($listItem) {
		$ps = new PreparedStatement('INSERT carts(checkout_datetime) VALUES(?)');
		$ps->setString( 1, get_cur_datetime() );
		if ( $this->ds->execute($ps->getSql()) ) {
			$cart_id = $this->ds->getLastId();
			
			$cartItemDAO = new CartItemDAO($this->ds);
			
			foreach ($listItem as $key => $value) {
				$cartItemDAO->insert($cart_id, $key, $value);
			}
			
			return $cart_id;
		} else {
			return -1;
		}
	}
	
	public function findById($id) {
		$sql = 'SELECT * FROM carts WHERE id = ?';
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$cart = NULL;
		if ($row = mysql_fetch_array($rs)) {
			$cart = array(
				'id'				=> $id,
				'checkout_datetime'	=> htmlspecialchars( $row['checkout_datetime'] )
			);
		}
		
		mysql_free_result($rs);
		return $cart;
	}
	
	public function findByDateRange($fromDate, $toDate) {
		$sql = 'SELECT * FROM carts WHERE checkout_datetime >= ? AND checkout_datetime <= ? ORDER BY checkout_datetime';
		$ps = new PreparedStatement( $sql );
		$ps->setString(1, $fromDate);
		$ps->setString(2, $toDate);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$carts = array();
		while ($row = mysql_fetch_array($rs)) {
			$carts[$row['id']] = $row['checkout_datetime'];
		}
		
		mysql_free_result($rs);
		return $carts;
	}
	
	public function findDetail($cart_id) {
		$sql = 'SELECT CI.product_id, CI.quantity, P.code, P.price_sell ' .
					'FROM cart_items AS CI INNER JOIN products AS P ON CI.product_id = P.id ' .
					'WHERE CI.cart_id = ?';
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $cart_id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$cart_items = array();
		while ($row = mysql_fetch_array($rs)) {
			$cart_items[$row['product_id']] = array(
				'code'			=> htmlspecialchars( $row['code'] ),
				'quantity'		=> (int)$row['quantity'],
				'price_sell'	=> (int)$row['price_sell']
			);
		}
		
		mysql_free_result($rs);
		return $cart_items;
	}
	
	function delete($id) {
		$sql = 'DELETE FROM carts WHERE id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		$this->ds->execute( $ps->getSql() );
		
		$cartItemDAO = new CartItemDAO($this->ds);
		$cartItemDAO->deleteInCart($id);
	}
	
	public function deleteBatch($strBatch) {
		$sql = "DELETE FROM carts WHERE id IN($strBatch)";
		
		$this->ds->execute( $sql );
		
		$cartItemDAO = new CartItemDAO($this->ds);
		$cartItemDAO->deleteInCart_Batch($strBatch);
	}
	
}

?>
