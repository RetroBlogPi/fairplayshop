<?php

if (isset($smarty))
{
	$smarty->assign(array(
		'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn'),
		'HOOK_FOOTER' => Module::hookExec('footer'),
		'content_only' => intval(Tools::getValue('content_only')),
		'foot_addr' => Configuration::get('PS_SHOP_ADDR'),
		'foot_code' => Configuration::get('PS_SHOP_CODE'),
		'foot_city' => Configuration::get('PS_SHOP_CITY'),
		'foot_mobil' => Configuration::get('PS_SHOP_MOBIL'),
		'foot_email' => Configuration::get('PS_SHOP_EMAIL'),		    
    ));
		
		
		
	$smarty->display(_PS_THEME_DIR_.'footer.tpl');
}

?>