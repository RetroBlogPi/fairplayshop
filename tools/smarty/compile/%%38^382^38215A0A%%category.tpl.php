<?php /* Smarty version 2.6.20, created on 2012-04-23 13:14:11
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/category.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/category.tpl', 8, false),array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/category.tpl', 77, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./breadcrumb.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['subcats1'] && count ( $this->_tpl_vars['subcats1'] ) > 0): ?>
<div class="subcats">
  <ul>
  <?php $_from = $this->_tpl_vars['subcats1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subcats1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subcats1']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sc']):
        $this->_foreach['subcats1']['iteration']++;
?>
    <li<?php if (($this->_foreach['subcats1']['iteration'] == $this->_foreach['subcats1']['total'])): ?> style="border-right:none;"<?php endif; ?>><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getCategoryLink($this->_tpl_vars['sc']['id_category'],$this->_tpl_vars['sc']['link_rewrite']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo $this->_tpl_vars['sc']['name']; ?>
</a></li>
  <?php endforeach; endif; unset($_from); ?>
  </li>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['subcats2'] && count ( $this->_tpl_vars['subcats2'] ) > 0): ?>
<div class="subcats">
  <ul>
  <?php $_from = $this->_tpl_vars['subcats2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subcats2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subcats2']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sc']):
        $this->_foreach['subcats2']['iteration']++;
?>
    <li<?php if (($this->_foreach['subcats2']['iteration'] == $this->_foreach['subcats2']['total'])): ?> style="border-right:none;"<?php endif; ?>><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getCategoryLink($this->_tpl_vars['sc']['id_category'],$this->_tpl_vars['sc']['link_rewrite']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" style="font-weight: normal;"><?php echo $this->_tpl_vars['sc']['name']; ?>
</a></li>
  <?php endforeach; endif; unset($_from); ?>
  </li>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['category']->id && $this->_tpl_vars['category']->active): ?>
	<h1 class="category_title">
		<?php echo ((is_array($_tmp=$this->_tpl_vars['category']->name)) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
		
	</h1>
  <?php if ($this->_tpl_vars['scenes'] || $this->_tpl_vars['category']->id_image || $this->_tpl_vars['category']->description): ?>
  <div class="block_content2">
	
  <?php if ($this->_tpl_vars['scenes']): ?>
		<!-- Scenes -->
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./scenes.tpl", 'smarty_include_vars' => array('scenes' => $this->_tpl_vars['scenes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<!-- Category image -->
		<?php if ($this->_tpl_vars['category']->id_image): ?>
			<img src="<?php echo $this->_tpl_vars['link']->getCatImageLink($this->_tpl_vars['category']->link_rewrite,$this->_tpl_vars['category']->id_image,'category'); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['category']->name)) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['category']->name)) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" id="categoryImage" />
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['category']->description): ?>
		<div class="cat_desc"><?php echo $this->_tpl_vars['category']->description; ?>
</div>
	<?php endif; ?>
	
	</div><?php if (! $this->_tpl_vars['products']): ?><span class="cntfoot">&nbsp;</span><?php endif; ?>
	<?php endif; ?>
	
	  
	<?php if ($this->_tpl_vars['products']): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./product-sort.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./product-list.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./pagination.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<span class="cntfoot">&nbsp;</span>
		<?php elseif (! isset ( $this->_tpl_vars['subcategories'] )): ?>
			<div class="block_content2"><p class="warning"><?php echo smartyTranslate(array('s' => 'There is no product in this category.'), $this);?>
</p></div><span class="cntfoot">&nbsp;</span>
		<?php endif; ?>
<?php elseif ($this->_tpl_vars['category']->id): ?>
	<div class="block_content2"><p class="warning"><?php echo smartyTranslate(array('s' => 'This category is currently unavailable.'), $this);?>
</p></div><span class="cntfoot">&nbsp;</span>
<?php endif; ?>