<?php

__import('business/OrderHome');
__import('business/ProductHome');

__import('miscutils/MiscUtils');

class OrderItem
{

	private $orderId;
	private $productId;
	private $quantity;
	private $order = NULL;
	private $hasLoadOrder = false;
	private $product = NULL;
	private $hasLoadProduct = false;

	public function __construct($orderId, $productId)
	{
		$this->orderId = $orderId;
		$this->productId = $productId;
	}

	public function setBasicData($quantity)
	{
		$this->quantity = $quantity;
	}

	//---------------------------------------------------------------------------------
	public function getOrder()
	{
		if (!$this->hasLoadOrder)
		{
			$this->order = OrderHome::instance()->findById($this->orderId);
			$this->hasLoadOrder = true;
		}

		return $this->order;
	}

	public function hasLoadOrder()
	{
		return $this->hasLoadOrder;
	}

	public function clearOrder()
	{
		$this->order = NULL;
		$this->hasLoadOrder = false;
	}

	//---------------------------------------------------------------------------------
	public function getProduct()
	{
		if (!$this->hasLoadProduct)
		{
			$this->product = ProductHome::instance()->findById($this->productId);
			$this->hasLoadProduct = true;
		}

		return $this->product;
	}

	public function hasLoadProduct()
	{
		return $this->hasLoadProduct;
	}

	public function clearProduct()
	{
		$this->product = NULL;
		$this->hasLoadProduct = false;
	}

	//---------------------------------------------------------------------------------
	public function clearAllExternals()
	{
		$this->clearOrder();
		$this->clearProduct();
	}

	//---------------------------------------------------------------------------------
	public function getId()
	{
		return $this->orderId . '_' . $this->productId;
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	public function getProductId()
	{
		return $this->productId;
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	//----------------------------------------------------------------------------------
	public function getConsumedQtyInDateRange($fromDate, $toDate)
	{
		$order = $this->getOrder();
		if (!$order->isPaid())
			return 0;

		$isRoom = $this->getProduct()->isRoom();

		$start = MiscUtils::removeTime($order->getConsumedDatetime());
		$qty = 0;

		if ($isRoom)
		{
			$dur = $this->quantity - 1;
			assert($dur >= 0);
			$end = MiscUtils::addDays($start, $dur);

			$left = $fromDate ? MiscUtils::max($fromDate, $start) : $start;
			$right = $toDate ? MiscUtils::min($toDate, $end) : $end;

			$qty = $left > $right ? 0 : MiscUtils::countDays($left, $right);
		}
		else
		{
			$b1 = $b2 = true;
			if ($fromDate)
			{
				$b1 = $fromDate <= $start;
			}
			if ($toDate)
			{
				$b2 = $start <= $toDate;
			}
			if ($b1 && $b2)
			{
				$qty = $this->quantity;
			}
		}

		return $qty;
	}

	public function getPrice()
	{
		return $this->quantity * $this->getProduct()->getUnitPrice();
	}

	public function isRoom()
	{
		return $this->getProduct()->isRoom();
	}

}

?>
