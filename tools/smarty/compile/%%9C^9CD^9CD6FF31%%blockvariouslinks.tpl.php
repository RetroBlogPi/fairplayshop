<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blockvariouslinks/blockvariouslinks.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blockvariouslinks/blockvariouslinks.tpl', 4, false),array('modifier', 'addslashes', '/home/www/fairplayshop.cz/www/modules/blockvariouslinks/blockvariouslinks.tpl', 10, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/modules/blockvariouslinks/blockvariouslinks.tpl', 10, false),)), $this); ?>
<!-- MODULE Block various links -->
<ul class="block_various_links" id="block_various_links_footer">
		<li class="first_item"><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
new-products.php" title=""><?php echo smartyTranslate(array('s' => 'New products','mod' => 'blockvariouslinks'), $this);?>
</a></li>
			
	<?php $_from = $this->_tpl_vars['cmslinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cmslink']):
?>
		<li class="item"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['cmslink']['link'])) ? $this->_run_mod_handler('addslashes', true, $_tmp) : addslashes($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['cmslink']['meta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cmslink']['meta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
</ul>
<!-- /MODULE Block various links -->