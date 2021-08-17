<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blockinfos/blockinfos.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/blockinfos/blockinfos.tpl', 3, false),array('modifier', 'strip_tags', '/home/www/fairplayshop.cz/www/modules/blockinfos/blockinfos.tpl', 6, false),array('modifier', 'truncate', '/home/www/fairplayshop.cz/www/modules/blockinfos/blockinfos.tpl', 6, false),)), $this); ?>
<!-- Block informations module -->
<div id="informations_block_left" class="block">
	<h4><?php echo smartyTranslate(array('s' => 'Information','mod' => 'blockinfos'), $this);?>
</h4>
	<ul class="block_content">
		<?php $_from = $this->_tpl_vars['cmslinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['cmslinksloop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cmslinksloop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['cmslink']):
        $this->_foreach['cmslinksloop']['iteration']++;
?>
			<li<?php if ($this->_foreach['cmslinksloop']['iteration'] == 3): ?> class="last"<?php endif; ?>><a class="preview" href="<?php echo $this->_tpl_vars['cmslink']['link']; ?>
" title="<?php echo $this->_tpl_vars['cmslink']['meta_title']; ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['cmslink']['content'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 100) : smarty_modifier_truncate($_tmp, 100)); ?>
</a><br /><a href="<?php echo $this->_tpl_vars['cmslink']['link']; ?>
" class="morelink">Více...</a></li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<!-- /Block informations module -->