{include file=$tpl_dir./breadcrumb.tpl} 
{include file=$tpl_dir./errors.tpl}

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

{if $category->id AND $category->active}
	<h1 class="category_title">
		{$category->name|escape:'htmlall':'UTF-8'}		
	</h1>
  {if $scenes || $category->id_image || $category->description}
  <div class="block_content2">
	
  {if $scenes}
		<!-- Scenes -->
		{include file=$tpl_dir./scenes.tpl scenes=$scenes}
	{else}
		<!-- Category image -->
		{if $category->id_image}
			<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" />
		{/if}
	{/if}

	{if $category->description}
		<div class="cat_desc">{$category->description}</div>
	{/if}
	
	</div>{if !$products}<span class="cntfoot">&nbsp;</span>{/if}
	{/if}
	
	{*if isset($subcategories)}
	<!-- Subcategories -->
	<div id="subcategories">
		<h3>{l s='Subcategories'}</h3>
		<ul class="inline_list">
		{foreach from=$subcategories item=subcategory}
			<li>
				<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}">
					{if $subcategory.id_image}
						<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium')}" alt="" />
					{else}
						<img src="{$img_cat_dir}default-medium.jpg" alt="" />
					{/if}
				</a>
				<br />
				<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
			</li>
		{/foreach}
		</ul>
		<br class="clear"/>
	</div>
	{/if*}
  
	{if $products}
			{include file=$tpl_dir./product-sort.tpl}
			{include file=$tpl_dir./product-list.tpl products=$products}
			{include file=$tpl_dir./pagination.tpl}
			<span class="cntfoot">&nbsp;</span>
		{elseif !isset($subcategories)}
			<div class="block_content2"><p class="warning">{l s='There is no product in this category.'}</p></div><span class="cntfoot">&nbsp;</span>
		{/if}
{elseif $category->id}
	<div class="block_content2"><p class="warning">{l s='This category is currently unavailable.'}</p></div><span class="cntfoot">&nbsp;</span>
{/if}
