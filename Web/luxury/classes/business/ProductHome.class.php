<?php

__import('business/AbstractHome');

__import('business/Product');

class ProductHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new ProductHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function create($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new Product($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($id) {
		if (!$id) return NULL;
		
		if ($obj = $this->findObjectInPool($id)) return $obj;
		
		$ps = new PreparedStatement(
			'SELECT * FROM `product` WHERE `id` = ?'
		);
		$ps->setValue( 1, $id );
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$obj = NULL;
		
		if ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $id );
			$obj->setBasicData(
				$row['name'],
				$row['category'],
				$row['unitPrice']
			);
		}
		
		mysqli_free_result($rs);
		
		return $obj;
	}

	public function findAllRooms() {
		return $this->findByCategories(array(Category::ROOM));
	}

	public function findAllFoodbs() {
		return $this->findByCategories(array(Category::FOOD, Category::BEVERAGE));
	}

	//====================================================================================
	private function findByCategories($categories) {
		if ( !is_null($categories) && is_array($categories) ) {
			$ps = new PreparedStatement('SELECT * FROM `product` WHERE `category` IN (?) ORDER BY `name`');
			$ps->setValue( 1, $categories );
		} else {
			$ps = new PreparedStatement('SELECT * FROM `product` ORDER BY `name`');
		}
		
		$rs = DataSource::instance()->execute( $ps->getSql() );
		
		$list = array();
		
		while ( $row = mysqli_fetch_array($rs) ) {
			$obj = $this->create( $row['id'] );
			$obj->setBasicData(
				$row['name'],
				$row['category'],
				$row['unitPrice']
			);
			$list[] = $obj;
		}
		
		mysqli_free_result($rs);
		
		return $list;
	}
	
}

?>
