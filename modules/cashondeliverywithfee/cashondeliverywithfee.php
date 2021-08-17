<?php
/*
*  LEGAL NOTICE
* Prestaworks® - http://www.prestaworks.com
Copyright (c) 2008
by Prestaworks
* Permission is hereby granted, to the buyer of this software to use it freely in association with prestashop. 
* The buyer are free to use/edit/modify this software in anyway he/she see fit.
* The buyer are NOT allowed to redistribute this module in anyway or resell it or redistribute it to third party.
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
class CashOnDeliveryWithFee extends PaymentModule
{	
 	private	$_html = '';
	private $_postErrors = array();
	
	function __construct()
	{
		$this->name = 'cashondeliverywithfee';
		$this->tab = 'Payment';
		$this->version = 1.0;

		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		
		parent::__construct();

		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Cash on delivery with fee (COD)');
		$this->description = $this->l('Accept cash on delivery payments with extra fee');
	}

	function install()
	{
		if (!parent::install() OR !Configuration::updateValue('COD_FEE', '0') OR !Configuration::updateValue('COD_FEE_TYPE', '0') OR !Configuration::updateValue('COD_FEE_MIN', '0')
			OR !$this->registerHook('payment') OR !$this->registerHook('paymentReturn'))
			return false;
		return true;
	}	
	public function uninstall()
	{
		if (!Configuration::deleteByName('COD_FEE') OR (!Configuration::deleteByName('COD_FEE_TYPE')) OR !parent::uninstall() OR (!Configuration::deleteByName('COD_FEE_MIN')))
			return false;
		return true;
	}
	
	public function getContent()
	{
		$this->_html = '<h2>Platba na dobírku</h2>';
		if (isset($_POST['submitCOD']))
		{
			if (empty($_POST['fee']))
				$_POST['fee'] = '0';
			if (empty($_POST['feetype']))
				$_POST['feetype'] = '0';
			if (empty($_POST['feemin']))
				$_POST['feemin'] = '0';
			if (!sizeof($this->_postErrors))
			{
				Configuration::updateValue('COD_FEE', floatval( $_POST['fee']) );
				Configuration::updateValue('COD_FEE_TYPE', floatval( $_POST['feetype']) );
				Configuration::updateValue('COD_FEE_MIN', floatval( $_POST['feemin']) );
				$this->displayConf();
			}
			else
				$this->displayErrors();
		}

		$this->displayCOD();
		$this->displayFormSettings();
		return $this->_html;
	}
	
	public function displayCOD()
	{
		$this->_html .= '
		<img src="../modules/cashondelivery/logo.gif" style="float:left; margin-right:15px;" />
		<b>'.$this->l('Tento modul umožňuje nastavit poplatek k platbě na dobírku.').'</b><br /><br /><br />';
	}
	
	
	public function displayFormSettings()
	{
		$conf = Configuration::getMultiple(array('COD_FEE','COD_FEE_TYPE','COD_FEE_MIN'));
		$fee = array_key_exists('fee', $_POST) ? $_POST['fee'] : (array_key_exists('COD_FEE', $conf) ? $conf['COD_FEE'] : '');
		$feetype = array_key_exists('feetype', $_POST) ? $_POST['feetype'] : (array_key_exists('COD_FEE_TYPE', $conf) ? $conf['COD_FEE_TYPE'] : '');
		$feemin = array_key_exists('feemin', $_POST) ? $_POST['feemin'] : (array_key_exists('COD_FEE_MIN', $conf) ? $conf['COD_FEE_MIN'] : '');

		$this->_html .= '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
		<fieldset>
			<legend><img src="../img/admin/contact.gif" />'.$this->l('Nastavení').'</legend>
			<label>'.$this->l('Poplatek').'</label>
			<div class="margin-form"><input type="text" size="33" name="fee" value="'.htmlentities($fee, ENT_COMPAT, 'UTF-8').'" /></div>
			<br />
			<label>'.$this->l('Typ').'</label>
			<div class="margin-form">
			<input type="radio" name="feetype" value="0" '.(!$feetype ? 'checked="checked"' : '').' /> '.$this->l('Amount').'
			<input type="radio" name="feetype" value="1" '.($feetype ? 'checked="checked"' : '').' /> '.$this->l('%').'
			</div>
			<label>'.$this->l('Minimum Fee').'</label>
			<div class="margin-form"><input type="text" size="33" name="feemin" value="'.htmlentities($feemin, ENT_COMPAT, 'UTF-8').'" /></div>
			<br />
			<br /><center><input type="submit" name="submitCOD" value="'.$this->l('Uložit nastavení').'" class="button" /></center>
		</fieldset>
		</form><br /><br />';
	}
	


public function displayConf()
	{
		$this->_html .= '
		<div class="conf confirm">
			<img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />
			'.$this->l('Settings updated').'
		</div>';
	}
	
	public function displayErrors()
	{
		$nbErrors = sizeof($this->_postErrors);
		$this->_html .= '
		<div class="alert error">
			<h3>'.($nbErrors > 1 ? $this->l('There are') : $this->l('There is')).' '.$nbErrors.' '.($nbErrors > 1 ? $this->l('errors') : $this->l('error')).'</h3>
			<ol>';
		foreach ($this->_postErrors AS $error)
			$this->_html .= '<li>'.$error.'</li>';
		$this->_html .= '
			</ol>
		</div>';
	}

	function hookPayment($params)
	{
		global $smarty;
		$currency = $this->getCurrency();
	 	/* Photo is copyrighted by Leticia Wilson - Fotolia.com, licenced to PrestaShop company */
		$smarty->assign(array(
            'this_path' => $this->_path,
			'fee' => number_format($this->getCost($params), 2, '.', ''),
            'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/'.$this->name.'/'
            ));
		return $this->display(__FILE__, 'payment.tpl');
	}
	
	//Return the fee cost
	function getCost($params)
	{
	if(Configuration::get('COD_FEE_TYPE')==0)
	{
		return floatval(Configuration::get('COD_FEE'));
	}
	else
	{
		$minimalfee = floatval(Configuration::get('COD_FEE_MIN'));
		$cartvalue = floatval($params['cart']->getOrderTotal(true, 3));
		$percent = floatval(Configuration::get('COD_FEE'));
		$percent = $percent / 100;
		$fee = $cartvalue * $percent;
		
		if($fee<$minimalfee)
		{
			$fee=$minimalfee;
		}
		return floatval($fee);
	}
	}
	
	//Return the fee cost
	function getCostValidated($cart)
	{
	if(Configuration::get('COD_FEE_TYPE')==0)
	{
		return floatval(Configuration::get('COD_FEE'));
	}
	else
	{
		$minimalfee = floatval(Configuration::get('COD_FEE_MIN'));
		$cartvalue = floatval($cart->getOrderTotal(true, 3));
		$percent = floatval(Configuration::get('COD_FEE'));
		$percent = $percent / 100;
		$fee = $cartvalue * $percent;
		if($fee<$minimalfee)
		{
			$fee=$minimalfee;
		}
		return floatval($fee);
	}
	}
	
	function hookPaymentReturn($params)
	{
		return $this->display(__FILE__, 'confirmation.tpl');
	}
	
	/**
	* Validate an order in database AND ADD EXTRA COST
	* Function called from a payment module
	*
	* @param integer $id_cart Value
	* @param integer $id_order_state Value
	* @param float $amountPaid Amount really paid by customer (in the default currency)
	* @param string $paymentMethod Payment method (eg. 'Credit cart')
	* @param string $message Message to attach to order
	*/

	function validateOrderCOD($id_cart, $id_order_state, $amountPaid, $paymentMethod = 'Unknown', $message = NULL, $extraVars = array(), $currency_special = NULL)
	{
		$cart = new Cart(intval($id_cart));
		$CODfee = $this->getCostValidated($cart);

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
			$amountPaid = !$dont_touch_amount ? floatval(Tools::convertPrice(floatval(number_format($amountPaid+$CODfee, 2, '.', '')), $currency)) : $amountPaid+$CODfee;
			$order->total_paid_real = $amountPaid;
			$order->total_products = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderTotal(false, 1), 2, '.', '')), $currency));
			$order->total_discounts = floatval(Tools::convertPrice(floatval(number_format(abs($cart->getOrderTotal(true, 2)), 2, '.', '')), $currency));
			$order->total_shipping = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderShippingCost()+$CODfee, 2, '.', '')), $currency));
			$order->total_wrapping = floatval(Tools::convertPrice(floatval(number_format(abs($cart->getOrderTotal(true, 6)), 2, '.', '')), $currency));
			$order->total_paid = floatval(Tools::convertPrice(floatval(number_format($cart->getOrderTotal(true, 3)+$CODfee, 2, '.', '')), $currency));
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
					(`id_order`, `product_id`, `product_attribute_id`, `product_name`, `product_quantity`, `sizew`, `sizeh`, `pocet`, `product_quantity_in_stock`, `product_price`, `product_quantity_discount`, `product_ean13`, `product_reference`, `product_supplier_reference`, `product_weight`, `tax_name`, `tax_rate`, `ecotax`, `download_deadline`, `download_hash`)				VALUES ';

				$customizedDatas = Product::getAllCustomizedDatas(intval($order->id_cart));
				Product::addCustomizationPrice($products, $customizedDatas);
				
				// VE.cz - odkaz na produktech
				$link = new Link();				
				
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
								$history->addWithemail(true, false, false);
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
							<td style="padding:0.6em 0.4em;"><strong><a href="'.$link->getProductLink($product['id_product'], $product['link_rewrite']).'">'.$product['name'].(isset($product['attributes_small']) ? ' '.$product['attributes_small'] : '').' - '.$this->l('Customized').'</a></strong></td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($price * $customsizeprice * ($tax + 100) / 100, $currency, false, false).'</td>
							<td style="padding:0.6em 0.4em; text-align:center;">'.$customizationQuantity.'</td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($customizationQuantity * $priceWithTax, $currency, false, false).'</td>
						</tr>';
					}

					if (!$customizationQuantity OR intval($product['quantity']) > $customizationQuantity)
					
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
							<td style="padding:0.6em 0.4em;"><strong><a href="'.$link->getProductLink($product['id_product'], $product['link_rewrite']).'">'.$product['name'].(isset($product['attributes_small']) ? ' '.$product['attributes_small'] : '').'</a></strong></td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice($price * $customsizeprice * ($tax + 100) / 100, $currency, false, false).'</td>
							<td style="padding:0.6em 0.4em; text-align:center;">'.$pqty.'</td>
							<td style="padding:0.6em 0.4em; text-align:right;">'.Tools::displayPrice((intval($product['quantity']) - $customizationQuantity) * $priceWithTax, $currency, false, false).'</td>
						</tr>';
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

        // VE.cz - poznamka do emailu
        $message_email = "";

				// Specify order id for message
				$oldMessage = Message::getMessageByCartId(intval($cart->id));
				if ($oldMessage)
				{
					$message = new Message(intval($oldMessage['id_message']));
					$message->id_order = intval($order->id);
					
					$message_email = $message->message;
					
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
					$new_history->addWithemail(true, $extraVars, false);
				}

				// Send an e-mail to customer
				if ($id_order_state != _PS_OS_ERROR_ AND $id_order_state != _PS_OS_CANCELED_ AND $customer->id)
				{
					$invoice = new Address(intval($order->id_address_invoice));
					$delivery = new Address(intval($order->id_address_delivery));
					$carrier = new Carrier(intval($order->id_carrier));
					$delivery_state = $delivery->id_state ? new State(intval($delivery->id_state)) : false;
					$invoice_state = $invoice->id_state ? new State(intval($invoice->id_state)) : false;

					$data = array(
					
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
						'{order_name}' => sprintf("#%06d", intval($order->id)),
						'{date}' => Tools::displayDate(date('Y-m-d H:i:s'), intval($order->id_lang), 1),
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

					if ($orderStatus->send_email AND Validate::isEmail($customer->email))
					{
						Mail::Send(intval($order->id_lang), 'order_conf', 'Order confirmation', $data, $customer->email, $customer->firstname.' '.$customer->lastname, NULL, NULL, $fileAttachment);

            // Povrzeni objednavky vlastnikovi                              						
						Mail::Send(intval($order->id_lang), 'order_conf', 'Objednavka c.: '.$order->id, $data, Configuration::get('PS_SHOP_EMAIL'), '', $customer->email, $customer->firstname.' '.$customer->lastname, NULL);
            
            						
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

?>