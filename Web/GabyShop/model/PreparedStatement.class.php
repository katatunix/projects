<?
class PreparedStatement {
	var $list;
	var $pieces;
	var $count;
	
	function PreparedStatement($sql) {
		$this->pieces = explode('?', $sql);
		$this->count = count($this->pieces);
		$this->list = array();
	}
	
	function setString($index, $value) {
		return $this->setValue($index, $value, 'string');
	}
	
	function setInt($index, $value) {
		return $this->setValue($index, $value, 'int');
	}
	
	function setNull($index) {
		if ($index < 1 || $index >= $this->count) {
			return FALSE;
		}
		
		$this->list[$index] = array('type' => 'null');
		return TRUE;
	}
	
	function setValue($index, $value, $type) {
		if ($index < 1 || $index >= $this->count) {
			return FALSE;
		}
		
		$this->list[$index] = array('value' => $value, 'type' => $type);	
		return TRUE;
	}
	
	function getSql() {
		$s = '';
		
		for ($i = 0; $i < $this->count - 1; $i++) {
			$s .= $this->pieces[$i];
			$j = $i + 1;
			
			if ( isset($this->list[$j]) ) {
				$type = $this->list[$j]['type'];
				if ($type == 'string') {
					$s .= "'" . $this->list[$j]['value'] . "'";
				} else if ($type == 'int') {
					$s .= $this->list[$j]['value'];
				} else if ($type == 'null') {
					$s .= 'NULL';
				}
			}
		}
		
		$s .= $this->pieces[$this->count - 1];
		
		return $s;
	}
}

?>
