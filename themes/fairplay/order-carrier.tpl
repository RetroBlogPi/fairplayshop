<script type="text/javascript" src="{$base_dir_ssl}js/conditions.js"></script>
<script type="text/javascript" src="{$js_dir}layer.js"></script>
<script type="text/javascript" src="{$js_dir}tools/statesManagement.js"></script>
<script type="text/javascript" src="{$base_dir_ssl}js/onepage.js"></script>

{if !$virtual_cart && $giftAllowed && $cart->gift == 1}
<script type="text/javascript">{literal}
// <![CDATA[
    $('document').ready( function(){
        $('#gift_div').toggle('slow');
    });
//]]>
{/literal}</script>
{/if}


<script type="text/javascript">
// <![CDATA[
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{$fields_cookie.f_id_state|intval}{/if};
idInvoiceSelectedCountry = {if isset($smarty.post.inv_id_state)}{$smarty.post.inv_id_state|intval}{else}{$fields_cookie.f_inv_id_state|intval}{/if};
countries = new Array();
csz = new Array();
{foreach from=$countries item='country'}
        {if isset($country.states)}
                countries[{$country.id_country|intval}] = new Array();
                csz[{$country.id_country|intval}] = new Array();
                {foreach from=$country.states item='state' name='states'}
                        countries[{$country.id_country|intval}]['{$state.id_state|intval}'] = '{$state.name|escape:'htmlall':'UTF-8'}';
                        csz[{$country.id_country|intval}]['{$state.id_state|intval}'] = '{$state.id_zone|intval}';
                {/foreach}
        {/if}
{/foreach}
dlv_addresses = new Array();
inv_addresses = new Array();
{foreach from=$dlv_addresses item='address'}
  dlv_addresses[{$address.id_address}]=new Array('{$address.id_country}','{$address.id_state}','{$address.company}','{$address.lastname}','{$address.firstname}','{$address.address1}','{$address.address2}','{$address.postcode}','{$address.city}','{$address.other}','{$address.phone}','{$address.phone_mobile}');
{/foreach}
{foreach from=$inv_addresses item='address'}
  inv_addresses[{$address.id_address}]=new Array('{$address.id_country}','{$address.id_state}','{$address.company}','{$address.lastname}','{$address.firstname}','{$address.address1}','{$address.address2}','{$address.postcode}','{$address.city}','{$address.other}','{$address.phone}','{$address.phone_mobile}');
{/foreach}



{literal}
$(document).ready(
	function () {
		if ($("input[@name='id_type']:checked").val()==0) $("#icdic").hide();
		else $("#icdic").show();
		$("input[@name='id_type']").click(function(){
				if ($("input[@name='id_type']:checked").val()==0) {
          $("#icdic").hide();
          $("#companysup").hide();           
        }else {
         $("#icdic").show();
         $("#companysup").show();
        }
			}
		);
	}
	);

function checkAddress() {
	if ($("input[@name='id_type']:checked").val()==1 && $("input[@name='ic']").val()=='') {
		alert('{/literal}{l s='Fill company identification number'}{literal}');
		return false;
	}
	
	if ($("input[@name='id_type']:checked").val()==0) {
		$("input[@name='ic']").val('');
		$("input[@name='dic']").val('');
	}
	return true;
}
{/literal}

//]]>
</script>


<script type="text/javascript">
// <![CDATA[
{foreach from=$countries item=v}
  add_country_zone({$v.id_country}, {$v.id_zone});
{/foreach}
//]]>
</script>

<script type="text/javascript">
// <![CDATA[
{literal}
document['onkeypress'] = detectEvent; /* Opera browsers, but not FF<3.5 */
document['onkeydown'] = detectEvent; /* FF<3.5, but not Opera */
function detectEvent(e) {
        var evt = e || window.event;
        if (evt.keyCode == 13)
                return false;
        else
                return document.defaultAction;
}
function stopEventBubbling(e) {
        if (e.stopPropagation) {
          e.stopPropagation();
        } else {
          e.cancelBubble = true;
        }
}
function checkPaymentMethod(msg) {
	// check if payment on same page is turned on
        if ($('#payment_content').length == 0)
	  return true;
	if ($('input[name=id_payment_method]:checked').val() == undefined) {
	  alert(msg);
	  return false;
	} else {
	  return true;
	}
}
{/literal}
//]]>
</script>



{include file=$tpl_dir./thickbox.tpl}

{capture name=path}{l s='Shopping cart summary' template='shopping-cart'}{/capture}
{* {include file=$tpl_dir./breadcrumb.tpl} *}

<h2 style="margin-top: 0px">{l s='Shopping cart summary' template='shopping-cart'}</h2>
<div class="block_content2">
{assign var='current_step' value='shipping'}

{include file=$tpl_dir./errors.tpl}


<form name="checkoutform" id="form" class="std" action="{$base_dir_ssl}order.php" method="post" onsubmit="set_carrier('0'); if (document.validate_conditions != 'no') return (acceptCGV('{l s='Please accept the terms of service before the next step.' js=1}') && checkPaymentMethod('{l s='Please select payment method.' js=1}')); ">

