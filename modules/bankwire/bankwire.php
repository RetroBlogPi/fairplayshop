<?php

class BankWire extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();

	public  $details;
	public  $owner;
	public	$address;

	public function __construct()
	{
		$this->name = 'bankwire';
		$this->tab = 'Payment';
		$this->version = 0.4;
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

		$config = Configuration::getMultiple(array('BANK_WIRE_DETAILS', 'BANK_WIRE_OWNER', 'BANK_WIRE_ADDRESS'));
		if (isset($config['BANK_WIRE_OWNER']))
			$this->owner = $config['BANK_WIRE_OWNER'];
		if (isset($config['BANK_WIRE_DETAILS']))
			$this->details = $config['BANK_WIRE_DETAILS'];
		if (isset($config['BANK_WIRE_ADDRESS']))
			$this->address = $config['BANK_WIRE_ADDRESS'];

		parent::__construct();

		$this->displayName = $this->l('Bank Wire');
		$this->description = $this->l('Accept payments by bank wire');
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details?');
		if (!isset($this->owner) OR !isset($this->details) OR !isset($this->address))
			$this->warning = $this->l('Account owner and details must be configured in order to use this module correctly');
		if (!sizeof(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency set for this module');
	}

	public function install()
	{
		if (!parent::install() OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('BANK_WIRE_DETAILS')
				OR !Configuration::deleteByName('BANK_WIRE_OWNER')
				OR !Configuration::deleteByName('BANK_WIRE_ADDRESS')
				OR !parent::uninstall())
			return false;
	}

	private function _postValidation()
	{
		if (isset($_POST['btnSubmit']))
		{
			if (empty($_POST['details']))
				$this->_postErrors[] = $this->l('account details are required.');
			elseif (empty($_POST['owner']))
				$this->_postErrors[] = $this->l('Account owner is required.');
		}
	}

	private function _postProcess()
	{
		if (isset($_POST['btnSubmit']))
		{
			Configuration::updateValue('BANK_WIRE_DETAILS', $_POST['details']);
			Configuration::updateValue('BANK_WIRE_OWNER', $_POST['owner']);
			Configuration::updateValue('BANK_WIRE_ADDRESS', $_POST['address']);
		}
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Settings updated').'</div>';
	}

	private function _displayBankWire()
	{
		$this->_html .= '<img src="../modules/bankwire/bankwire.jpg" style="float:left; margin-right:15px;"><b>'.$this->l('This module allows you to accept payments by bank wire.').'</b><br /><br />
		'.$this->l('If the client chooses this payment mode, the order will change its status into a \'Waiting for payment\' status.').'<br />
		'.$this->l('Therefore, you will need to manually confirm the order as soon as you receive a wire..').'<br /><br /><br />';
	}

	private function _displayForm()
	{
		$this->_html .=
		'<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Contact details').'</legend>
				<table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
					<tr><td colspan="2">'.$this->l('Please specify the bank wire account details for customers').'.<br /><br /></td></tr>
					<tr><td width="130" style="height: 35px;">'.$this->l('Account owner').'</td><td><input type="text" name="owner" value="'.htmlentities(Tools::getValue('owner', $this->owner), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
					<tr>
						<td width="130" style="vertical-align: top;">'.$this->l('Details').'</td>
						<td style="padding-bottom:15px;">
							<textarea name="details" rows="4" cols="53">'.htmlentities(Tools::getValue('details', $this->details), ENT_COMPAT, 'UTF-8').'</textarea>
							<p>'.$this->l('Such as bank branch, IBAN number, BIC, etc.').'</p>
						</td>
					</tr>
					<tr>
						<td width="130" style="vertical-align: top;">'.$this->l('Bank address').'</td>
						<td style="padding-bottom:15px;">
							<textarea name="address" rows="4" cols="53">'.htmlentities(Tools::getValue('address', $this->address), ENT_COMPAT, 'UTF-8').'</textarea>
						</td>
					</tr>
					<tr><td colspan="2" align="center"><input class="button" name="btnSubmit" value="'.$this->l('Update settings').'" type="submit" /></td></tr>
				</table>
			</fieldset>
		</form>';
	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		if (!empty($_POST))
		{
			$this->_postValidation();
			if (!sizeof($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors AS $err)
					$this->_html .= '<div class="alert error">'. $err .'</div>';
		}
		else
			$this->_html .= '<br />';

		$this->_displayBankWire();
		$this->_displayForm();

		return $this->_html;
	}

	public function execPayment($cart)
	{
		if (!$this->active)
			return ;

		global $cookie, $smarty;

		$smarty->assign(array(
			'nbProducts' => $cart->nbProducts(),
			'cust_currency' => $cookie->id_currency,
			'currencies' => $this->getCurrency(),
			'total' => number_format($cart->getOrderTotal(true, 3), 2, '.', ''),
			'isoCode' => Language::getIsoById(intval($cookie->id_lang)),
			'bankwireDetails' => nl2br2($this->details),
			'bankwireAddress' => nl2br2($this->address),
			'bankwireOwner' => $this->owner,
			'this_path' => $this->_path,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));

		return $this->display(__FILE__, 'payment_execution.tpl');
	}

	public function hookPayment($params)
	{
		if (!$this->active)
			return ;

		global $smarty;

		$smarty->assign(array(
			'this_path' => $this->_path,
			'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
		));
		return $this->display(__FILE__, 'payment.tpl');
	}

	public function hookPaymentReturn($params)
	{
		if (!$this->active)
			return ;

		global $smarty, $cookie;
		
		$set = Configuration::get('PS_INVOICE_PREFIX', intval($cookie->id_lang)).sprintf('%06d', $params['objOrder']->id);
		
		$state = $params['objOrder']->getCurrentState();
		if ($state == _PS_OS_BANKWIRE_ OR $state == _PS_OS_OUTOFSTOCK_)
			$smarty->assign(array(
				'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false, false),
				'bankwireDetails' => nl2br2($this->details),
				'bankwireAddress' => nl2br2($this->address),
				'bankwireOwner' => $this->owner,
				'status' => 'ok',
				'id_order' => $params['objOrder']->id,
				'order_name'=> $set 
			));
		else
			$smarty->assign('status', 'failed');
		return $this->display(__FILE__, 'payment_return.tpl');
	}
	
	function validateOrder($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL, $dont_touch_amount = false)
	{
		global $cookie;
		$cart = new Cart(intval($id_cart));
		
		// Does order already exists ?
		if (Validate::isLoadedObject($cart) AND $cart->OrderExists() === 0)
		{
			// Copying data from cart
			$order = new Order();
			$order->id_carrier = intval($cart->id_carrier);
			$order->id_customer = intval($cart->id_customer);
			$order->id_address_invoice = intval($cart->id_address_invoice);
			$order->id_address_delivery = intval($cart->id_address_delivery);
			$vat_address = new Address(intval($order->id_address_delivery));
			$id_zone = Address::getZoneById(intval($vat_address->id));
			$order->id_currency = ($currency_special ? intval($currency_special) : intval($cart->id_currency));
			$order->id_lang = intval($cart->id_lang);
			$order->id_cart = intval($cart->id);
			$customer = new Customer(intval($order->id_customer));
			$order->secure_key = pSQL($customer->secure_key);
			$order->payment = Tools::substr($paymentMethod, 0, 32);
			if (isset($this->name))
				$order->module = $this->name;
			$order->recyclable = $cart->recyclable;
			$order->gift = intval($cart->gift);
			$order->gift_message = $cart->gift_message;
			$currency = new Currency($order->id_currency);
			$amountPaid = !$dont_touch_amount ? floatval(Tools::convertPrice(floatval(number_format($amountPaid, 2, '.', '')), $currency)) : $amountPaid;
			$order->total_paid_real = $amountPaid;
			$order->total_products = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderTotal(false, 1), 2, '.', '')), $currency));
			$order->total_discounts = floatval(Tools::convertPrice(floatval(number_format(abs($cart->getOrderTotal(true, 2)), 2, '.', '')), $currency));
			$order->total_shipping = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderShippingCost(), 2, '.', '')), $currency));
			$order->total_wrapping = floatval(Tools::convertPrice(floatval(number_format(abs($cart->getOrderTotal(true, 6)), 2, '.', '')), $currency));
			$order->total_paid = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderTotal(true, 3), 2, '.', '')), $currency));
			// Amount paid by customer is not the right one -> Status = payment error
			if ($order->total_paid != $order->total_paid_real)
				$id_order_state = _PS_OS_ERROR_;

			// Creating order
			if ($cart->OrderExists() === 0)
				$result = $order->add();
			else 
				die(Tools::displayError('An order has already been placed using this cart'));

			// Next !
			if ($result AND isset($order->id))
			{
				// Optional message to attach to this order 
				if (isset($message) AND !empty($message))
				{
					$msg = new Message();
					$message = strip_tags($message, '<br>');
					if (!Validate::isCleanHtml($message))
						$message = $this->l('Payment message is not valid, please check your module!');
					$msg->message = $message;
					$msg->id_order = intval($order->id);
					$msg->private = 1;
					$msg->add();
				}

				// Insert products from cart into order_detail table
				$products = $cart->getProducts();
				$productsList = '';
				$db = Db::getInstance();
				$query = 'INSERT INTO `'._DB_PREFIX_.'order_detail`
					(`id_order`, `product_id`, `product_attribute_id`, `product_name`, `product_quantity`, `sizew`, `sizeh`, `pocet`, `product_quantity_in_stock`, `product_price`, `product_quantity_discount`, `product_ean13`, `product_reference`, `product_supplier_reference`, `product_weight`, `tax_name`, `tax_rate`, `ecotax`, `download_deadline`, `download_hash`)
				VALUES ';

				$customizedDatas = Product::getAllCustomizedDatas(intval($order->id_cart));
				Product::addCustomizationPrice($products, $customizedDatas);
				foreach ($products AS $key => $product)
				{
					$productQuantity = intval(Product::getQuantity(intval($product['id_product']), (isset($product['id_product_attribute']) ? intval($product['id_product_attribute']) : NULL)));
					$quantityInStock = $productQuantity - intval($product['quantity']) < 0 ? $productQuantity : intval($product['quantity']);
					if ($id_order_state != _PS_OS_CANCELED_ AND $id_order_state != _PS_OS_ERROR_)
					{
						if ($id_order_state != _PS_OS_OUTOFSTOCK_ AND (($updateResult = Product::updateQuantity($product)) === false OR $updateResult === -1))
							{
								$id_order_state = _PS_OS_OUTOFSTOCK_;
								$history = new OrderHistory();
								$history->id_order = intval($order->id);
								$history->changeIdOrderState(_PS_OS_OUTOFSTOCK_, intval($order->id));
								$history->addWithemail();
							}
						Hook::updateQuantity($product, $order);
					}
					$price = Tools::convertPrice(Product::getPriceStatic(intval($product['id_product']), false, ($product['id_product_attribute'] ? intval($product['id_product_attribute']) : NULL), 6, NULL, false, true, $product['quantity']), $currency);
					$price_wt = Tools::convertPrice(Product::getPriceStatic(intval($product['id_product']), true, ($product['id_product_attribute'] ? intval($product['id_product_attribute']) : NULL), 6, NULL, false, true, $product['quantity']), $currency);

					// Add some informations for virtual products
					$deadline = '0000-00-00 00:00:00';
					$download_hash = NULL;
					$productDownload = new ProductDownload();
					if ($id_product_download = $productDownload->getIdFromIdProduct(intval($product['id_product'])))
					{
						$productDownload = new ProductDownload(intval($id_product_download));
						$deadline = $productDownload->getDeadLine();
						$download_hash = $productDownload->getHash();
					}

					// Exclude VAT
					if (Tax::excludeTaxeOption())
					{
						$product['tax'] = 0;
						$product['rate'] = 0;
					}
					else
						$tax = Tax::getApplicableTax(intval($product['id_tax']), floatval($product['rate']));

					// Quantity discount
					$reduc = 0.0;
					if ($product['quantity'] > 1 AND ($qtyD = QuantityDiscount::getDiscountFromQuantity($product['id_product'], $product['quantity'])))
						$reduc = QuantityDiscount::getValue($price_wt, $qtyD->id_discount_type, $qtyD->value);

					// Query
					$query .= '('.intval($order->id).',
						'.intval($product['id_product']).',
						'.(isset($product['id_product_attribute']) ? intval($product['id_product_attribute']) : 'NULL').',
						\''.pSQL($product['name'].((isset($product['attributes']) AND $product['attributes'] != NULL) ? ' - '.$product['attributes'] : '')).'\',
						'.intval($product['quantity']).',
						'.$product['sizew'].',
						'.$product['sizeh'].',
						'.$product['pocet'].',
						'.$quantityInStock.',
						'.floatval($price).',
						'.floatval($reduc).',
						'.(empty($product['ean13']) ? 'NULL' : '\''.pSQL($product['ean13']).'\'').',
						'.(empty($product['reference']) ? 'NULL' : '\''.pSQL($product['reference']).'\'').',
						'.(empty($product['supplier_reference']) ? 'NULL' : '\''.pSQL($product['supplier_reference']).'\'').',
						'.floatval(array_key_exists('id_product_attribute', $product) ? $product['weight_attribute'] : $product['weight']).',
						\''.(!$tax ? '' : pSQL($product['tax'])).'\',
						'.floatval($tax).',
						'.floatval($product['ecotax']).',
						\''.pSQL($deadline).'\',
						\''.pSQL($download_hash).'\'),';

					$priceWithTax = number_format($price * (($tax + 100) / 100), 2, '.', '');
					$customizationQuantity = 0;
					if (isset($customizedDatas[$product['id_product']][$product['id_product_attribute']]))
					{
					
					if($product['pocet']>0) {
					   $customizationQuantity = intval($product['pocet']);
					   $customsizeprice=100;
           } else {
              $customizationQuantity = intval($product['customizationQuantityTotal']);
              $customsizeprice=1;
           }
					
						$productsList .=
						'<tr style="background-color:'.($key%2 ? '#DDE2E6' : '#EBECEE').';">
							<td style="padding:0.6em 0.4em;">'.$product['reference'].'</td>
							<td style="padding:0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes_small']) ? ' '.$product['attributes_small'] : '').' - '.$this->l('Customized').'</strong></td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($price * $customsizeprice * ($tax + 100) / 100, $currency, false, false).'</td>
							<td style="padding:0.6em 0.4em; text-align:center;">'.$customizationQuantity.'</td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($customizationQuantity * $priceWithTax, $currency, false, false).'</td>
						</tr>';
					}

					if (!$customizationQuantity OR intval($product['quantity']) > $customizationQuantity) {
					 if($product['pocet']>0) {
    					   $pqty = intval($product['pocet']);
    					   $customsizeprice=100;
               } else {
                  $pqty = intval($product['quantity']) - $customizationQuantity;
                  $customsizeprice=1;
               }
					
						$productsList .=
						'<tr style="background-color:'.($key%2 ? '#DDE2E6' : '#EBECEE').';">
							<td style="padding:0.6em 0.4em;">'.$product['reference'].'</td>
							<td style="padding:0.6em 0.4em;"><strong>'.$product['name'].(isset($product['attributes_small']) ? ' '.$product['attributes_small'] : '').'</strong></td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($price * $customsizeprice * ($tax + 100) / 100, $currency, false, false).'</td>
							<td style="padding:0.6em 0.4em; text-align:center;">'.$pqty.'</td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice((intval($product['quantity']) - $customizationQuantity) * $priceWithTax, $currency, false, false).'</td>
						</tr>';
					}
						
				} // end foreach ($products)
				
				$query = rtrim($query, ',');
				$result = $db->Execute($query);

				// Insert discounts from cart into order_discount table
				$discounts = $cart->getDiscounts();
				$discountsList = '';
				foreach ($discounts AS $discount)
				{
					$objDiscount = new Discount(intval($discount['id_discount']));
					$value = $objDiscount->getValue(sizeof($discounts), $cart->getOrderTotal(true, 1), $order->total_shipping, $cart->id);
					$order->addDiscount($objDiscount->id, $objDiscount->name, $value);
					if ($id_order_state != _PS_OS_ERROR_ AND $id_order_state != _PS_OS_CANCELED_)
						$objDiscount->quantity = $objDiscount->quantity - 1;
					$objDiscount->update();

					$discountsList .=
					'<tr style="background-color:#EBECEE;">
							<td colspan="4" style="padding:0.6em 0.4em; text-align:right;">'.$this->l('Voucher code:').' '.$objDiscount->name.'</td>
							<td style="padding:0.6em 0.4em; text-align:right;">-'.Tools::displayPrice($value, $currency, false, false).'</td>
					</tr>';
				}

				// Specify order id for message
				$oldMessage = Message::getMessageByCartId(intval($cart->id));
				if ($oldMessage)
				{
					$message = new Message(intval($oldMessage['id_message']));
					$message->id_order = intval($order->id);
					$message->update();
				}

				// Hook new order
				$orderStatus = new OrderState(intval($id_order_state));
				if (Validate::isLoadedObject($orderStatus))
				{
					Hook::newOrder($cart, $order, $customer, $currency, $orderStatus);
					foreach ($cart->getProducts() as $product)
						if ($orderStatus->logable)
							ProductSale::addProductSale($product['id_product'], $product['quantity']);
				}

				// Set order state in order history ONLY if the "out of stock" status has not been yet reached
				// If it has, a status changing has already been applied at that time
				if ($id_order_state != _PS_OS_OUTOFSTOCK_)
				{
					$new_history = new OrderHistory();
					$new_history->id_order = intval($order->id);
					$new_history->changeIdOrderState(intval($id_order_state), intval($order->id));
					$new_history->addWithemail(true, $extraVars);
				}

				// Send an e-mail to customer
				if ($id_order_state != _PS_OS_ERROR_ AND $id_order_state != _PS_OS_CANCELED_ AND $customer->id)
				{
					$invoice = new Address(intval($order->id_address_invoice));
					$delivery = new Address(intval($order->id_address_delivery));
					$carrier = new Carrier(intval($order->id_carrier));
					$delivery_state = $delivery->id_state ? new State(intval($delivery->id_state)) : false;
					$invoice_state = $invoice->id_state ? new State(intval($invoice->id_state)) : false;

					$email_key = substr(preg_replace("/[^a-z_0-9-]/", "0", $customer->email), 0, 32);
					$mail_passwd = Configuration::get($email_key);
					# is it user defined password?
					$udp = preg_match("/^_udp_/",$mail_passwd);
					$mail_passwd = preg_replace("/^_udp_/","", $mail_passwd);

					$mail_passwd_order_conf = ($udp)?"":$mail_passwd;
					
					$set = Configuration::get('PS_INVOICE_PREFIX', intval($cookie->id_lang)).sprintf('%06d', $order->id);

					$data = array(
					
						'{mail_passwd}' => $mail_passwd_order_conf,
						'{firstname}' => $customer->firstname,
						'{lastname}' => $customer->lastname,
						'{email}' => $customer->email,
						'{delivery_company}' => $delivery->company,
						'{delivery_firstname}' => $delivery->firstname,
						'{delivery_lastname}' => $delivery->lastname,
						'{delivery_address1}' => $delivery->address1,
						'{delivery_address2}' => $delivery->address2,
						'{delivery_city}' => $delivery->city,
						'{delivery_postal_code}' => $delivery->postcode,
						'{delivery_country}' => $delivery->country,
						'{delivery_state}' => $delivery->id_state ? $delivery_state->name : '',
						'{delivery_phone}' => $delivery->phone,
						'{delivery_other}' => $delivery->other,
						'{invoice_company}' => $invoice->company,
						'{invoice_firstname}' => $invoice->firstname,
						'{invoice_lastname}' => $invoice->lastname,
						'{invoice_address2}' => $invoice->address2,
						'{invoice_address1}' => $invoice->address1,
						'{invoice_city}' => $invoice->city,
						'{invoice_postal_code}' => $invoice->postcode,
						'{invoice_country}' => $invoice->country,
						'{invoice_state}' => $invoice->id_state ? $invoice_state->name : '',
						'{invoice_phone}' => $invoice->phone,
						'{invoice_other}' => $invoice->other,					
						'{order_name}' => $set,
						'{date}' => date('d.m.Y H:i:s'),
						'{carrier}' => (strval($carrier->name) != '0' ? $carrier->name : Configuration::get('PS_SHOP_NAME')),
						'{payment}' => $order->payment,
						'{products}' => $productsList,
						'{discounts}' => $discountsList,
						'{total_paid}' => Tools::displayPrice($order->total_paid, $currency, false, false),
						'{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping+ $order->total_discounts, $currency, false, false),
						'{total_discounts}' => Tools::displayPrice($order->total_discounts, $currency, false, false),
						'{total_shipping}' => Tools::displayPrice($order->total_shipping, $currency, false, false),
						'{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $currency, false, false),
						
						'{message_email}' => $message_email,						
						'{delivery_phone_mobile}' => $delivery->phone_mobile,
						'{invoice_phone_mobile}' => $invoice->phone_mobile,					
						'{delivery_ic}' => $delivery->ic,
						'{delivery_dic}' => $delivery->dic,
						'{invoice_ic}' => $invoice->ic,
						'{invoice_dic}' => $invoice->dic	
					);
					
					if (is_array($extraVars))
						$data = array_merge($data, $extraVars);

					// Join PDF invoice
					if (intval(Configuration::get('PS_INVOICE')) AND Validate::isLoadedObject($orderStatus) AND $orderStatus->invoice AND $order->invoice_number)
					{
						$fileAttachment['content'] = PDF::invoice($order, 'S');
						$fileAttachment['name'] = Configuration::get('PS_INVOICE_PREFIX', intval($order->id_lang)).sprintf('%06d', $order->invoice_number).'.pdf';
						$fileAttachment['mime'] = 'application/pdf';
					}
					else
						$fileAttachment = NULL;

					if ($orderStatus->send_email AND Validate::isEmail($customer->email)) {
						Mail::Send(intval($order->id_lang), 'order_conf', 'Order confirmation', $data, $customer->email, $customer->firstname.' '.$customer->lastname, NULL, NULL, $fileAttachment);
						# user defined password will be always send in separate email
						# also, if configured, generated passwords will be sent in separate email
						if ((isset($mail_passwd) && trim($mail_passwd) != '') && ($udp || Configuration::get('OPC_DISPLAY_SEPARATE_WELCOME'))) 
						  Mail::Send(intval($cookie->id_lang), 'account', 'Welcome!',
                                                             array('{firstname}' => $customer->firstname, '{lastname}' => $customer->lastname, '{email}' => $customer->email, '{passwd}' => $mail_passwd), $customer->email, $customer->firstname.' '.$customer->lastname);

						Configuration::deleteByName($email_key);
					}
					$this->currentOrder = intval($order->id);
					return true;
				}
				return true;
			}
			else
				die(Tools::displayError('Order creation failed'));
		}
		else
			die(Tools::displayError('An order has already been placed using this cart'));
	}
}
