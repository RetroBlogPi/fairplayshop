<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');
if ($cookie->isLogged() && !isset($cookie->orders_since))
	Tools::redirect('my-account.php');

//CSS ans JS file calls
$js_files = array(
	_THEME_JS_DIR_.'tools/statesManagement.js'
);
$errors = array();

$back = Tools::getValue('back');
if (!empty($back))
	$smarty->assign('back', Tools::safeOutput($back));


if (Tools::getValue('create_account'))
{
	$create_account = 1;
	$smarty->assign('email_create', 1);
}

if (Tools::isSubmit('SubmitCreate'))
{
	if (!Validate::isEmail($email = Tools::getValue('email_create')))
		$errors[] = Tools::displayError('invalid e-mail address');
	elseif (Customer::customerExists($email))
		$errors[] = Tools::displayError('someone has already registered with this e-mail address');	
	else
	{
		$create_account = 1;
		$smarty->assign('email_create', Tools::safeOutput($email));
		$_POST['email'] = $email;
	}
}

if (Tools::isSubmit('submitAccount'))
{
	$create_account = 1;
	$smarty->assign('email_create', 1);

	if (!Validate::isEmail($email = Tools::getValue('email')))
		$errors[] = Tools::displayError('e-mail not valid');
	elseif (!Validate::isPasswd(Tools::getValue('passwd')))
		$errors[] = Tools::displayError('invalid password');
	elseif (Customer::customerExists($email))
		$errors[] = Tools::displayError('someone has already registered with this e-mail address');	
	elseif (!@checkdate(Tools::getValue('months'), Tools::getValue('days'), Tools::getValue('years')) AND !(Tools::getValue('months') == '' AND Tools::getValue('days') == '' AND Tools::getValue('years') == ''))
		$errors[] = Tools::displayError('invalid birthday');
	else
	{
		$customer = new Customer();
		if (Tools::isSubmit('newsletter'))
		{
			$customer->ip_registration_newsletter = pSQL($_SERVER['REMOTE_ADDR']);
			$customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
		}
		
		$customer->birthday = (empty($_POST['years']) ? '' : intval($_POST['years']).'-'.intval($_POST['months']).'-'.intval($_POST['days']));

		/* Customer and address, same fields, caching data */
		$addrLastname = isset($_POST['lastname']) ? $_POST['lastname'] : $_POST['customer_lastname'];
		$addrFirstname = isset( $_POST['firstname']) ?  $_POST['firstname'] : $_POST['customer_firstname'];
		$_POST['lastname'] = $_POST['customer_lastname'];
		$_POST['firstname'] = $_POST['customer_firstname'];
		$errors = $customer->validateControler();
		$_POST['lastname'] = $addrLastname;
		$_POST['firstname'] = $addrFirstname;
		$address = new Address();
		$address->id_customer = 1;
		$errors = array_unique(array_merge($errors, $address->validateControler()));
		if (!sizeof($errors))
		{
			if (!$country = new Country($address->id_country) OR !Validate::isLoadedObject($country))
				die(Tools::displayError());
			if (intval($country->contains_states) AND !intval($address->id_state))
				$errors[] = Tools::displayError('this country require a state selection');
			else
			{
				$customer->active = 1;
				if (!$customer->add())
					$errors[] = Tools::displayError('an error occurred while creating your account');
				else
				{
					$address->id_customer = intval($customer->id);
					$address->alias = "dlv-".$address->alias;
					if (!$address->add())
						$errors[] = Tools::displayError('an error occurred while creating your address');
					else
					{
						if (!Mail::Send(intval($cookie->id_lang), 'account', 'Welcome!', 
						array('{firstname}' => $customer->firstname, '{lastname}' => $customer->lastname, '{email}' => $customer->email, '{passwd}' => Tools::getValue('passwd')), $customer->email, $customer->firstname.' '.$customer->lastname))
							$errors[] = Tools::displayError('cannot send email');
						$smarty->assign('confirmation', 1);
						$cookie->id_customer = intval($customer->id);
						$cookie->customer_lastname = $customer->lastname;
						$cookie->customer_firstname = $customer->firstname;
						$cookie->passwd = $customer->passwd;
						$cookie->logged = 1;
						$cookie->email = $customer->email;

						setFieldsCookie($customer);

						Module::hookExec('createAccount', array(
							'_POST' => $_POST,
							'newCustomer' => $customer
						));
						if ($back)
							Tools::redirect($back);
					}
				}
			}
		}
	}
}