{if isset($empty)}
	<p class="warning">{l s='Your shopping cart is empty.' template='shopping-cart'}</p>

{else}
{if isset($lastProductAdded) AND $lastProductAdded}
	{foreach from=$products item=product}
		{if $product.id_product == $lastProductAdded}
			<table id="cart_summary" class="std" style="width:300px; margin-left:130px;">
				<thead>
					<tr>
						<th class="cart_product first_item">&nbsp;</th>
						<th class="cart_description item">{l s='Last added product' template='shopping-cart'}</th>
						<th class="cart_total last_item">&nbsp;</th>
					</tr>
				</thead>
			</table>
			<table style="margin:5px 0px 10px 130px;">
				<tr>
					<td class="cart_product"><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}"><img src="{$img_prod_dir}{$product.id_image}-small.jpg" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></a></td>
					<td class="cart_description">
						<h5><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h5>
						{if $product.attributes}<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
					</td>
				</tr>
			</table>
		{/if}
	{/foreach}
{/if}


{if $one_page_checkout_settings.scroll_cart} 
{* Marker for JS *}
<input type="hidden" id="scroll_cart" />
{/if}

{if $one_page_checkout_settings.scroll_summary} 
{* Marker for JS *}
<input type="hidden" id="scroll_summary" />
{/if}

{if $one_page_checkout_settings.checkout_tracker} 
{* Marker for JS *}
<input type="hidden" id="checkout_tracker" />
{/if}

{if $one_page_checkout_settings.hide_carrier} 
{* Marker for JS *}
<input type="hidden" id="hide_carrier" />
{/if}

{if $one_page_checkout_settings.hide_payment} 
{* Marker for JS *}
<input type="hidden" id="hide_payment" />
{/if}

{if $one_page_checkout_settings.ex_texts} 
{* Marker for JS *}
<input type="hidden" id="ex_texts" />
{/if}


<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<thead id="thead_static">
			<tr>
				<th class="cart_product first_item">{l s='Product' template='shopping-cart'}</th>
				<th class="cart_description item">{l s='Description' template='shopping-cart'}</th>
				<th class="cart_ref item">{l s='Ref.' template='shopping-cart'}</th>
				<th class="cart_availability item">{l s='Avail.' template='shopping-cart'}</th>
				<th class="cart_unit item">{*l s='Unit price' template='shopping-cart'*}Cena za kus/m2/bm</th>
				<th class="cart_quantity item" style="width: 5em;">{*l s='Qty' template='shopping-cart'*}Množství</th>
				<th class="cart_total last_item">{l s='Total' template='shopping-cart'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$products item=product name=productLoop}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{* Display the product line *}
			{include file=$tpl_dir./shopping-cart-product-line.tpl}
			{* Then the customized datas ones*}
			{if isset($customizedDatas.$productId.$productAttributeId)}
				{foreach from=$customizedDatas.$productId.$productAttributeId key='id_customization' item='customization'}
					<tr class="alternate_item cart_item">
						<td colspan="5">
							{foreach from=$customization.datas key='type' item='datas'}
								{if $type == $CUSTOMIZE_FILE}
									<div class="customizationUploaded">
										<ul class="customizationUploaded">
											{foreach from=$datas item='picture'}<li><img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" /></li>{/foreach}
										</ul>
									</div>
								{elseif $type == $CUSTOMIZE_TEXTFIELD}
									<ul class="typedText">
										{foreach from=$datas item='textField' name='typedText'}<li>{l s='Text #'}{$smarty.foreach.typedText.index+1}{l s=':'} {$textField.value}</li>{/foreach}
									</ul>
								{/if}
							{/foreach}
						</td>
						<td class="cart_quantity">Customization product:
							{$customization.quantity}
							<input class="cart_quantity_up"   type="image" title="{l s='Add'}"      alt="{l s='Add'}"      src="{$img_dir}icon/quantity_up.gif"   onClick="onepage_cartupdate(document, this.form, '{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}');"  />
							<input class="cart_quantity_down" type="image" title="{l s='Subtract'}" alt="{l s='Subtract'}" src="{$img_dir}icon/quantity_down.gif" onClick="onepage_cartupdate(document, this.form, '{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}');"  />
							
