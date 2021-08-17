<?php

/* SSL Management */
$useSSL = true;


include_once(dirname(__FILE__).'/config/config.inc.php');
/* Step number is needed on some modules */
$step = intval(Tools::getValue('step'));
include_once(dirname(__FILE__).'/init.php');

$customer_email_check = Tools::getValue('customer_email_check');
if ($customer_email_check) {
	$customer = new Customer();
        if (Validate::isEmail($customer_email_check))
          $existing_customer = $customer->getByEmail($customer_email_check);

	echo "{ 'result': ".intval($existing_customer)."}";
	exit;
}



if (isset($_GET['ajax_carrier']))
{
 if (Tools::getValue('ajax_carrier', '0')) {

    $cart->id_carrier = Tools::getValue('ajax_carrier');
    if (Tools::getValue('userChoice') == '1')
      $cookie->userChoiceCarrier = Tools::getValue('ajax_carrier');
    $cookie->artificial_zone_id = Tools::getValue('ajax_zone_id');
    $cart->update();
    //$cookie->write();

 }
    echo "{ }";
    exit;
}

if (Tools::getValue('zone_carriers', '0')) {

        $id_zone = Tools::getValue('zone_carriers', '0');
        $result = Carrier::getCarriers(intval($cookie->id_lang), true, false, intval($id_zone));

	restoreFieldsCookie();

	// default or cookie or cheapest carrier selection
        $min_price = 1000000;
        $cheapest_carrier = 0;
        $default_carrier = intval(Configuration::get('PS_CARRIER_DEFAULT'));
        $default_carrier_present = false;
        //$cookie_carrier = $fields_cookie['f_id_carrier'];
        $cookie_carrier = $cookie->userChoiceCarrier;
        $cookie_carrier_present = false;

	$pickup_carrier = Configuration::get('OPC_FIELD_PICKUP_CRR');


	$resultsArray = array();
	foreach ($result AS $k => $row)
	{
		$carrier = new Carrier(intval($row['id_carrier']));

		if ((Configuration::get('PS_SHIPPING_METHOD') AND $carrier->getMaxDeliveryPriceByWeight($id_zone) === false)
		OR (!Configuration::get('PS_SHIPPING_METHOD') AND $carrier->getMaxDeliveryPriceByPrice($id_zone) === false))
		{
			unset($result[$k]);
			continue ;
		}
		if ($row['range_behavior'])
		{
			// Get id zone
			if ((Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByWeight($row['id_carrier'], $cart->getTotalWeight(), $id_zone)))
			OR (!Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByPrice($row['id_carrier'], $cart->getOrderTotal(true, 4), $id_zone))))
				{
					unset($result[$k]);
					continue ;
				}
		}
		$row['name'] = (strval($row['name']) != '0' ? $row['name'] : Configuration::get('PS_SHOP_NAME'));
		$row['price'] = $cart->getOrderShippingCost(intval($row['id_carrier']), true, $id_zone);
		$row['price_tax_exc'] = $cart->getOrderShippingCost(intval($row['id_carrier']), false, $id_zone);
		$row['img'] = file_exists(_PS_SHIP_IMG_DIR_.intval($row['id_carrier']).'.jpg') ? _THEME_SHIP_DIR_.intval($row['id_carrier']).'.jpg' : '';
		$resultsArray[] = $row;

                if ($row['price'] < $min_price) {
			// if this is not pick-up carrier
			if (!preg_match("/$pickup_carrier/", $row['name']))
			{
                          $min_price = $row['price'];
                          $cheapest_carrier = $row['id_carrier'];
			}
                }
                if ($default_carrier == $row['id_carrier']) $default_carrier_present = true;
                if ($cookie_carrier == $row['id_carrier']) $cookie_carrier_present = true;

	}//foreach ($result)



	if ($cookie_carrier_present)
	  $checked = $cookie_carrier;
	elseif (Configuration::get('OPC_DISPLAY_CHEAP_CARRIER'))
	  $checked = $cheapest_carrier;
	elseif ($default_carrier_present)
	  $checked = $default_carrier;


    $smarty->assign(array(
	'fields_cookie' => $fields_cookie,
	'carriers' => $resultsArray,
        'checked' => intval($checked)
    ));

    $smarty->display(_PS_THEME_DIR_.'zone-carriers.tpl');
    exit;

}//if (Tools::getValue('zone_carriers')

    function getPaymentMethods($id_country=0, $id_carrier=0) {
     
            global $cart;
           
      $ship2payActive = Configuration::get('OPC_DISPLAY_SHIP2PAY_ACTIVE','0');
     
      if ($id_country > 0) {
        $paymentHook = Module::hookExecPaymentAjax($id_country, $id_carrier, ($ship2payActive==1));
      } else {
        if ($ship2payActive==1)
          $paymentHook = Module::hookExecPaymentFront($id_carrier);
        else
          $paymentHook = Module::hookExecPayment();
      }
     
      $summary = $cart->getSummaryDetails();
            //$customizedDatas = Product::getAllCustomizedDatas(intval($cart->id));
            //roduct::addCustomizationPrice($summary['products'], $customizedDatas);
     
            if ($free_ship = intval(Configuration::get('PS_SHIPPING_FREE_PRICE')))
            {
                    $discounts = $cart->getDiscounts();
                    // VE.cz
                    //$total_free_ship =  $free_ship - ($summary['total_products_wt'] + $summary['total_discounts']);
                    $total_free_ship =  $free_ship - $summary['total_products_wt'] + $summary['total_discounts'];
                    
                    foreach ($discounts as $discount)
                            if ($discount['id_discount_type'] == 3)
                            {
                                    $total_free_ship = 0;
                                    break ;
                            }              
            } else $total_free_ship = 1;
     
     
     
      $payment_methods = array();
     
      if (trim($paymentHook) != "")
       $payment_methods[0]['whole_content'] = $paymentHook;
      if (preg_match_all('/^.*?<a .*?href="(.*?)".*?>.*?(<img .*?\/>).*?(.*?)<\/a>.*?$/ms', $paymentHook, $matches, PREG_SET_ORDER)) {
            //print_r($matches);
            $i = 0;
            foreach ($matches as $match) {
              $payment_methods[$i]['link'] = trim($match[1]);
              $payment_methods[$i]['link_hash'] = substr(md5(trim($match[1])),0,8);
              $payment_methods[$i]['img'] = preg_replace('/(\r)?\n/m', " ", trim($match[2]));
              $payment_methods[$i]['desc'] = preg_replace('/(\r)?\n/m', " ", trim($match[3]));
             
              //poslání ceny dobírky do šablony, aby se mohla v javascriptu pøièíst
              if(preg_match('/cashondeliverywithfee/',$match[1]) AND $total_free_ship > 0)
              {
                $cashOnDelivery = new CashOnDeliveryWithFee();
                    $payment_methods[$i]['cena'] = $cashOnDelivery->getCostValidated($cart);
           
                   
        } else
            {
                    $payment_methods[$i]['cena'] = 0;
                    if(preg_match('/cashondeliverywithfee/',$match[1]) )
                            $payment_methods[$i]['desc'] = 'DobÃ­rka';
            }
              //$payment_methods[$i]['full'] = $match[0];
              $i++;
            }
      }
      return $payment_methods;
    }




