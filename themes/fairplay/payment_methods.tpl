{ldelim}
'html_data': ' \
 <input type="hidden" id="payment_country" name="payment_country" value="{$payment_country}" /> \
 <input type="hidden" id="payment_carrier" name="payment_carrier" value="{$payment_carrier}" /> \
        {if $payment_methods && count($payment_methods)} \
        <div class="table_block"> \
                <table class="std"> \
   <thead> \
    <tr><th colspan="3">{l s='Available payment methods for selected address and carrier'}</th><th class="last_item" style="width: 10px;">&nbsp;</th></tr> \
   </thead> \
                        <tbody> \
                        {foreach from=$payment_methods item=payment_method name=myLoop} \
                                <tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{/if} {if $smarty.foreach.myLoop.index % 2}alternate_item{else}item{/if}"> \
                                        <td class="payment_method_action radio"><!--{$fields_cookie.f_firstname};{$fields_cookie.f_id_payment_method},{$payment_method.id_payment_method},{$smarty.post.id_payment_method},{$checked}--> \
                                                <input type="radio" name="id_payment_method" id="pm{$payment_method.link_hash}" value="{$payment_method.link_hash}" {if ($payment_methods|@sizeof == 1)}checked="checked"{/if} onclick="set_carrier2({$payment_method.cena},this)" /> \
                                        </td> \
                                        <td class="payment_method_name"> \
                                                <label for="pm{$payment_method.link_hash}"> \
                                                        {if $payment_method.img}{$payment_method.img|regex_replace:"/ src/":" height=\"35\"\\0"}{/if} \
                                                </label> \
                                        </td> \
     <td> \
       {$payment_method.desc} \
     </td> \
     <td> \
       &nbsp; \
     </td> \
                                </tr> \
                        {/foreach} \
                        </tbody> \
                </table> \
        </div> \
        {else} \
  <p class="warning">{l s='No payment modules available for this country / carrier combination.'}</p></td></tr> \
        {/if} \
 ',
'num_payments': '{$payment_methods|@sizeof}'
{rdelim}