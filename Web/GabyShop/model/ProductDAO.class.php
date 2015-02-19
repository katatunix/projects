<?

require_once 'includes/utils.php';

__autoload('PreparedStatement');

class ProductDAO {

	private $ds;
	
	public function ProductDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findById($id) {
		$sql = 'SELECT * FROM products WHERE id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$prod = NULL;
		
		if ( $row = mysql_fetch_array($rs) ) {
			$prod = array(
				'id'			=> $row['id'] ,
				'code'			=> htmlspecialchars( $row['code'] ),
				'category_id'	=> (int)$row['category_id'],
				'brand_id'		=> (int)$row['brand_id'],
				'price_fonds'	=> (int)$row['price_fonds'],
				'price_sell'	=> (int)$row['price_sell'],
				'description'	=> htmlspecialchars( $row['description'] ),
				'pics'			=> $row['pics'],
				'seo_url'		=> $row['seo_url']
			);
		}
		
		mysql_free_result($rs);
		
		return $prod;
	}
	
	public function findBySeoUrl($seo_url) {
		$sql = 'SELECT * FROM products WHERE seo_url = ?';
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $this->ds->escape($seo_url) );
		$rs = $this->ds->execute( $ps->getSql() );
		
		$prod = NULL;
		
		if ( $row = mysql_fetch_array($rs) ) {
			$prod = array(
				'id'			=> $row['id'],
				'code'			=> htmlspecialchars( $row['code'] ),
				'category_id'	=> (int)$row['category_id'],
				'brand_id'		=> (int)$row['brand_id'],
				'price_fonds'	=> (int)$row['price_fonds'],
				'price_sell'	=> (int)$row['price_sell'],
				'description'	=> htmlspecialchars( $row['description'] ),
				'pics'			=> $row['pics'],
				'seo_url'		=> $row['seo_url']
			);
		}
		
		mysql_free_result($rs);
		