<!--							<a class="cart_quantity_up" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}" title="{l s='Add'}"><img src="{$img_dir}icon/quantity_up.gif" alt="{l s='Add'}" /></a><br />
							<a class="cart_quantity_down" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}" title="{l s='Substract'}"><img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Substract'}" /></a> -->
						</td>
						<td class="cart_total">
							<!--<a class="cart_quantity_delete" href="{$base_dir_ssl}cart.php?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" title="{l s='Delete this customization'}" class="icon" /></a>-->
							<input class="cart_quantity_delete"   type="image" title="{l s='Add'}"      alt="{l s='Add'}"      src="{$img_dir}icon/delete.gif" alt="{l s='Delete this customization'}"  title="{l s='Delete this customization'}"  onClick="onepage_cartupdate(document, this.form, '{$base_dir_ssl}cart.php?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}');"  />
						</td>
					</tr>
					{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
				{/foreach}
				{* If it exists also some uncustomized products *}
				{if $product.quantity-$quantityDisplayed > 0}{include file=$tpl_dir./shopping-cart-product-line.tpl}{/if}
			{/if}
		{/foreach}
		</tbody>
	{if $discounts}
		<tbody>
		{foreach from=$discounts item=discount name=discountLoop}
			<tr class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if}">
				<td class="cart_discount_name" colspan="2">{$discount.name}</td>
				<td class="cart_discount_description" colspan="3">{$discount.description}</td>
				<td class="cart_discount_delete">
				<input type="image" src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}"  title="{l s='Delete'}"  onClick="onepage_cartupdate(document, this.form, '{$base_dir_ssl}order.php?deleteDiscount={$discount.id_discount}');"  />
				</td>
				<!--<td class="cart_discount_delete"><a href="{$base_dir_ssl}order.php?deleteDiscount={$discount.id_discount}" title="{l s='Delete'}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="icon" /></a></td>-->
				<td class="cart_discount_price"><span class="price-discount">{convertPrice price=$discount.value_real*-1}</span></td>
			</tr>
		{/foreach}
		</tbody>
	{/if}
	</table>
		
                <div id="tfoot_static_underlay" style="position: fixed; top:0; background: #eee; border: 1px solid #999; border-top: 0px; filter:alpha(opacity=92); opacity: 0.92; -moz-opacity: 0.92; display: none;"></div>
                <div id="tfoot_static">
		<table class="std">
			<tfoot>
                        {if $priceDisplay}
                                <tr class="cart_total_productsEx">
                                        <td colspan="6">{l s='Total products (tax excl.):' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_products}</td>
                                </tr>
                        {/if}
                        {if !$priceDisplay || $priceDisplay == 2}
                                <tr class="cart_total_products">
                                        <td colspan="6">{l s='Total products (tax incl.):' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_products_wt}</td>
                                </tr>
                        {/if}
                        {if $total_discounts != 0}
                                {if $priceDisplay}
                                        <tr class="cart_total_voucher">
                                                <td colspan="6">{l s='Total vouchers (tax excl.):' template='shopping-cart'}</td>
                                                <td class="price-discount">{convertPrice price=$total_discounts_tax_exc}</td>
                                        </tr>
                                {/if}
                                {if !$priceDisplay || $priceDisplay == 2}
                                        <tr class="cart_total_voucher">
                                                <td colspan="6">{l s='Total vouchers (tax incl.):' template='shopping-cart'}</td>
                                                <td class="price-discount">{convertPrice price=$total_discounts}</td>
                                        </tr>
                                {/if}
                        {/if}
                        {if $total_wrapping > 0}
                                {if $priceDisplay}
                                        <tr class="cart_total_voucher">
                                                <td colspan="6">{l s='Total gift-wrapping (tax excl.):' template='shopping-cart'}</td>
                                                <td class="price-discount">{convertPrice price=$total_wrapping_tax_exc}</td>
                                        </tr>
                                {/if}
                                {if !$priceDisplay || $priceDisplay == 2}
                                        <tr class="cart_total_voucher">
                                                <td colspan="6">{l s='Total gift-wrapping (tax incl.):' template='shopping-cart'}</td>
                                                <td class="price-discount">{convertPrice price=$total_wrapping}</td>
                                        </tr>
                                {/if}
                        {/if}
                        {if $shippingCost > -1}
                                {if $priceDisplay}
                                        <tr class="cart_total_deliveryEx">
                                                <td colspan="6">{l s='Total shipping (tax excl.):' template='shopping-cart'}</td>
                                                <td class="price">{convertPrice price=$shippingCostTaxExc}</td>
                                        </tr>
                                {/if}
                                {if !$priceDisplay || $priceDisplay == 2}
                                        <tr class="cart_total_delivery">
                                                <td colspan="6">{l s='Total shipping (tax incl.):' template='shopping-cart'}</td>
                                                <td class="price">{convertPrice price=$shippingCost}</td>
                                        </tr>
                                {/if}
                        {/if}
                        {if $priceDisplay}
                                <tr class="cart_total_priceEx">
                                        <td colspan="6">{l s='Total (tax excl.):' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_price_without_tax}</td>
                                </tr>
				<!--
                                <tr class="cart_total_voucher">
                                        <td colspan="6">{l s='Total tax:' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_tax}</td>
                                </tr>
				-->
			{else}
                        <tr class="cart_total_price">
                                <td colspan="6">{l s='Total (tax incl.):' template='shopping-cart'}</td>
                                <td class="price">{convertPrice price=$total_price}</td>
                        </tr>
                        {/if}
                        {if $free_ship > 0}
                        <tr class="cart_free_shipping">
                                <td colspan="6" style="white-space: normal;">{l s='Remaining amount to be added to your cart in order to obtain free shipping:' template='shopping-cart'}</td>
                                <td class="price">{convertPrice price=$free_ship}</td>
                        </tr>
                        {/if}
		</tfoot>
                </table>
		</div>
</div>

<br />
<br />


