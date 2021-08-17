<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blockuserinfo/blockuserinfo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blockuserinfo/blockuserinfo.tpl', 15, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/modules/blockuserinfo/blockuserinfo.tpl', 17, false),)), $this); ?>
<!-- Block user information module HEADER -->
	
<div id="header_user">
  <a id="basketlink" href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php">&nbsp;</a>
	<ul id="header_nav">
		<li id="shopping_cart">
			<a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php" title="<?php echo smartyTranslate(array('s' => 'Your Shopping Cart','mod' => 'blockuserinfo'), $this);?>
"><?php echo smartyTranslate(array('s' => 'Cart:','mod' => 'blockuserinfo'), $this);?>
:</a>
				<span class="ajax_cart_quantity<?php if ($this->_tpl_vars['cart_qties'] == 0): ?> hidden<?php endif; ?>"><?php if ($this->_tpl_vars['cart_qties'] > 0): ?><?php echo $this->_tpl_vars['cart_qties']; ?>
<?php endif; ?></span><span class="ajax_cart_product_txt<?php if ($this->_tpl_vars['cart_qties'] == 0): ?> hidden<?php endif; ?>">Ks /</span>				<span class="ajax_cart_total<?php if ($this->_tpl_vars['cart_qties'] == 0): ?> hidden<?php endif; ?>"><?php if ($this->_tpl_vars['cart_qties'] > 0): ?><?php if ($this->_tpl_vars['priceDisplay'] == 1): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['cart']->getOrderTotal(false,4)), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['cart']->getOrderTotal(true,4)), $this);?>
<?php endif; ?><?php endif; ?></span>
				<span class="ajax_cart_no_product<?php if ($this->_tpl_vars['cart_qties'] > 0): ?> hidden<?php endif; ?>"><?php echo smartyTranslate(array('s' => '(empty)','mod' => 'blockuserinfo'), $this);?>
</span>
		</li>
		<li id="your_account"><a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
my-account.php" title="<?php echo smartyTranslate(array('s' => 'Your Account','mod' => 'blockuserinfo'), $this);?>
"><?php echo smartyTranslate(array('s' => 'Your Account','mod' => 'blockuserinfo'), $this);?>
</a></li>
	</ul>
</div>
<!-- /Block user information module HEADER -->