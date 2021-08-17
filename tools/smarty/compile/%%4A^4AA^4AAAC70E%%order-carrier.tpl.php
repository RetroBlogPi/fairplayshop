<?php /* Smarty version 2.6.20, created on 2012-04-24 12:35:10
         compiled from /home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 19, false),array('modifier', 'escape', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 28, false),array('modifier', 'count', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 426, false),array('modifier', 'regex_replace', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 435, false),array('function', 'l', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 64, false),array('function', 'convertPrice', '/home/www/fairplayshop.cz/www/themes/fairplay/order-carrier.tpl', 268, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
js/conditions.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js_dir']; ?>
layer.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['js_dir']; ?>
tools/statesManagement.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
js/onepage.js"></script>

<?php if (! $this->_tpl_vars['virtual_cart'] && $this->_tpl_vars['giftAllowed'] && $this->_tpl_vars['cart']->gift == 1): ?>
<script type="text/javascript"><?php echo '
// <![CDATA[
    $(\'document\').ready( function(){
        $(\'#gift_div\').toggle(\'slow\');
    });
//]]>
'; ?>
</script>
<?php endif; ?>


<script type="text/javascript">
// <![CDATA[
idSelectedCountry = <?php if (isset ( $_POST['id_state'] )): ?><?php echo ((is_array($_tmp=$_POST['id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['fields_cookie']['f_id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
<?php endif; ?>;
idInvoiceSelectedCountry = <?php if (isset ( $_POST['inv_id_state'] )): ?><?php echo ((is_array($_tmp=$_POST['inv_id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp=$this->_tpl_vars['fields_cookie']['f_inv_id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
<?php endif; ?>;
countries = new Array();
csz = new Array();
<?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['country']):
?>
        <?php if (isset ( $this->_tpl_vars['country']['states'] )): ?>
                countries[<?php echo ((is_array($_tmp=$this->_tpl_vars['country']['id_country'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
] = new Array();
                csz[<?php echo ((is_array($_tmp=$this->_tpl_vars['country']['id_country'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
] = new Array();
                <?php $_from = $this->_tpl_vars['country']['states']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['states'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['states']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['state']):
        $this->_foreach['states']['iteration']++;
?>
                        countries[<?php echo ((is_array($_tmp=$this->_tpl_vars['country']['id_country'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
]['<?php echo ((is_array($_tmp=$this->_tpl_vars['state']['id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
'] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['state']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
';
                        csz[<?php echo ((is_array($_tmp=$this->_tpl_vars['country']['id_country'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
]['<?php echo ((is_array($_tmp=$this->_tpl_vars['state']['id_state'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
'] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['state']['id_zone'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
';
                <?php endforeach; endif; unset($_from); ?>
        <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
dlv_addresses = new Array();
inv_addresses = new Array();
<?php $_from = $this->_tpl_vars['dlv_addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['address']):
?>
  dlv_addresses[<?php echo $this->_tpl_vars['address']['id_address']; ?>
]=new Array('<?php echo $this->_tpl_vars['address']['id_country']; ?>
','<?php echo $this->_tpl_vars['address']['id_state']; ?>
','<?php echo $this->_tpl_vars['address']['company']; ?>
','<?php echo $this->_tpl_vars['address']['lastname']; ?>
','<?php echo $this->_tpl_vars['address']['firstname']; ?>
','<?php echo $this->_tpl_vars['address']['address1']; ?>
','<?php echo $this->_tpl_vars['address']['address2']; ?>
','<?php echo $this->_tpl_vars['address']['postcode']; ?>
','<?php echo $this->_tpl_vars['address']['city']; ?>
','<?php echo $this->_tpl_vars['address']['other']; ?>
','<?php echo $this->_tpl_vars['address']['phone']; ?>
','<?php echo $this->_tpl_vars['address']['phone_mobile']; ?>
');
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['inv_addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['address']):
?>
  inv_addresses[<?php echo $this->_tpl_vars['address']['id_address']; ?>
]=new Array('<?php echo $this->_tpl_vars['address']['id_country']; ?>
','<?php echo $this->_tpl_vars['address']['id_state']; ?>
','<?php echo $this->_tpl_vars['address']['company']; ?>
','<?php echo $this->_tpl_vars['address']['lastname']; ?>
','<?php echo $this->_tpl_vars['address']['firstname']; ?>
','<?php echo $this->_tpl_vars['address']['address1']; ?>
','<?php echo $this->_tpl_vars['address']['address2']; ?>
','<?php echo $this->_tpl_vars['address']['postcode']; ?>
','<?php echo $this->_tpl_vars['address']['city']; ?>
','<?php echo $this->_tpl_vars['address']['other']; ?>
','<?php echo $this->_tpl_vars['address']['phone']; ?>
','<?php echo $this->_tpl_vars['address']['phone_mobile']; ?>
');
<?php endforeach; endif; unset($_from); ?>



<?php echo '
$(document).ready(
	function () {
		if ($("input[@name=\'id_type\']:checked").val()==0) $("#icdic").hide();
		else $("#icdic").show();
		$("input[@name=\'id_type\']").click(function(){
				if ($("input[@name=\'id_type\']:checked").val()==0) {
          $("#icdic").hide();
          $("#companysup").hide();           
        }else {
         $("#icdic").show();
         $("#companysup").show();
        }
			}
		);
	}
	);

function checkAddress() {
	if ($("input[@name=\'id_type\']:checked").val()==1 && $("input[@name=\'ic\']").val()==\'\') {
		alert(\''; ?>
<?php echo smartyTranslate(array('s' => 'Fill company identification number'), $this);?>
<?php echo '\');
		return false;
	}
	
	if ($("input[@name=\'id_type\']:checked").val()==0) {
		$("input[@name=\'ic\']").val(\'\');
		$("input[@name=\'dic\']").val(\'\');
	}
	return true;
}
'; ?>


//]]>
</script>


<script type="text/javascript">
// <![CDATA[
<?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
  add_country_zone(<?php echo $this->_tpl_vars['v']['id_country']; ?>
, <?php echo $this->_tpl_vars['v']['id_zone']; ?>
);
<?php endforeach; endif; unset($_from); ?>
//]]>
</script>

<script type="text/javascript">
// <![CDATA[
<?php echo '
document[\'onkeypress\'] = detectEvent; /* Opera browsers, but not FF<3.5 */
document[\'onkeydown\'] = detectEvent; /* FF<3.5, but not Opera */
function detectEvent(e) {
        var evt = e || window.event;
        if (evt.keyCode == 13)
                return false;
        else
                return document.defaultAction;
}
function stopEventBubbling(e) {
        if (e.stopPropagation) {
          e.stopPropagation();
        } else {
          e.cancelBubble = true;
        }
}
function checkPaymentMethod(msg) {
	// check if payment on same page is turned on
        if ($(\'#payment_content\').length == 0)
	  return true;
	if ($(\'input[name=id_payment_method]:checked\').val() == undefined) {
	  alert(msg);
	  return false;
	} else {
	  return true;
	}
}
'; ?>

//]]>
</script>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./thickbox.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php ob_start(); ?><?php echo smartyTranslate(array('s' => 'Shopping cart summary','template' => 'shopping-cart'), $this);?>
<?php $this->_smarty_vars['capture']['path'] = ob_get_contents(); ob_end_clean(); ?>

<h2 style="margin-top: 0px"><?php echo smartyTranslate(array('s' => 'Shopping cart summary','template' => 'shopping-cart'), $this);?>
</h2>
<div class="block_content2">
<?php $this->assign('current_step', 'shipping'); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<form name="checkoutform" id="form" class="std" action="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php" method="post" onsubmit="set_carrier('0'); if (document.validate_conditions != 'no') return (acceptCGV('<?php echo smartyTranslate(array('s' => 'Please accept the terms of service before the next step.','js' => 1), $this);?>
') && checkPaymentMethod('<?php echo smartyTranslate(array('s' => 'Please select payment method.','js' => 1), $this);?>
')); ">

<?php if (isset ( $this->_tpl_vars['empty'] )): ?>
	<p class="warning"><?php echo smartyTranslate(array('s' => 'Your shopping cart is empty.','template' => 'shopping-cart'), $this);?>
</p>

<?php else: ?>
<?php if (isset ( $this->_tpl_vars['lastProductAdded'] ) && $this->_tpl_vars['lastProductAdded']): ?>
	<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
		<?php if ($this->_tpl_vars['product']['id_product'] == $this->_tpl_vars['lastProductAdded']): ?>
			<table id="cart_summary" class="std" style="width:300px; margin-left:130px;">
				<thead>
					<tr>
						<th class="cart_product first_item">&nbsp;</th>
						<th class="cart_description item"><?php echo smartyTranslate(array('s' => 'Last added product','template' => 'shopping-cart'), $this);?>
</th>
						<th class="cart_total last_item">&nbsp;</th>
					</tr>
				</thead>
			</table>
			<table style="margin:5px 0px 10px 130px;">
				<tr>
					<td class="cart_product"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><img src="<?php echo $this->_tpl_vars['img_prod_dir']; ?>
<?php echo $this->_tpl_vars['product']['id_image']; ?>
-small.jpg" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /></a></td>
					<td class="cart_description">
						<h5><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></h5>
						<?php if ($this->_tpl_vars['product']['attributes']): ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['link']->getProductLink($this->_tpl_vars['product']['id_product'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['attributes'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a><?php endif; ?>
					</td>
				</tr>
			</table>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>


<?php if ($this->_tpl_vars['one_page_checkout_settings']['scroll_cart']): ?> 
<input type="hidden" id="scroll_cart" />
<?php endif; ?>

<?php if ($this->_tpl_vars['one_page_checkout_settings']['scroll_summary']): ?> 
<input type="hidden" id="scroll_summary" />
<?php endif; ?>

<?php if ($this->_tpl_vars['one_page_checkout_settings']['checkout_tracker']): ?> 
<input type="hidden" id="checkout_tracker" />
<?php endif; ?>

<?php if ($this->_tpl_vars['one_page_checkout_settings']['hide_carrier']): ?> 
<input type="hidden" id="hide_carrier" />
<?php endif; ?>

<?php if ($this->_tpl_vars['one_page_checkout_settings']['hide_payment']): ?> 
<input type="hidden" id="hide_payment" />
<?php endif; ?>

<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?> 
<input type="hidden" id="ex_texts" />
<?php endif; ?>


<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<thead id="thead_static">
			<tr>
				<th class="cart_product first_item"><?php echo smartyTranslate(array('s' => 'Product','template' => 'shopping-cart'), $this);?>
</th>
				<th class="cart_description item"><?php echo smartyTranslate(array('s' => 'Description','template' => 'shopping-cart'), $this);?>
</th>
				<th class="cart_ref item"><?php echo smartyTranslate(array('s' => 'Ref.','template' => 'shopping-cart'), $this);?>
</th>
				<th class="cart_availability item"><?php echo smartyTranslate(array('s' => 'Avail.','template' => 'shopping-cart'), $this);?>
</th>
				<th class="cart_unit item">Cena za kus/m2/bm</th>
				<th class="cart_quantity item" style="width: 5em;">Množství</th>
				<th class="cart_total last_item"><?php echo smartyTranslate(array('s' => 'Total','template' => 'shopping-cart'), $this);?>
</th>
			</tr>
		</thead>
		<tbody>
		<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['productLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['productLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['productLoop']['iteration']++;
?>
			<?php $this->assign('productId', $this->_tpl_vars['product']['id_product']); ?>
			<?php $this->assign('productAttributeId', $this->_tpl_vars['product']['id_product_attribute']); ?>
			<?php $this->assign('quantityDisplayed', 0); ?>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./shopping-cart-product-line.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php if (isset ( $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']] )): ?>
				<?php $_from = $this->_tpl_vars['customizedDatas'][$this->_tpl_vars['productId']][$this->_tpl_vars['productAttributeId']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id_customization'] => $this->_tpl_vars['customization']):
?>
					<tr class="alternate_item cart_item">
						<td colspan="5">
							<?php $_from = $this->_tpl_vars['customization']['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['datas']):
?>
								<?php if ($this->_tpl_vars['type'] == $this->_tpl_vars['CUSTOMIZE_FILE']): ?>
									<div class="customizationUploaded">
										<ul class="customizationUploaded">
											<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['picture']):
?><li><img src="<?php echo $this->_tpl_vars['pic_dir']; ?>
<?php echo $this->_tpl_vars['picture']['value']; ?>
_small" alt="" class="customizationUploaded" /></li><?php endforeach; endif; unset($_from); ?>
										</ul>
									</div>
								<?php elseif ($this->_tpl_vars['type'] == $this->_tpl_vars['CUSTOMIZE_TEXTFIELD']): ?>
									<ul class="typedText">
										<?php $_from = $this->_tpl_vars['datas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['typedText'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['typedText']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['textField']):
        $this->_foreach['typedText']['iteration']++;
?><li><?php echo smartyTranslate(array('s' => 'Text #'), $this);?>
<?php echo ($this->_foreach['typedText']['iteration']-1)+1; ?>
<?php echo smartyTranslate(array('s' => ':'), $this);?>
 <?php echo $this->_tpl_vars['textField']['value']; ?>
</li><?php endforeach; endif; unset($_from); ?>
									</ul>
								<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						</td>
						<td class="cart_quantity">Customization product:
							<?php echo $this->_tpl_vars['customization']['quantity']; ?>

							<input class="cart_quantity_up"   type="image" title="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      alt="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_up.gif"   onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
							<input class="cart_quantity_down" type="image" title="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
" alt="<?php echo smartyTranslate(array('s' => 'Subtract'), $this);?>
" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_down.gif" onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;op=down&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
							
<!--							<a class="cart_quantity_up" href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_up.gif" alt="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
" /></a><br />
							<a class="cart_quantity_down" href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;op=down&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Substract'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/quantity_down.gif" alt="<?php echo smartyTranslate(array('s' => 'Substract'), $this);?>
" /></a> -->
						</td>
						<td class="cart_total">
							<!--<a class="cart_quantity_delete" href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?delete&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
" title="<?php echo smartyTranslate(array('s' => 'Delete this customization'), $this);?>
" class="icon" /></a>-->
							<input class="cart_quantity_delete"   type="image" title="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      alt="<?php echo smartyTranslate(array('s' => 'Add'), $this);?>
"      src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete this customization'), $this);?>
"  title="<?php echo smartyTranslate(array('s' => 'Delete this customization'), $this);?>
"  onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
cart.php?delete&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;ipa=<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['id_product_attribute'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;id_customization=<?php echo $this->_tpl_vars['id_customization']; ?>
&amp;token=<?php echo $this->_tpl_vars['token_cart']; ?>
');"  />
						</td>
					</tr>
					<?php $this->assign('quantityDisplayed', $this->_tpl_vars['quantityDisplayed']+$this->_tpl_vars['customization']['quantity']); ?>
				<?php endforeach; endif; unset($_from); ?>
								<?php if ($this->_tpl_vars['product']['quantity']-$this->_tpl_vars['quantityDisplayed'] > 0): ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./shopping-cart-product-line.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
	<?php if ($this->_tpl_vars['discounts']): ?>
		<tbody>
		<?php $_from = $this->_tpl_vars['discounts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['discountLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['discountLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['discount']):
        $this->_foreach['discountLoop']['iteration']++;
?>
			<tr class="cart_discount <?php if (($this->_foreach['discountLoop']['iteration'] == $this->_foreach['discountLoop']['total'])): ?>last_item<?php elseif (($this->_foreach['discountLoop']['iteration'] <= 1)): ?>first_item<?php else: ?>item<?php endif; ?>">
				<td class="cart_discount_name" colspan="2"><?php echo $this->_tpl_vars['discount']['name']; ?>
</td>
				<td class="cart_discount_description" colspan="3"><?php echo $this->_tpl_vars['discount']['description']; ?>
</td>
				<td class="cart_discount_delete">
				<input type="image" src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  title="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"  onClick="onepage_cartupdate(document, this.form, '<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php?deleteDiscount=<?php echo $this->_tpl_vars['discount']['id_discount']; ?>
');"  />
				</td>
				<!--<td class="cart_discount_delete"><a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
order.php?deleteDiscount=<?php echo $this->_tpl_vars['discount']['id_discount']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
"><img src="<?php echo $this->_tpl_vars['img_dir']; ?>
icon/delete.gif" alt="<?php echo smartyTranslate(array('s' => 'Delete'), $this);?>
" class="icon" /></a></td>-->
				<td class="cart_discount_price"><span class="price-discount"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['discount']['value_real']*-1), $this);?>
</span></td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
		</tbody>
	<?php endif; ?>
	</table>
		
                <div id="tfoot_static_underlay" style="position: fixed; top:0; background: #eee; border: 1px solid #999; border-top: 0px; filter:alpha(opacity=92); opacity: 0.92; -moz-opacity: 0.92; display: none;"></div>
                <div id="tfoot_static">
		<table class="std">
			<tfoot>
                        <?php if ($this->_tpl_vars['priceDisplay']): ?>
                                <tr class="cart_total_productsEx">
                                        <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total products (tax excl.):','template' => 'shopping-cart'), $this);?>
</td>
                                        <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_products']), $this);?>
</td>
                                </tr>
                        <?php endif; ?>
                        <?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?>
                                <tr class="cart_total_products">
                                        <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total products (tax incl.):','template' => 'shopping-cart'), $this);?>
</td>
                                        <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_products_wt']), $this);?>
</td>
                                </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['total_discounts'] != 0): ?>
                                <?php if ($this->_tpl_vars['priceDisplay']): ?>
                                        <tr class="cart_total_voucher">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total vouchers (tax excl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price-discount"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_discounts_tax_exc']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                                <?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?>
                                        <tr class="cart_total_voucher">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total vouchers (tax incl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price-discount"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_discounts']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['total_wrapping'] > 0): ?>
                                <?php if ($this->_tpl_vars['priceDisplay']): ?>
                                        <tr class="cart_total_voucher">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total gift-wrapping (tax excl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price-discount"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_wrapping_tax_exc']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                                <?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?>
                                        <tr class="cart_total_voucher">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total gift-wrapping (tax incl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price-discount"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_wrapping']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['shippingCost'] > -1): ?>
                                <?php if ($this->_tpl_vars['priceDisplay']): ?>
                                        <tr class="cart_total_deliveryEx">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total shipping (tax excl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['shippingCostTaxExc']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                                <?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?>
                                        <tr class="cart_total_delivery">
                                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total shipping (tax incl.):','template' => 'shopping-cart'), $this);?>
</td>
                                                <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['shippingCost']), $this);?>
</td>
                                        </tr>
                                <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['priceDisplay']): ?>
                                <tr class="cart_total_priceEx">
                                        <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total (tax excl.):','template' => 'shopping-cart'), $this);?>
</td>
                                        <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_price_without_tax']), $this);?>
</td>
                                </tr>
				<!--
                                <tr class="cart_total_voucher">
                                        <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total tax:','template' => 'shopping-cart'), $this);?>
</td>
                                        <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_tax']), $this);?>
</td>
                                </tr>
				-->
			<?php else: ?>
                        <tr class="cart_total_price">
                                <td colspan="6"><?php echo smartyTranslate(array('s' => 'Total (tax incl.):','template' => 'shopping-cart'), $this);?>
</td>
                                <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['total_price']), $this);?>
</td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['free_ship'] > 0): ?>
                        <tr class="cart_free_shipping">
                                <td colspan="6" style="white-space: normal;"><?php echo smartyTranslate(array('s' => 'Remaining amount to be added to your cart in order to obtain free shipping:','template' => 'shopping-cart'), $this);?>
</td>
                                <td class="price"><?php echo Product::convertPrice(array('price' => $this->_tpl_vars['free_ship']), $this);?>
</td>
                        </tr>
                        <?php endif; ?>
		</tfoot>
                </table>
		</div>
</div>

<br />
<br />


<?php if ($this->_tpl_vars['voucherAllowed']): ?>
<div id="cart_voucher" class="table_block" style="margin-top: 0px; margin-bottom: 0px;">
	<?php if ($this->_tpl_vars['errors_discount']): ?>
		<ul class="error">
		<?php $_from = $this->_tpl_vars['errors_discount']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['error']):
?>
			<li><?php echo ((is_array($_tmp=$this->_tpl_vars['error'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</li>
		<?php endforeach; endif; unset($_from); ?>
		</ul>
	<?php endif; ?>
	
		<fieldset style="margin-bottom: 5px; padding-bottom: 7px;">
			<h4><?php echo smartyTranslate(array('s' => 'Vouchers','template' => 'shopping-cart'), $this);?>
</h4>
				<label for="discount_name"><?php echo smartyTranslate(array('s' => 'Code:','template' => 'shopping-cart'), $this);?>
</label>
				<input type="text" id="discount_name" name="discount_name" value="<?php if ($this->_tpl_vars['discount_name']): ?><?php echo $this->_tpl_vars['discount_name']; ?>
<?php endif; ?>" />
			
			<input type="submit" style="display: inline;" 	name="submitDiscount" value="<?php echo smartyTranslate(array('s' => 'Add','template' => 'shopping-cart'), $this);?>
" class="button" onClick="this.form.step.value='2'; document.validate_conditions='no'" />
		</fieldset>
	
</div>
<?php endif; ?>


		<input type="hidden" name="link" value="" />
		<input type="hidden" name="cartupdateflag" value="0" />

<?php endif; ?>





                <fieldset id="delivery_address_form" class="account_creation">
<?php if (! ( $this->_tpl_vars['virtual_cart'] && $this->_tpl_vars['one_page_checkout_settings']['virtual_no_delivery'] )): ?>
                        <h3>
			  <div style="float: right;">
			    <?php if (count($this->_tpl_vars['dlv_addresses']) == 0): ?>
							    <?php elseif (count($this->_tpl_vars['dlv_addresses']) == 1): ?>
							    <?php else: ?>
			    <span style="font-size: 0.7em;"><?php echo smartyTranslate(array('s' => 'Choose another address'), $this);?>
:</span>
			    <select id="dlv_addresses" style="width: 100px; margin-left: 0px;">
			      <?php $_from = $this->_tpl_vars['dlv_addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['address']):
?>
				<?php if (isset ( $_POST['address1'] )): ?><?php $this->assign('field_address', $_POST['address1']); ?><?php else: ?><?php $this->assign('field_address', $this->_tpl_vars['fields_cookie']['f_address1']); ?><?php endif; ?>
				<option value="<?php echo $this->_tpl_vars['address']['id_address']; ?>
" <?php if ($this->_tpl_vars['address']['address1'] == $this->_tpl_vars['field_address']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['address']['alias'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^dlv\-/", "") : smarty_modifier_regex_replace($_tmp, "/^dlv\-/", "")); ?>
</option>
			      <?php endforeach; endif; unset($_from); ?>
			    </select>
			    <?php endif; ?>
			  </div><?php echo smartyTranslate(array('s' => 'Your delivery address','template' => 'order-address'), $this);?>

			</h3>
<?php endif; ?>
                        <p class="<?php if (! $this->_tpl_vars['one_page_checkout_settings']['optional_email']): ?>required <?php endif; ?>text" <?php if ($this->_tpl_vars['one_page_checkout_settings']['hide_email']): ?>style="display:none;"<?php endif; ?>>
                                <label for="email"><?php echo smartyTranslate(array('s' => 'E-mail','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="email" name="email" value="<?php if (isset ( $_POST['email'] )): ?><?php echo $_POST['email']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_email']; ?>
<?php endif; ?>" />
                                <?php if (! $this->_tpl_vars['one_page_checkout_settings']['optional_email']): ?><sup>*</sup><?php endif; ?>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'jack@gmail.com'), $this);?>
)</i><?php endif; ?>
				<br />
			 	<div style="text-align: center; display: none;" id="existing_email_msg"><?php echo smartyTranslate(array('s' => 'This email is already registered, you can either'), $this);?>
 <a href="#" id="existing_email_login"><?php echo smartyTranslate(array('s' => 'log-in'), $this);?>
</a> <?php echo smartyTranslate(array('s' => 'or just fill in details below.'), $this);?>
</div>
                        </p>



			<p <?php if (! $this->_tpl_vars['one_page_checkout_settings']['gender']): ?>style="display:none;"<?php endif; ?>>
						      
			  <label for="id_gender1" class="top"><?php echo smartyTranslate(array('s' => 'Mr.','template' => 'authentication'), $this);?>
</label>
							<input type="radio" name="id_gender" id="id_gender1" value="1" <?php if (( isset ( $_POST['id_gender'] ) && $_POST['id_gender'] == 1 ) || ( $this->_tpl_vars['fields_cookie']['f_id_gender'] == 1 )): ?>checked="checked"<?php endif; ?> />
							<label for="id_gender2" style="float: none"><?php echo smartyTranslate(array('s' => 'Ms.','template' => 'authentication'), $this);?>
</label>
							<input type="radio" name="id_gender" id="id_gender2" value="2" <?php if (( isset ( $_POST['id_gender'] ) && $_POST['id_gender'] == 2 ) || ( $this->_tpl_vars['fields_cookie']['f_id_gender'] == 2 )): ?>checked="checked"<?php endif; ?> />

			</p>

			<p class="select" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['birthday']): ?>style="display:none;"<?php endif; ?>>
				    <span><?php echo smartyTranslate(array('s' => 'Birthday','template' => 'authentication'), $this);?>
</span>
				    <select id="days" name="days">
					    <option value="">-</option>
					    <?php $_from = $this->_tpl_vars['days']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['day']):
?>
						    <option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['day'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" <?php if (( isset ( $_POST['days'] ) && $_POST['days'] == $this->_tpl_vars['day'] ) || ( $this->_tpl_vars['fields_cookie']['f_days'] == $this->_tpl_vars['day'] )): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['day'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
&nbsp;&nbsp;</option>
					    <?php endforeach; endif; unset($_from); ?>
				    </select>
				    				    <select id="months" name="months">
					    <option value="">-</option>
					    <?php $_from = $this->_tpl_vars['months']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['month']):
?>
						    <option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" <?php if (( isset ( $_POST['months'] ) && $_POST['months'] == $this->_tpl_vars['k'] ) || ( $this->_tpl_vars['fields_cookie']['f_months'] == $this->_tpl_vars['k'] )): ?>selected="selected"<?php endif; ?>><?php echo smartyTranslate(array('s' => ($this->_tpl_vars['month']),'template' => 'authentication'), $this);?>
&nbsp;</option>
					    <?php endforeach; endif; unset($_from); ?>
				    </select>
				    <select id="years" name="years">
					    <option value="">-</option>
					    <?php $_from = $this->_tpl_vars['years']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['year']):
?>
						    <option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['year'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" <?php if (( isset ( $_POST['years'] ) && $_POST['years'] == $this->_tpl_vars['year'] ) || ( $this->_tpl_vars['fields_cookie']['f_years'] == $this->_tpl_vars['year'] )): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['year'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
&nbsp;&nbsp;</option>
					    <?php endforeach; endif; unset($_from); ?>
				    </select>
			</p> 




                        <p class="checkbox" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['newsletter']): ?>style="display:none;"<?php endif; ?>>
                                <input type="checkbox" name="newsletter" id="newsletter" value="1" <?php if (( isset ( $_POST['newsletter'] ) && $_POST['newsletter'] == 1 ) || ( ! isset ( $_POST['newsletter'] ) && $this->_tpl_vars['fields_cookie']['f_newsletter'] == 1 )): ?> checked="checked"<?php endif; ?> />
                                <label for="newsletter"><?php echo smartyTranslate(array('s' => 'Sign up for our newsletter','template' => 'authentication'), $this);?>
</label>
                        </p>
                        <p class="checkbox" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['special_offers']): ?>style="display:none;"<?php endif; ?>>
                                <input type="checkbox"name="optin" id="optin" value="1" <?php if (( isset ( $_POST['optin'] ) && $_POST['optin'] == 1 ) || ( ! isset ( $_POST['optin'] ) && $this->_tpl_vars['fields_cookie']['f_optin'] == 1 )): ?> checked="checked"<?php endif; ?> />
                                <label for="optin"><?php echo smartyTranslate(array('s' => 'Receive special offers from our partners','template' => 'authentication'), $this);?>
</label>
                        </p>
<?php if ($this->_tpl_vars['one_page_checkout_settings']['newsletter'] || $this->_tpl_vars['one_page_checkout_settings']['special_offers']): ?>
		<div style="border-bottom: 1px solid #ccc;">
		</div>
<?php endif; ?>

<?php if (! ( $this->_tpl_vars['virtual_cart'] && $this->_tpl_vars['one_page_checkout_settings']['virtual_no_delivery'] )): ?>
                        
                  			<p class="radio required" style="line-height: 29px;">
                  				<span><?php echo smartyTranslate(array('s' => 'Type of address'), $this);?>
</span>
                  				<input type="radio" name="id_type" id="id_type1" value="0" checked="checked"/>
                  				<label for="id_gender1" class="top"><?php echo smartyTranslate(array('s' => 'Personal'), $this);?>
</label>
                  				<input type="radio" name="id_type" id="id_type2" value="1" <?php if (isset ( $_POST['id_type'] ) && $_POST['id_type'] == 1): ?>checked="checked"<?php endif; ?> />
                  				<label for="id_gender2" class="top"><?php echo smartyTranslate(array('s' => 'Firm'), $this);?>
</label>
                  			</p>                        
                        
                        <div id="icdic">
                        
                        <p class="required text" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['company_delivery']): ?>style="display:none;"<?php endif; ?>>
                                <label for="company"><?php echo smartyTranslate(array('s' => 'Company','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="company" name="company" value="<?php if (isset ( $_POST['company'] )): ?><?php echo $_POST['company']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_company']; ?>
<?php endif; ?>" />
                                <sup id="companysup">*</sup>
                        </p>                        
                        
                        <p class="required text">
                                <label for="ic"><?php echo smartyTranslate(array('s' => 'IC','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="ic" name="ic" value="<?php if (isset ( $_POST['ic'] )): ?><?php echo $_POST['ic']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_ic']; ?>
<?php endif; ?>" />
                                <sup>*</sup>  
                        </p>
                        
                        <p class="text">
                                <label for="dic"><?php echo smartyTranslate(array('s' => 'DIC','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="dic" name="dic" value="<?php if (isset ( $_POST['dic'] )): ?><?php echo $_POST['dic']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_dic']; ?>
<?php endif; ?>" />                                  
                        </p>                    
                        </div>                        
                        
                        
                        <p class="required text">
                                <label for="firstname"><?php echo smartyTranslate(array('s' => 'First name','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="firstname" name="firstname" value="<?php if (isset ( $_POST['firstname'] )): ?><?php echo $_POST['firstname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_firstname']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Jack'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="lastname"><?php echo smartyTranslate(array('s' => 'Last name','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="lastname" name="lastname" value="<?php if (isset ( $_POST['lastname'] )): ?><?php echo $_POST['lastname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_lastname']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Thompson'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="address1"><?php echo smartyTranslate(array('s' => 'Address','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="address1" id="address1" value="<?php if (isset ( $_POST['address1'] )): ?><?php echo $_POST['address1']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_address1']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => '15 High Street'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="text" id="p_address2" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['address2_delivery']): ?>style="display:none;"<?php endif; ?>>
                                <label for="address2"><?php echo smartyTranslate(array('s' => 'Address (2)','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="address2" id="address2" value="<?php if (isset ( $_POST['address2'] )): ?><?php echo $_POST['address2']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_address2']; ?>
<?php endif; ?>" />
                        </p>
                        <p class="required text">
                                <label for="postcode"><?php echo smartyTranslate(array('s' => 'Postal code / Zip code','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="postcode" id="postcode" value="<?php if (isset ( $_POST['postcode'] )): ?><?php echo $_POST['postcode']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_postcode']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => '90104'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="city"><?php echo smartyTranslate(array('s' => 'City','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="city" id="city" value="<?php if (isset ( $_POST['city'] )): ?><?php echo $_POST['city']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_city']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Paris'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required select" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['country_delivery']): ?>style="display:none;"<?php endif; ?>>
                                <label for="id_country"><?php echo smartyTranslate(array('s' => 'Country','template' => 'address'), $this);?>
</label>
                                <!-- <select name="id_country" id="id_country" onchange="carriers_display(this.options[this.selectedIndex].value);"> -->
                                 <select name="id_country" id="id_country"> 
                               <!-- <select name="id_country" id="id_country" onchange="this.form.step.value='2'; document.validate_conditions='no'; this.form.submit();">-->
                                        <option value="">-</option>
                                        <?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
                                        <option value="<?php echo $this->_tpl_vars['v']['id_country']; ?>
" <?php if (( $this->_tpl_vars['sl_country'] == $this->_tpl_vars['v']['id_country'] )): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required id_state select" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['country_delivery']): ?>style="display:none;"<?php endif; ?>>
                                <label for="id_state"><?php echo smartyTranslate(array('s' => 'State','template' => 'address'), $this);?>
</label>
                                <select name="id_state" id="id_state">
                                        <option value="">-</option>
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required text" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['phone']): ?>style="display:none;"<?php endif; ?>>
                                <label for="phone_mobile"><?php echo smartyTranslate(array('s' => 'Mobile phone','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="phone_mobile" id="phone_mobile" value="<?php if (isset ( $_POST['phone_mobile'] )): ?><?php echo $_POST['phone_mobile']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_phone_mobile']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => '555-100200'), $this);?>
)</i><?php endif; ?>
			</p>
			
        <p class="textarea">
            <label for="message"><?php echo smartyTranslate(array('s' => 'Additional information','template' => 'address'), $this);?>
</label>
            <textarea name="message" id="message" cols="26" rows="3" onkeydown="stopEventBubbling(event);"  onkeypress="stopEventBubbling(event);"><?php if (isset ( $_POST['message'] )): ?><?php echo $_POST['message']; ?>
<?php endif; ?></textarea>
    </p>			
    
    			


<?php else: ?>   <input type="hidden" name="firstname" value="<?php echo $this->_tpl_vars['virtual']['name']; ?>
" />
  <input type="hidden" name="lastname" value="<?php echo $this->_tpl_vars['virtual']['lastname']; ?>
" />
  <input type="hidden" name="address1" value="<?php echo $this->_tpl_vars['virtual']['address']; ?>
" />
  <input type="hidden" name="postcode" value="<?php echo $this->_tpl_vars['virtual']['zip']; ?>
" />
  <input type="hidden" name="city" value="<?php echo $this->_tpl_vars['virtual']['city']; ?>
" />
  <input type="hidden" name="id_country" value="<?php echo $this->_tpl_vars['one_page_checkout_settings']['default_country']; ?>
" />
  <input type="hidden" name="id_state" value="<?php echo $this->_tpl_vars['one_page_checkout_settings']['default_state']; ?>
" />

<?php endif; ?> 
		<p class="checkbox" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['same_addresses']): ?>style="display:none;"<?php endif; ?>>
                        <input type="checkbox" name="same" id="addressesAreEquals" value="1" onclick="$('#invoice_address_form').toggle('slow');" <?php if (( isset ( $_POST['email'] ) && ( isset ( $_POST['same'] ) && $_POST['same'] == 1 ) ) || ( ! isset ( $_POST['email'] ) && $this->_tpl_vars['fields_cookie']['f_same'] == 1 ) || ( ! isset ( $_POST['email'] ) && ! isset ( $this->_tpl_vars['fields_cookie']['f_email'] ) )): ?>checked="checked"<?php endif; ?> />

<?php if (! ( $this->_tpl_vars['virtual_cart'] && $this->_tpl_vars['one_page_checkout_settings']['virtual_no_delivery'] )): ?>
                        <label for="addressesAreEquals"><?php echo smartyTranslate(array('s' => 'Use the same address for billing.','template' => 'order-address'), $this);?>
</label>
<?php else: ?>                         <label for="addressesAreEquals"><?php echo $this->_tpl_vars['one_page_checkout_settings']['invoice_address_message']; ?>
</label>
<?php endif; ?>                 </p>

                </fieldset>




                <fieldset id="invoice_address_form" class="account_creation" <?php if (( isset ( $_POST['processCarrier'] ) && ( isset ( $_POST['same'] ) && $_POST['same'] == 1 ) ) || ( ! isset ( $_POST['processCarrier'] ) && ( $this->_tpl_vars['cart']->id_address_invoice == $this->_tpl_vars['cart']->id_address_delivery ) )): ?>style="display: none;"<?php endif; ?>>
                        <h3>
			  <div style="float: right;">
			    <?php if (count($this->_tpl_vars['inv_addresses']) == 0): ?>
							    <?php elseif (count($this->_tpl_vars['inv_addresses']) == 1): ?>
							    <?php else: ?>
			    <span style="font-size: 0.7em;"><?php echo smartyTranslate(array('s' => 'Choose another address'), $this);?>
:</span>
			    <select id="inv_addresses" style="width: 100px; margin-left: 0px;">
			      <?php $_from = $this->_tpl_vars['inv_addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['address']):
?>
				<?php if (isset ( $_POST['inv_address1'] )): ?><?php $this->assign('field_address', $_POST['inv_address1']); ?><?php else: ?><?php $this->assign('field_address', $this->_tpl_vars['fields_cookie']['f_inv_address1']); ?><?php endif; ?>

				<option value="<?php echo $this->_tpl_vars['address']['id_address']; ?>
" <?php if ($this->_tpl_vars['address']['address1'] == $this->_tpl_vars['field_address']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['address']['alias'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^inv\-/", "") : smarty_modifier_regex_replace($_tmp, "/^inv\-/", "")); ?>
</option>
			      <?php endforeach; endif; unset($_from); ?>
			    </select>
			    <?php endif; ?>
			  </div><?php echo smartyTranslate(array('s' => 'Your billing address','template' => 'order-address'), $this);?>

			</h3>
                        <p class="text" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['company_invoice']): ?>style="display:none;"<?php endif; ?>>
                                <label for="inv_company"><?php echo smartyTranslate(array('s' => 'Company','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="inv_company" name="inv_company" value="<?php if (isset ( $_POST['inv_company'] )): ?><?php echo $_POST['inv_company']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_company']; ?>
<?php endif; ?>" />
                                <sup>&nbsp;&nbsp;</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'My Company, Ltd.'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="inv_firstname"><?php echo smartyTranslate(array('s' => 'First name','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="inv_firstname" name="inv_firstname" value="<?php if (isset ( $_POST['inv_firstname'] )): ?><?php echo $_POST['inv_firstname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_firstname']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Jack'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="inv_lastname"><?php echo smartyTranslate(array('s' => 'Last name','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" id="inv_lastname" name="inv_lastname" value="<?php if (isset ( $_POST['inv_lastname'] )): ?><?php echo $_POST['inv_lastname']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_lastname']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Thompson'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="inv_address1"><?php echo smartyTranslate(array('s' => 'Address','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="inv_address1" id="inv_address1" value="<?php if (isset ( $_POST['inv_address1'] )): ?><?php echo $_POST['inv_address1']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_address1']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => '15 High Street'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="text" id="p_inv_address2" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['address2_invoice']): ?>style="display:none;"<?php endif; ?>>
                                <label for="inv_address2"><?php echo smartyTranslate(array('s' => 'Address (2)','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="inv_address2" id="inv_address2" value="<?php if (isset ( $_POST['inv_address2'] )): ?><?php echo $_POST['inv_address2']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_address2']; ?>
<?php endif; ?>" />
                        </p>
                        <p class="required text">
                                <label for="inv_postcode"><?php echo smartyTranslate(array('s' => 'Postal code / Zip code','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="inv_postcode" id="inv_postcode" value="<?php if (isset ( $_POST['inv_postcode'] )): ?><?php echo $_POST['inv_postcode']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_postcode']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => '90104'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required text">
                                <label for="inv_city"><?php echo smartyTranslate(array('s' => 'City','template' => 'address'), $this);?>
</label>
                                <input type="text" class="text" name="inv_city" id="inv_city" value="<?php if (isset ( $_POST['inv_city'] )): ?><?php echo $_POST['inv_city']; ?>
<?php else: ?><?php echo $this->_tpl_vars['fields_cookie']['f_inv_city']; ?>
<?php endif; ?>" />
                                <sup>*</sup>
				<?php if ($this->_tpl_vars['one_page_checkout_settings']['ex_texts']): ?><i class="ex_blur">&nbsp;&nbsp;(<?php echo smartyTranslate(array('s' => 'ex.'), $this);?>
 <?php echo smartyTranslate(array('s' => 'Paris'), $this);?>
)</i><?php endif; ?>
                        </p>
                        <p class="required select" <?php if (! $this->_tpl_vars['one_page_checkout_settings']['country_invoice']): ?>style="display:none;"<?php endif; ?>>
                                <label for="inv_id_country"><?php echo smartyTranslate(array('s' => 'Country','template' => 'address'), $this);?>
</label>
                                <select name="inv_id_country" id="inv_id_country">
                                        <option value="">-</option>
                                        <?php $_from = $this->_tpl_vars['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
                                        <option value="<?php echo $this->_tpl_vars['v']['id_country']; ?>
" <?php if (( $this->_tpl_vars['inv_sl_country'] == $this->_tpl_vars['v']['id_country'] )): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</option>
                                        <?php endforeach; endif; unset($_from); ?>
                                </select>
                                <sup>*</sup>
                        </p>
                        <p class="required inv_id_state select" style="display:none;">
                                <label for="inv_id_state"><?php echo smartyTranslate(array('s' => 'State','template' => 'address'), $this);?>
</label>
                                <select name="inv_id_state" id="inv_id_state">
                                        <option value="">-</option>
                                </select>
                                <sup>*</sup>
                        </p>
		</fieldset>
























<?php if ($this->_tpl_vars['virtual_cart']): ?>
	<input id="input_virtual_carrier" class="hidden" type="hidden" name="id_carrier" value="0" />
<?php else: ?>
	<fieldset class="account_creation" id="carrier_selection" <?php if ($this->_tpl_vars['one_page_checkout_settings']['hide_carrier']): ?>style="display: none"<?php endif; ?>>
	<h3 class="carrier_title"><?php echo smartyTranslate(array('s' => 'Choose your delivery method'), $this);?>
</h3>
	<?php if ($this->_tpl_vars['recyclablePackAllowed']): ?>
	<p class="checkbox">
		<input type="checkbox" name="recyclable" id="recyclable" value="1" <?php if (( ( isset ( $_POST['recyclable'] ) && ( $_POST['recyclable'] == 1 ) ) || $this->_tpl_vars['recyclable'] == 1 )): ?>checked="checked"<?php endif; ?> />
		<label for="recyclable"><?php echo smartyTranslate(array('s' => 'I agree to receive my order in recycled packaging'), $this);?>
</label>
	</p>
	<?php endif; ?>


	<input type="hidden" id="id_zone_hidden" name="id_zone" value="" />
	<input type="hidden" id="id_carrier_hidden" name="id_carrier" value="" />
	<input type="hidden" id="price_carrier_hidden" name="price_carrier" value="" />



	<img id="carriers_wait_img" align="right" src="<?php echo $this->_tpl_vars['img_dir']; ?>
ajax-loader.gif" style="margin-right: 48%; display: none;position:relative;top:30px;margin-top:-33px;z-index:100;" />
	<div id="carriers2">
	<!-- content from zone-carriers.tpl -->
	<!-- <?php echo smartyTranslate(array('s' => 'Carrier'), $this);?>
, <?php echo smartyTranslate(array('s' => 'Information'), $this);?>
, <?php echo smartyTranslate(array('s' => 'Price'), $this);?>
, <?php echo smartyTranslate(array('s' => 'Free!'), $this);?>
-->
	</div>


	</fieldset>



	<?php if ($this->_tpl_vars['giftAllowed']): ?>
	<fieldset class="account_creation" style="padding-bottom: 0px;">
	<!--	<h3 class="gift_title"><?php echo smartyTranslate(array('s' => 'Gift'), $this);?>
</h3>-->
		<p class="checkbox">
			<input type="checkbox" name="gift" id="gift" value="1" <?php if ($this->_tpl_vars['cart']->gift == 1): ?>checked="checked"<?php endif; ?> onclick="$('#gift_div').toggle('slow');" />
			<label for="gift"><?php echo smartyTranslate(array('s' => 'I would like the order to be gift-wrapped.'), $this);?>
</label>
			<?php if ($this->_tpl_vars['gift_wrapping_price'] > 0): ?>(<?php echo smartyTranslate(array('s' => 'Additional cost of'), $this);?>
&nbsp;<?php echo Product::convertPrice(array('price' => $this->_tpl_vars['gift_wrapping_price']), $this);?>
)<?php endif; ?>
		</p>
		<p id="gift_div" class="textarea">
			<label for="gift_message"><?php echo smartyTranslate(array('s' => 'If you wish, you can add a note to the gift:'), $this);?>
</label>
			<textarea rows="5" cols="35" id="gift_message" name="gift_message"><?php echo ((is_array($_tmp=$this->_tpl_vars['cart']->gift_message)) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</textarea>
		</p>
	</fieldset>
	<?php endif; ?>
<?php endif; ?>


<?php if ($this->_tpl_vars['one_page_checkout_settings']['payment_on_same_page']): ?>
	<input type="hidden" id="ship2pay_active" value="<?php echo $this->_tpl_vars['one_page_checkout_settings']['ship2pay_active']; ?>
" />
	<fieldset class="account_creation" id="payment_selection" <?php if ($this->_tpl_vars['one_page_checkout_settings']['hide_payment']): ?>style="display: none"<?php endif; ?>>
	<h3 class="carrier_title"><?php echo smartyTranslate(array('s' => 'Choose your payment method','template' => 'order-payment'), $this);?>
</h3>
	<img id="payment_wait_img" align="right" src="<?php echo $this->_tpl_vars['img_dir']; ?>
ajax-loader.gif" style="margin-right: 48%; display: none;position:relative;top:30px;margin-top:-33px;z-index:100;" />
	<div id="payment_content">
	<!-- content from payment_methods.tpl -->
	</div>
	</fieldset>
<?php endif; ?>


<?php if ($this->_tpl_vars['conditions']): ?>
	<fieldset class="account_creation" style="padding-bottom: 0px;">
	<!--<h3 class="condition_title"><?php echo smartyTranslate(array('s' => 'Terms of service'), $this);?>
</h3>-->
	<p class="checkbox">
		<input type="checkbox" name="cgv" id="cgv" value="1" <?php if (isset ( $_POST['cgv'] ) || $this->_tpl_vars['checkedTOS']): ?>checked="checked"<?php endif; ?> />
		<label for="cgv"><?php echo smartyTranslate(array('s' => 'I agree with the terms of service and I adhere to them unconditionally.'), $this);?>
</label> <a href="<?php echo $this->_tpl_vars['base_dir']; ?>
cms.php?id_cms=3&amp;content_only=1&amp;TB_iframe=true&amp;width=450&amp;height=500&amp;thickbox=true" class="thickbox"><?php echo smartyTranslate(array('s' => '(read)'), $this);?>
</a>
	</p>
	</fieldset>
<?php endif; ?>





                        <span><sup>*</sup><?php echo smartyTranslate(array('s' => 'Required field','template' => 'authentication'), $this);?>
</span>
	<p class="cart_navigation required submit">
		<input type="hidden" name="step" value="3" />
		<input type="submit" name="processCarrier" value="<?php echo smartyTranslate(array('s' => 'Next'), $this);?>
 &raquo;" class="exclusive" />

<input class="button_large"   type="button" value="&laquo; <?php echo smartyTranslate(array('s' => 'Continue shopping','template' => 'shopping-cart'), $this);?>
" onClick="onepage_cartupdate(document, this.form, '<?php if ($_SERVER['HTTP_REFERER'] && strstr ( $_SERVER['HTTP_REFERER'] , 'order.php' )): ?><?php echo $this->_tpl_vars['base_dir_ssl']; ?>
index.php<?php else: ?><?php echo $_SERVER['HTTP_REFERER']; ?>
<?php endif; ?>');"  />

       <!--<a href="<?php if ($_SERVER['HTTP_REFERER'] && strstr ( $_SERVER['HTTP_REFERER'] , 'order.php' )): ?><?php echo $this->_tpl_vars['base_dir_ssl']; ?>
index.php<?php else: ?><?php echo $_SERVER['HTTP_REFERER']; ?>
<?php endif; ?>" class="button_large" title="<?php echo smartyTranslate(array('s' => 'Continue shopping','template' => 'shopping-cart'), $this);?>
">&laquo; <?php echo smartyTranslate(array('s' => 'Continue shopping','template' => 'shopping-cart'), $this);?>
</a> -->
	</p>

</form>
<hr class="cleaner" />
</div><span class="cntfoot">&nbsp;</span>
<script>
  invoice_address();
//  carriers_display(document.forms['form'].id_country.value);
</script>