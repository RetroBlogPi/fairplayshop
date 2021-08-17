<?php

class OnePageCheckout extends Module
{
	function __construct()
	{
		$this->name = 'onepagecheckout';
		$this->tab = 'Vlastní Eshop Cz - Modules';
		$this->version = '1.0.4';

		parent::__construct(); // The parent construct is required for translations

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('One Page Checkout');
		$this->description = $this->l('Simplifies checkout process.');
	}

	function install()
	{

//		echo "<div style=\"font-weight: bold; color: red;\">[ERROR] $error_msg</div>";
		require(_PS_MODULE_DIR_."onepagecheckout/install_files.php");
		if ($ret == false)
	 	  return false;

		if (!parent::install() 
			OR Configuration::updateValue('OPC_FIELD_PICKUP_CRR', 'Pick up!') == false
			OR Configuration::updateValue('OPC_DISPLAY_SCROLL_CART', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_CHEAP_CARRIER', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_NEWSLETTER', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_COMPANY_DELIVERY', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_PHONE', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_ADDITIONAL_INFO', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_COUNTRY_DELIVERY', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_SAME_ADDRESSES', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_COMPANY_INVOICE', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_COUNTRY_INVOICE', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_NO_DELIVERY', 1) == false
			OR Configuration::updateValue('OPC_FIELD_PWD_LEN', '5') == false
			OR Configuration::updateValue('OPC_FIELD_GEN_DOMAIN', 'create.me') == false
			OR Configuration::updateValue('OPC_FIELD_INV_ADDR_MSG', 'I do not wish to provide billing address.') == false
			OR Configuration::updateValue('OPC_FIELD_VIRTUAL_NAME', 'Virtual') == false
			OR Configuration::updateValue('OPC_FIELD_VIRTUAL_LASTNAME', 'CUSTOMER') == false
			OR Configuration::updateValue('OPC_FIELD_VIRTUAL_ADDRESS', 'Virtual Street') == false
			OR Configuration::updateValue('OPC_FIELD_VIRTUAL_ZIP', '99999') == false
			OR Configuration::updateValue('OPC_FIELD_VIRTUAL_CITY', 'Virtual City') == false
			OR Configuration::updateValue('OPC_DISPLAY_SHOW_PASSWORD', 1) == false
			OR Configuration::updateValue('OPC_DISPLAY_SAMPLE_VALUES', 1) == false
			)
			return false;
			
		return true;
	} 
	


	private function _updateRadioValue($name)
	{
			$opc_value = Tools::getValue('opc_'.$name);
			if ($opc_value != 0 AND $opc_value != 1)
				$ret = '<div class="alert error">'.$this->l($name.' : Invalid choice.').'</div>';
			else
			{
				Configuration::updateValue('OPC_DISPLAY_'.strtoupper($name), intval($opc_value));
			}
			return $ret;
	}

	private function _updateValue($name)
	{
			$opc_value = Tools::getValue('opc_'.$name);
			Configuration::updateValue('OPC_FIELD_'.strtoupper($name), $opc_value);
	}
	
	function getContent()
	{
	/* display the module name */
		$this->_html = '<h2>'.$this->displayName.'</h2>';


		/* update the editorial xml */
		if (Tools::isSubmit('submitOPC'))
		{
				$output .= $this->_updateRadioValue("payment_sp");
				$output .= $this->_updateRadioValue("ship2pay_active");

				$output .= $this->_updateRadioValue("scroll_cart");
				$output .= $this->_updateRadioValue("scroll_summary");

				$output .= $this->_updateRadioValue("checkout_tracker");

				$output .= $this->_updateRadioValue("hide_carrier");
				$output .= $this->_updateRadioValue("hide_payment");

				$output .= $this->_updateRadioValue("sample_values");
				$output .= $this->_updateRadioValue("gender");
				$output .= $this->_updateRadioValue("birthday");

				$output .= $this->_updateRadioValue("already_registered");
				$output .= $this->_updateRadioValue("cheap_carrier");

				$output .= $this->_updateRadioValue("newsletter");
				$output .= $this->_updateRadioValue("special_offers");
				$output .= $this->_updateRadioValue("company_delivery");
				$output .= $this->_updateRadioValue("address2_delivery");
				$output .= $this->_updateRadioValue("country_delivery");
				$output .= $this->_updateRadioValue("phone");
				$output .= $this->_updateRadioValue("additional_info");

				$output .= $this->_updateRadioValue("same_addresses");
				$output .= $this->_updateRadioValue("company_invoice");
				$output .= $this->_updateRadioValue("address2_invoice");
				$output .= $this->_updateRadioValue("country_invoice");


				$this->_updateValue("pwd_len");
				$output .= $this->_updateRadioValue("separate_welcome");
				$output .= $this->_updateRadioValue("optional_email");
				$output .= $this->_updateRadioValue("hide_email");
				$output .= $this->_updateRadioValue("show_password");
				$this->_updateValue("gen_domain");


				$output .= $this->_updateRadioValue("no_delivery");

				$this->_updateValue("pickup_crr");

				$this->_updateValue("inv_addr_msg");
				$this->_updateValue("virtual_name");
				$this->_updateValue("virtual_lastname");
				$this->_updateValue("virtual_address");
				$this->_updateValue("virtual_zip");
				$this->_updateValue("virtual_city");
				
				$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';
		}

		/* display the editorial's form */
		$this->_displayForm();

		return $this->_html;
	}

