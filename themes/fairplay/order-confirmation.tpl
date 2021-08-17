<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Order confirmation'}{/capture}
{*include file=$tpl_dir./breadcrumb.tpl*}

<h2 class="category_title">{l s='Order confirmation'}</h2>

<div class="block">
<div class="block_content">
{assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl}

{include file=$tpl_dir./errors.tpl}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}

<br />
{*
<a href="{$base_dir_ssl}history.php" title="{l s='Back to orders'}"><img src="{$img_dir}icon/order.gif" alt="{l s='Back to orders'}" class="icon" /></a>
<a href="{$base_dir_ssl}history.php" title="{l s='Back to orders'}">{l s='Back to orders'}</a>
*}

</div>
</div>