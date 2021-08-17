<!-- Block informations module -->
<div id="informations_block_left" class="block">
	<h4>{l s='Information' mod='blockinfos'}</h4>
	<ul class="block_content">
		{foreach from=$cmslinks item=cmslink name='cmslinksloop'}
			<li{if $smarty.foreach.cmslinksloop.iteration==3} class="last"{/if}><a class="preview" href="{$cmslink.link}" title="{$cmslink.meta_title}">{$cmslink.content|strip_tags|truncate:100}</a><br /><a href="{$cmslink.link}" class="morelink">Více...</a></li>
		{/foreach}
	</ul>
</div>
<!-- /Block informations module -->
