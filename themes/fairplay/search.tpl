{capture name=path}{l s='Search'}{/capture}
{*include file=$tpl_dir./breadcrumb.tpl*}

<h2 class="category_title">{l s='Search'}&nbsp;{if $nbProducts > 0}"{if $query}{$query|escape:'htmlall':'UTF-8'}{elseif $tag}{$tag|escape:'htmlall':'UTF-8'}{elseif $ref}{$ref|escape:'htmlall':'UTF-8'}{/if}"{/if}</h2>

{include file=$tpl_dir./errors.tpl}

{if !$nbProducts}
  <div class="block">
  <div class="block_content">
	<p class="warning">
		{if $query}
			{l s='No results found for your search'}&nbsp;"{$query|escape:'htmlall':'UTF-8'}"
		{else}
			{l s='Please type a search keyword'}
		{/if}
	</p>
	</div>
	</div>
{else}
	{include file=$tpl_dir./product-sort.tpl}
	<div class="block">
  <div class="block_content">
  <h3><span class="big">{$nbProducts|intval}</span>&nbsp;{if $nbProducts == 1}{l s='result has been found.'}{else}{l s='results have been found.'}{/if}</h3>
	{include file=$tpl_dir./product-list.tpl products=$products}
	</div>
	{include file=$tpl_dir./pagination.tpl}
	</div>
{/if}
