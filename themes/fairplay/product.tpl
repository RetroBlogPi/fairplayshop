{include file=$tpl_dir./errors.tpl}
{if $errors|@count == 0}
<script type="text/javascript">
// <![CDATA[

// PrestaShop internal settings
var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
var currencyRate = '{$currencyRate|floatval}';
var currencyFormat = '{$currencyFormat|intval}';
var currencyBlank = '{$currencyBlank|intval}';
var taxRate = {$product->tax_rate|floatval};
var jqZoomEnabled = {if $jqZoomEnabled}true{else}false{/if};

//JS Hook
var oosHookJsCodeFunctions = new Array();

// Parameters
var id_product = '{$product->id|intval}';
var productHasAttributes = {if isset($groups)}true{else}false{/if};
var quantitiesDisplayAllowed = {if $display_qties == 1}true{else}false{/if};
var quantityAvailable = {if $display_qties == 1 && $product->quantity}{$product->quantity}{else}0{/if};
var allowBuyWhenOutOfStock = {if $allow_oosp == 1}true{else}false{/if};
var availableNowValue = '{$product->available_now|escape:'quotes':'UTF-8'}';
var availableLaterValue = '{$product->available_later|escape:'quotes':'UTF-8'}';
var productPriceWithoutReduction = {$product->getPriceWithoutReduct()|default:'null'}{if $product->customsize}*100{/if};
var reduction_percent = {if $product->reduction_percent}{$product->reduction_percent}{else}0{/if};
var reduction_price = {if $product->reduction_percent}0{else}{$product->getPrice(true, $smarty.const.NULL, 2, $smarty.const.NULL, true)}{/if};
var reduction_from = '{$product->reduction_from}';
var reduction_to = '{$product->reduction_to}';
var group_reduction = '{$group_reduction}';
var default_eco_tax = {$product->ecotax};
var currentDate = '{$smarty.now|date_format:'%Y-%m-%d'}';
var maxQuantityToAllowDisplayOfLastQuantityMessage = {$last_qties};
var noTaxForThisProduct = {if $no_tax == 1}true{else}false{/if};
var displayPrice = {$priceDisplay};

// Customizable field
var img_ps_dir = '{$img_ps_dir}';
var customizationFields = new Array();
{assign var='imgIndex' value=0}
{assign var='textFieldIndex' value=0}
{foreach from=$customizationFields item='field' name='customizationFields'}
{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
	customizationFields[{$smarty.foreach.customizationFields.index|intval}] = new Array();
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][0] = '{if $field.type|intval == 0}img{$imgIndex++}{else}textField{$textFieldIndex++}{/if}';
	customizationFields[{$smarty.foreach.customizationFields.index|intval}][1] = {if $field.type|intval == 0 AND $pictures.$key}2{else}{$field.required|intval}{/if};
{/foreach}

// Images
var img_prod_dir = '{$img_prod_dir}';
var combinationImages = new Array();
{foreach from=$combinationImages item='combination' key='combinationId' name='f_combinationImages'}
combinationImages[{$combinationId}] = new Array();
{foreach from=$combination item='image' name='f_combinationImage'}
combinationImages[{$combinationId}][{$smarty.foreach.f_combinationImage.index}] = {$image.id_image|intval};
{/foreach}
{/foreach}

combinationImages[0] = new Array();
{foreach from=$images item='image' name='f_defaultImages'}
combinationImages[0][{$smarty.foreach.f_defaultImages.index}] = {$image.id_image};
{/foreach}

// Translations
var doesntExist = '{l s='The product does not exist in this model. Please choose another.' js=1}';
var doesntExistNoMore = '{l s='This product is no longer in stock' js=1}';
var doesntExistNoMoreBut = '{l s='with those attributes but is available with others' js=1}';
var uploading_in_progress = '{l s='Uploading in progress, please wait...' js=1}';
var fieldRequired = '{l s='Please fill all required fields' js=1}';


