<?php /* Smarty version 2.6.20, created on 2012-04-23 13:03:02
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/footer.tpl */ ?>
	<?php if (! $this->_tpl_vars['content_only']): ?>
			</div>

<!-- Right -->    
			<div id="right_column" class="column">
				<?php echo $this->_tpl_vars['HOOK_RIGHT_COLUMN']; ?>

			</div>
    <br class="clear" />
<!-- Footer -->
		</div>
		<div id="footer"><div class="footcnt"><?php echo $this->_tpl_vars['HOOK_FOOTER']; ?>

      <p><span class="uppercase"><?php echo $this->_tpl_vars['shop_name']; ?>
</span>, <?php echo $this->_tpl_vars['foot_addr']; ?>
 <?php echo $this->_tpl_vars['foot_city']; ?>
 <?php echo $this->_tpl_vars['foot_code']; ?>
, tel.: <?php echo $this->_tpl_vars['foot_mobil']; ?>
, email: <?php echo $this->_tpl_vars['foot_email']; ?>
</p>
    </div></div>
	<?php endif; ?>
	</body>
</html>