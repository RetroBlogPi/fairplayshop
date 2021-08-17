<?php /* Smarty version 2.6.20, created on 2012-06-25 15:49:45
         compiled from /home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/validation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/validation.tpl', 1, false),array('function', 'convertPriceWithCurrency', '/home/www/fairplayshop.cz/www/modules/cashondeliverywithfee/validation.tpl', 17, false),)), $this); ?>
<?php ob_start(); ?><?php echo smartyTranslate(array('s' => 'Shipping'), $this);?>
<?php $this->_smarty_vars['capture']['path'] = ob_get_contents(); ob_end_clean(); ?>

<h2><?php echo smartyTranslate(array('s' => 'Order summation','mod' => 'cashondeliverywithfee'), $this);?>
</h2>

<?php $this->assign('current_step', 'payment'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./order-steps.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h3><?php echo smartyTranslate(array('s' => 'Cash on delivery (COD) payment','mod' => 'cashondeliverywithfee'), $this);?>
</h3>

<form action="<?php echo $this->_tpl_vars['this_path_ssl']; ?>
validation.php" method="post">
<input type="hidden" name="confirm" value="1" />
<p>
	<img src="<?php echo $this->_tpl_vars['this_path']; ?>
cashondelivery.jpg" alt="<?php echo smartyTranslate(array('s' => 'Cash on delivery (COD) payment','mod' => 'cashondeliverywithfee'), $this);?>
" style="float:left; margin: 0px 10px 5px 0px;" />
	<?php echo smartyTranslate(array('s' => 'You have chosen the cash on delivery method.','mod' => 'cashondeliverywithfee'), $this);?>

	<br/><br />
	<?php echo smartyTranslate(array('s' => 'The total amount of your order is','mod' => 'cashondeliverywithfee'), $this);?>

	<span id="amount_<?php echo $this->_tpl_vars['currencies']['0']['id_currency']; ?>
" class="price"><?php echo Product::convertPriceWithCurrency(array('price' => $this->_tpl_vars['total'],'currency' => $this->_tpl_vars['currency']), $this);?>
</span>
</p>
<p>
	<br /><br />
	<br /><br />
	<b><?php echo smartyTranslate(array('s' => 'Please confirm your order by clicking \'I confirm my order\'','mod' => 'cashondeliverywithfee'), $this);?>
.</b>
</p>
<p class="cart_navigation">
	<a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php?step=1" class="button_large"><?php echo smartyTranslate(array('s' => 'Other payment methods','mod' => 'cashondeliverywithfee'), $this);?>
</a>
	<input type="submit" name="submit" value="<?php echo smartyTranslate(array('s' => 'I confirm my order','mod' => 'cashondeliverywithfee'), $this);?>
" class="exclusive_large" />
</p>
</form>