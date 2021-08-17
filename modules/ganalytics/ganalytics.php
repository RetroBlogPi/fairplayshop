<?php

class GAnalytics extends Module
{	
	function __construct()
	{
	 	$this->name = 'ganalytics';
	 	$this->tab = 'Stats';
	 	$this->version = '1.2';
        $this->displayName = 'Google Analytics';
		
	 	parent::__construct();
		
		if (!Configuration::get('GANALYTICS_ID'))
			$this->warning = $this->l('You have not yet set your Google Analytics ID');
        $this->description = $this->l('Integrate the Google Analytics script into your shop');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
	}
	
    function install()
    {
        if (!parent::install() OR !$this->registerHook('footer') OR !$this->registerHook('orderConfirmation'))
			return false;
		return true;
    }
	
	function uninstall()
	{
		if (!Configuration::deleteByName('GANALYTICS_ID') OR !parent::uninstall())
			return false;
		return true;
	}
	
	public function getContent()
	{
		$output = '<h2>Google Analytics</h2>';
		if (Tools::isSubmit('submitGAnalytics') AND ($gai = Tools::getValue('ganalytics_id')))
		{
			Configuration::updateValue('GANALYTICS_ID', $gai);
			$output .= '
			<div class="conf confirm">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Settings updated').'
			</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset class="width2">
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Your username').'</label>
				<div class="margin-form">
					<input type="text" name="ganalytics_id" value="'.Tools::getValue('ganalytics_id', Configuration::get('GANALYTICS_ID')).'" />
					<p class="clear">'.$this->l('Example:').' UA-1234567-1</p>
				</div>
				<center><input type="submit" name="submitGAnalytics" value="'.$this->l('Update ID').'" class="button" /></center>
			</fieldset>
		</form>';
		
		return $output;
	}
	
	function hookFooter($params)
	{
		global $step, $protocol_content;

		$output = '
		<script type="text/javascript">
			document.write(unescape("%3Cscript src=\''.$protocol_content.'www.google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try
		{
			var pageTracker = _gat._getTracker("'.Configuration::get('GANALYTICS_ID').'");
			pageTracker._trackPageview();
			'.(strpos($_SERVER['REQUEST_URI'], __PS_BASE_URI__.'order.php') === 0 ? 'pageTracker._trackPageview("/order/step'.intval($step).'.html");' : '').'
		}
		catch(err)
			{}
		</script>';
		return $output;
	}
	
	function hookOrderConfirmation($params)
	{
		global $protocol_content;

		$order = $params['objOrder'];
		if (Validate::isLoadedObject($order))
		{
			$deliveryAddress = new Address(intval($order->id_address_delivery));
			
			/* Order general informations */
			$output = '
			<script src="'.$protocol_content.'www.google-analytics.com/ga.js" type="text/javascript"></script>
	
			<script type="text/javascript">
			  var pageTracker = _gat._getTracker("'.Configuration::get('GANALYTICS_ID').'");
			  pageTracker._initData();
			
			  pageTracker._addTrans(
				"'.intval($order->id).'",               	// Order ID
				"PrestaShop",      							// Affiliation
				"'.floatval($order->total_paid).'",       	// Total
				"0",               							// Tax
				"'.floatval($order->total_shipping).'",     // Shipping
				"'.$deliveryAddress->city.'",           	// City
				"",         								// State
				"'.$deliveryAddress->country.'"             // Country
			  );';

			/* Product informations */
			$products = $order->getProducts();
			foreach ($products AS $product)
			{
				$output .= '
				pageTracker._addItem(
					"'.intval($order->id).'",						// Order ID
					"'.$product['product_reference'].'",			// SKU
					"'.$product['product_name'].'",					// Product Name 
					"",												// Category
					"'.floatval($product['product_price_wt']).'",		// Price
					"'.intval($product['product_quantity']).'"		// Quantity
				);';
			}
			
			$output .= '
			  pageTracker._trackTrans();
			</script>';
			
			return $output;
		}
	}
}
