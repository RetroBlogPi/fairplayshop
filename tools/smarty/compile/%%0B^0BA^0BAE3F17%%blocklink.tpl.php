<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/modules/blocklink/blocklink.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlentities', '/home/www/fairplayshop.cz/www/modules/blocklink/blocklink.tpl', 12, false),)), $this); ?>
<!-- Block links module -->
<div id="links_block_left" class="block">
	<h4>
	<?php if ($this->_tpl_vars['url']): ?>
		<a href="<?php echo $this->_tpl_vars['url']; ?>
"><?php echo $this->_tpl_vars['title']; ?>
</a>
	<?php else: ?>
		<?php echo $this->_tpl_vars['title']; ?>

	<?php endif; ?>
	</h4>
	<ul class="block_content bullet">
	<?php $_from = $this->_tpl_vars['blocklink_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['blocklink_link']):
?>
		<li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['blocklink_link']['url'])) ? $this->_run_mod_handler('htmlentities', true, $_tmp) : htmlentities($_tmp)); ?>
"<?php if ($this->_tpl_vars['blocklink_link']['newWindow']): ?> onclick="window.open(this.href);return false;"<?php endif; ?>><?php echo $this->_tpl_vars['blocklink_link'][$this->_tpl_vars['lang']]; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<!-- /Block links module -->