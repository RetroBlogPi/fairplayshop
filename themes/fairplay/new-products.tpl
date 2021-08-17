{capture name=path}{l s='New products'}{/capture}
{*include file=$tpl_dir./breadcrumb.tpl*}

<h2 class="category_title">{l s='New products'}</h2>

{if $products}
	{include file=$tpl_dir./product-sort.tpl}
  <div class="block_content">
	{include file=$tpl_dir./product-list.tpl products=$products}
	</div>
	{include file=$tpl_dir./pagination.tpl}
	<span class="cntfoot">&nbsp;</span>
{else}
  <div class="block">
  <div class="block_content">
	<p class="warning">{l s='No new products.'}</p>
	</div>
  </div>
{/if}