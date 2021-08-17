<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/cashondeliveryosobni.php');

$confirm = Tools::getValue('confirm');

/* Validate order */
if ($confirm)
{
 	$cashOnDelivery = new CashOnDeliveryOsobni();
	$total = floatval(number_format($cart->getOrderTotal(true, 3), 2, '.', ''));
	$cashOnDelivery->validateOrderCOD(intval($cart->id), _PS_OS_PREPARATION_, $total, $cashOnDelivery->displayName);
	$order = new Order(intval($cashOnDelivery->currentOrder));
	Tools::redirectLink(__PS_BASE_URI__.'order-confirmation.php?id_cart='.intval($cart->id).'&id_module='.intval($cashOnDelivery->id).'&key='.$order->secure_key);
}
else
{
/* or ask for confirmation */ 
	$cashOnDelivery = new CashOnDeliveryOsobni();	
	
	$CODfee = $cashOnDelivery->getCostValidated($cart);
	$cartcost = $cart->getOrderTotal(true, 3);
	$total = $CODfee + $cartcost;
	
	$smarty->assign(array(
		'currency' => new Currency(intval($cart->id_currency)),
		'total' => number_format(floatval( $total ), 2, '.', ''),
		'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/cashondeliveryosobni/'
	));

    $smarty->assign('this_path', __PS_BASE_URI__.'modules/cashondeliveryosobni/');
    echo Module::display(__FILE__, 'validation.tpl');
}

include(dirname(__FILE__).'/../../footer.php');
?>