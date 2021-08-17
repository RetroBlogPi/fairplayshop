<!-- Block Newsletter module-->

<div id="newsletter_block_left" class="block">	
	<div class="block_content">
		<form action="{$base_dir}" name="formnewsletter" method="post">
		  <p><a href="#" onClick="var x=getElementById('nlaction'); x.value='0'; $('#newlssubmit').click(); return false;">Objednat</a> / <a href="#" onClick="var x=getElementById('nlaction'); x.value='1'; $('#newlssubmit').click(); return false;">Odhlásit</a></p>
			<p><input type="text" name="email" size="18" value="{if $value}{$value}{else}{l s='Novinky do emailu' mod='blocknewsletter'}{/if}" onfocus="javascript:if(this.value=='{l s='Novinky do emailu' mod='blocknewsletter'}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='Novinky do emailu' mod='blocknewsletter'}';" /></p>
			<p>
				{*<select name="action">
					<option value="0"{if $action == 0} selected="selected"{/if}>{l s='Subscribe' mod='blocknewsletter'}</option>
					<option value="1"{if $action == 1} selected="selected"{/if}>{l s='Unsubscribe' mod='blocknewsletter'}</option>
				</select>*}
				<input type="hidden" name="action" id="nlaction" value="0" />
				<input type="submit" value="ok" class="button_mini" id="newlssubmit" name="submitNewsletter" />
			</p>
			{if $msg}
        <p style="clear: both;" class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
  	  {/if}
		</form>
		<br class="clear" />
	</div>
</div>

<!-- /Block Newsletter module-->
