<?php

__import('business/AbstractHome');

__import('business/Order');

class OrderHome extends AbstractHome {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new OrderHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================

	private function create($id)
	{
		$obj = $this->findObjectInPool($id);
		if (!$obj)
		{
			$obj = new Order($id);
		}
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	
	//====================================================================================

	public function findById($id)
	{
		if (!$id) return NULL;

		if ($obj = $this->findObjectInPool($id)) return $obj;

		$ps = new PreparedStatement('SELECT * FROM `order` WHERE `id` = ?');
		$ps->setValue( 1, $id );

		$rs = DataSource::instance()->execute( $ps->getSql() );

		$obj = NULL;

		if ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $id );

			$obj->setBasicData(
				$row['createdDatetime'],
				$row['customer'],
				$row['consumedDatetime'],
				$row['status'],
				$row['accountId']
			);
		}

		mysqli_free_result($rs);

		return $obj;
	}

	public function findByAccountId($accountId, $fromDate = NULL, $toDate = NULL)
	{
		$list = array();
		if (!$accountId) return $list;

		$index = 1;
		$ps = new PreparedStatement();

		$sql = 'SELECT * FROM `order` WHERE `accountId` = ?';
		$ps->setValue($index++, $accountId);

		if ($fromDate) {
			$sql .= ' AND `createdDatetime` >= ?';
			$ps->setValue($index++, $fromDate);
		}
		if ($toDate) {
			$sql .= ' AND `createdDatetime` <= ?';
			$ps->setValue($index++, $toDate . ' 23:59');
		}

		$sql .= ' ORDER BY `createdDatetime` DESC';

		$ps->setSql($sql);

		$rs = DataSource::instance()->execute( $ps->getSql() );

		while ( $row = mysqli_fetch_array($rs) )
		{
			$obj = $this->create( $row['id'] );

			$obj->setBasicData(
				$row['createdDatetime'],
				$row['customer'],
				$row['consumedDatetime'],
				$row['status'],
				$accountId
			);

			$list[] = $obj;
		}

		mysqli_free_result($rs);

		return $list;
	}

	public function insert($customer, $consumedDate, $consumedHour, $consumedMinute,
						   $statusString, $accountId, $pidList, $qtyList)
	{
		return $this->insertOrUpdate(false, $customer, $consumedDate, $consumedHour, $consumedMinute,
			$statusString, $accountId, 0, $pidList, $qtyList);
	}

	public function update($orderId, $customer, $consumedDate, $consumedHour, $consumedMinute,
						   $statusString, $pidList, $qtyList)
	{
		return $this->insertOrUpdate(true, $customer, $consumedDate, $consumedHour, $consumedMinute,
			$statusString, 0, $orderId, $pidList, $qtyList);
	}

	private function insertOrUpdate($isUpdate, $customer, $consumedDate, $consumedHour, $consumedMinute,
						   $statusString, $accountId, $orderId, $pidList, $qtyList)
	{
		$res = array();
		$res[0] = false;
		
		$SP = 'Order_InsertOrUpdate';
		DataSource::instance()->setSavePoint($SP);

		if ($isUpdate)
		{
			$order = $this->findById($orderId);
			if (!$order)
			{
				$res[1] = 'The order is not found.';
				$res[2] = true;
				goto my_end;
			}
			if ($order->isPaid())
			{
				$res[1] = 'Cannot edit the order because it was paid already.';
				goto my_end;
			}
		}

		if (!$customer) $customer = NULL;

		if (!$consumedDate)
		{
			$consumedDatetime = MiscUtils::getCurrentDatetime();
			$consumedDate = MiscUtils::removeTime($consumedDatetime);
		}
		else
		{
			try
			{
				$dt = DateTime::createFromFormat('Y-m-d', $consumedDate);
			}
			catch (Exception $ex)
			{
				$res[1] = 'Invalid datetime format.';
				goto my_end;
			}

			//
			$ok = false;
			if (!$consumedHour)
			{
				$consumedHour = 0;
			}
			if (is_numeric($consumedHour))
			{
				$tmp = (int)$consumedHour;
				if ($tmp >= 0 && $tmp <= 23)
				{
					$ok = true;
				}
			}
			if (!$ok)
			{
				$res[1] = 'Invalid datetime format.';
				goto my_end;
			}

			//
			$ok = false;
			if (!$consumedMinute)
			{
				$consumedMinute = 0;
			}
			if (is_numeric($consumedMinute)) {
				$tmp = (int)$consumedMinute;
				if ($tmp >= 0 && $tmp <= 59) {
					$ok = true;
				}
			}
			if (!$ok) {
				$res[1] = 'Invalid datetime format.';
				goto my_end;
			}

			//
			$consumedDatetime = "$consumedDate $consumedHour:$consumedMinute";
		}

		$status = OrderStatus::toEnum($statusString);
		if ($status === false)
		{
			$res[1] = 'Invalid status.';
			goto my_end;
		}

		if (!$isUpdate)
		{
			if (!AccountHome::instance()->findById($accountId))
			{
				$res[1] = 'The account is not found.';
				goto my_end;
			}
		}

		// pass validation
		if ($isUpdate)
		{
			$sql = 'UPDATE `order` SET `customer` = ?, `consumedDatetime` = ?, `status` = ?
						WHERE `id` = ?';
			$ps = new PreparedStatement($sql);
			$ps->setValue(1, $customer);
			$ps->setValue(2, $consumedDatetime);
			$ps->setValue(3, $status);
			$ps->setValue(4, $orderId);
		}
		else
		{
			$sql = 'INSERT `order`(`customer`, `createdDatetime`, `consumedDatetime`, `status`, `accountId`)
						VALUES(?, ?, ?, ?, ?)';
			$ps = new PreparedStatement($sql);
			$ps->setValue(1, $customer);
			$ps->setValue(2, MiscUtils::getCurrentDatetime());
			$ps->setValue(3, $consumedDatetime);
			$ps->setValue(4, $status);
			$ps->setValue(5, $accountId);
		}

		if ( DataSource::instance()->execute( $ps->getSql() ) )
		{
			if (!$isUpdate)
			{
				$orderId = DataSource::instance()->getLastId();
			}
			// Now insert/replace the items
			$res = OrderItemHome::instance()->deleteAndInsertForOrder($orderId, $pidList, $qtyList);
			if ( !$res[0] ) goto my_end;
			
			// PASS ALL
			$res[0] = true;
			if ($isUpdate)
			{
				$this->removeObjectFromPool($orderId);
			}
			else
			{
				$res[1] = $orderId;
			}
		}
		else
		{
			$res[1] = 'Something went wrong.';
		}
		
		$res[0] = true;
		
		my_end:
			
		if ($res[0])
		{
			
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
