<?

require_once 'includes/utils.php';

__autoload('PreparedStatement');

class CustomerDAO {
	private $ds;
	private static $PAGE_LENGTH = 30;
	
	function CustomerDAO($ds) {
		$this->ds = $ds;
	}
	
	function insert($full_name, $birthday, $address, $email, $city_id, $note, $phone) {
		$sql = 'INSERT customers(full_name, birthday, address, email, city_id, note, phone) ' .
					'VALUES(?, ?, ?, ?, ?, ?, ?)';
		
		$ps = new PreparedStatement($sql);
		
		$ps->setString( 1, $this->ds->escape($full_name) );
		
		if ( !isset($birthday) || strlen($birthday) == 0 ) {
			$ps->setNull(2);
		} else {
			$ps->setString( 2, $this->ds->escape($birthday) );
		}
		
		if ( !isset($address) || strlen($address) == 0 ) {
			$ps->setNull(3);
		} else {
			$ps->setString( 3, $this->ds->escape($address) );
		}
		
		if ( !isset($email) || strlen($email) == 0 ) {
			$ps->setNull(4);
		} else {
			$ps->setString( 4, $this->ds->escape($email) );
		}
		
		if ( !isset($city_id) || $city_id <= 0 ) {
			$ps->setNull(5);
		} else {
			$ps->setInt( 5, $city_id );
		}
		
		if ( !isset($note) || strlen($note) == 0 ) {
			$ps->setNull(6);
		} else {
			$ps->setString( 6, $this->ds->escape($note) );
		}
		
		if ( !isset($phone) || strlen($phone) == 0 ) {
			$ps->setNull(7);
		} else {
			$ps->setString( 7, $this->ds->escape($phone) );
		}
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	function update($id, $full_name, $birthday, $address, $email, $city_id, $note, $phone) {
		$sql = 'UPDATE customers ' .
			'SET full_name = ?, birthday = ?, address = ?, email = ?, city_id = ?, note = ?, phone = ? ' .
			'WHERE id = ?';
		
		$ps = new PreparedStatement($sql);
		
		$ps->setString( 1, $this->ds->escape($full_name) );
		
		if ( !isset($birthday) || strlen($birthday) == 0 ) {
			$ps->setNull(2);
		} else {
			$ps->setString( 2, $this->ds->escape($birthday) );
		}
		
		if ( !isset($address) || strlen($address) == 0 ) {
			$ps->setNull(3);
		} else {
			$ps->setString( 3, $this->ds->escape($address) );
		}
		
		if ( !isset($email) || strlen($email) == 0 ) {
			$ps->setNull(4);
		} else {
			$ps->setString( 4, $this->ds->escape($email) );
		}
		
		if ( !isset($city_id) || $city_id <= 0 ) {
			$ps->setNull(5);
		} else {
			$ps->setInt( 5, $city_id );
		}
		
		if ( !isset($note) || strlen($note) == 0 ) {
			$ps->setNull(6);
		} else {
			$ps->setString( 6, $this->ds->escape($note) );
		}
		
		if ( !isset($phone) || strlen($phone) == 0 ) {
			$ps->setNull(7);
		} else {
			$ps->setString( 7, $this->ds->escape($phone) );
		}
		
		$ps->setInt( 8, $id );
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	function findById($id) {
		$sql = 'SELECT * FROM customers WHERE id = ? ORDER BY id';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$cust = NULL;
		
		if ( $row = mysql_fetch_array($rs) ) {
			$cust = array(
				'id'			=> $row['id'] ,
				'full_name'		=> htmlspecialchars( $row['full_name'] ),
				'address'		=> htmlspecialchars( $row['address'] ),
				'email'			=> htmlspecialchars( $row['email'] ),
				'city_id'		=> $row['city_id'],
				'birthday'		=> convert_date_to_vn( $row['birthday'] ),
				'note'			=> htmlspecialchars( $row['note'] ),
				'phone'			=> htmlspecialchars( $row['phone'] )
			);	
		}
		
		mysql_free_result($rs);
		
		return $cust;
	}
	
	function findById_Brief($id) {
		$sql = 'SELECT P.id, full_name, birthday, phone, ' .
					'(SELECT name FROM cities AS Q WHERE Q.id = P.city_id) AS city_name ' .
					'FROM customers AS P ' .
					'WHERE P.id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$list_cust = array();
		$list_cust['cust_num'] = 0;
		
		if ( $row = mysql_fetch_array($rs) ) {
			$id = (int)$row['id'];
			$list_cust[$id] = array(
				'full_name'		=> htmlspecialchars( $row['full_name'] ),
				'birthday'		=> convert_date_to_vn( $row['birthday'] ),
				'city_name'		=> htmlspecialchars( $row['city_name'] ),
				'phone'			=> htmlspecialchars( $row['phone'] )
			);
			$list_cust['cust_num'] = 1;
		}
		
		mysql_free_result($rs);
		
		$list_cust['page_num'] = 1;
		
		return $list_cust;
	}
	
	function findBySearchInfo_Brief($s_info, $page) {
		$sql1 = 'SELECT P.id, full_name, birthday, phone, ' .
					'(SELECT name FROM cities AS Q WHERE Q.id = P.city_id) AS city_name ' .
					'FROM customers AS P ' .
					'WHERE 1 = 1 ';
		$sql2 = 'SELECT count(id) as cust_num FROM customers WHERE 1 = 1 ';
		
		$sql3 = '';
		
		if ( isset($s_info['s_full_name']) ) {
			$full_name = $this->ds->escape($s_info['s_full_name']);
			$sql3 .= "AND LOCATE('$full_name', full_name) > 0 ";
		}
		
		if ( isset($s_info['s_city']) ) {
			$city_id = (int)$s_info['s_city'];
			if ($city_id == 0) {
				$sql3 .= "AND city_id IS NULL ";
			} else {
				$sql3 .= "AND city_id = $city_id ";
			}
			
		}
		
		if ( isset($s_info['s_phone']) ) {
			$phone = $this->ds->escape($s_info['s_phone']);
			$sql3 .= "AND LOCATE('$phone', phone) > 0 ";
		}
		
		if ( isset($s_info['s_min_age']) ) {
			$min_age = (int)$s_info['s_min_age'];
			$cur_year = (int)date('Y');
			$max_year = $cur_year - $min_age;
			
			$sql3 .= "AND EXTRACT(YEAR FROM birthday) <= $max_year ";
		}
		
		if ( isset($s_info['s_max_age']) ) {
			$max_age = (int)$s_info['s_max_age'];
			$cur_year = (int)date('Y');
			$min_year = $cur_year - $max_age;
			
			$sql3 .= "AND EXTRACT(YEAR FROM birthday) >= $min_year ";
		}
		
		// Count all
		$rs = $this->ds->execute( $sql2 . $sql3 );
		$row = mysql_fetch_array( $rs );
		$cust_num = (int)$row['cust_num'];
		$page_num = (int)( ($cust_num - 1) / self::$PAGE_LENGTH + 1 );
		mysql_free_result($rs);
		
		$sql4 = '';
		if ($page > 0) {
			if ($page > $page_num) $page = $page_num;
			$offset = ($page - 1) * self::$PAGE_LENGTH;
			$sql4 .= 'LIMIT ' . $offset . ', ' . self::$PAGE_LENGTH;
		}
		
		$rs = $this->ds->execute( $sql1 . $sql3 . ' ORDER BY P.id ' . $sql4  );
		
		$list_cust = array();
		$list_cust['page_num'] = $page_num;
		$list_cust['cust_num'] = $cust_num;
		
		while ( $row = mysql_fetch_array($rs) ) {
			$id = (int)$row['id'];
			$list_cust[$id] = array(
				'full_name'		=> htmlspecialchars( $row['full_name'] ),
				'birthday'		=> convert_date_to_vn( $row['birthday'] ),
				'city_name'		=> htmlspecialchars( $row['city_name'] ),
				'phone'			=> htmlspecialchars( $row['phone'] )
			);
		}
		
		mysql_free_result($rs);
		
		return $list_cust;
	}
	
	function findByAll() {
		$sql = 'SELECT P.id, full_name, birthday, P.phone, ' .
					'(SELECT name FROM cities AS Q WHERE Q.id = P.city_id) AS city_name ' .
					'FROM customers AS P ' .
					'ORDER BY P.id';
		$rs = $this->ds->execute( $sql );
		
		$list_cust = array();
		
		while ( $row = mysql_fetch_array($rs) ) {
			$id = (int)$row['id'];
			$list_cust[$id] = array(
				'full_name'		=> htmlspecialchars( $row['full_name'] ),
				'birthday'		=> convert_date_to_vn( $row['birthday'] ),
				'city_name'		=> htmlspecialchars( $row['city_name'] ),
				'phone'			=> htmlspecialchars( $row['phone'] )
			);
		}
		
		mysql_free_result($rs);
		
		return $list_cust;
	}
	
	function delete($id) {
		$sql = 'DELETE FROM customers WHERE id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		$this->ds->execute( $ps->getSql() );
	}
	
	public function countAll() {
		$sql = 'SELECT count(id) as C FROM customers';
		$this->ds->execute( $sql );
		$count = 0;
		if ( $row = mysql_fetch_array($rs) ) {
			$count = (int)$row['C'];
		}
		mysql_free_result($rs);
		return $count;
	}
	
	public function countPageNum() {
		return ( ( $this->countAll() - 1 ) / self::$PAGE_LENGTH ) + 1;
	}
	
	public function updateCity($old_city_id, $new_city_id) {
		$sql = 'UPDATE customers SET city_id = ? WHERE city_id = ?';
		$ps = new PreparedStatement($sql);
		if ($new_city_id == 'NULL') {
			$ps->setNull(1);
		} else {
			$ps->setInt(1, $new_city_id);
		}
		$ps->setInt(2, $old_city_id);
		$this->ds->execute( $ps->getSql() );
	}
}
?>