	private function _displayOption($title, $name, $desc)
	{
		return '
				<label>'.$this->l($title).'</label>
				<div class="margin-form">
					<input type="radio" name="opc_'.$name.'" id="opc_'.$name.'_on" value="1" '.(Tools::getValue('opc_'.$name, Configuration::get(strtoupper('OPC_DISPLAY_'.$name))) ? 'checked="checked" ' : '').'/>
					<label class="t" for="opc_'.$name.'_on"> <img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" /></label>
					<input type="radio" name="opc_'.$name.'" id="opc_'.$name.'_off" value="0" '.(!Tools::getValue('opc_'.$name, Configuration::get(strtoupper('OPC_DISPLAY_'.$name))) ? 'checked="checked" ' : '').'/>
					<label class="t" for="opc_'.$name.'_off"> <img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" /></label>
					<p class="clear">'.$this->l($desc).'</p>
				</div>
		';
	}

	private function _displayTextField($title, $name, $desc)
	{
		return '
				<label>'.$this->l($title).'</label>
				<div class="margin-form">
					<textarea rows="2" cols="50" name="opc_'.$name.'" id="opc_'.$name.'">'.Tools::getValue('opc_'.$name, Configuration::get(strtoupper('OPC_FIELD_'.$name))).'</textarea>
					<p class="clear">'.$this->l($desc).'</p>
				</div>
		';
	}

	private function _displayInputField($title, $name, $desc)
	{
		return '
				<label>'.$this->l($title).'</label>
				<div class="margin-form">
					<input type="text" size="15" name="opc_'.$name.'" id="opc_'.$name.'" value="'.Tools::getValue('opc_'.$name, Configuration::get(strtoupper('OPC_FIELD_'.$name))).'" />
					<p class="clear">'.$this->l($desc).'</p>
				</div>
		';
	}
	
