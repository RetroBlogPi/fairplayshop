<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');


// VE.cz - nakup bez registrace
/*
if($cookie->tmpuser==1)
  $cookie->mylogout();			
*/  

// VE.cz - nakup bez registrace
if (!$cookie->isLogged() || $cookie->tmpuser==1)
  $cookie->mylogout();

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=my-account.php');

include(dirname(__FILE__).'/header.php');
$smarty->assign(array(
	'voucherAllowed' => intval(Configuration::get('PS_VOUCHERS')),
	'returnAllowed' => intval(Configuration::get('PS_ORDER_RETURN')),
	'HOOK_CUSTOMER_ACCOUNT' => Module::hookExec('customerAccount')));

$smarty->display(_PS_THEME_DIR_.'my-account.tpl');

include(dirname(__FILE__).'/footer.php');

?>