<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');
__import('facade/ProductFacade');
__import('facade/PObject');

__import('webutils/WebUtils');

class MainWsServlet extends AbstractServlet {

	public function checkPermission($action) {
		return true;
	}

	public function index()	{
		$server = new SoapServer(__RES_DIR_PATH . '/public/luxury.wsdl');
		$server->setObject( $this );

		$server->handle();
	}

	public function getReport($key, $isRoom, $fromDate, $toDate) {
		$res = array();

		$xml = MiscUtils::loadXmlFile(__RES_DIR_PATH . '/private/ServiceKey.xml');
		if (!$xml || $key != $xml->key) {
			// failed
			return array(0/*isSuccess*/, $res);
		}

		// pass
		$p = ProductFacade::instance()->getProductReport($isRoom, $fromDate, $toDate);
		$products = isset($p->products) ? $p->products : array();

		foreach ($products as $prod) {
			$obj = new stdClass();
			$obj->productName	= $prod->name;
			$obj->unitPrice		= $prod->unitPrice;
			$obj->paidQty		= $prod->paidQty;

			$res[] = $obj;
		}

		return array(1/*isSuccess*/, $res);
	}
}

?>
