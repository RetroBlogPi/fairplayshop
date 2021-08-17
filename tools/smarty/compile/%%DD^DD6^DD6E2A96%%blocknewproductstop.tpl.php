<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl', 10, false),array('modifier', 'truncate', '/home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl', 14, false),array('modifier', 'date_format', '/home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl', 17, false),array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl', 14, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/modules/blocknewproducts/blocknewproductstop.tpl', 25, false),)), $this); ?>
<!-- MODULE Block new products -->
<div id="new-products_block_top" class="block products_block">
	<h4><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
new-products.php" title="Novinky a akce">Novinky a akce</a></h4>
	<div class="block_content">
    <?php if ($this->_tpl_vars['new_products'] && count ( $this->_tpl_vars['new_products'] ) > 0): ?>
    <ul id="product_list" class="new_products">
	<?php $_from = $this->_tpl_vars['new_products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['new_products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['new_products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['new_products']['iteration']++;
?>
		<li class="<?php if ($this->_foreach['new_products']['iteration']%3 == 0): ?>end_line<?php endif; ?>">
			<div class="center_block">
				<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" class="product_img_link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><img src="<?php echo $this->_tpl_vars['link']->getImageLink($this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['id_image'],'home'); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /></a>
							</div>
			<div class="right_block">
			  <h3><?php if ($this->_tpl_vars['product']['new'] == 1): ?><span class="new"><?php echo smartyTranslate(array('s' => 'new'), $this);?>
</span><?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 35, '...') : smarty_modifier_truncate($_tmp, 35, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></h3>
				<?php if ($this->_tpl_vars['product']['on_sale']): ?>
					<span class="on_sale">Výprodej</span>
				<?php elseif (( $this->_tpl_vars['product']['reduction_price'] != 0 || $this->_tpl_vars['product']['reduction_percent'] != 0 ) && ( $this->_tpl_vars['product']['reduction_from'] == $this->_tpl_vars['product']['reduction_to'] || ( ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')) <= $this->_tpl_vars['product']['reduction_to'] && ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')) >= $this->_tpl_vars['product']['reduction_from'] ) )): ?>
					<span class="discount">Akční cena</span>
				<?php else: ?>
				  <span class="discount">Novinka</span>
				<?php endif; ?>
				<?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?><div>
          <span class="price" style="display: inline;">
          
          <?php if ($this->_tpl_vars['product']['customsize']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']*100), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']), $this);?>
<?php endif; ?>
          </span>
          
          <?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '+Tx'), $this);?>
<?php endif; ?></div><?php endif; ?>
				<?php if ($this->_tpl_vars['priceDisplay']): ?><div><span class="price" style="display: inline;"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price_tax_exc']), $this);?>
</span><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '-Tx'), $this);?>
<?php endif; ?></div><?php endif; ?>

				<a class="button" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" title="<?php echo smartyTranslate(array('s' => 'View'), $this);?>
">Více informací</a>
			</div>
			<br class="clear"/>
		</li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
    
	<?php else: ?>
		<p><?php echo smartyTranslate(array('s' => 'No new product at this time','mod' => 'blocknewproducts'), $this);?>
</p>
	<?php endif; ?>
	<hr class="cleaner" />
	</div>
</div>
<!-- /MODULE Block new products -->