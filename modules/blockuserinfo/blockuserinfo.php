<?php

class BlockUserInfo extends Module
{
	function __construct()
	{
		$this->name = 'blockuserinfo';
		$this->tab = 'Blocks';
		$this->version = 0.1;

		parent::__construct();
		
		$this->displayName = $this->l('User info block');
		$this->description = $this->l('Adds a block that displays information about the customer');
	}

	function install()
	{
		if (!parent::install())
			return false;
		if (!$this->registerHook('top'))
			return false;
		return true;
	}

	/**
	* Returns module content for header
	*
	* @param array $params Parameters
	* @return string Content
	*/
	function hookTop($params)
	{
		global $smarty, $cookie, $cart;
		$cart_qties=0;
		$tmp=Db::getInstance()->executeS('SELECT quantity,pocet FROM '._DB_PREFIX_.'cart_product WHERE id_cart='.intval($cart->id));
		foreach($tmp as $t) {
		  if($t['pocet']>0)
        $cart_qties+=$t['pocet'];
      else
        $cart_qties+=$t['quantity'];
    }
		$smarty->assign(array(
			'cart' => $cart,
			'cart_qties' => $cart_qties,//$cart->nbProducts(),			
			'customerName' => (($cookie->logged and $cookie->tmpuser==0) ? ($cookie->customer_firstname.' '.$cookie->customer_lastname) : false),
			'firstName' => (($cookie->logged and $cookie->tmpuser==0) ? $cookie->customer_firstname : false),
			'lastName' => (($cookie->logged and $cookie->tmpuser==0) ? $cookie->customer_lastname : false),
			'logged' => (($cookie->isLogged() and $cookie->tmpuser==0) ? 1 : 0)
		));
		return $this->display(__FILE__, 'blockuserinfo.tpl');
	}
}

?>
