<?php /* Smarty version 2.6.20, created on 2012-04-24 13:05:11
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/search.tpl', 1, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/search.tpl', 4, false),array('modifier', 'intval', '/home/www/fairplayshop.cz/www/themes/fairplay/search.tpl', 24, false),)), $this); ?>
<?php ob_start(); ?><?php echo smartyTranslate(array('s' => 'Search'), $this);?>
<?php $this->_smarty_vars['capture']['path'] = ob_get_contents(); ob_end_clean(); ?>

<h2 class="category_title"><?php echo smartyTranslate(array('s' => 'Search'), $this);?>
&nbsp;<?php if ($this->_tpl_vars['nbProducts'] > 0): ?>"<?php if ($this->_tpl_vars['query']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['query'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
<?php elseif ($this->_tpl_vars['tag']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['tag'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
<?php elseif ($this->_tpl_vars['ref']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['ref'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
<?php endif; ?>"<?php endif; ?></h2>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (! $this->_tpl_vars['nbProducts']): ?>
  <div class="block">
  <div class="block_content">
	<p class="warning">
		<?php if ($this->_tpl_vars['query']): ?>
			<?php echo smartyTranslate(array('s' => 'No results found for your search'), $this);?>
&nbsp;"<?php echo ((is_array($_tmp=$this->_tpl_vars['query'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"
		<?php else: ?>
			<?php echo smartyTranslate(array('s' => 'Please type a search keyword'), $this);?>

		<?php endif; ?>
	</p>
	</div>
	</div>
<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./product-sort.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="block">
  <div class="block_content">
  <h3><span class="big"><?php echo ((is_array($_tmp=$this->_tpl_vars['nbProducts'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
</span>&nbsp;<?php if ($this->_tpl_vars['nbProducts'] == 1): ?><?php echo smartyTranslate(array('s' => 'result has been found.'), $this);?>
<?php else: ?><?php echo smartyTranslate(array('s' => 'results have been found.'), $this);?>
<?php endif; ?></h3>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./product-list.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./pagination.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
<?php endif; ?>