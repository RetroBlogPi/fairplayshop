<?php /* Smarty version 2.6.20, created on 2012-04-24 12:35:12
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/payment_methods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/payment_methods.tpl', 9, false),array('modifier', 'sizeof', '/home/www/fairplayshop.cz/www/themes/fairplay/payment_methods.tpl', 15, false),array('modifier', 'regex_replace', '/home/www/fairplayshop.cz/www/themes/fairplay/payment_methods.tpl', 19, false),)), $this); ?>
{
'html_data': ' \
 <input type="hidden" id="payment_country" name="payment_country" value="<?php echo $this->_tpl_vars['payment_country']; ?>
" /> \
 <input type="hidden" id="payment_carrier" name="payment_carrier" value="<?php echo $this->_tpl_vars['payment_carrier']; ?>
" /> \
        <?php if ($this->_tpl_vars['payment_methods'] && count ( $this->_tpl_vars['payment_methods'] )): ?> \
        <div class="table_block"> \
                <table class="std"> \
   <thead> \
    <tr><th colspan="3"><?php echo smartyTranslate(array('s' => 'Available payment methods for selected address and carrier'), $this);?>
</th><th class="last_item" style="width: 10px;">&nbsp;</th></tr> \
   </thead> \
                        <tbody> \
                        <?php $_from = $this->_tpl_vars['payment_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['myLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['myLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['payment_method']):
        $this->_foreach['myLoop']['iteration']++;
?> \
                                <tr class="<?php if (($this->_foreach['myLoop']['iteration'] <= 1)): ?>first_item<?php elseif (($this->_foreach['myLoop']['iteration'] == $this->_foreach['myLoop']['total'])): ?>last_item<?php endif; ?> <?php if (($this->_foreach['myLoop']['iteration']-1) % 2): ?>alternate_item<?php else: ?>item<?php endif; ?>"> \
                                        <td class="payment_method_action radio"><!--<?php echo $this->_tpl_vars['fields_cookie']['f_firstname']; ?>
;<?php echo $this->_tpl_vars['fields_cookie']['f_id_payment_method']; ?>
,<?php echo $this->_tpl_vars['payment_method']['id_payment_method']; ?>
,<?php echo $_POST['id_payment_method']; ?>
,<?php echo $this->_tpl_vars['checked']; ?>
--> \
                                                <input type="radio" name="id_payment_method" id="pm<?php echo $this->_tpl_vars['payment_method']['link_hash']; ?>
" value="<?php echo $this->_tpl_vars['payment_method']['link_hash']; ?>
" <?php if (( sizeof($this->_tpl_vars['payment_methods']) == 1 )): ?>checked="checked"<?php endif; ?> onclick="set_carrier2(<?php echo $this->_tpl_vars['payment_method']['cena']; ?>
,this)" /> \
                                        </td> \
                                        <td class="payment_method_name"> \
                                                <label for="pm<?php echo $this->_tpl_vars['payment_method']['link_hash']; ?>
"> \
                                                        <?php if ($this->_tpl_vars['payment_method']['img']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['payment_method']['img'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/ src/", " height=\"35\"\\0") : smarty_modifier_regex_replace($_tmp, "/ src/", " height=\"35\"\\0")); ?>
<?php endif; ?> \
                                                </label> \
                                        </td> \
     <td> \
       <?php echo $this->_tpl_vars['payment_method']['desc']; ?>
 \
     </td> \
     <td> \
       &nbsp; \
     </td> \
                                </tr> \
                        <?php endforeach; endif; unset($_from); ?> \
                        </tbody> \
                </table> \
        </div> \
        <?php else: ?> \
  <p class="warning"><?php echo smartyTranslate(array('s' => 'No payment modules available for this country / carrier combination.'), $this);?>
</p></td></tr> \
        <?php endif; ?> \
 ',
'num_payments': '<?php echo sizeof($this->_tpl_vars['payment_methods']); ?>
'
}