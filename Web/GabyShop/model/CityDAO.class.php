<?

__autoload('DataSource');
__autoload('PreparedStatement');

class CityDAO {

	private $ds;
	
	public function CityDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findByAll() {
		$sql = "SELECT * FROM cities ORDER BY name";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = htmlspecialchars( $row['name'] );
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function update($id, $name) {
		$ps = new PreparedStatement( "UPDATE cities SET name = ? WHERE id = ?" );
		$ps->setString(1, $this->ds->escape($name) );
		$ps->setInt(2, $id);
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function findByAll_Show() {
		$sql =	"SELECT Q.id, Q.name, " .
          			"(SELECT COUNT(*) FROM customers AS P WHERE P.city_id = Q.id) AS cust_count " .
				"FROM cities AS Q " .
				"ORDER BY Q.id";
		$rs = $this->ds->execute($sql);
		
		$arr = array();
		while ($row = mysql_fetch_array($rs)) {
			$arr[$row['id']] = array(
				'name'			=> htmlspecialchars( $row['name'] ),
				'cust_count'	=> $row['cust_count']
			);
		}
		
		mysql_free_result($rs);
		
		return $arr;
	}
	
	public function insert($name) {
		$ps = new PreparedStatement( 'INSERT cities(name) VALUES(?)' );
		$ps->setString( 1, $this->ds->escape($name) );
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function delete($id) {
		$ps = new PreparedStatement( "DELETE FROM cities WHERE id = ?" );
		$ps->setInt(1, $id);
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			$customerDAO = new CustomerDAO( $this->ds );
			$customerDAO->updateCity( $id, 'NULL' );
			return TRUE;
		}
		
		return FALSE;
	}
	
}

?>