		return $prod;
	}
	
	public function findIdBySeoUrl($seo_url) {
		$ps = new PreparedStatement('SELECT id FROM products WHERE seo_url = ?');
		$ps->setString( 1, $this->ds->escape($seo_url) );
		
		$rs = $this->ds->execute( $ps->getSql() );
		$id = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$id = (int)$row['id'];
		}
		mysql_free_result($rs);
		
		return $id;
	}
	
	public function findByCatId($cat_id) {
		$sql = 'SELECT * FROM products WHERE category_id = ? ORDER BY order_in_cat';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $cat_id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$list_prod = array();
		
		while ( $row = mysql_fetch_array($rs) ) {
			$list_prod[ $row['id'] ] = array(
				'code'			=> htmlspecialchars( $row['code'] ),
				'category_id'	=> (int)$row['category_id'],
				'brand_id'		=> (int)$row['brand_id'],
				'price_fonds'	=> (int)$row['price_fonds'],
				'price_sell'	=> (int)$row['price_sell'],
				'description'	=> htmlspecialchars( $row['description'] ),
				'pics'			=> $row['pics'],
				'seo_url'		=> $row['seo_url']
			);
		}
		
		mysql_free_result($rs);
		
		return $list_prod;
	}
	
	public function findByBrandId($brand_id) {
		$sql = 'SELECT * FROM products WHERE category_id IS NOT NULL AND brand_id = ? ORDER BY category_id, order_in_cat';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $brand_id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$list_prod = array();
		
		while ( $row = mysql_fetch_array($rs) ) {
			$list_prod[ $row['id'] ] = array(
				'code'			=> htmlspecialchars( $row['code'] ),
				'category_id'	=> (int)$row['category_id'],
				'brand_id'		=> (int)$row['brand_id'],
				'price_fonds'	=> (int)$row['price_fonds'],
				'price_sell'	=> (int)$row['price_sell'],
				'description'	=> htmlspecialchars( $row['description'] ),
				'pics'			=> $row['pics'],
				'seo_url'		=> $row['seo_url']
			);
		}
		
		mysql_free_result($rs);
		
		return $list_prod;
	}
	
	public function updateCat($old_cat_id, $new_cat_id) {
		$sql = 'UPDATE products SET category_id = ? WHERE category_id = ?';
		$ps = new PreparedStatement($sql);
		if ($new_cat_id == 'NULL') {
			$ps->setNull(1);
		} else {
			$ps->setInt(1, $new_cat_id);
		}
		$ps->setInt(2, $old_cat_id);
		$this->ds->execute( $ps->getSql() );
	}
	
	public function updateBrand($old_brand_id, $new_brand_id) {
		$sql = 'UPDATE products SET brand_id = ? WHERE brand_id = ?';
		$ps = new PreparedStatement($sql);
		if ($new_brand_id == 'NULL') {
			$ps->setNull(1);
		} else {
			$ps->setInt(1, $new_brand_id);
		}
		$ps->setInt(2, $old_brand_id);
		$this->ds->execute( $ps->getSql() );
	}
	
	public function insert($code, $brand_id, $category_id, $price_fonds, $price_sell, $description, $pics, $seo_url) {
		$sql = 'INSERT products(code, brand_id, category_id, price_fonds, price_sell, description, pics, seo_url, order_in_cat) ' .
					'VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
		
		$ps = new PreparedStatement($sql);
		
		$ps->setString( 1, $this->ds->escape($code) );
		
		if ( !isset($brand_id) || $brand_id <= 0 ) {
			$ps->setNull(2);
		} else {
			$ps->setInt( 2, $brand_id );
		}
		
		$max_order = 0;
		if ( !isset($category_id) || $category_id <= 0 ) {
			$ps->setNull(3);
		} else {
			$ps->setInt( 3, $category_id );
			$max_order = $this->findMaxOrderInCat($category_id) + 1;
		}
		
		$ps->setInt( 4, $price_fonds );
		$ps->setInt( 5, $price_sell );
		
		if ( !isset($description) || strlen($description) == 0 ) {
			$ps->setNull(6);
		} else {
			$ps->setString( 6, $this->ds->escape($description) );
		}
		
		if ( !isset($pics) || strlen($pics) == 0 ) {
			$ps->setNull(7);
		} else {
			$ps->setString( 7, $this->ds->escape($pics) );
		}
		
		$ps->setString( 8, $seo_url );
		
		$ps->setInt( 9, $max_order );
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function update($id, $code, $brand_id, $category_id, $price_fonds, $price_sell, $description, $pics, $seo_url) {
		$sql = 'UPDATE products SET ' .
					'code=?, brand_id=?, category_id=?, price_fonds=?, price_sell=?, description=?, pics=?, seo_url=? ' .
					'WHERE id=?';
		
		$ps = new PreparedStatement($sql);
		
		$ps->setString( 1, $this->ds->escape($code) );
		
		if ( !isset($brand_id) || $brand_id <= 0 ) {
			$ps->setNull(2);
		} else {
			$ps->setInt( 2, $brand_id );
		}
		
		if ( !isset($category_id) || $category_id <= 0 ) {
			$ps->setNull(3);
		} else {
			$ps->setInt( 3, $category_id );
		}
		
		$ps->setInt( 4, $price_fonds );
		$ps->setInt( 5, $price_sell );
		
		if ( !isset($description) || strlen($description) == 0 ) {
			$ps->setNull(6);
		} else {
			$ps->setString( 6, $this->ds->escape($description) );
		}
		
		if ( !isset($pics) || strlen($pics) == 0 ) {
			$ps->setNull(7);
		} else {
			$ps->setString( 7, $this->ds->escape($pics) );
		}
		
		$ps->setString( 8, $seo_url );
		
		$ps->setInt( 9, $id );
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	// return 0: success
	// return 1: cannot delete
	// return 2: system error
	public function delete($id) {
		if ( $this->sumExport($id) > 0 || $this->sumImport($id) > 0 || $this->sumTransfer($id) > 0 ) {
			return 1;
		}
		$ps = new PreparedStatement( "DELETE FROM products WHERE id = ?" );
		$ps->setInt(1, $id);
		
		return $this->ds->execute( $ps->getSql() ) ? 0 : 2;
	}
	
	public function isExistedCode($code) {
		$sql = "SELECT id FROM products WHERE code = ?";
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $code);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$b = FALSE;
		if ( mysql_fetch_array($rs) ) {
			$b = TRUE;
		}
		
		mysql_free_result($rs);
		
		return $b;
	}
	
	public function isExistedSeoUrl($seo_url) {
		$sql = "SELECT id FROM products WHERE seo_url = ?";
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $seo_url);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$b = FALSE;
		if ( mysql_fetch_array($rs) ) {
			$b = TRUE;
		}
		
		mysql_free_result($rs);
		
		return $b;
	}
	
	public function isExistedCode_Except($code, $id) {
		$sql = "SELECT id FROM products WHERE code = ? AND id <> ?";
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $code);
		$ps->setInt(2, $id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$b = FALSE;
		if ( mysql_fetch_array($rs) ) {
			$b = TRUE;
		}
		
		mysql_free_result($rs);
		
		return $b;
	}
	
	public function isExistedSeoUrl_Except($seo_url, $id) {
		$sql = "SELECT id FROM products WHERE seo_url = ? AND id <> ?";
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $seo_url);
		$ps->setInt(2, $id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$b = FALSE;
		if ( mysql_fetch_array($rs) ) {
			$b = TRUE;
		}
		
		mysql_free_result($rs);
		
		return $b;
	}
	
	public function findByAll_Manage() {
		return $this->findByInfo_Manage( array() );
	}
	
	public function findByCode_Manage($code) {
		return $this->findByInfo_Manage(
			array(
				'code' => $code
			)
		);
	}
	
	public function findByInfo_Manage($info) {
		$s = ' WHERE 1 = 1 ';
		
		if ( isset($info['code']) ) {
			$s .= " AND P.code = '" . $this->ds->escape( $info['code'] ) . "'";
		}
		
		if ( isset($info['category_id']) ) {
			if ((int)$info['category_id'] == 0)
				$s .= ' AND P.category_id IS NULL ';
			else
				$s .= ' AND P.category_id = ' . $info['category_id'];
		}
		
		if ( isset($info['brand_id']) ) {
			if ((int)$info['brand_id'] == 0)
				$s .= ' AND P.brand_id IS NULL ';
			else
				$s .= ' AND P.brand_id = ' . $info['brand_id'];
		}
		
		$sql = "SELECT P.id, P.code, P.price_fonds, P.price_sell, P.pics, " .
					"(" .
    					"SELECT " .
        					"(SELECT IFNULL(SUM(IBI.amount), 0) FROM import_bill_items AS IBI WHERE IBI.product_id=P.id) - " .
							"(SELECT IFNULL(SUM(EBI.amount), 0) FROM export_bill_items AS EBI WHERE EBI.product_id=P.id) " .
        					
					") AS my_sum, " .
					"(SELECT C.name FROM categories AS C WHERE C.id = P.category_id) AS category_name, " .
					"(SELECT B.name FROM brands AS B WHERE B.id = P.brand_id) AS brand_name " .
					"FROM products AS P ".
					$s .
					" ORDER BY P.code";
		
		$rs = $this->ds->execute( $sql );
		
		$list_prod = array();
		
		while ( $row = mysql_fetch_array($rs) ) {
			$list_prod[ $row['id'] ] = array(
				'code'			=> htmlspecialchars( $row['code'] ),
				'price_fonds'	=> $row['price_fonds'],
				'price_sell'	=> $row['price_sell'],
				'pic'			=> get_pic_at( $row['pics'], 0 ),
				'my_sum'		=> $row['my_sum'],
				'category_name'	=> htmlspecialchars( $row['category_name'] ),
				'brand_name'	=> htmlspecialchars( $row['brand_name'] )
			);
		}
		
		mysql_free_result($rs);
		
		return $list_prod;
	}
	
	public function sumExport($prod_id) {
		$sql = "SELECT IFNULL(SUM(EBI.amount), 0) AS my_sum " .
					"FROM export_bill_items AS EBI WHERE EBI.product_id = ?";
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $prod_id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$sum = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$sum = (int)$row['my_sum'];
		}
		mysql_free_result($rs);
		
		return $sum;
	}
	
	public function sumImport($prod_id) {
		$sql = "SELECT IFNULL(SUM(IBI.amount), 0) AS my_sum " .
					"FROM import_bill_items AS IBI WHERE IBI.product_id = ?";
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $prod_id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$sum = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$sum = (int)$row['my_sum'];
		}
		mysql_free_result($rs);
		
		return $sum;
	}
	
	public function sumTransfer($prod_id) {
		$sql = "SELECT IFNULL(SUM(TBI.amount), 0) AS my_sum " .
					"FROM transfer_bill_items AS TBI WHERE TBI.product_id = ?";
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $prod_id);
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$sum = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$sum = (int)$row['my_sum'];
		}
		mysql_free_result($rs);
		
		return $sum;
	}
	
	public function findAllPics() {
		$sql = 'SELECT pics FROM products';
		$rs = $this->ds->execute( $sql );
		
		$arr = array();
		$i = 0;
		while ( $row = mysql_fetch_array($rs) ) {
			$p = explode('/', $row['pics']);
			$c = count($p);
			for ($j = 0; $j < $c; $j++) {
				$arr[$i] = $p[$j];
				$i++;
			}
			
		}
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function updateOrderInCat($product_id, $order) {
		$sql = 'UPDATE products SET order_in_cat = ? WHERE id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $order);
		$ps->setInt(2, $product_id);
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function resort($listIdStr) {
		$arrId = explode(',', $listIdStr);
		$index = 0;
		$count = count($arrId);
		
		for ($i = 0; $i < $count; $i++) {
			$this->updateOrderInCat($arrId[$i], $index);
			$index++;
		}
	}
	
	private function findMaxOrderInCat($cat_id) {
		$sql = 'SELECT MAX(order_in_cat) AS max_order FROM products WHERE category_id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $cat_id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$max_order = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$max_order = (int)$row['max_order'];
		}
		mysql_free_result($rs);
		
		return $max_order;
	}
}
?>
