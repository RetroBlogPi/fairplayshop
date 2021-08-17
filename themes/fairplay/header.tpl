<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang_iso}">
	<head>
		<title>{$meta_title|escape:'htmlall':'UTF-8'}</title>
{if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description}" />
{/if}
{if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords}" />
{/if}
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />		
		<meta name="robots" content="{if isset($nobots)}no{/if}index,follow" />		
		{*
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$img_ps_dir}favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="{$img_ps_dir}favicon.ico" />
		*}
		
{if isset($css_files)}
	{foreach from=$css_files key=css_uri item=media}
	<link href="{$css_uri}?3" rel="stylesheet" type="text/css" media="{$media}" />
	{/foreach}
{/if}
		<script type="text/javascript" src="{$content_dir}js/tools.js"></script>
		<script type="text/javascript">
			var baseDir = '{$content_dir}';
			var static_token = '{$static_token}';
			var token = '{$token}';
			var priceDisplayPrecision = {$priceDisplayPrecision*$currency->decimals};
		</script>
		<script type="text/javascript" src="{$content_dir}js/jquery/jquery-1.2.6.pack.js"></script>
		<script type="text/javascript" src="{$content_dir}js/jquery/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="{$content_dir}js/jquery/jquery.hotkeys-0.7.8-packed.js"></script>
{if isset($js_files)}
	{foreach from=$js_files item=js_uri}
	<script type="text/javascript" src="{$js_uri}"></script>
	{/foreach}
{/if}
		{$HOOK_HEADER}
		
<script type="text/javascript">
{literal}

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-31107786-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

{/literal}
</script>		
		
	</head>
	
	<body {if $page_name}id="{$page_name|escape:'htmlall':'UTF-8'}"{/if}>
	{if !$content_only}
		<noscript><ul><li>{l s='This shop requires JavaScript to run correctly. Please activate JavaScript in your browser.'}</li></ul></noscript>
		<div id="page">

			<!-- Header -->
			<div>
				{if $page_name=='index'}<h1 id="logo" style="background: none; width:0; height:0; padding:0;"><a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}"><img src="{$base_dir}themes/fairplay/img/fairplay/logo.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}" /></a></h1>
				{else}<div id="logo"><a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}"><img src="{$base_dir}themes/fairplay/img/fairplay/logo.png" alt="{$shop_name|escape:'htmlall':'UTF-8'}" /></a></div>
				{/if}
				<div id="header">
					{$HOOK_TOP}
				</div>
			</div>
        
        
          {$HOOK_TOP_NEWSLETTER}
          {if $page_name=='index'}            
            {$HOOK_HOME_TOP}
          {/if}        
          

			<!-- Left -->
			{*<div id="left_column" class="column">
				{$HOOK_LEFT_COLUMN}
			</div>*}

			<!-- Center -->
			<div id="center_column">
		  		  
	{/if}