	private function _displayForm()
	{
	
		/* Languages preliminaries */
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$iso = Language::getIsoById($defaultLanguage);
		$divLangName = 'text_left¤text_right';




                $zones = Zone::getZones(true);
		$zones_html = "<select name=\"default_zone\">\n";
                foreach ($zones AS $zone) {
			$selected = ($zone['id_zone'] == $zone['active'])?" selected=\"selected\"":"";
			if ($zone['enabled'] == 1)
	                        $zones_html .= "<option value=\"{$zone['id_zone']}\"$selected>{$zone['name']}</option>\n";
                }
		$zones_html .= "</select>";


		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>'.
				'<h3>General settings:</h3>'.
				'<!--'.
				$this->_displayOption("Already registered link", "already_registered", "Display 'already registered? click here.' on the top of checkout page for registered customers.").
				'-->'.
				$this->_displayOption("Payment options on same page", "payment_sp", "Displays payment options on same page (just below carrier selection).").
				$this->_displayOption("Ship2pay support", "ship2pay_active", "Enable support for ship2pay module. NB: You need to install ship2pay module to enable this, but do not overwrite classes/Module.php file from ship2pay installation.").
				$this->_displayOption("Cheapest carrier selection", "cheap_carrier", "Enable the cheapest carrier selection. It will override even default carrier, unless customer manually selects preferred carrier.").
				$this->_displayInputField("Pick-up carrier", "pickup_crr", "Name of pickup carrier. This will be excluded when selecting cheapest carrier (shipping cost calculation). Supports regexp match, so to match more carriers, you can write: (Pick up place1)|(Pick up place2)").	
				$this->_displayOption("Sticky cart block", "scroll_cart", "Keep cart block sticky when scrolling down on checkout page.").
				$this->_displayOption("Sticky cart summary", "scroll_summary", "Keep cart summary (totals / shipping) sticky when scrolling down on checkout page.").
				$this->_displayOption("GA checkout form tracker", "checkout_tracker", "Records filled-in fields into your Google Analytics account, so you can see where customer left checkout form. Requires Google Analytics module installed.").
				$this->_displayOption("Hide carrier selection", "hide_carrier", "Hide block with carrier selection, if there is only one carrier for selected country.").
				$this->_displayOption("Hide payment selection", "hide_payment", "Hide block with payment selection, if there is only one payment for selected country / carrier (if ship2pay installed) AND only if 'Payment options on same page' is also enabled.").
				'<center><input type="submit" name="submitOPC" value="'.$this->l('Save').'" class="button" /></center>'.
				'<hr />'.
				'<h3>Control which fields or checkboxes are displayed in order form:</h3>'.
				$this->_displayOption("Sample values", "sample_values", "Display sample values next to checkout form fields. You may want to change values (texts) in BO-Tools-Translations-Front Office, section order-carrier. Also styles 'i.ex_focus' and 'i.ex_blur' in global.css should be created.").
				$this->_displayOption("Gender", "gender", "Display radio buttons with gender selection.").
				$this->_displayOption("Birthday", "birthday", "Display dropdowns for birthday.").
				$this->_displayOption("Newsletter", "newsletter", "Display 'Sign up for newsletter.' checkbox in checkout form.").
				$this->_displayOption("Special offers", "special_offers", "Display 'Sign up for special offers...' checkbox in checkout form.").
				'<hr /><h3>Delivery address:</h3>'.
				$this->_displayOption("Company", "company_delivery", "Display field 'Company' in delivery address.").
				$this->_displayOption("Address (2)", "address2_delivery", "Display field 'Address (2)' in delivery address.").
				$this->_displayOption("Country", "country_delivery", "Display field 'Country' in delivery address.").
				$this->_displayOption("Phone", "phone", "Display field 'Phone' in delivery address.").
				$this->_displayOption("Additional Info", "additional_info", "Display field 'Additional Information' in delivery address.").
				'<hr /><h3>Billing address:</h3>'.
				$this->_displayOption("Same addresses", "same_addresses", "Display checkbox 'Use same address for billing' in checkout form.").
				$this->_displayOption("Company", "company_invoice", "Display field 'Company' in billing address.").
				$this->_displayOption("Address (2)", "address2_invoice", "Display field 'Address (2)' in billing address.").
				$this->_displayOption("Country", "country_invoice", "Display field 'Country' in billing address.").
				'<hr /><h3>Password and Email settings:</h3>'.
				$this->_displayOption("Send password in separate email", "separate_welcome", "Always send generated / user defined password in separate (Welcome) email. Turned off would send password in order confirmation email - unless checked 'Create account' during checkout.").
				$this->_displayInputField("Auto-generated password length", "pwd_len", "Length of auto-generated passwords. (Default value = 5)").	
				$this->_displayOption("Make email field optional", "optional_email", "Makes email field optional. Email would be auto-generated for all customers who do not fill in email and thus no emails would be sent to them.").
				$this->_displayOption("Hide email", "hide_email", "This option would make email field optional and would also hide it for each customer.").
				$this->_displayOption("Show password", "show_password", "Shows password field under email. Customers can define their own password instead of generated one.").
				$this->_displayInputField("Auto-generated domain", "gen_domain", "Domain part of generated emails. This will show up in email fields in backoffice.").	
				'<hr /><h3>Virtual items:</h3>'.
				$this->_displayOption("Hide delivery address", "no_delivery", "Hide delivery address when only virtual items in cart.").
				$this->_displayTextField("Invoice Address Message", "inv_addr_msg", "Message displayed instead of 'Use the same address for billing'.").	
				$this->_displayInputField("Virtual Name", "virtual_name", "String used instead of real name in case delivery address is hidden.").	
				$this->_displayInputField("Virtual Lastname", "virtual_lastname", "String used instead of real lastname in case delivery address is hidden.").	
				$this->_displayInputField("Virtual Address", "virtual_address", "String used instead of real address in case delivery address is hidden.").	
				$this->_displayInputField("Virtual ZIP", "virtual_zip", "String used instead of real ZIP code in case delivery address is hidden.").	
				$this->_displayInputField("Virtual City", "virtual_city", "String used instead of real city code in case delivery address is hidden.").	
				'<center><input type="submit" name="submitOPC" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
	}

	function putContent($xml_data, $key, $field, $forbidden)
	{
		foreach ($forbidden AS $line)
			if ($key == $line)
				return 0;
		$field = htmlspecialchars($field);
		if (!$field)
			return 0;
		return ("\n".'		<'.$key.'>'.$field.'</'.$key.'>');
	}
	
	public function uninstall()
	{
		require(_PS_MODULE_DIR_."onepagecheckout/uninstall_files.php");

		if (!parent::uninstall())
			return false;
		return true;
	}

}
?>