function setFieldsCookie($customer) {
  global $cookie;

  # based on $customer->id, we'll get addreses, and last 2 (delivery / invoice) will be used
  $delivery_address_id = intval(Address::getLastCustomerAddressIdByType(intval($customer->id), "inv-")); // 2nd param is negated
  $invoice_address_id = intval(Address::getLastCustomerAddressIdByType(intval($customer->id), "dlv-"));

  if (!($delivery_address_id > 0))
    $delivery_address_id = intval(Address::getFirstCustomerAddressId(intval($customer->id)));

  $fields_cookie['f_id_gender'] = $customer->id_gender;
  list ($fields_cookie['f_days'],
  	$fields_cookie['f_months'], 
  	$fields_cookie['f_years']) = explode('-',$customer->birthday);
  $fields_cookie['f_customer_firstname'] = $customer->firstname;
  $fields_cookie['f_customer_lastname'] = $customer->lastname;
  $fields_cookie['f_email'] = $customer->email;
  $fields_cookie['f_newsletter'] = $customer->newsletter;
  $fields_cookie['f_optin'] = $customer->optin;


  # set defaults (due to implode command)
  $fields_cookie['f_company'] = "";
  $fields_cookie['f_firstname'] = "";
  $fields_cookie['f_lastname'] = "";
  $fields_cookie['f_address1'] = "";

  $fields_cookie['f_address2'] = "";
  $fields_cookie['f_postcode'] = "";
  $fields_cookie['f_city'] = "";

  $fields_cookie['f_id_country'] = "";
  $fields_cookie['f_id_state'] = "";
  $fields_cookie['f_other'] = "";

  $fields_cookie['f_phone'] = "";
  $fields_cookie['f_phone_mobile'] = "";

  $fields_cookie['f_inv_company'] = "";
  $fields_cookie['f_inv_firstname'] = "";
  $fields_cookie['f_inv_lastname'] = "";
  $fields_cookie['f_inv_address1'] = "";
  $fields_cookie['f_inv_address2'] = "";
  $fields_cookie['f_inv_postcode'] = "";
  $fields_cookie['f_inv_city'] = "";

  $fields_cookie['f_inv_id_country'] = "";
  $fields_cookie['f_inv_id_state'] = "";
  $fields_cookie['f_inv_other'] = "";

  $fields_cookie['f_inv_phone'] = "";
  $fields_cookie['f_inv_phone_mobile'] = "";


  if (intval($delivery_address_id)>0) {
	$delivery_address = new Address(intval($delivery_address_id));

        $fields_cookie['f_company'] = $delivery_address->company;
        $fields_cookie['f_firstname'] = $delivery_address->firstname;
        $fields_cookie['f_lastname'] = $delivery_address->lastname;
        $fields_cookie['f_address1'] = $delivery_address->address1;

        $fields_cookie['f_address2'] = $delivery_address->address2;
        $fields_cookie['f_postcode'] = $delivery_address->postcode;
        $fields_cookie['f_city'] = $delivery_address->city;

        $fields_cookie['f_id_country'] = $delivery_address->id_country;
        $fields_cookie['f_id_state'] = $delivery_address->id_state;
        $fields_cookie['f_other'] = $delivery_address->other;

        $fields_cookie['f_phone'] = $delivery_address->phone;
        $fields_cookie['f_phone_mobile'] = $delivery_address->phone_mobile;
  }

  if (intval($invoice_address_id)>0) {
        $invoice_address = new Address(intval($invoice_address_id));

        $fields_cookie['f_inv_company'] = $invoice_address->company;
        $fields_cookie['f_inv_firstname'] = $invoice_address->firstname;
        $fields_cookie['f_inv_lastname'] = $invoice_address->lastname;
        $fields_cookie['f_inv_address1'] = $invoice_address->address1;
        $fields_cookie['f_inv_address2'] = $invoice_address->address2;
        $fields_cookie['f_inv_postcode'] = $invoice_address->postcode;
        $fields_cookie['f_inv_city'] = $invoice_address->city;

        $fields_cookie['f_inv_id_country'] = $invoice_address->id_country;
        $fields_cookie['f_inv_id_state'] = $invoice_address->id_state;
        $fields_cookie['f_inv_other'] = $invoice_address->other;

        $fields_cookie['f_inv_phone'] = $invoice_address->phone;
        $fields_cookie['f_inv_phone_mobile'] = $invoice_address->phone_mobile;

  }

        $fields_cookie['f_id_carrier'] = Address::getLastCustomerCarrierId($customer->id);

        $fields_cookie['f_same'] = (intval($delivery_address_id)>0 && intval($invoice_address_id)>0 && intval($delivery_address_id) != intval($invoice_address_id))?"0":"1";

        $cookie->form_fields = implode(";", $fields_cookie);

}

