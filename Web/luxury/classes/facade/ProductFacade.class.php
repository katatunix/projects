<?php

__import('facade/PObjectConverter');
__import('business/ProductHome');
__import('business/Category');

class ProductFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new ProductFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findById($id) {
		$cv = new PObjectConverter();
		$obj = ProductHome::instance()->findById($id);
		if ($obj) {
			$obj->clearAllExternals();
			$cv->convertProduct($obj);
		}

		return $cv->getResult();
	}

	public function findAllRooms() {
		return $this->findByCategory(true);
	}
	
	public function findAllFoodbs() {
		return $this->findByCategory(false);
	}

	public function getProductReport($isRoom, $fromDate, $toDate) {
		$list = $isRoom ? ProductHome::instance()->findAllRooms() : ProductHome::instance()->findAllFoodbs();
		$cv = new PObjectConverter();

		foreach ($list as $obj) {
			$obj->clearAllExternals();
			$p = $cv->convertProduct($obj);

			$p->paidQty = $obj->getPaidQuantity($fromDate, $toDate);
		}

		return $cv->getResult();
	}

	//=======================================================================================
	private function findByCategory($isRoom) {
		$list = $isRoom ? ProductHome::instance()->findAllRooms() : ProductHome::instance()->findAllFoodbs();

		$cv = new PObjectConverter();
		foreach ($list as $obj) {
			$obj->clearAllExternals();
			$cv->convertProduct($obj);
		}

		return $cv->getResult();
	}
	
}

?>
