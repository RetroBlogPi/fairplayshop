<?php /* Smarty version 2.6.20, created on 2012-04-24 12:35:10
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl', 9, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl', 23, false),array('modifier', 'intval', '/home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl', 23, false),array('modifier', 'sizeof', '/home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl', 23, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/zone-carriers.tpl', 27, false),)), $this); ?>
{
'html_data': ' \
        <?php if ($this->_tpl_vars['carriers'] && count ( $this->_tpl_vars['carriers'] )): ?> \
        <div class="table_block"> \
                <table class="std"> \
                        <thead> \
                                <tr> \
                                        <th class="carrier_action first_item"></th> \
                                        <th class="carrier_name item"><?php echo smartyTranslate(array('s' => 'Carrier','template' => 'order-carrier'), $this);?>
</th> \
                                        <th class="carrier_infos item"><?php echo smartyTranslate(array('s' => 'Information','template' => 'order-carrier'), $this);?>
</th> \
                                        <th class="carrier_price last_item"><?php echo smartyTranslate(array('s' => 'Price','template' => 'order-carrier'), $this);?>
</th> \
                                </tr> \
                        </thead> \
                        <tbody> \
                        <?php $_from = $this->_tpl_vars['carriers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['myLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['myLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['carrier']):
        $this->_foreach['myLoop']['iteration']++;
?> \
				<?php if ($this->_tpl_vars['priceDisplay']): ?> \
					<?php $this->assign('shown_price', $this->_tpl_vars['carrier']['price_tax_exc']); ?> \
				<?php else: ?>\
					<?php $this->assign('shown_price', $this->_tpl_vars['carrier']['price']); ?> \
				<?php endif; ?> \
                                <tr class="<?php if (($this->_foreach['myLoop']['iteration'] <= 1)): ?>first_item<?php elseif (($this->_foreach['myLoop']['iteration'] == $this->_foreach['myLoop']['total'])): ?>last_item<?php endif; ?> <?php if (($this->_foreach['myLoop']['iteration']-1) % 2): ?>alternate_item<?php else: ?>item<?php endif; ?>"> \
                                        <td class="carrier_action radio"><!--<?php echo $this->_tpl_vars['fields_cookie']['f_firstname']; ?>
;<?php echo $this->_tpl_vars['fields_cookie']['f_id_carrier']; ?>
,<?php echo $this->_tpl_vars['carrier']['id_carrier']; ?>
,<?php echo $_POST['id_carrier']; ?>
,<?php echo $this->_tpl_vars['checked']; ?>
--> \
                                                <input type="radio" name="id_carrier" onclick="set_carrier(\'<?php if ($this->_tpl_vars['shown_price'] > 0): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['shown_price']), $this);?>
<?php else: ?>0<?php endif; ?>\', 1);" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['id_carrier'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" id="id_carrier<?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['id_carrier'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" <?php if (( $_POST['id_carrier'] == $this->_tpl_vars['carrier']['id_carrier'] ) || ( $this->_tpl_vars['carrier']['id_carrier'] == $this->_tpl_vars['checked'] && ! isset ( $_POST['id_carrier'] ) ) || ( $this->_tpl_vars['checked'] == 0 && $this->_tpl_vars['i'] == 0 ) || ( sizeof($this->_tpl_vars['carriers']) == 1 )): ?>checked="checked"<?php $this->assign('selected_price', $this->_tpl_vars['shown_price']); ?><?php endif; ?> /> \
                                        </td> \
                                        <td class="carrier_name"> \
                                                <label for="id_carrier<?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['id_carrier'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
"> \
                                                        <?php if ($this->_tpl_vars['carrier']['img']): ?><img class="carrier" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['img'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /><?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
<?php endif; ?> \
                                                </label> \
                                        </td> \
                                        <td class="carrier_infos"><?php echo ((is_array($_tmp=$this->_tpl_vars['carrier']['delay'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</td> \
                                        <td class="carrier_price"><?php if ($this->_tpl_vars['shown_price']): ?><span id="xyz" class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['shown_price']), $this);?>
</span><?php else: ?><?php echo smartyTranslate(array('s' => 'Free!'), $this);?>
<?php endif; ?></td> \
                                </tr> \
                        <?php endforeach; endif; unset($_from); ?> \
                        </tbody> \
                </table> \
        </div> \
        <?php else: ?> \
                <p class="warning"><?php echo smartyTranslate(array('s' => 'There is no carrier available that will deliver to this address!','template' => 'order-carrier'), $this);?>
</td></tr> \
        <?php endif; ?> \
	',
'selected_price': '<?php if ($this->_tpl_vars['selected_price']): ?><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['selected_price']), $this);?>
<?php else: ?>0<?php endif; ?>',
'num_carriers': '<?php echo sizeof($this->_tpl_vars['carriers']); ?>
'
}