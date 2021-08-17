<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');

if (!$cookie->isLogged())
	Tools::redirect('authentication.php?back=history.php');

/* JS files call */
$js_files = array(__PS_BASE_URI__.'js/jquery/jquery.scrollto.js', _THEME_JS_DIR_.'history.js');

if ($orders_all = Order::getCustomerOrders(intval($cookie->id_customer)))
	foreach ($orders_all AS &$order)
	{
		$myOrder = new Order(intval($order['id_order']));
		if (Validate::isLoadedObject($myOrder))
			$order['virtual'] = $myOrder->isVirtual(false);
		
		# security - don't allow older orders for not logged-in customers
		if (!isset($cookie->orders_since) || (isset($cookie->orders_since) && ($order['date_upd'] > $cookie->orders_since)))
		  $orders[] = $order;
	}

include(dirname(__FILE__).'/header.php');
$smarty->assign(array(
	'orders' => $orders,
	'invoiceAllowed' => intval(Configuration::get('PS_INVOICE'))));
$smarty->display(_PS_THEME_DIR_.'history.tpl');
include(dirname(__FILE__).'/footer.php');

?>
