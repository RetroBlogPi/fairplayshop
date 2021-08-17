<?php
	
	# rename old file (if exists) and check if it has been changed since orig release
	function _restore_backup_file($path, &$warnings, &$errors) {
	  if (file_exists($path.".opbak") && !rename($path.".opbak", $path))
	    $errors[] = "Failed to rename file '$path.opbak' to '$path'";
	}


	$errors = array();
	$warnings = array();


	# check base dir permissions
	if (!is_writable(_PS_ROOT_DIR_)) {
	  $errors[] = "Prestashop base dir is not writable. Please set permissions using: \"sudo chmod g+rw "._PS_ROOT_DIR_." -R\", if it does not help, use: \"sudo chmod o+rw "._PS_ROOT_DIR_." -R\". You can revoke these permissions by replacig \"+\" sign with \"-\""; 
	}
	else {

	  _restore_backup_file(_PS_ROOT_DIR_."/order.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/config/smarty.config.inc.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/classes/InvoiceAddress.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/js/onepage.js", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/classes/PaymentModule.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/classes/Cart.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/mails/en/order_conf.html", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/mails/en/order_conf.txt", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/authentication.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/classes/Address.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/classes/Cookie.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/history.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/addresses.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/order-slip.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/identity.php", $warnings, $errors); 
	  _restore_backup_file(_PS_ROOT_DIR_."/discount.php", $warnings, $errors); 

	  _restore_backup_file(_PS_THEME_DIR_."order-carrier.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."shopping-cart-product-line.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."order-payment.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."authentication.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."zone-carriers.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."shopping-cart-payment.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."shopping-cart-product-line-payment.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."order-steps.tpl", $warnings, $errors); 

	  _restore_backup_file(_PS_THEME_DIR_."js/tools/statesManagement.js", $warnings, $errors); 

	  //_restore_backup_file(_PS_MODULE_DIR_."blockmyaccount/blockmyaccount.tpl", $warnings, $errors); 

	  _restore_backup_file(_PS_ROOT_DIR_."/classes/Mail.php", $warnings, $errors); 

	  _restore_backup_file(_PS_ROOT_DIR_."/classes/Module.php", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."address.tpl", $warnings, $errors); 
	  _restore_backup_file(_PS_THEME_DIR_."addresses.tpl", $warnings, $errors); 

	  _restore_backup_file(_PS_ROOT_DIR_."/cart.php", $warnings, $errors); 

	}



	if (!empty($warnings))
	  foreach ($warnings as $warning_msg)
	    echo "<div style=\"font-weight: bold; color: blue;\">[WARNING] $warning_msg</div>";

	if (!empty($errors)) {
	  foreach ($errors as $error_msg)
	  echo "<div style=\"font-weight: bold; color: red;\">[ERROR] $error_msg</div>";
	  return false;
	}	

?>