if (Tools::getValue('ajax_payment_country', '0')) {
 
    $id_country = Tools::getValue('ajax_payment_country', '0');
    $id_carrier = Tools::getValue('ajax_payment_carrier', '0');

	# Payment on same page
	if (Configuration::get('OPC_DISPLAY_PAYMENT_SP')) { 
	  $payment_methods = getPaymentMethods($id_country, $id_carrier);

	  $smarty->assign('payment_methods',$payment_methods);
	  $smarty->assign('payment_country',$id_country);
	  $smarty->assign('payment_carrier',$id_carrier);
	}

    $smarty->display(_PS_THEME_DIR_.'payment_methods.tpl');
    exit;
}//if (Tools::getValue('payment_methods')



/* Disable some cache related bugs on the cart/order */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$errors = array();

/* Class FreeOrder to use PaymentModule (abstract class, cannot be instancied) */
class	FreeOrder extends PaymentModule {}

# shopping cart updates (add / subtract / delete products)
if (Tools::getValue('cartupdateflag', '0') == '1') {
    # save post variables so we don't loose filled-in data
    savePostVariablesToCookies();

    Tools::redirect($_POST['link'], "");
}


function initFieldsCookie() {
  global $fields_cookie;
        if ( !isset($fields_cookie['f_id_gender'])) $fields_cookie['f_id_gender'] = "";
        if ( !isset($fields_cookie['f_days'])) $fields_cookie['f_days'] = "";
        if ( !isset($fields_cookie['f_months'])) $fields_cookie['f_months'] = "";
        if ( !isset($fields_cookie['f_years'])) $fields_cookie['f_years'] = "";
        if ( !isset($fields_cookie['f_customer_firstname'])) $fields_cookie['f_customer_firstname'] = "";
        if ( !isset($fields_cookie['f_customer_lastname'])) $fields_cookie['f_customer_lastname'] = "";
        if ( !isset($fields_cookie['f_email'])) $fields_cookie['f_email'] = "";
        if ( !isset($fields_cookie['f_newsletter'])) $fields_cookie['f_newsletter'] = "";
        if ( !isset($fields_cookie['f_optin'])) $fields_cookie['f_optin'] = "";
        if ( !isset($fields_cookie['f_company'])) $fields_cookie['f_company'] = "";
        if ( !isset($fields_cookie['f_firstname'])) $fields_cookie['f_firstname'] = "";
        if ( !isset($fields_cookie['f_lastname'])) $fields_cookie['f_lastname'] = "";
        if ( !isset($fields_cookie['f_address1'])) $fields_cookie['f_address1'] = "";

        if ( !isset($fields_cookie['f_address2'])) $fields_cookie['f_address2'] = "";
        if ( !isset($fields_cookie['f_postcode'])) $fields_cookie['f_postcode'] = "";
        if ( !isset($fields_cookie['f_city'])) $fields_cookie['f_city'] = "";

        if ( !isset($fields_cookie['f_id_country'])) $fields_cookie['f_id_country'] = "";
        if ( !isset($fields_cookie['f_id_state'])) $fields_cookie['f_id_state'] = "";
        if ( !isset($fields_cookie['f_other'])) $fields_cookie['f_other'] = "";

        if ( !isset($fields_cookie['f_phone'])) $fields_cookie['f_phone'] = "";
        if ( !isset($fields_cookie['f_phone_mobile'])) $fields_cookie['f_phone_mobile'] = "";

        if ( !isset($fields_cookie['f_inv_company'])) $fields_cookie['f_inv_company'] = "";
        if ( !isset($fields_cookie['f_inv_firstname'])) $fields_cookie['f_inv_firstname'] = "";
        if ( !isset($fields_cookie['f_inv_lastname'])) $fields_cookie['f_inv_lastname'] = "";
        if ( !isset($fields_cookie['f_inv_address1'])) $fields_cookie['f_inv_address1'] = "";
        if ( !isset($fields_cookie['f_inv_address2'])) $fields_cookie['f_inv_address2'] = "";
        if ( !isset($fields_cookie['f_inv_postcode'])) $fields_cookie['f_inv_postcode'] = "";
        if ( !isset($fields_cookie['f_inv_city'])) $fields_cookie['f_inv_city'] = "";

        if ( !isset($fields_cookie['f_inv_id_country'])) $fields_cookie['f_inv_id_country'] = "";
        if ( !isset($fields_cookie['f_inv_id_state'])) $fields_cookie['f_inv_id_state'] = "";
        if ( !isset($fields_cookie['f_inv_other'])) $fields_cookie['f_inv_other'] = "";

        if ( !isset($fields_cookie['f_inv_phone'])) $fields_cookie['f_inv_phone'] = "";
        if ( !isset($fields_cookie['f_inv_phone_mobile'])) $fields_cookie['f_inv_phone_mobile'] = "";

        if ( !isset($fields_cookie['f_id_carrier'])) $fields_cookie['f_id_carrier'] = "";

        if ( !isset($fields_cookie['f_same'])) $fields_cookie['f_same'] = "";
}//initFieldsCookie();




