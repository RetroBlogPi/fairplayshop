<?php /* Smarty version 2.6.20, created on 2018-02-23 07:11:46
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/contact-form.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/contact-form.tpl', 1, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/contact-form.tpl', 16, false),array('modifier', 'intval', '/home/www/fairplayshop.cz/www/themes/fairplay/contact-form.tpl', 24, false),array('modifier', 'stripslashes', '/home/www/fairplayshop.cz/www/themes/fairplay/contact-form.tpl', 39, false),)), $this); ?>
<?php ob_start(); ?><?php echo smartyTranslate(array('s' => 'Contact'), $this);?>
<?php $this->_smarty_vars['capture']['path'] = ob_get_contents(); ob_end_clean(); ?>

<h2 class="category_title"><?php echo smartyTranslate(array('s' => 'Contact us'), $this);?>
</h2>

<div class="block">
<div class="block_content">
<?php if (isset ( $this->_tpl_vars['confirmation'] )): ?>
	<p><?php echo smartyTranslate(array('s' => 'Your message has been successfully sent to our team.'), $this);?>
</p>
	<ul class="footer_links">
		<li><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
"><img class="icon" alt="" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/home.gif"/></a><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
"><?php echo smartyTranslate(array('s' => 'Home'), $this);?>
</a></li>
	</ul>
<?php else: ?>
	<p class="bold"><?php echo smartyTranslate(array('s' => 'For questions about an order or for information about our products'), $this);?>
.</p>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<form action="<?php echo ((is_array($_tmp=$this->_tpl_vars['request_uri'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" method="post" class="std">
		<fieldset>
			<h3><?php echo smartyTranslate(array('s' => 'Send a message'), $this);?>
</h3>
			<p class="select">
				<label for="id_contact"><?php echo smartyTranslate(array('s' => 'Subject'), $this);?>
</label>
				<select id="id_contact" name="id_contact" onchange="showElemFromSelect('id_contact', 'desc_contact')">
					<option value="0"><?php echo smartyTranslate(array('s' => '-- Choose --'), $this);?>
</option>
				<?php $_from = $this->_tpl_vars['contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['contact']):
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['contact']['id_contact'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" <?php if (isset ( $_POST['id_contact'] ) && $_POST['id_contact'] == $this->_tpl_vars['contact']['id_contact']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['contact']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</p>
			<p id="desc_contact0" class="desc_contact">&nbsp;</p>
		<?php $_from = $this->_tpl_vars['contacts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['contact']):
?>
			<p id="desc_contact<?php echo ((is_array($_tmp=$this->_tpl_vars['contact']['id_contact'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" class="desc_contact" style="display:none;">
			<label>&nbsp;</label><?php echo ((is_array($_tmp=$this->_tpl_vars['contact']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</p>
		<?php endforeach; endif; unset($_from); ?>
		<p class="text">
			<label for="email"><?php echo smartyTranslate(array('s' => 'E-mail address'), $this);?>
</label>
			<input type="text" id="email" name="from" value="<?php echo $this->_tpl_vars['email']; ?>
" />
		</p>
		<p class="textarea">
			<label for="message"><?php echo smartyTranslate(array('s' => 'Message'), $this);?>
</label>
			 <textarea id="message" name="message" rows="7" cols="35"><?php if (isset ( $_POST['message'] )): ?><?php echo ((is_array($_tmp=((is_array($_tmp=$_POST['message'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')))) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
<?php endif; ?></textarea>
		</p>
		<p class="submit">
			<input type="submit" name="submitMessage" id="submitMessage" value="<?php echo smartyTranslate(array('s' => 'Send'), $this);?>
" class="button_large" />
		</p>
	</fieldset>
</form>
<?php endif; ?>
</div>
</div>