<?php /* Smarty version 2.6.20, created on 2012-04-23 15:04:57
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/cms.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/cms.tpl', 14, false),)), $this); ?>
<?php if ($this->_tpl_vars['cms']): ?>
	<?php if ($this->_tpl_vars['content_only']): ?>
	<div style="text-align:left; padding:10px;" class="rte">
		<?php echo $this->_tpl_vars['cms']->content; ?>

	</div>
	<?php else: ?>
	<span class="cnthead">&nbsp;</span>
	<div class="rte rtebg">
		<?php echo $this->_tpl_vars['cms']->content; ?>

	</div>
	<span class="cntfoot">&nbsp;</span>
	<?php endif; ?>
<?php else: ?>
	<?php echo smartyTranslate(array('s' => 'This page does not exist.'), $this);?>

<?php endif; ?>
<br />
<?php if (! $this->_tpl_vars['content_only']): ?>
<p><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Home'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/home.gif" alt="<?php echo smartyTranslate(array('s' => 'Home'), $this);?>
" class="icon" /></a><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Home'), $this);?>
"><?php echo smartyTranslate(array('s' => 'Home'), $this);?>
</a></p>
<?php endif; ?>