function savePostVariablesToCookies() {

    global $fields_cookie, $cart, $cookie;
	initFieldsCookie();

        if ( isset($_POST['id_gender'])) $fields_cookie['f_id_gender'] = $_POST['id_gender'];
        if ( isset($_POST['days'])) $fields_cookie['f_days'] = $_POST['days'];
        if ( isset($_POST['months'])) $fields_cookie['f_months'] = $_POST['months'];
        if ( isset($_POST['years'])) $fields_cookie['f_years'] = $_POST['years'];
        if ( isset($_POST['customer_firstname'])) $fields_cookie['f_customer_firstname'] = $_POST['customer_firstname'];
        if ( isset($_POST['customer_lastname'])) $fields_cookie['f_customer_lastname'] = $_POST['customer_lastname'];
        if ( isset($_POST['email'])) $fields_cookie['f_email'] = $_POST['email'];
        if ( isset($_POST['newsletter'])) $fields_cookie['f_newsletter'] = $_POST['newsletter'];
        if ( isset($_POST['optin'])) $fields_cookie['f_optin'] = $_POST['optin'];
        if ( isset($_POST['company'])) $fields_cookie['f_company'] = $_POST['company'];
        if ( isset($_POST['firstname'])) $fields_cookie['f_firstname'] = $_POST['firstname'];
        if ( isset($_POST['lastname'])) $fields_cookie['f_lastname'] = $_POST['lastname'];
        if ( isset($_POST['address1'])) $fields_cookie['f_address1'] = $_POST['address1'];

        if ( isset($_POST['address2'])) $fields_cookie['f_address2'] = $_POST['address2'];
        if ( isset($_POST['postcode'])) $fields_cookie['f_postcode'] = $_POST['postcode'];
        if ( isset($_POST['city'])) $fields_cookie['f_city'] = $_POST['city'];

        if ( isset($_POST['id_country'])) $fields_cookie['f_id_country'] = $_POST['id_country'];
        if ( isset($_POST['id_state'])) $fields_cookie['f_id_state'] = $_POST['id_state'];
        if ( isset($_POST['other'])) $fields_cookie['f_other'] = $_POST['other'];

        if ( isset($_POST['phone'])) $fields_cookie['f_phone'] = $_POST['phone'];
        if ( isset($_POST['phone_mobile'])) $fields_cookie['f_phone_mobile'] = $_POST['phone_mobile'];

        if ( isset($_POST['inv_company'])) $fields_cookie['f_inv_company'] = $_POST['inv_company'];
        if ( isset($_POST['inv_firstname'])) $fields_cookie['f_inv_firstname'] = $_POST['inv_firstname'];
        if ( isset($_POST['inv_lastname'])) $fields_cookie['f_inv_lastname'] = $_POST['inv_lastname'];
        if ( isset($_POST['inv_address1'])) $fields_cookie['f_inv_address1'] = $_POST['inv_address1'];
        if ( isset($_POST['inv_address2'])) $fields_cookie['f_inv_address2'] = $_POST['inv_address2'];
        if ( isset($_POST['inv_postcode'])) $fields_cookie['f_inv_postcode'] = $_POST['inv_postcode'];
        if ( isset($_POST['inv_city'])) $fields_cookie['f_inv_city'] = $_POST['inv_city'];

        if ( isset($_POST['inv_id_country'])) $fields_cookie['f_inv_id_country'] = $_POST['inv_id_country'];
        if ( isset($_POST['inv_id_state'])) $fields_cookie['f_inv_id_state'] = $_POST['inv_id_state'];
        if ( isset($_POST['inv_other'])) $fields_cookie['f_inv_other'] = $_POST['inv_other'];

        if ( isset($_POST['inv_phone'])) $fields_cookie['f_inv_phone'] = $_POST['inv_phone'];
        if ( isset($_POST['inv_phone_mobile'])) $fields_cookie['f_inv_phone_mobile'] = $_POST['inv_phone_mobile'];

        if ( isset($_POST['id_carrier'])) $fields_cookie['f_id_carrier'] = $_POST['id_carrier'];

        # different approach with checkboxes
        //if ( isset($_POST['email'])) $cart->recyclable = (isset($_POST['recyclable']) && $_POST['recyclable']=="1") ? "1" : "0";
        if ( isset($_POST['email'])) $fields_cookie['f_same'] = (isset($_POST['same']) && $_POST['same']=="1") ? "1" : "0";


        $cookie->form_fields = implode($fields_cookie, ";");


        if ( isset($_POST['email'])) $cookie->checkedTOS = (isset($_POST['cgv']) && $_POST['cgv']=="1")?"1":"0";

        if ( isset($_POST['gift_message'])) $cart->gift_message = strip_tags($_POST['gift_message']);
        if ( isset($_POST['email'])) $cart->gift = (isset($_POST['gift']) && $_POST['gift']=="1")?"1":"0";

        $cart->update();

}

function restoreFieldsCookie() {

	global $cookie, $fields_cookie;


list (
	$fields_cookie['f_id_gender'],
        $fields_cookie['f_days'],
        $fields_cookie['f_months'],
        $fields_cookie['f_years'],
	$fields_cookie['f_customer_firstname'],
	$fields_cookie['f_customer_lastname'],
	$fields_cookie['f_email'],
	$fields_cookie['f_newsletter'],
	$fields_cookie['f_optin'],
	$fields_cookie['f_company'],
	$fields_cookie['f_firstname'],
	$fields_cookie['f_lastname'],
	$fields_cookie['f_address1'],

	$fields_cookie['f_address2'],
	$fields_cookie['f_postcode'],
	$fields_cookie['f_city'],

	$fields_cookie['f_id_country'],
	$fields_cookie['f_id_state'],
	$fields_cookie['f_other'],

	$fields_cookie['f_phone'],
	$fields_cookie['f_phone_mobile'],

	$fields_cookie['f_inv_company'],
	$fields_cookie['f_inv_firstname'],
	$fields_cookie['f_inv_lastname'],
	$fields_cookie['f_inv_address1'],
	$fields_cookie['f_inv_address2'],
	$fields_cookie['f_inv_postcode'],
	$fields_cookie['f_inv_city'],

	$fields_cookie['f_inv_id_country'],
	$fields_cookie['f_inv_id_state'],
	$fields_cookie['f_inv_other'],

	$fields_cookie['f_inv_phone'],
	$fields_cookie['f_inv_phone_mobile'],

	$fields_cookie['f_id_carrier'],

	$fields_cookie['f_same'] 
     ) = explode (";", $cookie->form_fields);

} //restoreFieldsCookie()





/* If some products have disappear */
if (!$cart->checkQuantities())
{
	$step = 0;
	$errors[] = Tools::displayError('An item in your cart is no longer available, you cannot proceed with your order');
}

/* Check minimal account */
$orderTotal = $cart->getOrderTotal();

$orderTotalDefaultCurrency = Tools::convertPrice($cart->getOrderTotal(true, 1), Currency::getCurrency(intval(Configuration::get('PS_CURRENCY_DEFAULT'))));
$minimalPurchase = floatval(Configuration::get('PS_PURCHASE_MINIMUM'));
if ($orderTotalDefaultCurrency < $minimalPurchase)
{
	$step = 0;
	$errors[] = Tools::displayError('A minimum purchase total of').' '.Tools::displayPrice($minimalPurchase, Currency::getCurrency(intval($cart->id_currency))).
	' '.Tools::displayError('is required in order to validate your order');
}

#psliacky
#if (!$cookie->isLogged() AND in_array($step, array(1, 2, 3)))
#	Tools::redirect('authentication.php?back=order.php?step='.$step);

