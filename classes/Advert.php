<?php

/**
  * Advert class, Advert.php
  * Adverts management
  * @category classes
  *
  * @author PaulC (paulc010) <pcampbell@ecartservice.net>
  * @copyright ecartservice.net
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.0
  *
  */

class		Advert extends ObjectModel
{
	public 		$id;
	
	/** @var string Name */
	public 		$name;
	
	/** @var string A short description for the discount */
	public 		$description;
	
	/** @var string Object creation date */
	public 		$date_add;

	/** @var string Object last modification date */
	public 		$date_upd;

        public 		$position;
	
 	//protected 	$fieldsRequired = array('name');
 	//protected 	$fieldsSize = array('name' => 1024);
 	//protected 	$fieldsValidate = array('name' => 'isCatalogName');
	
	protected	$fieldsSizeLang = array('description' => 128);
	protected	$fieldsValidateLang = array('description' => 'isGenericName');
	
	protected 	$table = 'advert';
	protected 	$identifier = 'id_advert';
		
	public function getFields()
	{
		parent::validateFields();
		$fields['name'] = pSQL($this->name);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
                $fields['position'] = pSQL($this->position);
		return $fields;
	}
	
	public function getTranslationsFieldsChild()
	{
		parent::validateFieldsLang();
		return parent::getTranslationsFields(array('description'));
	}
		
	/**
	  * Return name from id
	  *
	  * @param integer $id_advert Advert ID
	  * @return string name
	  */
	static public function getNameById($id_advert)
	{
		$result = Db::getInstance()->getRow('
		SELECT `name`
		FROM `'._DB_PREFIX_.'advert`
		WHERE `id_advert` = '.intval($id_advert));
		if (isset($result['name']))
			return $result['name'];
		return false;
	}

////
// Return a random value
	function ad_rand($min = null, $max = null)
	{
    	static $seeded;

    	if (!isset($seeded)) {
      		mt_srand((double)microtime()*1000000);
      		$seeded = true;
    	}

    	if (isset($min) && isset($max)) {
      		if ($min >= $max) {
        		return $min;
      		} else {
        		return mt_rand($min, $max);
      		}
    	} else {
      		return mt_rand();
    	}
  	}

	/**
	  * Return name from id
	  *
	  * @param integer $id_advert Advert ID
	  * @return string name
	  */
	protected function getmaxAdvertIndex()
	{
		$result = Db::getInstance()->getRow('
			SELECT MAX(`id_advert`) AS max_num 
			FROM `'._DB_PREFIX_.'advert`');
		
		return isset($result) ? $result['max_num'] : 0;
	}
	
	protected function getminAdvertIndex()
	{
		$result = Db::getInstance()->getRow('
			SELECT MIN(`id_advert`) AS min_num 
			FROM `'._DB_PREFIX_.'advert`');
		
		return isset($result) ? $result['min_num'] : 0;
	}
		
	static public function getrandomAdvert()
	{
		$random = Advert::ad_rand(0,Advert::getmaxAdvertIndex());
		$result = Db::getInstance()->getRow('
			SELECT `id_advert` FROM `'._DB_PREFIX_.'advert` 
			WHERE `id_advert` >= '.intval($random)
		);
		if (isset($result['id_advert']))
			return $result['id_advert'];
		return false;
	}

	static public function getdefaultAdvert()
	{
		$result = Db::getInstance()->getRow('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert`');
		if (isset($result['id_advert']))
			return $result['id_advert'];
		return false;
	}

	static public function getallAdvert_left()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert` WHERE position = "left"');
                
		if (isset($result))
			return $result;
		return false;
	}

	static public function getallAdvert_right()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert` WHERE position = "right"');

		if (isset($result))
			return $result;
		return false;
	}

	static public function getallAdvert_hometop()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert` WHERE position = "hometop"');

		if (isset($result))
			return $result;
		return false;
	}

	static public function getallAdvert_homebottom()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert` WHERE position = "homebottom"');

		if (isset($result))
			return $result;
		return false;
	}

	static public function getallAdvert_footer()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT `id_advert`
		FROM `'._DB_PREFIX_.'advert` WHERE position = "footer"');

		if (isset($result))
			return $result;
		return false;
	}

}
?>