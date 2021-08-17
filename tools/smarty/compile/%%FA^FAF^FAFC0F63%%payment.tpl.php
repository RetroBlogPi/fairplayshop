<?php /* Smarty version 2.6.20, created on 2012-04-24 12:35:12
         compiled from /home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/payment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/payment.tpl', 2, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/payment.tpl', 4, false),)), $this); ?>
<p class="payment_module">
	<a href="<?php echo $this->_tpl_vars['this_path_ssl']; ?>
validation.php" title="<?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliverywithfee'), $this);?>
">
		<img src="<?php echo $this->_tpl_vars['this_path']; ?>
cashondelivery.jpg" style="float:left" alt="<?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliverywithfee'), $this);?>
" />
		<strong><?php echo smartyTranslate(array('s' => 'Cash on delivery (COD)','mod' => 'cashondeliverywithfee'), $this);?>
</strong><br /><?php echo smartyTranslate(array('s' => 'you pay for the merchandise upon delivery, additional cost: ','mod' => 'cashondeliverywithfee'), $this);?>
<strong> <?php echo Product::convertPrice(array('price' => $this->_tpl_vars['fee']), $this);?>
</strong><br style="clear:both;" />
	</a>
</p>