if ($cart->nbProducts())
{
	/* Manage discounts */
	if ((Tools::isSubmit('submitDiscount') OR isset($_GET['submitDiscount'])) AND Tools::getValue('discount_name'))
	{
            savePostVariablesToCookies();
		$discountName = Tools::getValue('discount_name');
		if (!Validate::isDiscountName($discountName))
			$errors[] = Tools::displayError('voucher name not valid');
		else
		{
			$discount = new Discount(intval(Discount::getIdByName($discountName)));
			if (is_object($discount) AND $discount->id)
			{
				if ($tmpError = $cart->checkDiscountValidity($discount, $cart->getDiscounts(), $cart->getOrderTotal(), $cart->getProducts(), true))
					$errors[] = $tmpError;
			}
			else
				$errors[] = Tools::displayError('voucher name not valid');
			if (!sizeof($errors))
			{
				$cart->addDiscount(intval($discount->id));
				Tools::redirect('order.php');
			}
			else
			{
				$smarty->assign(array(
					'errors' => $errors,
					'discount_name' => Tools::safeOutput($discountName)));
			}
		}
	}
	elseif (isset($_GET['deleteDiscount']) AND Validate::isUnsignedId($_GET['deleteDiscount']))
	{
		$cart->deleteDiscount(intval($_GET['deleteDiscount']));
		Tools::redirect('order.php');
	}

	/* Is there only virtual product in cart */
	if ($isVirtualCart = $cart->isVirtualCart())
		setNoCarrier();
	$smarty->assign('virtual_cart', $isVirtualCart);

	/* 4 steps to the order */
	switch (intval($step))
	{
		case 1:
			#displayAddress();
                        displayCarrier();
			break;
		case 2:
		#	if(Tools::isSubmit('processAddress'))
		#		processAddress();
			#autoStep(2);
			displayCarrier();
			break;
		case 3:
			if(Tools::isSubmit('processCarrier'))
				processCarrier();
	#		autoStep(3);
			checkFreeOrder();
			displayPayment();
			break;
		default:
			#$smarty->assign('errors', $errors);
			#displaySummary();
                        displayCarrier();
			break;
	}
}
else
{
	/* Default page */
	$smarty->assign('empty', 1);
	Tools::safePostVars();
	include_once(dirname(__FILE__).'/header.php');
	$smarty->display(_PS_THEME_DIR_.'shopping-cart.tpl');
}

include(dirname(__FILE__).'/footer.php');

/* Order process controller */
/* not used
function autoStep($step)
{
	global $cart, $isVirtualCart;

	if ($step >= 2 AND (!$cart->id_address_delivery OR !$cart->id_address_invoice))
		Tools::redirect('order.php?step=1');
	$delivery = new Address(intval($cart->id_address_delivery));
	$invoice = new Address(intval($cart->id_address_invoice));
	if ($delivery->deleted OR $invoice->deleted)
	{
		if ($delivery->deleted)
			unset($cart->id_address_delivery);
		if ($invoice->deleted)
			unset($cart->id_address_invoice);
		Tools::redirect('order.php?step=1');
	}
	elseif ($step >= 3 AND !$cart->id_carrier AND !$isVirtualCart)
		Tools::redirect('order.php?step=2');
}
*/
/* Bypass payment step if total is 0 */
function checkFreeOrder()
{
	global $cart;

	if ($cart->getOrderTotal() <= 0)
	{
		$order = new FreeOrder();
		$order->validateOrder(intval($cart->id), _PS_OS_PAYMENT_, 0, Tools::displayError('Free order', false));
		Tools::redirect('history.php');
	}
}

/**
 * Set id_carrier to 0 (no shipping price)
 *
 */
function setNoCarrier()
{
	global $cart;
	$cart->id_carrier = 0;
	$cart->update();
}


