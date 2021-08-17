<!-- MODULE Block new products -->
<div id="new-products_block_top" class="block products_block">
	<h4><a href="{$base_dir}new-products.php" title="Novinky a akce">Novinky a akce</a></h4>
	<div class="block_content">
    {if $new_products && count($new_products)>0}
    <ul id="product_list" class="new_products">
	{foreach from=$new_products item=product name=new_products}
		<li class="{if $smarty.foreach.new_products.iteration%3==0}end_line{/if}">
			<div class="center_block">
				<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" /></a>
				{*<p class="product_desc"><a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a></p>*}
			</div>
			<div class="right_block">
			  <h3>{if $product.new == 1}<span class="new">{l s='new'}</span>{/if}<a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h3>
				{if $product.on_sale}
					<span class="on_sale">Výprodej</span>
				{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
					<span class="discount">Akční cena</span>
				{else}
				  <span class="discount">Novinka</span>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}<div>
          <span class="price" style="display: inline;">
          
          {if $product.customsize}{convertPrice price=$product.price*100}{else}{convertPrice price=$product.price}{/if}
          </span>
          
          {if $priceDisplay == 2} {l s='+Tx'}{/if}</div>{/if}
				{if $priceDisplay}<div><span class="price" style="display: inline;">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}</div>{/if}

				<a class="button" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">Více informací</a>
			</div>
			<br class="clear"/>
		</li>
	{/foreach}
	</ul>
    
	{else}
		<p>{l s='No new product at this time' mod='blocknewproducts'}</p>
	{/if}
	<hr class="cleaner" />
	</div>
</div>
<!-- /MODULE Block new products -->
