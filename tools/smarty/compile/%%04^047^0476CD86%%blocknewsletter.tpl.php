<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blocknewsletter/blocknewsletter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blocknewsletter/blocknewsletter.tpl', 7, false),)), $this); ?>
<!-- Block Newsletter module-->

<div id="newsletter_block_left" class="block">	
	<div class="block_content">
		<form action="<?php echo $this->_tpl_vars['base_dir']; ?>
" name="formnewsletter" method="post">
		  <p><a href="#" onClick="var x=getElementById('nlaction'); x.value='0'; $('#newlssubmit').click(); return false;">Objednat</a> / <a href="#" onClick="var x=getElementById('nlaction'); x.value='1'; $('#newlssubmit').click(); return false;">Odhlásit</a></p>
			<p><input type="text" name="email" size="18" value="<?php if ($this->_tpl_vars['value']): ?><?php echo $this->_tpl_vars['value']; ?>
<?php else: ?><?php echo smartyTranslate(array('s' => 'Novinky do emailu','mod' => 'blocknewsletter'), $this);?>
<?php endif; ?>" onfocus="javascript:if(this.value=='<?php echo smartyTranslate(array('s' => 'Novinky do emailu','mod' => 'blocknewsletter'), $this);?>
')this.value='';" onblur="javascript:if(this.value=='')this.value='<?php echo smartyTranslate(array('s' => 'Novinky do emailu','mod' => 'blocknewsletter'), $this);?>
';" /></p>
			<p>
								<input type="hidden" name="action" id="nlaction" value="0" />
				<input type="submit" value="ok" class="button_mini" id="newlssubmit" name="submitNewsletter" />
			</p>
			<?php if ($this->_tpl_vars['msg']): ?>
        <p style="clear: both;" class="<?php if ($this->_tpl_vars['nw_error']): ?>warning_inline<?php else: ?>success_inline<?php endif; ?>"><?php echo $this->_tpl_vars['msg']; ?>
</p>
  	  <?php endif; ?>
		</form>
		<br class="clear" />
	</div>
</div>

<!-- /Block Newsletter module-->