/* Carrier step */
function processCarrier()
{

	global $cart, $smarty, $isVirtualCart, $orderTotal, $cookie;


	$errors = array();


        $cart->recyclable = (isset($_POST['recyclable']) AND !empty($_POST['recyclable'])) ? 1 : 0;

        if (isset($_POST['gift']) AND !empty($_POST['gift']))
        {
                if (!Validate::isMessage($_POST['gift_message']))
                        $errors[] = Tools::displayError('invalid gift message');
                else
                {
                        $cart->gift = 1;
                        $cart->gift_message = strip_tags($_POST['gift_message']);
                }
        }
        else
                $cart->gift = 0;
	

	$cart->update();


	# generate email for customer (if email field is empty and made non-required)
	if (isset($_POST['email']) && $_POST['email'] == '' && (Configuration::get('OPC_DISPLAY_OPTIONAL_EMAIL') || Configuration::get('OPC_DISPLAY_HIDE_EMAIL') ))
	{
		$generated_domain = Configuration::get('OPC_FIELD_GEN_DOMAIN');

                $result = Db::getInstance()->getRow('
                SELECT COUNT(`id_customer`) AS total
                FROM `'._DB_PREFIX_.'customer`
                WHERE `email` like \'%@'.$generated_domain.'\'');

                $next_id = $result['total']+1;

	  	$_POST['email'] = "c.".$next_id.".".Tools::passwdGen(3)."@".$generated_domain;
	}


        if (isset($_POST['email']))
            $cookie->f_email = $_POST['email'];

        $_POST['alias'] = "dlv-".substr($_POST['address1'], 0, 27);


	$customer = new Customer();
    # update data or returning customer
	if (Validate::isEmail($cookie->f_email))
          $existing_customer = $customer->getByEmail($cookie->f_email);

        if (!$existing_customer)
	{
	  
	  if (Configuration::get('OPC_DISPLAY_SHOW_PASSWORD') && !Configuration::get('OPC_DISPLAY_HIDE_EMAIL') && isset($_POST['password']) &&  trim($_POST['password']) != '') {
	    $_POST['passwd'] = $_POST['password'];
	  } else {
 	    $password_length = intval(Configuration::get('OPC_FIELD_PWD_LEN'));
	    if ($password_length < 1 || $password_length > 32)
	      $password_length = 5;
	    $password = Tools::passwdGen($password_length);
	    $_POST['passwd'] = $password; 
	  }
	  $cookie->passwd =  Tools::encrypt($_POST['passwd']);
	  #$cookie->mail_passwd = "<br />". Configuration::get('OPC_FIELD_PASSWORD_MESSAGE') ." <b>$password</b>";
          $email_key = substr(preg_replace("/[^a-z_0-9-]/", "0", $_POST['email']), 0, 32);
	    # we need to distinguish between user defined passwords and generated.
	    # this one sets _upd_ prefix, if user checked "registerme", but left empty password box
	  $udp_pwd = (isset($_POST['registerme']))?'_udp_'.$_POST['passwd']:$_POST['passwd'];
          Configuration::updateValue($email_key, $udp_pwd);
	  unset($cookie->orders_since);
	} else {

	  $cookie->passwd =  $existing_customer->passwd; # is in encrypted form already

	  # we need to set cookie to indicate different security level;
 	  # using same address one should not get access to previous orders, only when one logs in.
	  # do not set if already logged (normally)
	  if (!$cookie->isLogged())
	    $cookie->orders_since = date('Y-m-d H:i:s'); 
	}
	

  // VE.cz - informace o vytvoøení doèasného uživatele
  if((!isset($_POST['registerme']) && !$cookie->isLogged()) || (isset($cookie->tmpuser) && $cookie->tmpuser==1)) 
    $cookie->tmpuser=1;
  else 
    unset($cookie->tmpuser);  
  	

  $address_update_flag = 0;

	if ($existing_customer) {

	  // 2nd param is negated, searching all addresses aliases, but this.
	  $existing_delivery_address_id = intval(Address::getExistingAddressId($existing_customer->id, "inv-", $_POST['firstname'], $_POST['lastname'], $_POST['company'], $_POST['address1'], $_POST['address2'], $_POST['postcode'], $_POST['city'], $_POST['id_country'], $_POST['id_state']));

	  if ($existing_customer && $existing_delivery_address_id > 0) {
	    $existing_address = new Address($existing_delivery_address_id, $cookie->id_lang);
	    $_POST['alias'] = $existing_address->alias;
	    $address_update_flag = 1;
	  }
	}


	if ($address_update_flag)
	  $address = $existing_address;
	else
	    $address = new Address(NULL, $cookie->id_lang);

        $errors = $customer->validateControler();
        $errors = array_unique(array_merge($errors, $address->validateControler()));

        if (empty($errors)) {
            if (!$country = new Country($address->id_country) OR !Validate::isLoadedObject($country))
                        die(Tools::displayError());
                if (intval($country->contains_states) AND !intval($address->id_state))
                        $errors[] = Tools::displayError('this country require a state selection');
        }

    # add invoice address
    if (isset($_POST['processCarrier']) && !isset($_POST['same']))
    {
        $_POST['inv_alias'] = "inv-".substr($_POST['inv_address1'], 0, 27); 
        $invoice_address = new InvoiceAddress();
        $errors = array_unique(array_merge($errors, $invoice_address->validateControler()));
    }

    if (!sizeof($errors))
    {
	$customer->active = 1;
        $customer->birthday = (empty($_POST['years']) ? '' : intval($_POST['years']).'-'.intval($_POST['months']).'-'.intval($_POST['days']));

        if (!$existing_customer && Tools::isSubmit('newsletter')) {
          $customer->ip_registration_newsletter = pSQL($_SERVER['REMOTE_ADDR']);
          $customer->newsletter_date_add = pSQL(date('Y-m-d H:i:s'));
        }

        if (!$existing_customer)
            $customer->add(); 
        else 
            $customer->update();

	$address->id_customer = $customer->id;

        if ($address_update_flag)
	    $address->update(); 
        else 
	    $address->add();
	    
	    
    // VE.cz - ukladani spravne poznamky. Predtim se ukladala pouze "poznamka" k adrese
		if (isset($_POST['message']) AND !empty($_POST['message']))
		{
			if (!Validate::isMessage($_POST['message']))
				$errors[] = Tools::displayError('invalid message');
			elseif ($oldMessage = Message::getMessageByCartId(intval($cart->id)))
			{
				$message = new Message(intval($oldMessage['id_message']));
				$message->message = htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8');
				$message->update();
			}
			else
			{
				$message = new Message();
				$message->message = htmlentities($_POST['message'], ENT_COMPAT, 'UTF-8');
				$message->id_cart = intval($cart->id);
				$message->id_customer = intval($cart->id_customer);
				$message->add();
			}
		}
		// VE.cz END	    

        $cookie->logged = 1;
        # with address we dont care, we can add many (due to no DB restrictions)


	$cart->id_address_delivery = $address->id;
	$cart->id_address_invoice = $address->id;
	$cart->id_customer = $customer->id;

	$cookie->id_address_delivery = $address->id;
	$cookie->id_address_invoice = $address->id;
	$cookie->id_customer = $customer->id;

	$cookie->customer_lastname = $customer->lastname;
        $cookie->customer_firstname = $customer->firstname;


	# fix to match guest / customer in statsdata module
        Module::hookExec('createAccount', array(
                                                        '_POST' => $_POST,
                                                        'newCustomer' => $customer
                                                ));


        if (isset($invoice_address)) {

	  $invoice_address_update_flag = 0;
            # remap InvoiceAddress object data to standard Address (we past validation already)
	  if ($existing_customer) {

	  // 2nd param (dlv-) is negated - searching for all addresses aliases, but this.
          $existing_invoice_address_id = intval(Address::getExistingAddressId($existing_customer->id, "dlv-", $_POST['inv_firstname'], $_POST['inv_lastname'], $_POST['inv_company'], $_POST['inv_address1'], $_POST['inv_address2'], $_POST['inv_postcode'], $_POST['inv_city'], $_POST['inv_id_country'], $_POST['inv_id_state']));

	    if ($existing_customer && $existing_invoice_address_id > 0) {
	      $existing_invoice_address = new Address($existing_invoice_address_id, $cookie->id_lang);
	      $invoice_address->inv_alias = $existing_invoice_address->alias;
	      $invoice_address_update_flag = 1;
	    }
	  }
	   if ($invoice_address_update_flag)
	    $standard_invoice_address = $existing_invoice_address;
           else
	    $standard_invoice_address = new Address(NULL, $cookie->id_lang);


            $standard_invoice_address->alias =$invoice_address->inv_alias;

            $standard_invoice_address->id_country =$invoice_address->inv_id_country;
            $standard_invoice_address->id_state = $invoice_address->inv_id_state;
            $standard_invoice_address->alias = 	$invoice_address->inv_alias;
            $standard_invoice_address->company = $invoice_address->inv_company;
            $standard_invoice_address->lastname = $invoice_address->inv_lastname;
            $standard_invoice_address->firstname = $invoice_address->inv_firstname;
            $standard_invoice_address->address1 = $invoice_address->inv_address1;
            $standard_invoice_address->address2 = $invoice_address->inv_address2;
            $standard_invoice_address->postcode = $invoice_address->inv_postcode;
            $standard_invoice_address->city = $invoice_address->inv_city;
            $standard_invoice_address->other = $invoice_address->inv_other;
            $standard_invoice_address->phone = $invoice_address->inv_phone;
            //$standard_invoice_address->phone_mobile = $invoice_address->inv_phone_mobile;
            
            $standard_invoice_address->phone_mobile = $address->phone_mobile;


            $standard_invoice_address->id_customer = $customer->id;

	  if ($invoice_address_update_flag)
            $standard_invoice_address->update();
          else
            $standard_invoice_address->add();


            $cart->id_address_invoice = $standard_invoice_address->id;
            $cookie->id_address_invoice = $standard_invoice_address->id;

            if (!$inv_country = new Country($standard_invoice_address->id_country) OR !Validate::isLoadedObject($inv_country))
                        die(Tools::displayError());
                if (intval($inv_country->contains_states) AND !intval($standard_invoice_address->id_state))
                        $errors[] = Tools::displayError('this country require a state selection');
        }

	# we want keep already filled-in data in form
        #savePostVariablesToCookies();

	if (!Validate::isLoadedObject($address))
		die(Tools::displayError());

	if (!$id_zone = Address::getZoneById($address->id))
		$errors[] = Tools::displayError('no zone match with your address');
	if (isset($_POST['id_carrier']) AND Validate::isInt($_POST['id_carrier']) AND sizeof(Carrier::checkCarrierZone(intval($_POST['id_carrier']), intval($id_zone))))
		$cart->id_carrier = intval($_POST['id_carrier']);
	elseif (!$isVirtualCart)
		$errors[] = Tools::displayError('invalid carrier or no carrier selected');

	$cart->update();

    } # if(!sizeof($errors))
        savePostVariablesToCookies();
    
	if (sizeof($errors))
	{
		$smarty->assign('errors', $errors);
		displayCarrier();
		include(dirname(__FILE__).'/footer.php');
		exit;
	}

	$orderTotal = $cart->getOrderTotal();
}

