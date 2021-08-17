<?php /* Smarty version 2.6.20, created on 2013-01-04 15:28:16
         compiled from /home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 4, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 30, false),array('modifier', 'count', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 8, false),array('modifier', 'ceil', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 9, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 16, false),array('modifier', 'truncate', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 19, false),array('modifier', 'date_format', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 25, false),array('modifier', 'intval', '/home/www/fairplayshop.cz/www/modules/homefeatured/homefeatured.tpl', 33, false),)), $this); ?>
<!-- MODULE Home Featured Products -->

  <div class="block">
	<h4><?php echo smartyTranslate(array('s' => 'Nejoblíbenější produkty','mod' => 'homefeatured'), $this);?>
</h4>
	<?php if (isset ( $this->_tpl_vars['products'] ) && $this->_tpl_vars['products']): ?>

	<?php $this->assign('nbItemsPerLine', 4); ?>
	<?php $this->assign('nbLi', count($this->_tpl_vars['products'])); ?>
	<?php $this->assign('nbLines', ((is_array($_tmp=$this->_tpl_vars['nbLi']/$this->_tpl_vars['nbItemsPerLine'])) ? $this->_run_mod_handler('ceil', true, $_tmp) : ceil($_tmp))); ?>
	
	<ul id="product_list" class="clear">
	<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['products']['iteration']++;
?>
		<li class="ajax_block_product <?php if (($this->_foreach['products']['iteration'] <= 1)): ?>first_item<?php elseif (($this->_foreach['products']['iteration'] == $this->_foreach['products']['total'])): ?>last_item<?php else: ?>item<?php endif; ?> <?php if ($this->_foreach['products']['iteration']%$this->_tpl_vars['nbItemsPerLine'] == 0): ?>last_item_of_line<?php elseif ($this->_foreach['products']['iteration']%$this->_tpl_vars['nbItemsPerLine'] == 1): ?>first_item_of_line<?php endif; ?> <?php if ($this->_foreach['products']['iteration'] > ( $this->_foreach['products']['total'] - ( $this->_foreach['products']['total'] % $this->_tpl_vars['nbItemsPerLine'] ) )): ?>last_line<?php endif; ?>">
			<div class="center_block">
								<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" class="product_img_link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><img src="<?php echo $this->_tpl_vars['link']->getImageLink($this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['id_image'],'home'); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /></a>
				<h3>
                    <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 35, '...') : smarty_modifier_truncate($_tmp, 35, '...')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></h3>
							</div>
			<div class="right_block">
				<?php if ($this->_tpl_vars['product']['on_sale']): ?>
					<span class="on_sale"><?php echo smartyTranslate(array('s' => 'On sale!','mod' => 'homefeatured'), $this);?>
</span>
				<?php elseif (( $this->_tpl_vars['product']['reduction_price'] != 0 || $this->_tpl_vars['product']['reduction_percent'] != 0 ) && ( $this->_tpl_vars['product']['reduction_from'] == $this->_tpl_vars['product']['reduction_to'] || ( ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')) <= $this->_tpl_vars['product']['reduction_to'] && ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d') : smarty_modifier_date_format($_tmp, '%Y-%m-%d')) >= $this->_tpl_vars['product']['reduction_from'] ) )): ?>
					<span class="discount"><?php echo smartyTranslate(array('s' => 'Price lowered!','mod' => 'homefeatured'), $this);?>
</span>
				<?php else: ?>
				  <span class="discount">&nbsp;</span>
				<?php endif; ?>
				<?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?><div><span class="price" style="display: inline;"><?php if ($this->_tpl_vars['product']['customsize']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']*100), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']), $this);?>
<?php endif; ?></span><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '+Tx'), $this);?>
<?php endif; ?></div><?php endif; ?>
				<?php if ($this->_tpl_vars['priceDisplay']): ?><div><span class="price" style="display: inline;"><?php if ($this->_tpl_vars['product']['customsize']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price_tax_exc']*100), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price_tax_exc']), $this);?>
<?php endif; ?></span><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '-Tx'), $this);?>
<?php endif; ?></div><?php endif; ?>
				<?php if (( $this->_tpl_vars['product']['allow_oosp'] || $this->_tpl_vars['product']['quantity'] > 0 ) && $this->_tpl_vars['product']['customizable'] != 2): ?>
					<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" href="<?php echo $this->_tpl_vars['base_dir']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['static_token']; ?>
">Do košíku</a>
				<?php else: ?>
						<span class="exclusive">Do košíku</span>
				<?php endif; ?>
				<a class="button" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['link'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" title="Detail">Detail</a>
			</div>
			<br class="clear"/>
		</li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
			
		</div>
	<?php else: ?>
		<div class="block_content"><p><?php echo smartyTranslate(array('s' => 'No featured products','mod' => 'homefeatured'), $this);?>
</p></div>
	<?php endif; ?>

<!-- /MODULE Home Featured Products -->