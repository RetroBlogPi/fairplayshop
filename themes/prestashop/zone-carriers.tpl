{ldelim}
'html_data': ' \
        {if $carriers && count($carriers)} \
        <div class="table_block"> \
                <table class="std"> \
                        <thead> \
                                <tr> \
                                        <th class="carrier_action first_item"></th> \
                                        <th class="carrier_name item">{l s='Carrier' template='order-carrier'}</th> \
                                        <th class="carrier_infos item">{l s='Information' template='order-carrier'}</th> \
                                        <th class="carrier_price last_item">{l s='Price' template='order-carrier'}</th> \
                                </tr> \
                        </thead> \
                        <tbody> \
                        {foreach from=$carriers item=carrier name=myLoop} \
				{if $priceDisplay} \
					{assign var='shown_price' value=$carrier.price_tax_exc} \
				{else}\
					{assign var='shown_price' value=$carrier.price} \
				{/if} \
                                <tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{else}item{/if}"> \
                                        <td class="carrier_action radio"><!--{$fields_cookie.f_firstname};{$fields_cookie.f_id_carrier},{$carrier.id_carrier},{$smarty.post.id_carrier},{$checked}--> \
                                                <input type="radio" name="id_carrier" onclick="set_carrier(\'{if $shown_price > 0}{convertPrice price=$shown_price}{else}0{/if}\', 1);" value="{$carrier.id_carrier|intval}" id="id_carrier{$carrier.id_carrier|intval}" {if ($smarty.post.id_carrier == $carrier.id_carrier) || ($carrier.id_carrier == $checked AND !isset($smarty.post.id_carrier)) || ($checked == 0 && $i == 0) || ($carriers|@sizeof == 1)}checked="checked"{assign var="selected_price" value=$shown_price}{/if} /> \
                                        </td> \
                                        <td class="carrier_name"> \
                                                <label for="id_carrier{$carrier.id_carrier|intval}"> \
                                                        {if $carrier.img}<img class="carrier" src="{$carrier.img|escape:'htmlall':'UTF-8'}" alt="{$carrier.name|escape:'htmlall':'UTF-8'}" />{else}{$carrier.name|escape:'htmlall':'UTF-8'}{/if} \
                                                </label> \
                                        </td> \
                                        <td class="carrier_infos">{$carrier.delay|escape:'htmlall':'UTF-8'}</td> \
                                        <td class="carrier_price">{if $shown_price}<span id="xyz" class="price">{convertPrice price=$shown_price}</span>{else}{l s='Free!'}{/if}</td> \
                                </tr> \
                        {/foreach} \
                        </tbody> \
                </table> \
        </div> \
        {else} \
                <p class="warning">{l s='There is no carrier available that will deliver to this address!' template='order-carrier'}</td></tr> \
        {/if} \
	',
'selected_price': '{if $selected_price}{convertPrice price=$selected_price}{else}0{/if}',
'num_carriers': '{$carriers|@sizeof}'
{rdelim}