/* Carrier step */
function displayCarrier()
{
	global $smarty, $cart, $cookie, $defaultCountry, $fields_cookie;

	restoreFieldsCookie();

 /* Select the most appropriate country */
        if (isset($_POST['id_country']) AND is_numeric($_POST['id_country']))
                $selectedCountry = intval($_POST['id_country']);
	elseif (isset($cart->id_address_delivery) && $cart->id_address_delivery > 0) {
		$addr = new Address(intval($cart->id_address_delivery));
		$selectedCountry = $addr->id_country;
	} elseif (is_numeric($fields_cookie['f_id_country']) && $fields_cookie['f_id_country'] > 0)
		$selectedCountry = intval($fields_cookie['f_id_country']);
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

/* Select the most appropriate country for invoice address */
        if (isset($_POST['inv_id_country']) AND is_numeric($_POST['inv_id_country']))
                $inv_selectedCountry = intval($_POST['inv_id_country']);
	elseif (is_numeric($fields_cookie['f_inv_id_country']) && $fields_cookie['f_inv_id_country'] > 0)
		$inv_selectedCountry = intval($fields_cookie['f_inv_id_country']);
        elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
                $array = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                if (Validate::isLanguageIsoCode($array[0]))
                {
                        $inv_selectedCountry = Country::getByIso($array[0]);
                        if (!$inv_selectedCountry)
                                $inv_selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
                }
        }
        if (!isset($inv_selectedCountry))
                $inv_selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));



        $countries = Country::getCountries(intval($cookie->id_lang), true);

	$selectedCountryObject = new Country($selectedCountry);
	$default_zone = $selectedCountryObject->id_zone; #europe

	$result = Carrier::getCarriers(intval($cookie->id_lang), true, false, intval($default_zone));
	#$result = Carrier::getCarriers(intval($cookie->id_lang), true);


	$resultsArray = array();
	foreach ($result AS $k => $row)
	{
		$carrier = new Carrier(intval($row['id_carrier']));

		if ((Configuration::get('PS_SHIPPING_METHOD') AND $carrier->getMaxDeliveryPriceByWeight($id_zone) === false)
		OR (!Configuration::get('PS_SHIPPING_METHOD') AND $carrier->getMaxDeliveryPriceByPrice($id_zone) === false))
		{
			unset($result[$k]);
			continue ;
		}
		if ($row['range_behavior'])
		{
			// Get id zone
	        if (isset($cart->id_address_delivery) AND $cart->id_address_delivery)
				$id_zone = Address::getZoneById(intval($cart->id_address_delivery));
			else
				$id_zone = intval($defaultCountry->id_zone);
			if ((Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByWeight($row['id_carrier'], $cart->getTotalWeight(), $id_zone)))
			OR (!Configuration::get('PS_SHIPPING_METHOD') AND (!Carrier::checkDeliveryPriceByPrice($row['id_carrier'], $cart->getOrderTotal(true, 4), $id_zone))))
				{
					unset($result[$k]);
					continue ;
				}
		}
		$row['name'] = (strval($row['name']) != '0' ? $row['name'] : Configuration::get('PS_SHOP_NAME'));
		$row['price'] = $cart->getOrderShippingCost(intval($row['id_carrier']), true, $id_zone);
		$row['price_tax_exc'] = $cart->getOrderShippingCost(intval($row['id_carrier']), false);
		$row['img'] = file_exists(_PS_SHIP_IMG_DIR_.intval($row['id_carrier']).'.jpg') ? _THEME_SHIP_DIR_.intval($row['id_carrier']).'.jpg' : '';
		$resultsArray[] = $row;
	}



	// Wrapping fees
	$wrapping_fees = floatval(Configuration::get('PS_GIFT_WRAPPING_PRICE'));
	$wrapping_fees_tax = new Tax(intval(Configuration::get('PS_GIFT_WRAPPING_TAX')));
	$wrapping_fees_tax_exc = $wrapping_fees / (1 + ((floatval($wrapping_fees_tax->rate) / 100)));

	if (Validate::isUnsignedInt($cart->id_carrier))
	{
		$carrier = new Carrier(intval($cart->id_carrier));
		if ($carrier->active AND !$carrier->deleted)
			$checked = intval($cart->id_carrier);
	}
	
	// VE.cz
	if (!isset($checked) || (int)$checked==0)
		$checked = intval(Configuration::get('PS_CARRIER_DEFAULT'));

	$def_country = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
        $def_state = "";
        $tmp_country = new Country($def_country);
        if (intval($tmp_country->contains_states))
           $def_state = "1";


     	$one_page_checkout_settings = array (
                'hide_carrier' => intval(Configuration::get('OPC_DISPLAY_HIDE_CARRIER')),
                'hide_payment' => intval(Configuration::get('OPC_DISPLAY_HIDE_PAYMENT')),
                'checkout_tracker' => intval(Configuration::get('OPC_DISPLAY_CHECKOUT_TRACKER')),
                'scroll_cart' => intval(Configuration::get('OPC_DISPLAY_SCROLL_CART')),
                'scroll_summary' => intval(Configuration::get('OPC_DISPLAY_SCROLL_SUMMARY')),
                'ship2pay_active' => intval(Configuration::get('OPC_DISPLAY_SHIP2PAY_ACTIVE')),
                'payment_on_same_page' => intval(Configuration::get('OPC_DISPLAY_PAYMENT_SP')),
                'birthday' => intval(Configuration::get('OPC_DISPLAY_BIRTHDAY')),
                'gender' => intval(Configuration::get('OPC_DISPLAY_GENDER')),
                'virtual_no_delivery' => intval(Configuration::get('OPC_DISPLAY_NO_DELIVERY')),
                'default_country' => $def_country,
                'default_state' => $def_state,
		'invoice_address_message' => Configuration::get('OPC_FIELD_INV_ADDR_MSG'),
                'newsletter' => intval(Configuration::get('OPC_DISPLAY_NEWSLETTER')),
		'special_offers' => intval(Configuration::get('OPC_DISPLAY_SPECIAL_OFFERS')),
		'company_delivery' => intval(Configuration::get('OPC_DISPLAY_COMPANY_DELIVERY')),
		'address2_delivery' => intval(Configuration::get('OPC_DISPLAY_ADDRESS2_DELIVERY')),
		'country_delivery' => intval(Configuration::get('OPC_DISPLAY_COUNTRY_DELIVERY')),
		'phone' => intval(Configuration::get('OPC_DISPLAY_PHONE')),
		'additional_info' => intval(Configuration::get('OPC_DISPLAY_ADDITIONAL_INFO')),
		'same_addresses' => intval(Configuration::get('OPC_DISPLAY_SAME_ADDRESSES')),
		'company_invoice' => intval(Configuration::get('OPC_DISPLAY_COMPANY_INVOICE')),
		'address2_invoice' => intval(Configuration::get('OPC_DISPLAY_ADDRESS2_INVOICE')),
		'country_invoice' => intval(Configuration::get('OPC_DISPLAY_COUNTRY_INVOICE')),
		'optional_email' => intval(Configuration::get('OPC_DISPLAY_OPTIONAL_EMAIL')),
		'hide_email' => intval(Configuration::get('OPC_DISPLAY_HIDE_EMAIL')),
		'show_password' => intval(Configuration::get('OPC_DISPLAY_SHOW_PASSWORD')),
		'ex_texts' => intval(Configuration::get('OPC_DISPLAY_SAMPLE_VALUES'))
        );