{if $voucherAllowed}
<div id="cart_voucher" class="table_block" style="margin-top: 0px; margin-bottom: 0px;">
	{if $errors_discount}
		<ul class="error">
		{foreach from=$errors_discount key=k item=error}
			<li>{$error|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ul>
	{/if}
	
		<fieldset style="margin-bottom: 5px; padding-bottom: 7px;">
			<h4>{l s='Vouchers' template='shopping-cart'}</h4>
				<label for="discount_name">{l s='Code:' template='shopping-cart'}</label>
				<input type="text" id="discount_name" name="discount_name" value="{if $discount_name}{$discount_name}{/if}" />
			
			<input type="submit" style="display: inline;" 	name="submitDiscount" value="{l s='Add' template='shopping-cart'}" class="button" onClick="this.form.step.value='2'; document.validate_conditions='no'" />
		</fieldset>
	
</div>
{/if}


		<input type="hidden" name="link" value="" />
		<input type="hidden" name="cartupdateflag" value="0" />

{/if}


{*
{if !$isLogged && !$one_page_checkout_settings.hide_email}

  <fieldset id="alr_fieldset" style="padding-bottom: 0px;">
          <h3>{l s='Already registered ?' template='authentication'} &nbsp; <a href="#" id="alr_click_here">{l s='Click here.'}</a></h3>
          <div id="alr_body" style="display: none;">
    <img id="alr_wait_img" align="right" src="{$img_dir}ajax-loader.gif" style="margin-right: 48%; display: none;position:relative;top:76px;margin-top:-33px;z-index:100;" />
          <h4>{l s='Pro přihlášení zadejte Váš e-mail a Vaše heslo' template='authentication'}.</h4>
          <div id="alr_error" class="error" style="display: none;">error message</div>
          <p class="text">
                  <label for="email">{l s='E-mail address' template='authentication'}</label>
                  <input class="text" type="text" id="alr_email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall'|stripslashes}{/if}" />
          </p>
          <p class="text">
                  <label for="passwd">{l s='Password' template='authentication'}</label>
                  <input class="text" type="password" id="alr_passwd" name="passwd" value="{if isset($smarty.post.passwd)}{$smarty.post.passwd|escape:'htmlall'|stripslashes}{/if}" />
          </p>
          <p class="submit">
                  {if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
                  <input type="button" id="quick_login" class="button" value="{l s='Log in' template='authentication'}" />
          </p>
          <p><a href="{$base_dir}password.php">{l s='Forgot your password?' template='authentication'}</a></p>
          </div>
  </fieldset>
{/if}
*}



                <fieldset id="delivery_address_form" class="account_creation">
{if !($virtual_cart AND $one_page_checkout_settings.virtual_no_delivery)}
                        <h3>
			  <div style="float: right;">
			    {if $dlv_addresses|@count == 0}
				{* empty *}
			    {elseif $dlv_addresses|@count == 1}
				{* empty *}
			    {else}
			    <span style="font-size: 0.7em;">{l s='Choose another address'}:</span>
			    <select id="dlv_addresses" style="width: 100px; margin-left: 0px;">
			      {foreach from=$dlv_addresses item=address}
				{if isset($smarty.post.address1)}{assign var='field_address' value=$smarty.post.address1}{else}{assign var='field_address' value=$fields_cookie.f_address1}{/if}
				<option value="{$address.id_address}" {if $address.address1 == $field_address}selected="selected"{/if}>{$address.alias|regex_replace:"/^dlv\-/":""}</option>
			      {/foreach}
			    </select>
			    {/if}
			  </div>{l s='Your delivery address'  template='order-address'}
			</h3>
{/if}
                        <p class="{if !$one_page_checkout_settings.optional_email}required {/if}text" {if $one_page_checkout_settings.hide_email}style="display:none;"{/if}>
                                <label for="email">{l s='E-mail' template='address'}</label>
                                <input type="text" class="text" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{else}{$fields_cookie.f_email}{/if}" />
                                {if !$one_page_checkout_settings.optional_email}<sup>*</sup>{/if}
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='jack@gmail.com'})</i>{/if}
				<br />
			 	<div style="text-align: center; display: none;" id="existing_email_msg">{l s='This email is already registered, you can either'} <a href="#" id="existing_email_login">{l s='log-in'}</a> {l s='or just fill in details below.'}</div>
                        </p>



			<p {if !$one_page_checkout_settings.gender}style="display:none;"{/if}>
						      
			  <label for="id_gender1" class="top">{l s='Mr.' template='authentication'}</label>
							<input type="radio" name="id_gender" id="id_gender1" value="1" {if (isset($smarty.post.id_gender) && $smarty.post.id_gender == 1) || ($fields_cookie.f_id_gender == 1) }checked="checked"{/if} />
							<label for="id_gender2" style="float: none">{l s='Ms.' template='authentication'}</label>
							<input type="radio" name="id_gender" id="id_gender2" value="2" {if (isset($smarty.post.id_gender) && $smarty.post.id_gender == 2) || ($fields_cookie.f_id_gender == 2)}checked="checked"{/if} />

			</p>

			<p class="select" {if !$one_page_checkout_settings.birthday}style="display:none;"{/if}>
				    <span>{l s='Birthday' template='authentication'}</span>
				    <select id="days" name="days">
					    <option value="">-</option>
					    {foreach from=$days item=day}
						    <option value="{$day|escape:'htmlall':'UTF-8'}" {if (isset($smarty.post.days) && $smarty.post.days == $day) || ($fields_cookie.f_days == $day) }selected="selected"{/if}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
					    {/foreach}
				    </select>
				    {*
					    {l s='January' template='authentication'}
					    {l s='February' template='authentication'}
					    {l s='March' template='authentication'}
					    {l s='April' template='authentication'}
					    {l s='May' template='authentication'}
					    {l s='June' template='authentication'}
					    {l s='July' template='authentication'}
					    {l s='August' template='authentication'}
					    {l s='September' template='authentication'}
					    {l s='October' template='authentication'}
					    {l s='November' template='authentication'}
					    {l s='December' template='authentication'}
				    *}
				    <select id="months" name="months">
					    <option value="">-</option>
					    {foreach from=$months key=k item=month}
						    <option value="{$k|escape:'htmlall':'UTF-8'}" {if (isset($smarty.post.months) && $smarty.post.months == $k) || ($fields_cookie.f_months == $k) }selected="selected"{/if}>{l s="$month" template='authentication'}&nbsp;</option>
					    {/foreach}
				    </select>
				    <select id="years" name="years">
					    <option value="">-</option>
					    {foreach from=$years item=year}
						    <option value="{$year|escape:'htmlall':'UTF-8'}" {if (isset($smarty.post.years) && $smarty.post.years == $year) || ($fields_cookie.f_years == $year) }selected="selected"{/if}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
					    {/foreach}
				    </select>
			</p> 




                        <p class="checkbox" {if !$one_page_checkout_settings.newsletter}style="display:none;"{/if}>
                                <input type="checkbox" name="newsletter" id="newsletter" value="1" {if (isset($smarty.post.newsletter) AND $smarty.post.newsletter == 1) || (!isset($smarty.post.newsletter) && $fields_cookie.f_newsletter == 1)} checked="checked"{/if} />
                                <label for="newsletter">{l s='Sign up for our newsletter' template='authentication'}</label>
                        </p>
                        <p class="checkbox" {if !$one_page_checkout_settings.special_offers}style="display:none;"{/if}>
                                <input type="checkbox"name="optin" id="optin" value="1" {if (isset($smarty.post.optin) AND $smarty.post.optin == 1) || (!isset($smarty.post.optin) && $fields_cookie.f_optin == 1)} checked="checked"{/if} />
                                <label for="optin">{l s='Receive special offers from our partners' template='authentication'}</label>
                        </p>
{if $one_page_checkout_settings.newsletter ||  $one_page_checkout_settings.special_offers }
		<div style="border-bottom: 1px solid #ccc;">
		</div>
{/if}

