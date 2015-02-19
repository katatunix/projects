<?php

__import('business/AbstractHome');

__import('business/OrderItem');

class OrderItemHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new OrderItemHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================

	private function create($orderId, $productId) {
		$id = $orderId . '_' . $productId;
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new OrderItem($orderId, $productId);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================

	public function findById($orderId, $productId) {
		if (!$orderId || !$productId) return NULL;

		$id = $orderId . '_' . $productId;

		if ($obj = $this->findObjectInPool($id)) return $obj;

		$ps = new PreparedStatement('SELECT * FROM `orderitem` WHERE `orderId` = ? AND `productId` = ?');
		$ps->setValue( 1, $orderId );
		$ps->setValue( 2, $productId );

		$rs = DataSource::instance()->execute( $ps->getSql() );

		$obj = NULL;

		if ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $orderId, $productId );

			$obj->setBasicData(
				$row['quantity']
			);
		}

		mysqli_free_result($rs);

		return $obj;
	}

	public function findByOrderId($orderId) {
		$list = array();
		if (!$orderId) return $list;

		$ps = new PreparedStatement('SELECT * FROM `orderitem` WHERE `orderId` = ?');
		$ps->setValue(1, $orderId);

		$rs = DataSource::instance()->execute( $ps->getSql() );

		while ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $orderId, $row['productId'] );
			$obj->setBasicData(
				$row['quantity']
			);

			$list[] = $obj;
		}

		mysqli_free_result($rs);
		return $list;
	}

	public function findByProductId($productId) {
		$list = array();
		if (!$productId) return $list;

		$ps = new PreparedStatement('SELECT * FROM `orderitem` WHERE `productId` = ?');
		$ps->setValue(1, $productId);

		$rs = DataSource::instance()->execute( $ps->getSql() );

		while ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $row['orderId'], $productId );
			$obj->setBasicData(
				$row['quantity']
			);

			$list[] = $obj;
		}

		mysqli_free_result($rs);
		return $list;
	}

	public function deleteAndInsertForOrder($orderId, $pidList, $qtyList)
	{
		$res = array();
		$res[0] = false;
		
		$SP = 'OrderItemSavePoint';
		DataSource::instance()->setSavePoint($SP);

		if (!$pidList || !$qtyList || count($pidList) != count($qtyList))
		{
			$res[1] = 'The order items list must not be empty.';
			goto my_end;
		}

		$order = OrderHome::instance()->findById($orderId);
		if (!$order)
		{
			$res[1] = 'The order must be existed.';
			goto my_end;
		}

		$count1 = count($pidList);
		for ($i = 0; $i < $count1; $i++)
		{
			$qty = $qtyList[$i];
			if (!$qty || $qty <= 0)
			{
				$res[1] = 'The quantities must be positive integers.';
				goto my_end;
			}

			$pid = $pidList[$i];
			$prod = ProductHome::instance()->findById($pid);
			if (!$prod)
			{
				$res[1] = "The product id [$pid] must be existed.";
				goto my_end;
			}

			$consumedDate = MiscUtils::removeTime( $order->getConsumedDatetime() );
			if ( !$prod->isFree($consumedDate, $qty, $orderId) )
			{
				$res[1] = 'The occupancy of room [' . $prod->getName() . '] is overlapped.';
				goto my_end;
			}
		}

		// Delete
		$ps1 = new PreparedStatement('DELETE FROM `orderitem` WHERE `orderId` = ?');
		$ps1->setValue(1, $orderId);
		if ( !DataSource::instance()->execute( $ps1->getSql() ) )
		{
			$res[1] = "Could not delete order items for the order $orderId";
			goto my_end;
		}

		// Insert
		$sql = 'INSERT `orderitem`(`orderId`, `productId`, `quantity`) VALUES(?, ?, ?)';
		$ps2 = new PreparedStatement($sql);
		$count2 = count($pidList);
		for ($i = 0; $i < $count2; $i++)
		{
			$ps2->setValue(1, $orderId);
			$ps2->setValue(2, $pidList[$i]);
			$ps2->setValue(3, $qtyList[$i]);

			if ( !DataSource::instance()->execute( $ps2->getSql() ) )
			{
				$res[1] = "Could not insert the order item for the order $orderId, pid $pidList[$i], qty $qtyList[$i]";
				goto my_end;
			}
		}
		
		$res[0] = true;
		
		my_end:
		
		if ($res[0])
		{
			foreach ($this->pool as $id => $obj)
			{
				if ($obj->getOrderId() == $orderId)
				{
					$this->removeObjectFromPool($id);
				}
			}
		}
		else
		{
			DataSource::instance()->rollbackToSavePoint($SP);
		}
		
		DataSource::instance()->releaseSavePoint($SP);
		
		return $res;
	}

}

?>
