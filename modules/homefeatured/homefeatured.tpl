<!-- MODULE Home Featured Products -->

  <div class="block">
	<h4>{l s='Nejoblíbenější produkty' mod='homefeatured'}</h4>
	{if isset($products) AND $products}

	{assign var='nbItemsPerLine' value=4}
	{assign var='nbLi' value=$products|@count}
	{assign var='nbLines' value=$nbLi/$nbItemsPerLine|ceil}
	
	<ul id="product_list" class="clear">
	{foreach from=$products item=product name=products}
		<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{else}item{/if} {if $smarty.foreach.products.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.products.iteration%$nbItemsPerLine == 1}first_item_of_line{/if} {if $smarty.foreach.products.iteration > ($smarty.foreach.products.total - ($smarty.foreach.products.total % $nbItemsPerLine))}last_line{/if}">
			<div class="center_block">
				{*<span class="availability">{if ($product.allow_oosp OR $product.quantity > 0)}{l s='Available'}{else}{l s='Out of stock'}{/if}</span>*}
				<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" /></a>
				<h3>
          {*{if $product.new == 1}<span class="new">{l s='new'}</span>{/if}*}
          <a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h3>
				{*<p class="product_desc"><a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a></p>*}
			</div>
			<div class="right_block">
				{if $product.on_sale}
					<span class="on_sale">{l s='On sale!' mod='homefeatured'}</span>
				{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
					<span class="discount">{l s='Price lowered!' mod='homefeatured'}</span>
				{else}
				  <span class="discount">&nbsp;</span>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}<div><span class="price" style="display: inline;">{if $product.customsize}{convertPrice price=$product.price*100}{else}{convertPrice price=$product.price}{/if}</span>{if $priceDisplay == 2} {l s='+Tx'}{/if}</div>{/if}
				{if $priceDisplay}<div><span class="price" style="display: inline;">{if $product.customsize}{convertPrice price=$product.price_tax_exc*100}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}</div>{/if}
				{if ($product.allow_oosp OR $product.quantity > 0) && $product.customizable != 2}
					<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">Do košíku</a>
				{else}
						<span class="exclusive">Do košíku</span>
				{/if}
				<a class="button" href="{$product.link|escape:'htmlall':'UTF-8'}" title="Detail">Detail</a>
			</div>
			<br class="clear"/>
		</li>
	{/foreach}
	</ul>
			
		</div>
	{else}
		<div class="block_content"><p>{l s='No featured products' mod='homefeatured'}</p></div>
	{/if}

<!-- /MODULE Home Featured Products -->