#$smarty->debugging = true;

	restoreFieldsCookie();

	$virtual = array (
               'name' => Configuration::get('OPC_FIELD_VIRTUAL_NAME'),
               'lastname' => strtoupper(Configuration::get('OPC_FIELD_VIRTUAL_LASTNAME')),
               'address' => Configuration::get('OPC_FIELD_VIRTUAL_ADDRESS'),
               'city' => Configuration::get('OPC_FIELD_VIRTUAL_CITY'),
               'zip' => Configuration::get('OPC_FIELD_VIRTUAL_ZIP') 
	);

        $smarty->assign(array(
		'isLogged' => $cookie->isLogged(),
		'virtual' => $virtual,
                'fields_cookie' => $fields_cookie,
                'one_page_checkout_settings' => $one_page_checkout_settings,
                'countries' => $countries,
                'sl_country' => (isset($selectedCountry) ? $selectedCountry : 0),
                'inv_sl_country' => (isset($inv_selectedCountry) ? $inv_selectedCountry : 0),
		'checkedTOS' => intval($cookie->checkedTOS),
		'recyclablePackAllowed' => intval(Configuration::get('PS_RECYCLABLE_PACK')),
		'giftAllowed' => intval(Configuration::get('PS_GIFT_WRAPPING')),
		'conditions' => intval(Configuration::get('PS_CONDITIONS')),
		'recyclable' => intval($cart->recyclable),
		'gift_wrapping_price' => floatval(Configuration::get('PS_GIFT_WRAPPING_PRICE')),
		'carriers' => $resultsArray,
                'default_zone' => $default_zone,
		'HOOK_EXTRACARRIER' => Module::hookExec('extraCarrier', array('address' => $address)),
		'checked' => -1, //intval($checked),
		'back' => strval(Tools::getValue('back')),
		'total_wrapping' => number_format($wrapping_fees, 2, '.', ''),
		'total_wrapping_tax_exc' => number_format($wrapping_fees_tax_exc, 2, '.', '')));


        # shopping-cart template
        $summary = $cart->getSummaryDetails();
	$customizedDatas = Product::getAllCustomizedDatas(intval($cart->id));
	Product::addCustomizationPrice($summary['products'], $customizedDatas);

	if ($free_ship = intval(Configuration::get('PS_SHIPPING_FREE_PRICE')))
	{
		$discounts = $cart->getDiscounts();
		$total_free_ship =  $free_ship - ($summary['total_products_wt'] + $summary['total_discounts']);
		foreach ($discounts as $discount)
			if ($discount['id_discount_type'] == 3)
			{
				$total_free_ship = 0;
				break ;
			}
		$smarty->assign('free_ship', $total_free_ship);
	}
	$smarty->assign($summary);
	$token = Tools::getToken(false);
	$smarty->assign(array(
		'token_cart' => $token,
		'voucherAllowed' => Configuration::get('PS_VOUCHERS'),
		'HOOK_SHOPPING_CART' => Module::hookExec('shoppingCart', $summary),
		'HOOK_SHOPPING_CART_EXTRA' => Module::hookExec('shoppingCartExtra', $summary),
		'shippingCost' => $cart->getOrderTotal(true, 5),
		'shippingCostTaxExc' => $cart->getOrderTotal(false, 5),
		'customizedDatas' => $customizedDatas,
		'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
		'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
		'lastProductAdded' => $cart->getLastProduct()
		));

	$years = Tools::dateYears();
        $months = Tools::dateMonths();
        $days = Tools::dateDays();


        $smarty->assign(array(
                'years' => $years,
                'months' => $months,
                'days' => $days
        ));


	# Get all delivery / invoice addresses, so they can be displayed
	# ! this get delivery addresses ("inv-" means all but inv-)
	if (!isset($cookie->orders_since)) { # private data protection
	  $dlv_addresses = Address::getAllCustomerAddressesByType($cookie->id_customer, "inv-");
	  $inv_addresses = Address::getAllCustomerAddressesByType($cookie->id_customer, "dlv-");
 
	  $smarty->assign(array(
                'dlv_addresses' => $dlv_addresses,
                'inv_addresses' => $inv_addresses
          ));
	}




	Tools::safePostVars();
	$css_files = array(__PS_BASE_URI__.'css/thickbox.css' => 'all');
	$js_files = array(__PS_BASE_URI__.'js/jquery/thickbox-modified.js');
	include_once(dirname(__FILE__).'/header.php');
	$smarty->display(_PS_THEME_DIR_.'order-carrier.tpl');
}

