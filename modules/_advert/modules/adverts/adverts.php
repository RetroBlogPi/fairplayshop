<?php

/**
  * Adverts module, adverts.php
  * Adverts display
  * @category modules
  *
  * @author PaulC (paulc010) <pcampbell@ecartservice.net>
  * @copyright ecartservice.net
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.0
  *
  */

class Adverts extends Module
{
	function __construct()
	{
		$this->name = 'adverts';
		$this->tab = 'Advertisement';
		$this->version = 1.3;

		parent::__construct(); // The parent construct is required for translations

		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('Adverts and Callouts');
		$this->description = $this->l('Adds a block to display random adverts and callouts.');
	}

	function install()
	{
		if (!parent::install())
			return false;
		if (!$this->registerHook('rightColumn'))
			return false;
		
		// Create the required tables (if they don't exist)
		// If either fails, then don't install!
		$sql_1='CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advert` 
				(`id_advert` int(10) unsigned NOT NULL auto_increment,
				 `name` text,
				 `date_add` datetime NOT NULL,
				 `date_upd` datetime NOT NULL,
                                 `position` varchar(100),
				 PRIMARY KEY  (`id_advert`)
				)';
		$result1 = Db::getInstance()->Execute($sql_1);

		$sql_2='CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advert_lang`
				(`id_advert` int(10) unsigned NOT NULL,
				 `id_lang` int(10) unsigned NOT NULL,
				 `description` text,
  				 PRIMARY KEY  (`id_advert`,`id_lang`)
				 )';
		$result2 = Db::getInstance()->Execute($sql_2);
						
		if (($result1) && ($result2)) {
			return true;
		} else {
			return false;
		}
	}

    function uninstall()
	{
		if (!Configuration::deleteByName('advert_ENABLED') OR !parent::uninstall())
			return false;
		return true;
	}
	
	public function getContent()
	{
		$output = '<h2>Adverts and Callouts</h2>';
		if (Tools::isSubmit('submitAdverts'))
		{
		    $status = Tools::getValue('enabled');
			Configuration::updateValue('advert_ENABLED', $status);
			$output .= '
			<div class="conf confirm">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Settings updated').'
			</div>';
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<fieldset>
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Display all at random?').'</label>
				<div class="margin-form">	
					<input type="radio" name="enabled" value="1" '.(Configuration::get('advert_ENABLED') ? 'checked="checked" ' : '').'/>'.$this->l('yes').'
					<input type="radio" name="enabled" value="0" '.(!Configuration::get('advert_ENABLED') ? 'checked="checked" ' : '').'/>'.$this->l('no').'
				</div>
				<center><input type="submit" name="submitAdverts" value="'.$this->l('Update Settings').'" class="button" /></center>			
			</fieldset>
		</form>';

		$output .= '
		<fieldset class="space">
			<legend><img src="../img/admin/unknown.gif" alt="" class="middle" />'.$this->l('Help').'</legend>
			 <h3>'.$this->l('This module displays adverts or callouts in either the left or right column.').'</h3>
			 '.$this->l('To use, please follow these steps:').'
			 <ol>
			 	<li>'.$this->l('<b>Required:</b> Follow the installation instructions in the readme.html file FIRST').'</li>
			 	<li>'.$this->l('Go to the Adverts tab in the Back Office to add your creatives and their links').'</li>
				<li>'.$this->l('<b>Note:</b> There\'s no image size management implemented, so you need to ensure your creatives will fit!').'</li>
			 	<li>'.$this->l('By default the module will only display the first one found, unless the random option is enabled.').'</li>
			 	<li>'.$this->l('Enjoy!').'</li>
			</ol>
			<h3><i>Like this module? Want more? Then why not consider donating to the author?</i></h3>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="donations@ecartservice.net">
					<input type="hidden" name="item_name" value="Open Source Development and Support">
					<input type="hidden" name="no_shipping" value="0">
					<input type="hidden" name="no_note" value="1">
					<input type="hidden" name="currency_code" value="GBP">
					<input type="hidden" name="tax" value="0">
					<input type="hidden" name="lc" value="GB">
					<input type="hidden" name="bn" value="PP-DonationsBF">
					<input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
				</form>
			<p>&copy; <a href ="http://www.ecartservice.net" target=_blank >www.ecartservice.net</a> 2008</p>
		</fieldset>';
				
		return $output;
	}

	/**
	* Returns module content
	*
	* @param array $params Parameters
	* @return string Content
	*/
	function hookRightColumn($params)
	{
	    global $smarty;
				
                $adverts_id = Advert::getallAdvert_right();
                $adverts = array();
                foreach ($adverts_id as $advert_id) {
                        $temp = array();
                        $advert=new Advert(intval($advert_id['id_advert']), intval($params['cookie']->id_lang));
                        $temp['id'] = $advert->id;
                        $temp['link'] = $advert->name;
                        $temp['desc'] = $advert->description;
                        $temp['img'] = __PS_BASE_URI__.'img/a/'.$advert->id.'.jpg';
                        $adverts[] = $temp;
                }                

                $smarty->assign('adverts', $adverts);
                $smarty->assign('hook', 'right');


		return $this->display(__FILE__, 'adverts.tpl');
	}

	function hookLeftColumn($params)
	{
	    global $smarty;

                $adverts_id = Advert::getallAdvert_left();
                $adverts = array();
                foreach ($adverts_id as $advert_id) {
                        $temp = array();
                        $advert=new Advert(intval($advert_id['id_advert']), intval($params['cookie']->id_lang));
                        $temp['id'] = $advert->id;
                        $temp['link'] = $advert->name;
                        $temp['desc'] = $advert->description;
                        $temp['img'] = __PS_BASE_URI__.'img/a/'.$advert->id.'.jpg';
                        $adverts[] = $temp;
                }

                $smarty->assign('adverts', $adverts);
                $smarty->assign('hook', 'left');


		return $this->display(__FILE__, 'adverts.tpl');
	}

	function hookHomeTop($params)
	{
	    global $smarty;

                $adverts_id = Advert::getallAdvert_hometop();
                $adverts = array();
                foreach ($adverts_id as $advert_id) {
                        $temp = array();
                        $advert=new Advert(intval($advert_id['id_advert']), intval($params['cookie']->id_lang));
                        $temp['id'] = $advert->id;
                        $temp['link'] = $advert->name;
                        $temp['desc'] = $advert->description;
                        $temp['img'] = __PS_BASE_URI__.'img/a/'.$advert->id.'.jpg';
                        $adverts[] = $temp;
                }

                $smarty->assign('adverts', $adverts);
                $smarty->assign('hook', 'hometop');

		return $this->display(__FILE__, 'adverts.tpl');
	}

	function hookHomeBottom($params)
	{
	    global $smarty;

                $adverts_id = Advert::getallAdvert_homebottom();
                $adverts = array();
                foreach ($adverts_id as $advert_id) {
                        $temp = array();
                        $advert=new Advert(intval($advert_id['id_advert']), intval($params['cookie']->id_lang));
                        $temp['id'] = $advert->id;
                        $temp['link'] = $advert->name;
                        $temp['desc'] = $advert->description;
                        $temp['img'] = __PS_BASE_URI__.'img/a/'.$advert->id.'.jpg';
                        $adverts[] = $temp;
                }

                $smarty->assign('adverts', $adverts);
                $smarty->assign('hook', 'homebottom');

		return $this->display(__FILE__, 'adverts.tpl');
	}

	
	function hookTop($params)
	{
		return $this->hookRightColumn($params);
	}
	
	function hookFooter($params)
	{
		return $this->hookRightColumn($params);
	}

}

?>