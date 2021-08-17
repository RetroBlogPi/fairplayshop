{capture name=path}{l s='Shipping'}{/capture}

<h2>{l s='Order summation' mod='cashondeliverywithfee'}</h2>

{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

<h3>{l s='Cash on delivery (COD) payment' mod='cashondeliverywithfee'}</h3>

<form action="{$this_path_ssl}validation.php" method="post">
<input type="hidden" name="confirm" value="1" />
<p>
	<img src="{$this_path}cashondelivery.jpg" alt="{l s='Cash on delivery (COD) payment' mod='cashondeliverywithfee'}" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='You have chosen the cash on delivery method.' mod='cashondeliverywithfee'}
	<br/><br />
	{l s='The total amount of your order is' mod='cashondeliverywithfee'}
	<span id="amount_{$currencies.0.id_currency}" class="price">{convertPriceWithCurrency price=$total currency=$currency}</span>
</p>
<p>
	<br /><br />
	<br /><br />
	<b>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='cashondeliverywithfee'}.</b>
</p>
<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step=1" class="button_large">{l s='Other payment methods' mod='cashondeliverywithfee'}</a>
	<input type="submit" name="submit" value="{l s='I confirm my order' mod='cashondeliverywithfee'}" class="exclusive_large" />
</p>
</form>