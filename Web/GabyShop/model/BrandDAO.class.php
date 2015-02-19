<?

__autoload('DataSource');
__autoload('PreparedStatement');

class BrandDAO {

	private $ds;
	
	public function BrandDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findByAll() {
		$sql = "SELECT * FROM brands ORDER BY id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = htmlspecialchars( $row['name'] );
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function update($id, $name) {
		$ps = new PreparedStatement( "UPDATE brands SET name = ? WHERE id = ?" );
		$ps->setString(1, $this->ds->escape($name) );
		$ps->setInt(2, $id);
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function findByAll_Show() {
		$sql =	"SELECT Q.id, Q.name, " .
          			"(SELECT COUNT(*) FROM products AS P WHERE P.brand_id = Q.id) AS prod_count " .
				"FROM brands AS Q " .
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
		$ps = new PreparedStatement( 'INSERT brands(name) VALUES(?)' );
		$ps->setString( 1, $this->ds->escape($name) );
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function delete($id) {
		$ps = new PreparedStatement( "DELETE FROM brands WHERE id = ?" );
		$ps->setInt(1, $id);
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			$productDAO = new ProductDAO( $this->ds );
			$productDAO->updateBrand( $id, 'NULL' );
			return TRUE;
		}
		
		return FALSE;
	}
	
}

?>