{if !($virtual_cart AND $one_page_checkout_settings.virtual_no_delivery)}
                        
                  			<p class="radio required" style="line-height: 29px;">
                  				<span>{l s='Type of address'}</span>
                  				<input type="radio" name="id_type" id="id_type1" value="0" checked="checked"/>
                  				<label for="id_gender1" class="top">{l s='Personal'}</label>
                  				<input type="radio" name="id_type" id="id_type2" value="1" {if isset($smarty.post.id_type) && $smarty.post.id_type == 1}checked="checked"{/if} />
                  				<label for="id_gender2" class="top">{l s='Firm'}</label>
                  			</p>                        
                        
                        <div id="icdic">
                        
                        <p class="required text" {if !$one_page_checkout_settings.company_delivery}style="display:none;"{/if}>
                                <label for="company">{l s='Company' template='address'}</label>
                                <input type="text" class="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{$fields_cookie.f_company}{/if}" />
                                <sup id="companysup">*</sup>
                        </p>                        
                        
                        <p class="required text">
                                <label for="ic">{l s='IC' template='address'}</label>
                                <input type="text" class="text" id="ic" name="ic" value="{if isset($smarty.post.ic)}{$smarty.post.ic}{else}{$fields_cookie.f_ic}{/if}" />
                                <sup>*</sup>  
                        </p>
                        
                        <p class="text">
                                <label for="dic">{l s='DIC' template='address'}</label>
                                <input type="text" class="text" id="dic" name="dic" value="{if isset($smarty.post.dic)}{$smarty.post.dic}{else}{$fields_cookie.f_dic}{/if}" />                                  
                        </p>                    
                        </div>                        
                        
                        
                        <p class="required text">
                                <label for="firstname">{l s='First name' template='address'}</label>
                                <input type="text" class="text" id="firstname" name="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{$fields_cookie.f_firstname}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Jack'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="lastname">{l s='Last name' template='address'}</label>
                                <input type="text" class="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{$fields_cookie.f_lastname}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Thompson'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="address1">{l s='Address' template='address'}</label>
                                <input type="text" class="text" name="address1" id="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{$fields_cookie.f_address1}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='15 High Street'})</i>{/if}
                        </p>
                        <p class="text" id="p_address2" {if !$one_page_checkout_settings.address2_delivery}style="display:none;"{/if}>
                                <label for="address2">{l s='Address (2)' template='address'}</label>
                                <input type="text" class="text" name="address2" id="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{$fields_cookie.f_address2}{/if}" />
                        </p>
                        <p class="required text">
                                <label for="postcode">{l s='Postal code / Zip code' template='address'}</label>
                                <input type="text" class="text" name="postcode" id="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{$fields_cookie.f_postcode}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='90104'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="city">{l s='City' template='address'}</label>
                                <input type="text" class="text" name="city" id="city" value="{if isset($smarty.post.city)}{$smarty.post.city}{else}{$fields_cookie.f_city}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Paris'})</i>{/if}
                        </p>
                        <p class="required select" {if !$one_page_checkout_settings.country_delivery}style="display:none;"{/if}>
                                <label for="id_country">{l s='Country' template='address'}</label>
                                <!-- <select name="id_country" id="id_country" onchange="carriers_display(this.options[this.selectedIndex].value);"> -->
                                 <select name="id_country" id="id_country"> 
                               <!-- <select name="id_country" id="id_country" onchange="this.form.step.value='2'; document.validate_conditions='no'; this.form.submit();">-->
                                        <option value="">-</option>
                                        {foreach from=$countries item=v}
                                        <option value="{$v.id_country}" {if ($sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
                                        {/foreach}
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required id_state select" {if !$one_page_checkout_settings.country_delivery}style="display:none;"{/if}>
                                <label for="id_state">{l s='State' template='address'}</label>
                                <select name="id_state" id="id_state">
                                        <option value="">-</option>
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required text" {if !$one_page_checkout_settings.phone}style="display:none;"{/if}>
                                <label for="phone_mobile">{l s='Mobile phone' template='address'}</label>
                                <input type="text" class="text" name="phone_mobile" id="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{$fields_cookie.f_phone_mobile}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='555-100200'})</i>{/if}
			</p>
			
    {* VE.cz - spravna poznamka, predtim tu byla pouze "poznamka k adrese" *}
    <p class="textarea">
            <label for="message">{l s='Additional information' template='address'}</label>
            <textarea name="message" id="message" cols="26" rows="3" onkeydown="stopEventBubbling(event);"  onkeypress="stopEventBubbling(event);">{if isset($smarty.post.message)}{$smarty.post.message}{/if}</textarea>
    </p>			
    
    {*			
    <p class="textarea" {if !$one_page_checkout_settings.additional_info}style="display:none;"{/if}>
            <label for="other">{l s='Additional information' template='address'}</label>
            <textarea name="other" id="other" cols="26" rows="3" onkeydown="stopEventBubbling(event);"  onkeypress="stopEventBubbling(event);">{if isset($smarty.post.other)}{$smarty.post.other}{else}{$fields_cookie.f_other}{/if}</textarea>
    </p>
    *}			


{else} {* if !(virtual_cart and virtual_no_address) *}
  <input type="hidden" name="firstname" value="{$virtual.name}" />
  <input type="hidden" name="lastname" value="{$virtual.lastname}" />
  <input type="hidden" name="address1" value="{$virtual.address}" />
  <input type="hidden" name="postcode" value="{$virtual.zip}" />
  <input type="hidden" name="city" value="{$virtual.city}" />
  <input type="hidden" name="id_country" value="{$one_page_checkout_settings.default_country}" />
  <input type="hidden" name="id_state" value="{$one_page_checkout_settings.default_state}" />

{/if} {* if !(virtual_cart and virtual_no_address) *}

		<p class="checkbox" {if !$one_page_checkout_settings.same_addresses}style="display:none;"{/if}>
                        <input type="checkbox" name="same" id="addressesAreEquals" value="1" onclick="$('#invoice_address_form').toggle('slow');" {if (isset($smarty.post.email) AND (isset($smarty.post.same) AND $smarty.post.same == 1)) || (!isset($smarty.post.email) AND $fields_cookie.f_same == 1) || (!isset($smarty.post.email) AND !isset($fields_cookie.f_email))}checked="checked"{/if} />

{if !($virtual_cart AND $one_page_checkout_settings.virtual_no_delivery)}
                        <label for="addressesAreEquals">{l s='Use the same address for billing.' template='order-address'}</label>
{else} {* if !(virtual_cart and virtual_no_address) *}
                        <label for="addressesAreEquals">{$one_page_checkout_settings.invoice_address_message}</label>
{/if} {* if !(virtual_cart and virtual_no_address) *}
                </p>

                </fieldset>




                <fieldset id="invoice_address_form" class="account_creation" {if (isset($smarty.post.processCarrier) AND (isset($smarty.post.same) AND $smarty.post.same == 1)) || (!isset($smarty.post.processCarrier) AND ($cart->id_address_invoice == $cart->id_address_delivery))}style="display: none;"{/if}>
                        <h3>
			  <div style="float: right;">
			    {if $inv_addresses|@count == 0}
				{* empty *}
			    {elseif $inv_addresses|@count == 1}
				{* empty *}
			    {else}
			    <span style="font-size: 0.7em;">{l s='Choose another address'}:</span>
			    <select id="inv_addresses" style="width: 100px; margin-left: 0px;">
			      {foreach from=$inv_addresses item=address}
				{if isset($smarty.post.inv_address1)}{assign var='field_address' value=$smarty.post.inv_address1}{else}{assign var='field_address' value=$fields_cookie.f_inv_address1}{/if}

				<option value="{$address.id_address}" {if $address.address1 == $field_address}selected="selected"{/if}>{$address.alias|regex_replace:"/^inv\-/":""}</option>
			      {/foreach}
			    </select>
			    {/if}
			  </div>{l s='Your billing address' template='order-address'}
			</h3>
                        <p class="text" {if !$one_page_checkout_settings.company_invoice}style="display:none;"{/if}>
                                <label for="inv_company">{l s='Company' template='address'}</label>
                                <input type="text" class="text" id="inv_company" name="inv_company" value="{if isset($smarty.post.inv_company)}{$smarty.post.inv_company}{else}{$fields_cookie.f_inv_company}{/if}" />
                                <sup>&nbsp;&nbsp;</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='My Company, Ltd.'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="inv_firstname">{l s='First name' template='address'}</label>
                                <input type="text" class="text" id="inv_firstname" name="inv_firstname" value="{if isset($smarty.post.inv_firstname)}{$smarty.post.inv_firstname}{else}{$fields_cookie.f_inv_firstname}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Jack'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="inv_lastname">{l s='Last name' template='address'}</label>
                                <input type="text" class="text" id="inv_lastname" name="inv_lastname" value="{if isset($smarty.post.inv_lastname)}{$smarty.post.inv_lastname}{else}{$fields_cookie.f_inv_lastname}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Thompson'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="inv_address1">{l s='Address' template='address'}</label>
                                <input type="text" class="text" name="inv_address1" id="inv_address1" value="{if isset($smarty.post.inv_address1)}{$smarty.post.inv_address1}{else}{$fields_cookie.f_inv_address1}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='15 High Street'})</i>{/if}
                        </p>
                        <p class="text" id="p_inv_address2" {if !$one_page_checkout_settings.address2_invoice}style="display:none;"{/if}>
                                <label for="inv_address2">{l s='Address (2)' template='address'}</label>
                                <input type="text" class="text" name="inv_address2" id="inv_address2" value="{if isset($smarty.post.inv_address2)}{$smarty.post.inv_address2}{else}{$fields_cookie.f_inv_address2}{/if}" />
                        </p>
                        <p class="required text">
                                <label for="inv_postcode">{l s='Postal code / Zip code' template='address'}</label>
                                <input type="text" class="text" name="inv_postcode" id="inv_postcode" value="{if isset($smarty.post.inv_postcode)}{$smarty.post.inv_postcode}{else}{$fields_cookie.f_inv_postcode}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='90104'})</i>{/if}
                        </p>
                        <p class="required text">
                                <label for="inv_city">{l s='City' template='address'}</label>
                                <input type="text" class="text" name="inv_city" id="inv_city" value="{if isset($smarty.post.inv_city)}{$smarty.post.inv_city}{else}{$fields_cookie.f_inv_city}{/if}" />
                                <sup>*</sup>
				{if $one_page_checkout_settings.ex_texts}<i class="ex_blur">&nbsp;&nbsp;({l s='ex.'} {l s='Paris'})</i>{/if}
                        </p>
                        <p class="required select" {if !$one_page_checkout_settings.country_invoice}style="display:none;"{/if}>
                                <label for="inv_id_country">{l s='Country' template='address'}</label>
                                <select name="inv_id_country" id="inv_id_country">
                                        <option value="">-</option>
                                        {foreach from=$countries item=v}
                                        <option value="{$v.id_country}" {if ($inv_sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
                                        {/foreach}
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required inv_id_state select" style="display:none;">
                                <label for="inv_id_state">{l s='State' template='address'}</label>
                                <select name="inv_id_state" id="inv_id_state">
                                        <option value="">-</option>
                                </select>
                                <sup>*</sup>
                        </p>
		</fieldset>
























{if $virtual_cart}
	<input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
{else}
	<fieldset class="account_creation" id="carrier_selection" {if $one_page_checkout_settings.hide_carrier}style="display: none"{/if}>
	<h3 class="carrier_title">{l s='Choose your delivery method'}</h3>
	{if $recyclablePackAllowed}
	<p class="checkbox">
		<input type="checkbox" name="recyclable" id="recyclable" value="1" {if ((isset($smarty.post.recyclable) && ($smarty.post.recyclable == 1)) || $recyclable == 1)}checked="checked"{/if} />
		<label for="recyclable">{l s='I agree to receive my order in recycled packaging'}</label>
	</p>
	{/if}


	<input type="hidden" id="id_zone_hidden" name="id_zone" value="" />
	<input type="hidden" id="id_carrier_hidden" name="id_carrier" value="" />
	<input type="hidden" id="price_carrier_hidden" name="price_carrier" value="" />



	<img id="carriers_wait_img" align="right" src="{$img_dir}ajax-loader.gif" style="margin-right: 48%; display: none;position:relative;top:30px;margin-top:-33px;z-index:100;" />
	<div id="carriers2">
	<!-- content from zone-carriers.tpl -->
	<!-- {l s='Carrier'}, {l s='Information'}, {l s='Price'}, {l s='Free!'}-->
	</div>


	</fieldset>



	{if $giftAllowed}
	<fieldset class="account_creation" style="padding-bottom: 0px;">
	<!--	<h3 class="gift_title">{l s='Gift'}</h3>-->
		<p class="checkbox">
			<input type="checkbox" name="gift" id="gift" value="1" {if $cart->gift == 1}checked="checked"{/if} onclick="$('#gift_div').toggle('slow');" />
			<label for="gift">{l s='I would like the order to be gift-wrapped.'}</label>
			{if $gift_wrapping_price > 0}({l s='Additional cost of'}&nbsp;{convertPrice price=$gift_wrapping_price}){/if}
		</p>
		<p id="gift_div" class="textarea">
			<label for="gift_message">{l s='If you wish, you can add a note to the gift:'}</label>
			<textarea rows="5" cols="35" id="gift_message" name="gift_message">{$cart->gift_message|escape:'htmlall':'UTF-8'}</textarea>
		</p>
	</fieldset>
	{/if}
{/if}


{if $one_page_checkout_settings.payment_on_same_page}
	<input type="hidden" id="ship2pay_active" value="{$one_page_checkout_settings.ship2pay_active}" />
	<fieldset class="account_creation" id="payment_selection" {if $one_page_checkout_settings.hide_payment}style="display: none"{/if}>
	<h3 class="carrier_title">{l s='Choose your payment method' template='order-payment'}</h3>
	<img id="payment_wait_img" align="right" src="{$img_dir}ajax-loader.gif" style="margin-right: 48%; display: none;position:relative;top:30px;margin-top:-33px;z-index:100;" />
	<div id="payment_content">
	<!-- content from payment_methods.tpl -->
	</div>
	</fieldset>
{/if}


{if $conditions}
	<fieldset class="account_creation" style="padding-bottom: 0px;">
	<!--<h3 class="condition_title">{l s='Terms of service'}</h3>-->
	<p class="checkbox">
		<input type="checkbox" name="cgv" id="cgv" value="1" {if isset($smarty.post.cgv) || $checkedTOS}checked="checked"{/if} />
		<label for="cgv">{l s='I agree with the terms of service and I adhere to them unconditionally.'}</label> <a href="{$base_dir}cms.php?id_cms=3&amp;content_only=1&amp;TB_iframe=true&amp;width=450&amp;height=500&amp;thickbox=true" class="thickbox">{l s='(read)'}</a>
	</p>
	</fieldset>
{/if}


{*
  {if !$isLogged && !$one_page_checkout_settings.hide_email && $one_page_checkout_settings.show_password}
  	<!--optional registration-->
  	<fieldset class="account_creation" style="padding-bottom: 0px;" id="registerme_fieldset">
  	     <p class="checkbox">
  		<input type="checkbox" name="registerme" id="registerme" value="1" onclick="$('#registerme_password').slideToggle('slow');" {if isset($smarty.post.registerme)}checked="checked"{/if} />
  		<label for="registerme">{l s='Create an account and enjoy benefits of registered customers.'}</label> 
  	     </p>
               <p class="text" id="registerme_password" {if !isset($smarty.post.registerme)}style="display:none;"{/if}>
                  <label for="password" style="margin-left: 20px;">{l s='Choose your password'}</label>
                  <input type="password" class="text" id="password" name="password" value="{if isset($smarty.post.password)}{$smarty.post.password}{/if}" /> <i>{l s='(min. 5 characters)'}</i>
               </p>
  	</fieldset>
  {/if}
*}



                        <span><sup>*</sup>{l s='Required field' template='authentication'}</span>
	<p class="cart_navigation required submit">
		<input type="hidden" name="step" value="3" />
		<input type="submit" name="processCarrier" value="{l s='Next'} &raquo;" class="exclusive" />

<input class="button_large"   type="button" value="&laquo; {l s='Continue shopping' template='shopping-cart'}" onClick="onepage_cartupdate(document, this.form, '{if $smarty.server.HTTP_REFERER && strstr($smarty.server.HTTP_REFERER, 'order.php')}{$base_dir_ssl}index.php{else}{$smarty.server.HTTP_REFERER}{/if}');"  />

       <!--<a href="{if $smarty.server.HTTP_REFERER && strstr($smarty.server.HTTP_REFERER, 'order.php')}{$base_dir_ssl}index.php{else}{$smarty.server.HTTP_REFERER}{/if}" class="button_large" title="{l s='Continue shopping' template='shopping-cart'}">&laquo; {l s='Continue shopping' template='shopping-cart'}</a> -->
	</p>

</form>
<hr class="cleaner" />
</div><span class="cntfoot">&nbsp;</span>
<script>
  invoice_address();
//  carriers_display(document.forms['form'].id_country.value);
</script>
