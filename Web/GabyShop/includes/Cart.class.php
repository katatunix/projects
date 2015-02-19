<?

class Cart {
	public $listItem;
	
	private $lastCheckoutTime;
	
	private static $INTERVAL = 10; // seconds
	
	public function Cart() {
		$this->listItem = array();
		$this->lastCheckoutTime = 0;
	}
	
	public function addItem($productId, $quantity) {
		if (array_key_exists($productId, $this->listItem)) {
			$this->listItem[$productId] += $quantity;
		} else {
			$this->listItem[$productId] = $quantity;
		}
	}
	
	public function removeItem($productId) {
		unset( $this->listItem[$productId] );
	}
	
	public function getQuantitySum() {
		$sum = 0;
		foreach ($this->listItem as $key => $value) {
			$sum += $value;
		}
		return $sum;
	}
	
	public function checkout() {
		$cur = time();
		
		if ($this->lastCheckoutTime == 0) {
			$this->lastCheckoutTime = $cur;
			return TRUE;
		}
		
		if ($cur - $this->lastCheckoutTime <= self::$INTERVAL) {
			$this->lastCheckoutTime = $cur;
			return FALSE;
		}
		
		$this->lastCheckoutTime = $cur;
		return TRUE;
	}
}

?>
