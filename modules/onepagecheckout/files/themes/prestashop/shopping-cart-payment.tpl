<h2>{l s='Shopping cart summary' template='shopping-cart'}</h2>

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
<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<thead>
			<tr>
                                <th class="cart_product first_item">{l s='Product' template='shopping-cart'}</th>
                                <th class="cart_description item">{l s='Description' template='shopping-cart'}</th>
                                <th class="cart_ref item">{l s='Ref.' template='shopping-cart'}</th>
                                <th class="cart_availability item">{l s='Avail.' template='shopping-cart'}</th>
                                <th class="cart_unit item">{l s='Unit price' template='shopping-cart'}</th>
                                <th class="cart_quantity item" style="width: 5em;">{l s='Qty' template='shopping-cart'}</th>
                                <th class="cart_total last_item">{l s='Total' template='shopping-cart'}</th>
			</tr>
		</thead>
                <tfoot>
                        {if $priceDisplay}
                                <tr class="cart_total_products">
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
                        {if $shippingCost > 0}
                                {if $priceDisplay}
                                        <tr class="cart_total_delivery">
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
                                <tr class="cart_total_price">
                                        <td colspan="6">{l s='Total (tax excl.):' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_price_without_tax}</td>
                                </tr>
                                <tr class="cart_total_voucher">
                                        <td colspan="6">{l s='Total tax:' template='shopping-cart'}</td>
                                        <td class="price">{convertPrice price=$total_tax}</td>
                                </tr>
                        {/if}
                        <tr class="cart_total_price">
                                <td colspan="6">{l s='Total (tax incl.):' template='shopping-cart'}</td>
                                <td class="price">{convertPrice price=$total_price}</td>
                        </tr>
                        {if $free_ship > 0}
                        <tr class="cart_free_shipping">
                                <td colspan="6" style="white-space: normal;">{l s='Remaining amount to be added to your cart in order to obtain free shipping:' template='shopping-cart'}</td>
                                <td class="price">{convertPrice price=$free_ship}</td>
                        </tr>
                        {/if}
                </tfoot>
		<tbody>
		{foreach from=$products item=product name=productLoop}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{* Display the product line *}
			{include file=$tpl_dir./shopping-cart-product-line-payment.tpl}
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
						<td class="cart_quantity">
							{$customization.quantity}
						</td>
						<td class="cart_total">
						</td>
					</tr>
					{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
				{/foreach}
				{* If it exists also some uncustomized products *}
				{if $product.quantity-$quantityDisplayed > 0}{include file=$tpl_dir./shopping-cart-product-line-payment.tpl}{/if}
			{/if}
		{/foreach}
		</tbody>
	{if $discounts}
		<tbody>
		{foreach from=$discounts item=discount name=discountLoop}
			<tr class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if}">
				<td class="cart_discount_name" colspan="2">{$discount.name}</td>
				<td class="cart_discount_description" colspan="3">{$discount.description}</td>
				<td class="cart_discount_delete"></td>
				<td class="cart_discount_price"><span class="price-discount">{convertPrice price=$discount.value_real*-1}</span></td>
			</tr>
		{/foreach}
		</tbody>
	{/if}
	</table>
</div>

{$HOOK_SHOPPING_CART}
{if ($carrier->id AND !$virtualCart) OR $delivery->id OR $invoice->id}
<div class="order_delivery">
	{if ($delivery->id AND !($delivery->lastname == $virtual.lastname AND $delivery->firstname == $virtual.name AND $delivery->address1 == $virtual.address AND $delivery->city == $virtual.city))}
	<ul id="delivery_address" class="address item">
		<li class="address_title">{l s='Delivery address' template='shopping-cart'}</li>
		{if $delivery->company}<li class="address_company">{$delivery->company|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_name">{$delivery->lastname|escape:'htmlall':'UTF-8'} {$delivery->firstname|escape:'htmlall':'UTF-8'}</li>
		<li class="address_address1">{$delivery->address1|escape:'htmlall':'UTF-8'}</li>
		{if $delivery->address2}<li class="address_address2">{$delivery->address2|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_city">{$delivery->postcode|escape:'htmlall':'UTF-8'} {$delivery->city|escape:'htmlall':'UTF-8'}</li>
		<li class="address_country">{$delivery->country|escape:'htmlall':'UTF-8'}{if isset($delivery->state)} ({$delivery->state}){/if}</li>
	</ul>
	{/if}
	{if ($invoice->id AND !($invoice->lastname == $virtual.lastname AND $invoice->firstname == $virtual.name AND $invoice->address1 == $virtual.address AND $invoice->city == $virtual.city))}
	<ul id="invoice_address" class="address alternate_item">
		<li class="address_title">{l s='Invoice address' template='shopping-cart'}</li>
		{if $invoice->company}<li class="address_company">{$invoice->company|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_name">{$invoice->lastname|escape:'htmlall':'UTF-8'} {$invoice->firstname|escape:'htmlall':'UTF-8'}</li>
		<li class="address_address1">{$invoice->address1|escape:'htmlall':'UTF-8'}</li>
		{if $invoice->address2}<li class="address_address2">{$invoice->address2|escape:'htmlall':'UTF-8'}</li>{/if}
		<li class="address_city">{$invoice->postcode|escape:'htmlall':'UTF-8'} {$invoice->city|escape:'htmlall':'UTF-8'}</li>
		<li class="address_country">{$invoice->country|escape:'htmlall':'UTF-8'}{if isset($invoice->state)} ({$invoice->state}){/if}</li>
	</ul>
	{/if}
	{if $carrier->id AND !$virtualCart}
	<div id="order_carrier">
		<h4>{l s='Carrier:' template='shopping-cart'}</h4>
		{if isset($carrierPicture)}<img src="{$img_ship_dir}{$carrier->id}.jpg" alt="{l s='Carrier'}" />{/if}
		<span>{$carrier->name|escape:'htmlall':'UTF-8'}</span>
	</div>
	{/if}
</div>
{/if}
{/if}
