<?php

class PreparedStatement
{
	private $listArgs = array();
	private $sqlString;
	
	public function __construct($sql = NULL) {
		$this->sqlString = $sql;
	}
	
	public function setSql($sql) {
		$this->sqlString = $sql;
	}
	
	public function setValue($index, $value) {
		if ($index < 1) return false;
		
		$this->listArgs[$index] = $value;
		
		return true;
	}
	
	public function getSql() {
		if (!$this->sqlString) return NULL;
		
		$pieces = explode('?', $this->sqlString);
		$count = count($pieces);
		
		$s = '';
		
		for ($i = 0; $i < $count - 1; $i++) {
			$s .= $pieces[$i];
			$j = $i + 1;
			
			$value = $this->listArgs[$j];
			//if ( isset($value) )
			{
				if ( is_null($value) ) {
					$s .= 'NULL';
				} else if ( is_array($value) ) {
					$len = count( $value );
					if ($len > 0) {
						for ($k = 0; $k < $len - 1; $k++) {
							$s .= "'" . DataSource::instance()->escape($value[$k]) . "', ";
						}
						$s .= "'" . DataSource::instance()->escape($value[$len - 1]) . "'";
					}
				} else {
					$s .= "'" . DataSource::instance()->escape($value) . "'";
				}
			}
		}
		
		$s .= $pieces[$count - 1];
		
		return $s;
	}
}

?>
