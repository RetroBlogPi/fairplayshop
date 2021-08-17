<?php

class blocktopmenucat extends Module
{
  private $_menu = '';
  private $_html = '';

  public function __construct()
  {
    $this->name = 'blocktopmenucat';
    $this->tab = 'Julien Breux Developpement';
    $this->version = 1.3;
    parent::__construct();
    $this->displayName = $this->l('Category top horizontal menu');
    $this->description = $this->l('Add a new category menu on top of your shop.');
  }

  public function install()
  {
    if(!parent::install() || 
       !$this->registerHook('top') || 
       !$this->installDB())
      return false;
    return true;
  }

  public function installDb()
  {
    return true;
  }

  public function uninstall()
  {
    if(!parent::uninstall() || 
       !$this->uninstallDB())
      return false;
    return true;
  }

  private function uninstallDb()
  {
    return true;
  }

  private function getMenuItems()
  {
    global $cookie;
    $id_lang=intval($cookie->id_lang);
    $result = Db::getInstance()->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'category` c
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`
		WHERE `id_lang` = '.intval($id_lang).'
		AND `active` = 1 AND id_parent=1
		ORDER BY `sort_order` ASC');
		
		return $result;
  }

  private function makeMenu()
  {
		global $cookie, $page_name;
    foreach($this->getMenuItems() as $item)
    {
      $id = $item['id_category'];

      $this->getCategory($id, $cookie->id_lang);
    }
  }

  private function getCategory($id_category, $id_lang, $layer=0)
  {
    global $page_name;

    $categorie = new Category($id_category, $id_lang);
    if(is_null($categorie->id))
      return;
    $selected = ($page_name == 'category' && ((int)Tools::getValue('id_category') == $id_category)) ? ' class="sfHoverForce"' : '';
    $this->_menu .= '<li'.$selected.''.($id_category==21 ? ' style="margin-right: 0;"' : '').'>';
    if(count(explode('.', $categorie->name)) > 1)
      $name = str_replace('.', '', strstr($categorie->name, '.'));
    else
      $name = $categorie->name;
    
    if($categorie->id_parent==1) {
      $this->_menu .= '<a href="'.$categorie->getLink().'" title="'.$name.'" style="background: transparent url('._PS_BASE_URL_.__PS_BASE_URI__.'img/c/root/menu_button_'.$categorie->id.'.png) no-repeat left top;">'.$name.'</a>';
      //$this->_menu .= '<a href="'.$categorie->getLink().'" title="'.$name.'"><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'img/c/14-root.jpg" alt="'.$name.'" /></a>';
    } else {
      $this->_menu .= '<a href="'.$categorie->getLink().'">'.$name.'</a>';
    }
    
    $childrens = Category::getChildren($id_category, $id_lang);
    if($layer<1 && count($childrens))
    {
      $this->_menu .= '<div class="submenubg"><div class="submenutop" id="menu'.$categorie->id.'"><ul>';
      foreach($childrens as $children) {
        $this->getCategory($children['id_category'], $id_lang, $layer+1);
      }
      $this->_menu .= '</ul><hr class="cleaner" /></div></div>';
    }
    $this->_menu .= '</li>';
  }

  public function hooktop($param)
  {
		global $smarty;
		$this->makeMenu();
		$smarty->assign('MENU', $this->_menu);
		$smarty->assign('this_path', $this->_path);
    return $this->display(__FILE__, 'blocktopmenu.tpl');
  }
}
?>