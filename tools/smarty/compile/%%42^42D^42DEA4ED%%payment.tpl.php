<?php /* Smarty version 2.6.20, created on 2012-04-24 20:37:12
         compiled from /home/www/fairplayshop.cz/www/modules/cashondeliveryosobni/payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/cashondeliveryosobni/payment.tpl', 2, false),)), $this); ?>
<p class="payment_module">
	<a href="<?php echo $this->_tpl_vars['this_path_ssl']; ?>
validation.php" title="<?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliveryosobni'), $this);?>
">
		<img src="<?php echo $this->_tpl_vars['this_path']; ?>
cashondeliveryosobni.jpg" style="float:left" alt="<?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliveryosobni'), $this);?>
" />
		<strong><?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliveryosobni'), $this);?>
</strong><br /><?php echo smartyTranslate(array('s' => 'you pay for the merchandise upon delivery, additional cost: ','mod' => 'cashondeliveryosobni'), $this);?>
<br style="clear:both;" />
	</a>
</p>