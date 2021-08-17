<?php

/**
  * Adverts tab for admin panel, AdminAdverts.php
  * @category admin
  *
  * @author PaulC (paulc010) <pcampbell@ecartservice.net>
  * @copyright ecartservice.net
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.0
  *
  */

include_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');

class AdminAdverts extends AdminTab
{
	protected $maxImageSize = 200000;

	public function __construct()
	{
	 	$this->table = 'advert';
	 	$this->className = 'Advert';
	 	$this->view = false;
	 	$this->edit = true;
		$this->delete = true;
		
 		$this->fieldImageSettings = array('name' => 'logo', 'dir' => 'a');
		
		$this->fieldsDisplay = array(
			'id_advert' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
			'name' => array('title' => $this->l('Link'), 'width' => 120),                        
			'logo' => array('title' => $this->l('Logo'), 'align' => 'center', 'image' => 'a', 'orderby' => false, 'search' => false),
                        'position' => array('title' => $this->l('Position'), 'width' => 80),
		);
	
		parent::__construct();
	}
		
	public function displayForm()
	{
		global $currentIndex;
		
		$advert = $this->loadObject(true);

		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();

		echo '
		<script type="text/javascript">
			id_language = Number('.$defaultLanguage.');
		</script>
		<form action="'.$currentIndex.'&submitAdd'.$this->table.'=1&token='.$this->token.'" method="post" enctype="multipart/form-data" class="width3">
		'.($advert->id ? '<input type="hidden" name="id_'.$this->table.'" value="'.$advert->id.'" />' : '').'
			<fieldset><legend><img src="../img/admin/adverts.gif" />'.$this->l('Advert').'</legend>
				<label>'.$this->l('Position:').' </label>
				<div class="margin-form">
					<select id="position" name="position">
                                           <option value="none" '.(Tools::getValue('position', $advert->position)=='none' ? 'selected="selected"' : '').'>----</option>
                                           <option value="left" '.(Tools::getValue('position', $advert->position)=='left' ? 'selected="selected"' : '').'>Left column</option>
                                           <option value="hometop" '.(Tools::getValue('position', $advert->position)=='hometop' ? 'selected="selected"' : '').'>Home page - top</option>
                                           <option value="right" '.(Tools::getValue('position', $advert->position)=='right' ? 'selected="selected"' : '').'>Right column</option>';
                                           //<option value="homebottom" '.(Tools::getValue('position', $advert->position)=='homebottom' ? 'selected="selected"' : '').'>Home page - bottom</option>'
                                           echo '<option value="footer" '.(Tools::getValue('position', $advert->position)=='footer' ? 'selected="selected"' : '').'>Footer</option>
                                        </select>

					<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
				</div>
                                <label>'.$this->l('Link:').' </label>
				<div class="margin-form">
					<textarea rows="3" cols="33" name="name" />'.htmlentities(Tools::getValue('name', $advert->name), ENT_COMPAT, 'UTF-8').'</textarea>
					<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
				</div>
				<label>'.$this->l('Description:').' </label>
				<div class="margin-form">';
				foreach ($languages as $language)
					echo '
					<div id="description_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
						<textarea rows="3" cols="33" name="description_'.$language['id_lang'].'"/>'. htmlentities($this->getFieldValue($advert, 'description', intval($language['id_lang'])), ENT_COMPAT, 'UTF-8').'</textarea>
						<span class="hint" name="help_box">'.$this->l('Invalid characters:').' <>;=#{}<span class="hint-pointer">&nbsp;</span></span>
						<p></p>
					</div>';							
				$this->displayFlags($languages, $defaultLanguage, 'description', 'description');
		echo '	<br class="clear" /></div>
		        <br />
				<label>'.$this->l('Logo:').' </label>
				<div class="margin-form">
					<input type="file" name="logo" />
					<p>'.$this->l('Upload advert from your computer').'</p>
					<br class="clear" />
				</div>';

        echo '<br class="clear" /><div style="position: relative; float: none;">';
        $this->displayImage($advert->id, _PS_IMG_DIR_.'a/'.$advert->id.'.jpg',350);
        echo '</div><br class="clear" />';

		echo '<div class="margin-form">
					<input type="submit" value="'.$this->l('   Save   ').'" name="submitAdd'.$this->table.'" class="button" />
				</div>
				<div class="small"><sup>*</sup> '.$this->l('Required field').'</div>
			</fieldset>
		</form>';

        
	}
}
?>