<?php /* Smarty version 2.6.20, created on 2012-04-25 15:32:39
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/payment-gateway.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/payment-gateway.tpl', 2, false),)), $this); ?>

  <?php echo smartyTranslate(array('s' => 'Please wait, submitting...'), $this);?>

  <br />
  <div style="margin: 10px 0px 15px 160px;">
    <img src="<?php echo $this->_tpl_vars['img_dir']; ?>
ajax-loader.gif" />
  </div>
  <div id="manual_payment">
   <?php echo smartyTranslate(array('s' => 'If you are not redirected in 10 seconds, please click link:'), $this);?>
 <br />
   <a href="<?php echo $this->_tpl_vars['method_link']; ?>
" title="Submit payment"><?php echo $this->_tpl_vars['method_desc']; ?>
</a>
  </div>

  <div style="display: none;"><?php echo $this->_tpl_vars['method_content']; ?>
</div>



<script language="javascript">

  var method_link = "<?php echo $this->_tpl_vars['method_link']; ?>
";
  <?php echo '
  $(document).ready(function(){
    //$(\'#testlink\').click();
    window.location.href=method_link;
    $(\'#manual_payment\').hide();
    $(\'#manual_payment\').fadeTo(10000, 1,function() {$(this).show()});
  });
  '; ?>


</script>