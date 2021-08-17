##### Installation #####

 Before installation, make sure you have write permissions to these directories:
 [root_dir]
 /config
 /classes
 /js
 /mails
 /themes/[your_theme]/
 /themes/[your_theme]/js/tools/
 /modules/blockmyaccount/

 [root_dir] referrs to you prestashop root directory (e.g. /var/www/mystore)
 [your_theme] referrs to actual theme used (e.g. prestashop)

 Sample:
 cd /var/www/mystore
 chmod go+rw . config/ classes/ js/ mails/ themes/prestashop/ themes/prestashop/js/tools modules/blockmyaccount/


 To install module copy onepagecheckout directory to your modules directory.
 Then go to admin interface, tab Modules and click to install One Page Checkout module.


 
 
 ##### ship2pay module support #####
 
 Currently tested with ship2pay_1.2.2 (module version 0.1)
 
 Now can be simply turned on in Back Office, One page checkout module configuration.

 

 ##### Password sending for non-english stores (if separate emails turned off) #####

 See options "Send password in separate email" and "Show password" in configuration. If you disable
 password sending in separate email, you may want to configure inline password attaching to order confirmation.
 
 Please change following files:
  mails/your_language/order_conf.txt 
  mails/your_language/order_conf.html 
 add this string to any place in email template: {mail_passwd} 
 
 This pattern will be replaced with content of mail_passwd.html or mail_passwd.txt respectively. 
 Please update content of these two files also (with your translation, styles, ...)
 
 You can find sample setting in english templates.