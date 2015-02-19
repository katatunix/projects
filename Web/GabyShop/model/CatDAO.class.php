<?

__autoload('DataSource');
__autoload('PreparedStatement');

class CatDAO {

	private $ds;
	
	public function CatDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findByAll() {
		$sql = "SELECT * FROM categories ORDER BY id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = htmlspecialchars( $row['name'] );
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function findByAll_Navigation() {
		$sql = "SELECT C.id, C.name, " .
					"(SELECT P.seo_url FROM products AS P WHERE P.category_id = C.id ".
						"ORDER BY P.order_in_cat LIMIT 0, 1) ".
						"AS first_prod_seo_url ".
					"FROM categories AS C ORDER BY C.id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = array(
				'name'					=> htmlspecialchars( $row['name'] ),
				'first_prod_seo_url'	=> $row['first_prod_seo_url']
			);
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function update($id, $name) {
		$ps = new PreparedStatement( "UPDATE categories SET name = ? WHERE id = ?" );
		$ps->setString(1, $this->ds->escape($name) );
		$ps->setInt(2, $id);
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function findByAll_Show() {
		$sql =	"SELECT Q.id, Q.name, " .
          			"(SELECT COUNT(*) FROM products AS P WHERE P.category_id = Q.id) AS prod_count " .
				"FROM categories AS Q " .
				"ORDER BY Q.id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = array(
				'name'			=> htmlspecialchars( $row['name'] ),
				'prod_count'	=> $row['prod_count']
			);
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function insert($name) {
		$ps = new PreparedStatement( 'INSERT categories(name) VALUES(?)' );
		$ps->setString( 1, $this->ds->escape($name) );
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function delete($id) {
		$ps = new PreparedStatement( "DELETE FROM categories WHERE id = ?" );
		$ps->setInt(1, $id);
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			$productDAO = new ProductDAO( $this->ds );
			$productDAO->updateCat( $id, 'NULL' );
			return TRUE;
		}
		
		return FALSE;
	}
	
}

?>