/* Payment step */
function displayPayment()
{
	global $smarty, $cart, $currency, $cookie, $orderTotal;



	$normalFlow = (isset($_POST['id_payment_method']))?true:false;
	// if payment module on same page is turned on, redirect to "payment gateway"
	// which is simple generated page with single payment method and "click" will be generated there
	if (Configuration::get('OPC_DISPLAY_PAYMENT_SP') && $normalFlow)#config
	{
	  $id_payment_method = Tools::getValue('id_payment_method', '0');
	  $id_country =  Tools::getValue('payment_country', '0');
	  $id_carrier =  Tools::getValue('payment_carrier', '0');

	  $payment_methods = getPaymentMethods(0, $id_carrier);
	  foreach ($payment_methods as $method)
	    if ($method['link_hash'] == $id_payment_method) {
	       $found_method = $method;
	    }

	  $whole_content = "";
	  if (count($payment_methods)>0)
	    $whole_content = $payment_methods[0]['whole_content'];
	 // print_r($payment_methods);
	 // print_r($found_method);

	  $smarty->assign(array(
                'method_content' => $whole_content,
                'method_desc' => $found_method['desc'],
                'method_link' => $found_method['link']
          ));
	  include_once(dirname(__FILE__).'/header.php');
	  $smarty->display(_PS_THEME_DIR_.'payment-gateway.tpl');
	  exit;
	}//if (Configuration::get('OPC_DISPLAY_PAYMENT_SP') && $normalFlow)
	
	

	// Redirect instead of displaying payment modules if any module are grefted on
	Hook::backBeforePayment(strval(Tools::getValue('back')));


  



        /* We may need to display an order summary */
        //$smarty->assign($cart->getSummaryDetails());

        $cookie->checkedTOS = '1';


	$virtual = array (
               'name' => Configuration::get('OPC_FIELD_VIRTUAL_NAME'),
               'lastname' => strtoupper(Configuration::get('OPC_FIELD_VIRTUAL_LASTNAME')),
               'address' => Configuration::get('OPC_FIELD_VIRTUAL_ADDRESS'),
               'city' => Configuration::get('OPC_FIELD_VIRTUAL_CITY'),
               'zip' => Configuration::get('OPC_FIELD_VIRTUAL_ZIP') 
	);


        $wrapping_fees = floatval(Configuration::get('PS_GIFT_WRAPPING_PRICE'));
        $wrapping_fees_tax = new Tax(intval(Configuration::get('PS_GIFT_WRAPPING_TAX')));
        $wrapping_fees_tax_exc = $wrapping_fees / (1 + ((floatval($wrapping_fees_tax->rate) / 100)));

        $summary = $cart->getSummaryDetails();
	$customizedDatas = Product::getAllCustomizedDatas(intval($cart->id));
	Product::addCustomizationPrice($summary['products'], $customizedDatas);

	if ($free_ship = intval(Configuration::get('PS_SHIPPING_FREE_PRICE')))
	{
		$discounts = $cart->getDiscounts();
		$total_free_ship =  $free_ship - ($summary['total_products_wt'] + $summary['total_discounts']);
		foreach ($discounts as $discount)
			if ($discount['id_discount_type'] == 3)
			{
				$total_free_ship = 0;
				break ;
			}
		$smarty->assign('free_ship', $total_free_ship);
	}
	$smarty->assign($summary);


        $smarty->assign(array(
                        'token_cart' => $token,
                        'voucherAllowed' => Configuration::get('PS_VOUCHERS'),
                        'HOOK_SHOPPING_CART' => Module::hookExec('shoppingCart', $summary),
                        'HOOK_SHOPPING_CART_EXTRA' => Module::hookExec('shoppingCartExtra', $summary),
                        'shippingCost' => $cart->getOrderTotal(true, 5),
                        'shippingCostTaxExc' => $cart->getOrderTotal(false, 5),
                        'customizedDatas' => $customizedDatas,
                        'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
                        'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
                        'lastProductAdded' => $cart->getLastProduct(),
			'shippingCost' => $cart->getOrderTotal(true, 5),
			'virtual' => $virtual,
			'total_price' => floatval($orderTotal)
			));



  	if (Configuration::get('OPC_DISPLAY_SHIP2PAY_ACTIVE','0')==1) 
  	  $smarty->assign('HOOK_PAYMENT', Module::hookExecPaymentFront($cart->id_carrier));
	else
          $smarty->assign('HOOK_PAYMENT', Module::hookExecPayment());
 



	Tools::safePostVars();
	include_once(dirname(__FILE__).'/header.php');
	$smarty->display(_PS_THEME_DIR_.'order-payment.tpl');
}



/* Confirmation step */
/* not used
function displaySummary()
{
	global $smarty, $cart;
	
	if (file_exists(_PS_SHIP_IMG_DIR_.intval($cart->id_carrier).'.jpg'))
		$smarty->assign('carrierPicture', 1);
	$summary = $cart->getSummaryDetails();
	$customizedDatas = Product::getAllCustomizedDatas(intval($cart->id));
	Product::addCustomizationPrice($summary['products'], $customizedDatas);
	
	if ($free_ship = intval(Configuration::get('PS_SHIPPING_FREE_PRICE')))
	{
		$discounts = $cart->getDiscounts();
		$total_free_ship =  $free_ship - ($summary['total_products_wt'] + $summary['total_discounts']);
		foreach ($discounts as $discount)
			if ($discount['id_discount_type'] == 3)
			{
				$total_free_ship = 0;
				break ;
			}
		$smarty->assign('free_ship', $total_free_ship);
	}
	$smarty->assign($summary);
	$token = Tools::getToken(false);
	$smarty->assign(array(
		'token_cart' => $token,
		'voucherAllowed' => Configuration::get('PS_VOUCHERS'),
		'HOOK_SHOPPING_CART' => Module::hookExec('shoppingCart', $summary),
		'HOOK_SHOPPING_CART_EXTRA' => Module::hookExec('shoppingCartExtra', $summary),
		'shippingCost' => $cart->getOrderTotal(true, 5),
		'shippingCostTaxExc' => $cart->getOrderTotal(false, 5),
		'customizedDatas' => $customizedDatas,
		'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
		'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
		'lastProductAdded' => $cart->getLastProduct()
		));
	Tools::safePostVars();
	include_once(dirname(__FILE__).'/header.php');
	$smarty->display(_PS_THEME_DIR_.'shopping-cart.tpl');
}
*/

?>
