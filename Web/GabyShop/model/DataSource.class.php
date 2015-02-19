<?

class DataSource {

	// private static $host		= 'localhost';
	// private static $username	= 'gabyshop_root';
	// private static $password	= 'g1gabyt3?ac';
	// private static $database	= 'gabyshop_gabyshop';
	
	private static $host		= 'localhost';
	private static $username	= 'root';
	private static $password	= 'root';
	private static $database	= 'gabyshop';
	
	private static $instance = NULL;
	
	private $link;
	
	private function DataSource() {
		$this->link = mysql_connect(self::$host, self::$username, self::$password)
			or die('Could not connect database');
			
		mysql_select_db(self::$database, $this->link)
			or die('Error when select the database');
			
		mysql_query("SET NAMES 'utf8'");
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new DataSource();	
		}
		return self::$instance;
	}
	
	public static function closeCon() {
		if (self::$instance) {
			self::$instance->closeSql();
			self::$instance = NULL;
		}
	}
	
	public function escape($str) {
		return mysql_real_escape_string( $str, $this->link );
	}
	
	public function execute($sql) {
		return mysql_query($sql, $this->link);
	}
	
	public function getLastId() {
		return mysql_insert_id($this->link);
	}
	
	public function getLastError() {
		return mysql_error($this->link);
	}
	
	public function closeSql() {
		mysql_close($this->link);
	}
	
}

?>
