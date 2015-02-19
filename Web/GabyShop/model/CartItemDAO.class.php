<?

require_once('includes/utils.php');

__autoload('DataSource');
__autoload('PreparedStatement');

class CartItemDAO {

	private $ds;
	
	public function CartItemDAO($ds) {
		$this->ds = $ds;
	}
	
	public function insert($cart_id, $product_id, $quantity) {
		$ps = new PreparedStatement('INSERT cart_items(cart_id, product_id, quantity) VALUES(?, ?, ?)');
		$ps->setInt( 1, $cart_id );
		$ps->setInt( 2, $product_id );
		$ps->setInt( 3, $quantity );
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function deleteInCart($cart_id) {
		$ps = new PreparedStatement('DELETE FROM cart_items WHERE cart_id = ?');
		$ps->setInt(1, $cart_id);
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function deleteInCart_Batch($cart_id_batch_str) {
		$sql = "DELETE FROM cart_items WHERE cart_id IN ($cart_id_batch_str)";
		
		return $this->ds->execute( $sql );
	}
	
}

?>
