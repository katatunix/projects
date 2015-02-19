<?php

__import('dbconnector/DataSource');
__import('dbconnector/PreparedStatement');

abstract class AbstractHome {
	protected $pool = array();
	
	protected function findObjectInPool($id) {
		if (!$id) return NULL;
		
		if ( isset($this->pool[$id]) ) {
			return $this->pool[$id];
		}
		return NULL;
	}
	
	protected function addObjectToPool($id, $obj) {
		if (!$id) return FALSE;
		
		if (is_null($obj)) return FALSE;
		
		$this->pool[$id] = $obj;
		return TRUE;
	}
}

?>
