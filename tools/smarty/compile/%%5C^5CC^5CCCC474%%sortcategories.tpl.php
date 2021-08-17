<?php /* Smarty version 2.6.20, created on 2012-04-24 16:35:48
         compiled from /home/www/fairplayshop.cz/www/modules/sortcategories/sortcategories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/modules/sortcategories/sortcategories.tpl', 15, false),)), $this); ?>
<!-- Block categories module -->
<script type="text/javascript" src="<?php echo $this->_tpl_vars['modules_dir']; ?>
sortcategories/jquery-1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['modules_dir']; ?>
sortcategories/ui.core.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['modules_dir']; ?>
sortcategories/ui.sortable.js"></script>

<div id="categories_block_left" class="block">
	<div class="block_content" id="sort">
<?php $_from = $this->_tpl_vars['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categorie'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categorie']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['element']):
        $this->_foreach['categorie']['iteration']++;
?>
		<div class="sortables" id="sort_<?php echo $this->_tpl_vars['element']['id_category']; ?>
">
			<input type="hidden" name="new_order[<?php echo $this->_tpl_vars['element']['id_category']; ?>
][id_category]" value="<?php echo $this->_tpl_vars['element']['id_category']; ?>
" id="id_<?php echo $this->_tpl_vars['element']['id_category']; ?>
" >
			<input type="hidden" name="new_order[<?php echo $this->_tpl_vars['element']['id_category']; ?>
][position]" value="<?php echo $this->_tpl_vars['element']['position']; ?>
" id="position_<?php echo $this->_tpl_vars['element']['id_category']; ?>
" >
			<input type="hidden" name="new_order[<?php echo $this->_tpl_vars['element']['id_category']; ?>
][nom]" value="<?php echo $this->_tpl_vars['element']['nom']; ?>
" id="nom_<?php echo $this->_tpl_vars['element']['id_category']; ?>
">
			<?php echo $this->_tpl_vars['element']['nom']; ?>
 
				<?php if ($this->_tpl_vars['element']['link']): ?>
				<a href="<?php echo $this->_tpl_vars['element']['link']; ?>
#sorth"><?php echo smartyTranslate(array('s' => 'sort this category','mod' => 'sortcategories'), $this);?>
</a>
				<?php endif; ?>
		</div>
<?php endforeach; endif; unset($_from); ?>
	</div>
</div>
<!-- /Block categories module -->