if (trim(Tools::getValue('ajax_auth')=="1")) {
	$email = trim(Tools::getValue('alr_email'));
	$passwd = trim(Tools::getValue('alr_passwd'));
	$success = 0;

	if (empty($email))
		$errors[] = Tools::displayError('e-mail address is required');
	elseif (!Validate::isEmail($email))
		$errors[] = Tools::displayError('invalid e-mail address');
	elseif (empty($passwd))
		$errors[] = Tools::displayError('password is required');
	elseif (Tools::strlen($passwd) > 32)
		$errors[] = Tools::displayError('password is too long');
	elseif (!Validate::isPasswd($passwd))
		$errors[] = Tools::displayError('invalid password');
	else
	{
		$customer = new Customer();
		$authentication = $customer->getByemail(trim($email), trim($passwd));
		/* Handle brute force attacks */
		sleep(1);
		if (!$authentication OR !$customer->id)
			$errors[] = Tools::displayError('authentication failed');
		else
		{
			$cookie->id_customer = intval($customer->id);
			$cookie->customer_lastname = $customer->lastname;
			$cookie->customer_firstname = $customer->firstname;
			$cookie->logged = 1;
			$cookie->passwd = $customer->passwd;
			$cookie->email = $customer->email;
			if (Configuration::get('PS_CART_FOLLOWING') AND (empty($cookie->id_cart) OR Cart::getNbProducts($cookie->id_cart) == 0))
				$cookie->id_cart = intval(Cart::lastNoneOrderedCart(intval($customer->id)));
			$id_address = intval(Address::getFirstCustomerAddressId(intval($customer->id)));
			$cookie->id_address_delivery = $id_address;
			$cookie->id_address_invoice = $id_address;

			unset($cookie->orders_since);

			setFieldsCookie($customer);
			/*Module::hookExec('authentication');
			if ($back = Tools::getValue('back'))
				Tools::redirect($back);
			Tools::redirect('my-account.php');
			*/
			//Tools::redirect('order.php?step=1');
			$success = 1;
		}
	}



	$err = $errors[0];

	echo "{ 'success': $success, 'error': '".addslashes($err)."' }";

	exit;
}


if (Tools::isSubmit('SubmitLogin'))
{
        $passwd = trim(Tools::getValue('passwd'));
        $email = trim(Tools::getValue('email'));

	if (empty($email))
		$errors[] = Tools::displayError('e-mail address is required');
	elseif (!Validate::isEmail($email))
		$errors[] = Tools::displayError('invalid e-mail address');
	elseif (empty($passwd))
		$errors[] = Tools::displayError('password is required');
	elseif (Tools::strlen($passwd) > 32)
		$errors[] = Tools::displayError('password is too long');
	elseif (!Validate::isPasswd($passwd))
		$errors[] = Tools::displayError('invalid password');
	else
	{
		$customer = new Customer();
		$authentication = $customer->getByemail(trim($email), trim($passwd));
		/* Handle brute force attacks */
		sleep(1);
		if (!$authentication OR !$customer->id)
			$errors[] = Tools::displayError('authentication failed');
		else
		{
			$cookie->id_customer = intval($customer->id);
			$cookie->customer_lastname = $customer->lastname;
			$cookie->customer_firstname = $customer->firstname;
			$cookie->logged = 1;
			$cookie->passwd = $customer->passwd;
			$cookie->email = $customer->email;
			if (Configuration::get('PS_CART_FOLLOWING') AND (empty($cookie->id_cart) OR Cart::getNbProducts($cookie->id_cart) == 0))
				$cookie->id_cart = intval(Cart::lastNoneOrderedCart(intval($customer->id)));
			$id_address = intval(Address::getFirstCustomerAddressId(intval($customer->id)));
			$cookie->id_address_delivery = $id_address;
			$cookie->id_address_invoice = $id_address;

			unset($cookie->orders_since);

			setFieldsCookie($customer);
			Module::hookExec('authentication');
			if ($back = Tools::getValue('back'))
				Tools::redirect($back);
			Tools::redirect('my-account.php');
		}
	}
}

if (isset($create_account))
{
	/* Generate years, months and days */
	if (isset($_POST['years']) AND is_numeric($_POST['years']))
		$selectedYears = intval($_POST['years']);
	$years = Tools::dateYears();
	if (isset($_POST['months']) AND is_numeric($_POST['months']))
		$selectedMonths = intval($_POST['months']);
	$months = Tools::dateMonths();

	if (isset($_POST['days']) AND is_numeric($_POST['days']))
		$selectedDays = intval($_POST['days']);
	$days = Tools::dateDays();

	/* Select the most appropriate country */
	if (isset($_POST['id_country']) AND is_numeric($_POST['id_country']))
		$selectedCountry = intval($_POST['id_country']);
	elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
	{
		$array = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		if (Validate::isLanguageIsoCode($array[0]))
		{
			$selectedCountry = Country::getByIso($array[0]);
			if (!$selectedCountry)
				$selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
		}
	}
	if (!isset($selectedCountry))
		$selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
	$countries = Country::getCountries(intval($cookie->id_lang), true);

	$smarty->assign(array(
		'years' => $years,
		'sl_year' => (isset($selectedYears) ? $selectedYears : 0),
		'months' => $months,
		'sl_month' => (isset($selectedMonths) ? $selectedMonths : 0),
		'days' => $days,
		'sl_day' => (isset($selectedDays) ? $selectedDays : 0),
		'countries' => $countries,
		'sl_country' => (isset($selectedCountry) ? $selectedCountry : 0)
	));

	/* Call a hook to display more information on form */
	$smarty->assign('HOOK_CREATE_ACCOUNT_FORM', Module::hookExec('createAccountForm'));
}

include(dirname(__FILE__).'/header.php');

$smarty->assign('errors', $errors);
if (in_array($back, array('addresses.php','identity.php','discount.php')))
  $smarty->assign('back', $back);
Tools::safePostVars();
$smarty->display(_PS_THEME_DIR_.'authentication.tpl');

include(dirname(__FILE__).'/footer.php');

?>
