<?php /* Smarty version 2.6.20, created on 2012-04-24 12:35:10
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl', 4, false),array('modifier', 'intval', '/home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl', 24, false),array('modifier', 'count', '/home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl', 31, false),array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl', 13, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/themes/fairplay/./shopping-cart-product-line.tpl', 19, false),)), $this); ?>
	
	<tr class="<?php if (($this->_foreach['productLoop']['iteration'] == $this->_foreach['productLoop']['total'])): ?>last_item<?php elseif (($this->_foreach['productLoop']['iteration'] <= 1)): ?>first_item<?php endif; ?><?php if (isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] ) && $this->_tpl_vars['quantityDisplayed'] == 0): ?>alternate_item<?php endif; ?> cart_item">
	<td class="cart_product">
		<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><img src="<?php echo $this->_tpl_vars['img_prod_dir']; ?>
<?php echo $this->_tpl_vars['product']['id_image']; ?>
-small.jpg" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /></a>
	</td>
	<td class="cart_description">
		<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a>
		<?php if ($this->_tpl_vars['product']['attributes']): ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['attributes'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a><?php endif; ?>
	</td>
	<td class="cart_ref"><?php if ($this->_tpl_vars['product']['reference']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['reference'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
<?php else: ?>--<?php endif; ?></td>  
	<td class="cart_availability">
		<?php if ($this->_tpl_vars['product']['active'] && ( $this->_tpl_vars['product']['allow_oosp'] || $this->_tpl_vars['product']['stock_quantity'] > 0 )): ?>
			<img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/available.gif" alt="<?php echo smartyTranslate(array('s' => 'Available'), $this);?>
" />
		<?php else: ?>
			<img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/unavailable.gif" alt="<?php echo smartyTranslate(array('s' => 'Out of stock'), $this);?>
" />
		<?php endif; ?>
	</td>

	<td class="cart_unit"><span class="price"><?php if ($this->_tpl_vars['product']['sizeh'] > 0): ?><?php if ($this->_tpl_vars['priceDisplay']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']*100), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price_wt']*100), $this);?>
<?php endif; ?><?php else: ?><?php if ($this->_tpl_vars['priceDisplay']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price']), $this);?>
<?php else: ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['price_wt']), $this);?>
<?php endif; ?><?php endif; ?></span></td>
	
	<td class="cart_quantity">
		<?php if (isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] ) && $this->_tpl_vars['quantityDisplayed'] == 0): ?><?php echo $this->_tpl_vars['product']['customizationQuantityTotal']; ?>
<?php endif; ?>
		<?php if (! isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] ) || $this->_tpl_vars['quantityDisplayed'] > 0): ?>
		<!--	<a class="cart_quantity_delete" href="<?php echo $this->_tpl_vars['base_dir']; ?>
cart.php?delete&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
" class="icon" /></a> -->
		
		<!--<div style="float: left; padding: 5px 0px 0px 0px;">
		<input class="cart_quantity_delete"   type="image" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  title="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?delete&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
		</div>-->
		&nbsp;	

    <div  style="float: left;<?php if ($this->_tpl_vars['product']['sizeh'] > 0): ?>padding-top:6px;<?php else: ?> padding-top: 6px; padding-left: 8px;<?php endif; ?>"><?php if ($this->_tpl_vars['product']['sizeh'] > 0): ?><?php echo $this->_tpl_vars['product']['pocet']; ?>
ks <?php else: ?><?php if ($this->_tpl_vars['quantityDisplayed'] == 0 && isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] )): ?><?php echo count($this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']]); ?>
<?php else: ?><?php echo $this->_tpl_vars['product']['quantity']-$this->_tpl_vars['quantityDisplayed']; ?>
<?php endif; ?><?php endif; ?></div>

    <?php if ($this->_tpl_vars['product']['sizew'] == 0 && $this->_tpl_vars['product']['sizeh'] == 0): ?>		
		<div  style="width: 23px; float: left;">
		<input class="cart_quantity_up"   type="image" title="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      alt="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_up.gif"   onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />		
		<input class="cart_quantity_down" type="image" title="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
" alt="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_down.gif" onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;op=down&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
		</div>
		<?php else: ?>
		<div style="float: left; padding: 5px 0px 0px 0px;">
		<input class="cart_quantity_delete"   type="image" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  title="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?delete&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
		</div>
		<?php endif; ?>
		
			<!--<a class="cart_quantity_up" href="<?php echo $this->_tpl_vars['base_dir']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_up.gif" alt="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
" /></a><br />-->
			<!--<a class="cart_quantity_down" href="<?php echo $this->_tpl_vars['base_dir']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;op=down&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_down.gif" alt="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
" /></a>-->
		<?php endif; ?>
	</td>

	
	<td class="cart_total"><span class="price"><?php if ($this->_tpl_vars['quantityDisplayed'] == 0 && isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] )): ?>
		<?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['total_customization_wt']), $this);?>
<?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '+Tx'), $this);?>
<?php endif; ?><?php endif; ?><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?><br /><?php endif; ?>
				<?php if ($this->_tpl_vars['priceDisplay']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['total_customization']), $this);?>
<?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '-Tx'), $this);?>
<?php endif; ?><?php endif; ?>
			<?php else: ?>
				<?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['total_wt']), $this);?>
<?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '+Tx'), $this);?>
<?php endif; ?><?php endif; ?><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?><br /><?php endif; ?>
				<?php if ($this->_tpl_vars['priceDisplay']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['product']['total']), $this);?>
<?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '-Tx'), $this);?>
<?php endif; ?><?php endif; ?>
			<?php endif; ?> 
	     </span></td>


</tr>