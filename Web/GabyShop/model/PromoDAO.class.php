<?

__autoload('DataSource');
__autoload('PreparedStatement');

class PromoDAO {

	private $ds;
	
	public function PromoDAO($ds) {
		$this->ds = $ds;
	}
	
	public function findByAll() {
		$sql = "SELECT id, seo_url, subject, promo_date FROM promos ORDER BY promo_date DESC";
		$rs = $this->ds->execute( $sql );
		
		$list_promo = array();
		while ($row = mysql_fetch_array($rs)) {
			$list_promo[$row['id']] = array(
				'seo_url'			=> htmlspecialchars( $row['seo_url'] ),
				'subject'			=> htmlspecialchars( $row['subject'] ),
				'promo_date'		=> convert_date_to_vn( $row['promo_date'] )
			);
		}
		
		mysql_free_result($rs);
		return $list_promo;
	}
	
	public function findById($id) {
		$sql = "SELECT * FROM promos WHERE id = ?";
		$ps = new PreparedStatement( $sql );
		$ps->setInt(1, $id);
		$rs = $this->ds->execute( $ps->getSql() );
		
		$promo = NULL;
		if ($row = mysql_fetch_array($rs)) {
			$promo = array(
				'id'			=> $id,
				'seo_url'		=> htmlspecialchars( $row['seo_url'] ),
				'subject'		=> htmlspecialchars( $row['subject'] ),
				'promo_date'	=> convert_date_to_vn( $row['promo_date'] ),
				'content'		=> $row['content']
			);
		}
		
		mysql_free_result($rs);
		
		return $promo;
	}
	
	public function findBySeoUrl($seo_url) {
		$sql = 'SELECT * FROM promos WHERE seo_url = ?';
		$ps = new PreparedStatement($sql);
		$ps->setString(1, $this->ds->escape($seo_url) );
		$rs = $this->ds->execute( $ps->getSql() );
		
		$promo = NULL;
		
		if ( $row = mysql_fetch_array($rs) ) {
			$promo = array(
				'id'			=> $row['id'],
				'subject'		=> htmlspecialchars( $row['subject'] ),
				'promo_date'	=> convert_date_to_vn( $row['promo_date'] ),
				'content'		=> $row['content']
			);
		}
		
		mysql_free_result($rs);
		
		return $promo;
	}
	
	public function findNewestSeoUrl() {
		$sql = 'SELECT MAX(seo_url) AS seo_url FROM promos';
		
		$rs = $this->ds->execute( $sql );
		
		$seo_url = NULL;
		if ( $row = mysql_fetch_array($rs) ) {
			$seo_url = $row['seo_url'];
		}
		
		mysql_free_result($rs);
		
		return $seo_url;
	}
	
	public function findNewestSubject() {
		$sql = 'SELECT subject FROM promos ORDER BY promo_date DESC LIMIT 0, 1';
		
		$rs = $this->ds->execute( $sql );
		
		$subject = NULL;
		if ( $row = mysql_fetch_array($rs) ) {
			$subject = htmlspecialchars( $row['subject'] );
		}
		
		mysql_free_result($rs);
		
		return $subject;
	}
	
	public function findNewestList() {
		$sql = 'SELECT seo_url, subject, promo_date FROM promos '.
					'ORDER BY promo_date DESC LIMIT 0, 10';
		
		$rs = $this->ds->execute( $sql );
		
		$list_promo = array();
		while ( $row = mysql_fetch_array($rs) ) {
			$list_promo[] = array(
				'seo_url'		=> $row['seo_url'],
				'promo_date'	=> convert_date_to_vn( $row['promo_date'] ),
				'subject'		=> htmlspecialchars( $row['subject'] )
			);
		}
		
		mysql_free_result($rs);
		
		return $list_promo;
	}
	
	public function findRelated($promo_date, $seo_url) {
		$sql = 'SELECT seo_url, subject, promo_date FROM promos WHERE promo_date <= ? AND seo_url <> ? ' .
					'ORDER BY promo_date DESC LIMIT 0, 10';
		$ps = new PreparedStatement($sql);
		
		$ps->setString(1, $this->ds->escape(convert_date_to_us($promo_date)) );
		$ps->setString(2, $this->ds->escape($seo_url) );
		
		$rs = $this->ds->execute( $ps->getSql() );
		
		$list_promo = array();
		while ( $row = mysql_fetch_array($rs) ) {
			$list_promo[] = array(
				'seo_url'		=> $row['seo_url'],
				'promo_date'	=> convert_date_to_vn( $row['promo_date'] ),
				'subject'		=> htmlspecialchars( $row['subject'] )
			);
		}
		
		mysql_free_result($rs);
		
		return $list_promo;
	}
	
	public function update($seo_url, $subject, $promo_date, $content, $id) {
		$sql = 'UPDATE promos SET seo_url=?, subject=?, promo_date=?, content=? WHERE id=?';
		$ps = new PreparedStatement( $sql );
		
		$ps->setString( 1, $this->ds->escape($seo_url) );
		$ps->setString( 2, $this->ds->escape($subject) );
		$ps->setString( 3, $this->ds->escape($promo_date) );
		$ps->setString( 4, $this->ds->escape($content) );
		
		$ps->setInt( 5, $id );
		
		return $this->ds->execute( $ps->getSql() );
	}
	
	public function insert($seo_url, $subject, $promo_date, $content) {
		$sql = 'INSERT promos(seo_url, subject, promo_date, content) VALUES(?, ?, ?, ?)';
		$ps = new PreparedStatement( $sql );
		
		$ps->setString( 1, $this->ds->escape($seo_url) );
		$ps->setString( 2, $this->ds->escape($subject) );
		$ps->setString( 3, $this->ds->escape($promo_date) );
		$ps->setString( 4, $this->ds->escape($content) );
		
		if ( $this->ds->execute( $ps->getSql() ) ) {
			return $this->ds->getLastId();
		} else {
			return -1;
		}
	}
	
	public function isExistedSeoUrl($seo_url) {
		$sql = "SELECT id FROM promos WHERE seo_url = ?";
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
	
	public function isExistedSeoUrl_Except($seo_url, $id) {
		$sql = "SELECT id FROM promos WHERE seo_url = ? AND id <> ?";
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
	
	function delete($id) {
		$sql = 'DELETE FROM promos WHERE id = ?';
		$ps = new PreparedStatement($sql);
		$ps->setInt(1, $id);
		return $this->ds->execute( $ps->getSql() ) ? TRUE : FALSE;
	}
	
}

?>