{if isset($groups)}
	// Combinations
	{foreach from=$combinations key=idCombination item=combination}	  
		addCombination({$idCombination|intval}, new Array({$combination.list}), {$combination.quantity}, {$combination.price}, {$combination.ecotax}, {$combination.id_image}, '{$combination.reference|addslashes}');
	{/foreach}
	// Colors
	{if $colors|@count > 0}
		{if $product->id_color_default}var id_color_default = {$product->id_color_default|intval};{/if}
	{/if}
{/if}

//]]>
</script>

{include file=$tpl_dir./breadcrumb.tpl}

{if $subcats1 && count($subcats1)>0}
<div class="subcats">
  <ul>
  {foreach from=$subcats1 item=sc name=subcats1}
    <li{if $smarty.foreach.subcats1.last} style="border-right:none;"{/if}><a href="{$link->getCategoryLink($sc.id_category, $sc.link_rewrite)|escape:'htmlall':'UTF-8'}">{$sc.name}</a></li>
  {/foreach}
  </li>
</div>
{/if}

{if $subcats2 && count($subcats2)>0}
<div class="subcats">
  <ul>
  {foreach from=$subcats2 item=sc name=subcats2}
    <li{if $smarty.foreach.subcats2.last} style="border-right:none;"{/if}><a href="{$link->getCategoryLink($sc.id_category, $sc.link_rewrite)|escape:'htmlall':'UTF-8'}" style="font-weight: normal;">{$sc.name}</a></li>
  {/foreach}
  </li>
</div>
{/if}

