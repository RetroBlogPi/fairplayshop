<?php
	
	# rename old file (if exists) and check if it has been changed since orig release
	function _backup_file($path, &$warnings) {
	  if (file_exists($path.".opbak")) 
	    $warnings[] = "Backup file '".$path.".opbak' already existed.";
	  else {
	    /*if (file_exists($path) && md5_file(_PS_MODULE_DIR_."onepagecheckout/originals/".basename($path)) != md5_file($path))
	      $warnings[] = "Checksum for file '$path' failed, you have probably altered this file manually.";*/
	    if (file_exists($path) && !rename($path, $path.".opbak"))
	      $warnings[] = "Failed to rename file '$path' to '$path.opbak'";
	  }
	}


	# copy altered files to proper location, calling backup beforehand
	function _copy_file($source, $destination, &$warnings, &$errors) {
	  _backup_file($destination, $warnings);
	  if (!copy($source, $destination))
	    $errors[] = "Failed to copy file '$source' to '$destination'";
	}


	$errors = array();
	$warnings = array();


	# check base dir permissions
	if (!is_writable(_PS_ROOT_DIR_)) {
	  $errors[] = "Prestashop base dir is not writable. Please set permissions using: \"sudo chmod g+rw "._PS_ROOT_DIR_." -R\", if it does not help, use: \"sudo chmod o+rw "._PS_ROOT_DIR_." -R\". You can revoke these permissions by replacig \"+\" sign with \"-\""; 
	}
	else {
	  $files_dir = _PS_MODULE_DIR_."onepagecheckout/files/";

	  _copy_file($files_dir."order.php", 			_PS_ROOT_DIR_."/order.php", $warnings, $errors); 
	  _copy_file($files_dir."config/smarty.config.inc.php",	_PS_ROOT_DIR_."/config/smarty.config.inc.php", $warnings, $errors); 
	  _copy_file($files_dir."classes/InvoiceAddress.php", 	_PS_ROOT_DIR_."/classes/InvoiceAddress.php", $warnings, $errors); 
	  _copy_file($files_dir."js/onepage.js", 		_PS_ROOT_DIR_."/js/onepage.js", $warnings, $errors); 
	  _copy_file($files_dir."classes/PaymentModule.php",	_PS_ROOT_DIR_."/classes/PaymentModule.php", $warnings, $errors); 
	  _copy_file($files_dir."classes/Cart.php", 		_PS_ROOT_DIR_."/classes/Cart.php", $warnings, $errors); 
	  _copy_file($files_dir."mails/en/order_conf.html", 	_PS_ROOT_DIR_."/mails/en/order_conf.html", $warnings, $errors); 
	  _copy_file($files_dir."mails/en/order_conf.txt", 	_PS_ROOT_DIR_."/mails/en/order_conf.txt", $warnings, $errors); 
	  _copy_file($files_dir."authentication.php", 		_PS_ROOT_DIR_."/authentication.php", $warnings, $errors); 
	  _copy_file($files_dir."classes/Address.php", 		_PS_ROOT_DIR_."/classes/Address.php", $warnings, $errors); 
	  _copy_file($files_dir."classes/Cookie.php", 		_PS_ROOT_DIR_."/classes/Cookie.php", $warnings, $errors); 
	  _copy_file($files_dir."history.php", 			_PS_ROOT_DIR_."/history.php", $warnings, $errors); 
	  _copy_file($files_dir."addresses.php", 		_PS_ROOT_DIR_."/addresses.php", $warnings, $errors); 
	  _copy_file($files_dir."identity.php", 		_PS_ROOT_DIR_."/identity.php", $warnings, $errors); 
	  _copy_file($files_dir."order-slip.php", 		_PS_ROOT_DIR_."/order-slip.php", $warnings, $errors); 
	  _copy_file($files_dir."discount.php", 		_PS_ROOT_DIR_."/discount.php", $warnings, $errors); 

	  _copy_file($files_dir."themes/prestashop/order-carrier.tpl", 		_PS_THEME_DIR_."order-carrier.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/shopping-cart-product-line.tpl", _PS_THEME_DIR_."shopping-cart-product-line.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/order-payment.tpl", 		_PS_THEME_DIR_."order-payment.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/authentication.tpl", 	_PS_THEME_DIR_."authentication.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/zone-carriers.tpl", 		_PS_THEME_DIR_."zone-carriers.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/shopping-cart-payment.tpl", 	_PS_THEME_DIR_."shopping-cart-payment.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/shopping-cart-product-line-payment.tpl", _PS_THEME_DIR_."shopping-cart-product-line-payment.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/order-steps.tpl", 		_PS_THEME_DIR_."order-steps.tpl", $warnings, $errors); 

	  _copy_file($files_dir."themes/prestashop/js/tools/statesManagement.js",_PS_THEME_DIR_."js/tools/statesManagement.js", $warnings, $errors); 

	  //_copy_file($files_dir."blockmyaccount.tpl", 		_PS_MODULE_DIR_."blockmyaccount/blockmyaccount.tpl", $warnings, $errors); 

	  _copy_file($files_dir."themes/prestashop/img/ajax-loader.gif",_PS_THEME_DIR_."img/ajax-loader.gif", $warnings, $errors); 

	  _copy_file($files_dir."classes/Mail.php", 		_PS_ROOT_DIR_."/classes/Mail.php", $warnings, $errors); 

	  _copy_file($files_dir."themes/prestashop/payment_methods.tpl",_PS_THEME_DIR_."payment_methods.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/payment-gateway.tpl",_PS_THEME_DIR_."payment-gateway.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/address.tpl",	_PS_THEME_DIR_."address.tpl", $warnings, $errors); 
	  _copy_file($files_dir."themes/prestashop/addresses.tpl",	_PS_THEME_DIR_."addresses.tpl", $warnings, $errors); 
	  _copy_file($files_dir."classes/Module.php", 			_PS_ROOT_DIR_."/classes/Module.php", $warnings, $errors); 

	  _copy_file($files_dir."cart.php", 			_PS_ROOT_DIR_."/cart.php", $warnings, $errors); 

          $languages = Language::getLanguages();
          foreach ($languages as $language) {
                $iso = Language::getIsoById(intval($language['id_lang']));
	  	_copy_file($files_dir."mails/en/mail_passwd.html",_PS_ROOT_DIR_."/mails/$iso/mail_passwd.html", $warnings, $errors); 
	  	_copy_file($files_dir."mails/en/mail_passwd.txt",_PS_ROOT_DIR_."/mails/$iso/mail_passwd.txt", $warnings, $errors); 
	  }


	}

	$ret=true;

	if (!empty($warnings))
	  foreach ($warnings as $warning_msg)
	    echo "<div style=\"font-weight: bold; color: blue;\">[WARNING] $warning_msg</div>";

	if (!empty($errors)) {
	  foreach ($errors as $error_msg)
	  echo "<div style=\"font-weight: bold; color: red;\">[ERROR] $error_msg</div>";
	  $ret=false;
	}	

?>