<span class="cnthead">&nbsp;</span>
<div class="productcnt">
<div id="primary_block">

	{*<h2>{$product->name|escape:'htmlall':'UTF-8'}</h2>*}
	{if $confirmation}
	<p class="confirmation">
		{$confirmation}
	</p>
	{/if}

	<!-- right infos-->
	<div id="pb-right-column">
		<!-- product img-->
		<div id="image-block">
		{if $have_image}
				<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" {if $jqZoomEnabled}class="jqzoom" alt="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox')}"{else} title="{$product->name|escape:'htmlall':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" {/if} id="bigpic"/>
				<span>&nbsp;</span>
		{else}
			<img src="{$img_prod_dir}{$lang_iso}-default-large.jpg" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" />
		{/if}
		</div>

		{if count($images) > 0}
		<!-- thumbnails -->
		<div id="views_block" {if count($images) < 2}class="hidden"{/if}>
		{if count($images) > 3}<span class="view_scroll_spacer"><a id="view_scroll_left" class="hidden" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Previous'}</a></span>{/if}
		<div id="thumbs_list">
			<ul style="width: {math equation="width * nbImages" width=80 nbImages=$images|@count}px" id="thumbs_list_frame">
				{foreach from=$images item=image name=thumbnails}
				{assign var=imageIds value=`$product->id`-`$image.id_image`}
				<li id="thumbnail_{$image.id_image}">
					<a href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox')}" rel="other-views" class="{if !$jqZoomEnabled}thickbox{/if} {if $smarty.foreach.thumbnails.first}shown{/if}" title="{$image.legend|htmlspecialchars}">
						<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium')}" alt="{$image.legend|htmlspecialchars}" height="{$mediumSize.height}" width="{$mediumSize.width}" />
					</a>
				</li>
				{/foreach}
			</ul>
		</div>
		{if count($images) > 3}<a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a>{/if}
		</div>
		{/if}
		{*
		{if count($images) > 1}<p class="align_center clear"><a id="resetImages" href="{$link->getProductLink($product)}" onclick="return (false);">{l s='Display all pictures'}</a></p>{/if}
		*}
		<!-- usefull links-->
		<ul id="usefull_link_block">
			{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
			{*
			{if $have_image && !$jqZoomEnabled}
			<li><span id="view_full_size" class="span_link">{l s='View full size'}</span></li>
			{/if}
			*}
		</ul>
	</div>

	<!-- left infos-->
	<div id="pb-left-column">
	  <h1>{$product->name|escape:'htmlall':'UTF-8'}</h1>
	  <hr class="producthr" />
		{*if $product->description_short}
		<div id="short_description_block">
			{if $product->description_short}
				<div id="short_description_content" class="rte align_justify">{$product->description_short}</div>
			{/if}
			{if $product->description}
			<p class="buttons_bottom_block"><a href="javascript:{ldelim}{rdelim}" class="button">{l s='More details'}</a></p>
			{/if}			
		</div>
		{/if*}

		{if $colors}
		<!-- colors -->
		<div id="color_picker">
			<p>{l s='Pick a color:' js=1}</p>
			<div class="clear"></div>
			<ul id="color_to_pick_list">
			{foreach from=$colors key='id_attribute' item='color'}
				<li><a id="color_{$id_attribute|intval}" class="color_pick" style="background: {$color.value};" onclick="updateColorSelect({$id_attribute|intval});">{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}<img src="{$img_col_dir}{$id_attribute}.jpg" alt="" title="{$color.name}" />{/if}</a></li>
			{/foreach}
			</ul>
				<a id="color_all" onclick="updateColorSelect(0);"><img src="{$img_dir}icon/cancel.gif" alt="" title="{$color.name}" /></a>
			<div class="clear"></div>
		</div>
		{/if}

		<!-- add to cart form-->
		<form id="buy_block" action="{$base_dir}cart.php" method="post">

      
			<!-- hidden datas -->
			<p class="hidden">
				<input type="hidden" name="token" value="{$static_token}" />
				<input type="hidden" name="id_product" value="{$product->id|intval}" id="product_page_product_id" />
				<input type="hidden" name="add" value="1" />
				<input type="hidden" name="id_product_attribute" id="idCombination" value="" />
			</p>

			<!-- prices -->
			
			{if ($product->reduction_price != 0 || $product->reduction_percent != 0) && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
				<p id="old_price"><span class="bold">
				{if !$priceDisplay || $priceDisplay == 2}
					<span id="old_price_display">{if $product->customsize==1}{convertPrice price=$product->getPriceWithoutReduct()*100}{else}{convertPrice price=$product->getPriceWithoutReduct()}{/if}</span> {l s='původní cena'}</span>
				{/if}
				{if $priceDisplay == 1}
					<span id="old_price_display">{convertPrice price=$product->getPriceWithoutReduct(true)} {l s='původní cena'}</span>						
				{/if}
				</span>
				</p>
			{/if}
      
      {*			
			{if $product->reduction_percent != 0 && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
				<p id="reduction_percent">{l s='(price reduced by'} <span id="reduction_percent_display">{$product->reduction_percent|floatval}</span> %{l s=')'}</p>
			{/if}
			{if $product->ecotax != 0}
				<p class="price-ecotax">{l s='include'} <span id="ecotax_price_display">{convertPrice price=$product->ecotax}</span> {l s='for green tax'}</p>
			{/if}
			*}
			
			<p class="price">
				{if $product->on_sale}
				  {*
					<img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/>
					<span class="on_sale">{l s='On sale!'}</span>
					*}
				{elseif ($product->reduction_price != 0 || $product->reduction_percent != 0) && ($product->reduction_from == $product->reduction_to OR ($smarty.now|date_format:'%Y-%m-%d' <= $product->reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product->reduction_from))}
					
          {*<span class="discount">{l s='Price lowered!'}</span>*}
					<br />
				{/if}				
				<span class="our_price_display">
				{if !$priceDisplay || $priceDisplay == 2}
					<span id="our_price_display">{if $product->customsize==1}{convertPrice price=$product->getPrice(true, NULL, 2)*100} za m2{else}{convertPrice price=$product->getPrice(true, NULL, 2)}{/if}</span>{if $product->customsize==1}<span class="price"> {if array_key_exists(4,$groups)}/ m2{else}/ bm{/if}</span>{/if} {*{if $product->getPrice(true, NULL, 2) != $product->getPrice(false, NULL, 2)}{l s='incl. tax'}{/if}*} s DPH</span>					
				{/if}
				{if $priceDisplay == 1}
					<span id="our_price_display">{convertPrice price=$product->getPrice(false, $smarty.const.NULL, 2)}</span>
						{l s='tax excl.'}
				{/if}
				</span>
				{if $priceDisplay == 2}
					<br />
					<span id="pretaxe_price"><span id="pretaxe_price_display">{if $product->customsize==1}{convertPrice price=$product->getPrice(false, NULL, 2)*100}{else}{convertPrice price=$product->getPrice(false, NULL, 2)}{/if}</span> {l s='tax excl.)'}</span>
				{/if}				
			</p>
			<br class="clear" />
			<hr class="producthr" />
			
			<div class="buy_block_left">
			
			{if isset($groups)}
			<!-- attributes -->
			<div id="attributes">
			{foreach from=$groups key=id_attribute_group item=group}
			<p class="buyparam">
				<label for="group_{$id_attribute_group|intval}">{$group.name|escape:'htmlall':'UTF-8'} :</label>
				{assign var='groupName' value='group_'|cat:$id_attribute_group}
				<select name="{$groupName}" id="group_{$id_attribute_group|intval}" onchange="javascript:findCombination();">
					{foreach from=$group.attributes key=id_attribute item=group_attribute}
						<option value="{$id_attribute|intval}"{if (isset($smarty.get.$groupName) && $smarty.get.$groupName|intval == $id_attribute) || $group.default == $id_attribute} selected="selected"{/if}>{$group_attribute|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
			</p>
			{/foreach}
			</div>
			{/if}

			{if $product->reference}<p id="product_reference" {if isset($groups)}style="display:none;"{/if}><label for="product_reference">{l s='Reference :'} </label><span class="editable">{$product->reference|escape}</span></p>{/if}

			<!-- quantity wanted -->
			  {if $product->customsize==1}
			  <div id="customsize">
			  <input type="hidden" name="customsize" id="customsize" value="1" />
				
        <p class="buyparam">
				<label>Počet ks:</label>
				<input type="text" name="pocet" id="quantity_wanted_pocet" class="text" />
				</p>
				{if array_key_exists(4,$groups)}<p class="buyparam"><label>Plocha:</label><input id="customtotalS" type="text" /> <span>m<sup>2</sup></span></p>
				{else}<p class="buyparam"><label>Délka:</label><input id="customtotalS" type="text" /> <span>bm</span></p>
				{/if}
				<p class="buyparam"><label>Celkem:</label><input id="customtotalprice" type="text" /> <span>Kč</span></p>
				</div>
			  {else}
			  <p class="buyparam" id="quantity_wanted_p"{if (!$allow_oosp && $product->quantity == 0) || $virtual} style="display:none;"{/if}>
				<label>{l s='Quantity :'}</label>
				<input type="text" name="qty" id="quantity_wanted" class="text" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}1{/if}" size="2" maxlength="3" />
				</p>
		    {/if}

			<!-- availability -->
			<br class="clear" />
			<p id="availability_statut"{if ($allow_oosp && $product->quantity == 0 && !$product->available_later) || (!$product->available_now && $display_qties != 1) } style="display:none;"{/if}>
				<span id="availability_label">{l s='Availability:'}</span>
				<span id="availability_value"{if $product->quantity == 0} class="warning-inline"{/if}>
					{if $product->quantity == 0}{if $allow_oosp}{$product->available_later}{else}{l s='This product is no longer in stock'}{/if}{else}{$product->available_now}{/if}
				</span>
			</p>

			<!-- number of item in stock -->
			<p id="pQuantityAvailable"{if $display_qties != 1 || ($allow_oosp && $product->quantity == 0)} style="display:none;"{/if}>
				<span id="quantityAvailable">{$product->quantity|intval}</span>
				<span{if $product->quantity > 1} style="display:none;"{/if} id="quantityAvailableTxt">{l s='item in stock'}</span>
				<span{if $product->quantity < 2} style="display:none;"{/if} id="quantityAvailableTxtMultiple">{l s='items in stock'}</span>
			</p>
			
			<!-- Out of stock hook -->
			<p id="oosHook"{if $product->quantity > 0} style="display:none;"{/if}>
				{$HOOK_PRODUCT_OOS}
 			</p>
 			
 			</div>


			{*<p class="warning-inline" id="last_quantities"{if ($product->quantity > $last_qties || $product->quantity == 0) || $allow_oosp} style="display:none;"{/if} >{l s='Warning: Last items in stock!'}</p>*}

			<p{if !$allow_oosp && $product->quantity == 0} style="display:none;"{/if} id="add_to_cart" class="buttons_bottom_block"><input type="submit" name="Submit" value="Do košíku" class="exclusive" /></p>
			
			<br class="clear" />
			
			{if $HOOK_PRODUCT_ACTIONS}
				{$HOOK_PRODUCT_ACTIONS}
			{/if}
		</form>
		{if $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}
	</div>
</div>
<br class="clear" />

{if $quantity_discounts}
<!-- quantity discount -->
<ul class="idTabs">
	<li><a style="cursor: pointer">{l s='Quantity discount'}</a></li>
</ul>
<div id="quantityDiscount">
	<table class="std">
			<tr>
				{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
				<th>{$quantity_discount.quantity|intval} 
				{if $quantity_discount.quantity|intval > 1}
					{l s='quantities'}
				{else}
					{l s='quantity'}
				{/if}
				</th>
				{/foreach}
			</tr>
			<tr>
				{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
				<td>
				{if $quantity_discount.id_discount_type|intval == 1}
					-{$quantity_discount.value|floatval}%
				{else}
					-{convertPrice price=$quantity_discount.value|floatval}
				{/if}
				</td>
				{/foreach}
			</tr>
	</table>
</div>
{/if}

{$HOOK_PRODUCT_FOOTER}

<!-- description and features -->
{if $product->description || $features || $accessories || $HOOK_PRODUCT_TAB || $attachments}
<div id="more_info_block" class="clear">
	<ul id="more_info_tabs" class="idTabs idTabsShort">
		{if $product->description}<li><a id="more_info_tab_more_info" href="#idTab1">{l s='Popis'}</a></li>{/if}
		{if $features}<li><a id="more_info_tab_data_sheet" href="#idTab2">{l s='Data sheet'}</a></li>{/if}
		{if $attachments}<li><a id="more_info_tab_attachments" href="#idTab9">{l s='Ke stažení'}</a></li>{/if}
		{*if isset($accessories) AND $accessories}<li><a href="#idTab4">{l s='Accessories'}</a></li>{/if*}
		{$HOOK_PRODUCT_TAB}
			<li><a id="ico-print" href="javascript:print();">{l s='Print'}</a></li>
	</ul>
	<div id="more_info_sheets" class="sheets align_justify">
	{if $product->description}
		<!-- full description -->
		<div id="idTab1" class="rte">{$product->description}</div>
	{/if}
	{if $features}
		<!-- product's features -->
		<ul id="idTab2" class="bullet">
		{foreach from=$features item=feature}
			<li><span>{$feature.name|escape:'htmlall':'UTF-8'}</span> {$feature.value|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ul>
	{/if}
	{if $attachments}
		<ul id="idTab9" class="bullet">
		{foreach from=$attachments item=attachment}
			<li><a href="{$base_dir}attachment.php?id_attachment={$attachment.id_attachment}">{$attachment.name|escape:'htmlall':'UTF-8'}</a><br />{$attachment.description|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ul>
	{/if}
	{$HOOK_PRODUCT_TAB_CONTENT}
	</div>
</div>
{/if}

<hr class="producthr2" />

{if isset($accessories) AND $accessories}
<span id="accessories">Související zboží</span>


		<!-- accessories -->
			<div class="block products_block accessories_block">
			   {if count($accessories) > 4}<span class="access_spacer"><a id="view_scroll_left2" class="hidden" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Previous'}</a></span>{/if}
				<div class="block_content" id="access_list" style="width: 674px;">
					<ul style="width: {math equation="width * nbImages" width=164 nbImages=$accessories|@count}px">
					{foreach from=$accessories item=accessory name=accessories_list}
						{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
						<li class="ajax_block_product {if $smarty.foreach.accessories_list.first}first_item{elseif $smarty.foreach.accessories_list.last}last_item{else}item{/if} product_accessories_description">
							<div class="access_img_block"><a href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{$accessory.legend|escape:'htmlall':'UTF-8'}" class="product_image"><img src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'medium')}" alt="{$accessory.legend|escape:'htmlall':'UTF-8'}" /></a></div>
								{*<a href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{l s='More'}" class="product_description">{$accessory.description_short|strip_tags|truncate:100:'...'}</a>*}
							<h5><a href="{$accessoryLink|escape:'htmlall':'UTF-8'}">{$accessory.name|truncate:22:'...'|escape:'htmlall':'UTF-8'}</a></h5>
							<p class="product_accessories_price">
								<span class="price">{displayWtPrice p=$accessory.price}</span>
								<a class="button" href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>
								<a class="button ajax_add_to_cart_button" href="{$base_dir}cart.php?qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add" rel="ajax_id_product_{$accessory.id_product|intval}" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
							</p>
						</li>
					{/foreach}
					</ul>
				</div>
				{if count($accessories) > 4}<span class="access_spacer"><a id="view_scroll_right2" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a></span>{/if}
			</div>
	{/if}

<!-- Customizable products -->
{if $product->customizable}
	<ul class="idTabs">
		<li><a style="cursor: pointer">{l s='Product customization'}</a></li>
	</ul>
	<div class="customization_block">
		<form method="post" action="{$customizationFormTarget}" enctype="multipart/form-data" id="customizationForm">
			<p>
				<img src="{$img_dir}icon/infos.gif" alt="Informations" />
				{l s='After saving your customized product, do not forget to add it to your cart.'}
				{if $product->uploadable_files}<br />{l s='Allowed file formats are: GIF, JPG, PNG'}{/if}
			</p>
			{if $product->uploadable_files|intval}
			<h2>{l s='Pictures'}</h2>
			<ul id="uploadable_files">
				{counter start=0 assign='customizationField'}
				{foreach from=$customizationFields item='field' name='customizationFields'}
					{if $field.type == 0}
						<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='pictures_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
							{if isset($pictures.$key)}<div class="customizationUploadBrowse"><img src="{$pic_dir}{$pictures.$key}_small" alt="" /><a href="{$link->getUrlWith('deletePicture', $field.id_customization_field)}"><img src="{$img_dir}icon/delete.gif" alt="{l s='delete'}" class="customization_delete_icon" /></a></div>{/if}
							<div class="customizationUploadBrowse"><input type="file" name="file{$field.id_customization_field}" id="img{$customizationField}" class="customization_block_input {if isset($pictures.$key)}filled{/if}" />{if $field.required}<sup>*</sup>{/if}
							<div class="customizationUploadBrowseDescription">{if !empty($field.name)}{$field.name}{else}{l s='Please select an image file from your hard drive'}{/if}</div></div>
						</li>
						{counter}
					{/if}
				{/foreach}
			</ul>
			{/if}
			<div class="clear"></div>
			{if $product->text_fields|intval}
			<h2>{l s='Texts'}</h2>
			<ul id="text_fields">
				{counter start=0 assign='customizationField'}
				{foreach from=$customizationFields item='field' name='customizationFields'}
					{if $field.type == 1}
						<li class="customizationUploadLine{if $field.required} required{/if}">{assign var='key' value='textFields_'|cat:$product->id|cat:'_'|cat:$field.id_customization_field}
							{if !empty($field.name)}{$field.name}{/if}<input type="text" name="textField{$field.id_customization_field}" id="textField{$customizationField}" value="{if isset($textFields.$key)}{$textFields.$key|stripslashes}{/if}" class="customization_block_input" />{if $field.required}<sup>*</sup>{/if}
						</li>
						{counter}
					{/if}
				{/foreach}
			</ul>
			{/if}
			<p style="clear: left;" id="customizedDatas">
				<input type="hidden" name="quantityBackup" id="quantityBackup" value="" />
				<input type="hidden" name="submitCustomizedDatas" value="1" />
				<input type="button" class="button" value="{l s='Save'}" onclick="javascript:saveCustomization()" />
			</p>
		</form>
		<p class="clear required"><sup>*</sup> {l s='required fields'}</p>
	</div>
{/if}

<hr class="cleaner" />
</div>
<span class="cntfoot">&nbsp;</span>

{/if}
