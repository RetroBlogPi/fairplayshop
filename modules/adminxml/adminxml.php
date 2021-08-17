<?php

/**
  * XML exports module - AdminXML
  *
  * @author Radek Polasek <broucek@broucek-a-beruska.cz>
  * @copyright Radek Polasek, www.broucek-a-beruska.cz
  * @http://www.myshop.com/modules/adminxml/adminxml.php?cron=1 to use in cron
  *
  * The buyer can free use/edit/modify this software in anyway
  * The buyer is NOT allowed to redistribute this module in anyway or resell it or redistribute it to third party
*/

// cron condition possible
if (isset($_GET['cron']) && $_GET['cron']) {
        include('../../config/config.inc.php');
        include('../../classes/Module.php');
}

class AdminXML extends Module {

	function __construct() {
		$this->name = 'adminxml';
		$this->tab = 'Tools';
		$this->author = 'Brouček a Beruška Webdesign';

		parent::__construct();

		/* The parent construct is required for translations */
		$this->page = basename(__FILE__, '.php');
		$this->displayName = $this->l('XML exporty');
		$this->description = $this->l('Vygeneruje XML exporty zboží');
		
		$this->xml_server = array(
		        'Zbozi.cz',
		        'Heureka.cz',
		        'Hledejceny.cz',
		        'Monitor.cz',
		        'Srovname.cz',
		        'Najdislevu.cz',
		        'Nejnakup.cz',
		        'Naakup.cz',
		        'Zalevno.cz',
		        'Hyperzbozi.cz',
		        'Cenyzbozi.cz'
               );
               
               $this->heureka_delivery = array(
                        'Česká pošta' => 'CESKA_POSTA',
                        'Česká pošta - Balík Na poštu' => 'CESKA_POSTA_NA_POSTU',
                        'ČSAD Logistik Ostrava' => 'CSAD_LOGISTIK_OSTRAVA',
                        'DPD' => 'DPD',
                        'DHL' => 'DHL',
                        'EMS' => 'EMS',
                        'FOFR' => 'FOFR',
                        'Gebrüder Weiss' => 'GEBRUDER_WEISS',
                        'Geis' => 'GEIS',
                        'General Parcel' => 'GENERAL_PARCEL',
                        'GLS' => 'GLS',
                        'HDS' => 'HDS',
                        'HeurekaPoint' => 'HEUREKAPOINT',
                        'InTime' => 'INTIME',
                        'PPL' => 'PPL',
                        'Radiálka' => 'RADIALKA',
                        'Seegmuller' => 'SEEGMULLER',
                        'TNT' => 'TNT',
                        'TOPTRANS' => 'TOPTRANS',
                        'UPS' => 'UPS',
                        'Vlastní přeprava' => 'VLASTNI_PREPRAVA'
               );

               $this->extra_message = array(
                        'Prodloužená záruka' => 'extended_warranty',
                        'Příslušenství zdarma' => 'free_accessories',
                        'Pouzdro zdarma' => 'free_case',
                        'Doprava zdarma' => 'free_delivery',
                        'Dárek zdarma' => 'free_gift',
                        'Montáž zdarma' => 'free_installation',
                        'Osobní odběr zdarma' => 'free_store_pickup',
                        'Voucher na další nákup' => 'voucher'
               );
	}

	function install() {
		if (!parent::install() || !Configuration::updateValue('ADMINXML_VARIANTS', 1))
                        return false;
                return true;
	}
	
	public function uninstall() {
	        foreach ($this->xml_server as $server) {
                        $server = substr(strtolower($server), 0, -3);
                        Configuration::deleteByName('ADMINXML_'. strtoupper($server));
                }
		if (parent::uninstall()
                        AND Configuration::deleteByName('ADMINXML_VARIANTS')
                        AND Configuration::deleteByName('ADMINXML_FEATURES')
                        AND Configuration::deleteByName('ADMINXML_CATEG')
                        AND Configuration::deleteByName('ADMINXML_STOCK')
                        AND Configuration::deleteByName('ADMINXML_EXTRA_MESSAGE')
                        AND Configuration::deleteByName('ADMINXML_DESCRIPTION')
                        AND Configuration::deleteByName('ADMINXML_EXTRATEXT')
                        AND Configuration::deleteByName('ADMINXML_MAX_CPC')
                        AND Configuration::deleteByName('ADMINXML_MAX_CPC_SEARCH')
                        AND Configuration::deleteByName('ADMINXML_HEUREKA_DELIVERY')
                        AND Configuration::deleteByName('ADMINXML_HEUREKA_PRICE')
                        AND Configuration::deleteByName('ADMINXML_HEUREKA_PRICE_COD')
                        AND Configuration::deleteByName('ADMINXML_HEUREKA_CPC')

                ) return true;
                return false;
	}

	public function getContent() {
		echo $this->displayForm();
	}

	public function displayForm() {
                global $cookie, $currentIndex, $cart;
                include_once(dirname(__FILE__) . '/functions.inc.php');
                
                // core setting
                @set_time_limit(7200);
                if (!isset($cart) OR !$cart->id) $cart = new Cart();
                $shopurl = 'http://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8') . __PS_BASE_URI__;
                $id_lang = Configuration::get('PS_LANG_DEFAULT');
                $kc = new Currency(Currency::getIdByIsoCode('CZK'));
                $xml_server = $this->xml_server;
                sort($xml_server);
                
                echo '<h2>XML exporty pro vyhledávače zboží</h2>
                        <p style="width:96%" class="warning">
                                Adresa pro použití v cronu je - <a style="text-decoration:underline" href="'. $shopurl .'modules/adminxml/adminxml.php?cron=1">'. $shopurl .'modules/adminxml/adminxml.php?cron=1</a><br />
                                <img src="../img/admin/warning.gif" alt="" /> Nejdříve je nutné vybrané servery uložit v nastavení, až poté je možno generovat XML.<br />
                                <img src="../img/admin/warning.gif" alt="" /> Při nepoužívání spouštění v cronu je potřeba po každé úpravě cen v e-shopu vygenerovat XML exporty znovu, aby byly ceny aktuální.<br />
                                <img src="../img/admin/warning.gif" alt="" /> Při nadměrném počtu produktů v e-shopu je v některých případech nutné, kvůli omezení přidělené paměti hostingu, vygenerovat jednotlivé exporty zvlášť nebo zrušit generování variant produktů<br />
                                <img src="../img/admin/warning.gif" alt="" /> Pro správné zobrazení dostupnosti ve vyhledávačích uvádějte u produktů text skladem a text není skladem ve dnech (např. do 5 dnů)
                </p>';
               
               // update settings
               if (Tools::isSubmit('settings') OR Tools::isSubmit('submit_noproducts') OR Tools::isSubmit('submit_heureka') OR Tools::isSubmit('submit_zbozi'))
                        $_GET['submitXML'] = 0;

               if (Tools::isSubmit('settings')) {
                        foreach ($xml_server as $server) {
                                $server = substr(strtolower($server), 0, -3);
                                Configuration::updateValue('ADMINXML_'. strtoupper($server), intval(Tools::getValue('adminxml_'. $server)));
                        }
                        if (Configuration::updateValue('ADMINXML_VARIANTS', Tools::getValue('adminxml_variants'))
                                AND Configuration::updateValue('ADMINXML_FEATURES', Tools::getValue('adminxml_features'))
				AND Configuration::updateValue('ADMINXML_CATEG', Tools::getValue('adminxml_categ'))
				AND Configuration::updateValue('ADMINXML_STOCK', Tools::getValue('adminxml_stock'))
                                AND Configuration::updateValue('ADMINXML_DESCRIPTION', Tools::getValue('adminxml_description'))
                                AND Configuration::updateValue('ADMINXML_EXTRATEXT', Tools::getValue('adminxml_extratext'))
                        )
                                echo '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="" /> Nastavení upraveno.</div>';
                        else
                                echo '<div class="alert error">Nastala chyba!</div>';
                }
                if (Tools::isSubmit('submit_noproducts')) {
                        if (Configuration::updateValue('ADMINXML_NOPRODUCTS', implode(Tools::getValue('noproducts'), ',')))
                                echo '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="" /> Nastavení upraveno.</div>';
                        else
                                echo '<div class="conf warning"><img src="../img/admin/warning.gif" alt="" />Nastala chyba!</div>';
                }
                if (Tools::isSubmit('submit_heureka')) {
                        foreach (Tools::getValue('categories') as $key=>$value)
                                Configuration::updateValue('ADMINXML_HEUREKA_'. $key, $value);

                        $freedelivery = array();
                        foreach ($this->heureka_delivery as $value)
                                if (Tools::getValue('freedelivery_'. $value)) $freedelivery[] = $value;

                        if (Configuration::updateValue('ADMINXML_HEUREKA_FREEDELIVERY', implode($freedelivery, ','))
                                AND Configuration::updateValue('ADMINXML_HEUREKA_DELIVERY', Tools::getValue('delivery'))
                                AND Configuration::updateValue('ADMINXML_HEUREKA_PRICE', Tools::getValue('delivery_price'))
                                AND Configuration::updateValue('ADMINXML_HEUREKA_PRICE_COD', Tools::getValue('delivery_price_cod'))
                                AND Configuration::updateValue('ADMINXML_HEUREKA_CPC', Tools::getValue('adminxml_heureka_cpc'))
                        )
                                echo '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="" /> Nastavení upraveno.</div>';
                        else
                                echo '<div class="conf warning"><img src="../img/admin/warning.gif" alt="" />Nastala chyba!</div>';
                }
                if (Tools::isSubmit('submit_zbozi')) {
                        foreach (Tools::getValue('categories') as $key=>$value)
                                Configuration::updateValue('ADMINXML_ZBOZI_'. $key, $value);
                        if (Configuration::updateValue('ADMINXML_EXTRA_MESSAGE', Tools::getValue('extra_message'))
                                AND Configuration::updateValue('ADMINXML_MAX_CPC', Tools::getValue('adminxml_max_cpc'))
                                AND Configuration::updateValue('ADMINXML_MAX_CPC_SEARCH', Tools::getValue('adminxml_max_cpc_search'))
                                AND Configuration::updateValue('ADMINXML_DEPOTS', Tools::getValue('adminxml_depots'))
                        )
                                echo '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="" /> Nastavení upraveno.</div>';
                        else
                                echo '<div class="conf warning"><img src="../img/admin/warning.gif" alt="" />Nastala chyba!</div>';
                }

                // directory path
                if (isset($_GET['cron']) && $_GET['cron']) $path = '../../xml';
                else $path = '../xml';
                if (!file_exists($path))   mkdir($path, 0777);

                // submit or cron condition
	        if (Tools::getValue('submitXML') || (isset($_GET['cron']) && $_GET['cron'])) {
                        
                        // only active exports
                        $tmp_server = $xml_server;
	                foreach ($xml_server as $key=>$server) {
                                if  (!Configuration::get('ADMINXML_'. substr(strtoupper($server), 0, -3))) unset($xml_server[$key]);
                        }

                        // categories
                        $result = Db::getInstance()->ExecuteS('
                                SELECT c.id_category, c.id_parent, c.level_depth, cl.name
                                FROM '._DB_PREFIX_.'category c
                                LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cl.id_category = c.id_category AND cl.id_lang = '. $id_lang .')
                                WHERE c.active = 1 AND c.level_depth > 0
                                AND (SELECT COUNT(cat.id_category) FROM '._DB_PREFIX_.'category cat
                                WHERE c.id_parent = cat.id_category AND cat.active = 1)
                                ORDER BY level_depth, id_category
                        ');
                        $categories = $heureka_categories = $zbozi_categories = $listCategories = array();
                        foreach ($result as $row) {
                                $listCategories[] = $row['id_category'];
                                if ($row['level_depth'] == 1) {
                                        $categories[$row['id_category']] = Category::hideCategoryPosition($row['name']);

                                        // Heureka kategorie
                                        if (Configuration::get('ADMINXML_HEUREKA_'. $row['id_category']))
                                                $heureka_categories[$row['id_category']] = Configuration::get('ADMINXML_HEUREKA_'. $row['id_category']);
                                        else
                                                $heureka_categories[$row['id_category']] = Category::hideCategoryPosition($row['name']);

                                        // Zbozi kategorie
                                        if (Configuration::get('ADMINXML_ZBOZI_'. $row['id_category']))
                                                $zbozi_categories[$row['id_category']] = Configuration::get('ADMINXML_ZBOZI_'. $row['id_category']);
                                        else
                                                $zbozi_categories[$row['id_category']] = Category::hideCategoryPosition($row['name']);
                                }
                                else {
                                        $categories[$row['id_category']] = $categories[$row['id_parent']] . " - " . Category::hideCategoryPosition($row['name']);

                                        // Heureka kategorie
                                        if (Configuration::get('ADMINXML_HEUREKA_'. $row['id_category']))
                                                $heureka_categories[$row['id_category']] = $heureka_categories[$row['id_parent']] . " | " . Configuration::get('ADMINXML_HEUREKA_'. $row['id_category']);
                                        else
                                                $heureka_categories[$row['id_category']] = $heureka_categories[$row['id_parent']] . " | " . Category::hideCategoryPosition($row['name']);

                                        // Zbozi kategorie
                                        if (Configuration::get('ADMINXML_ZBOZI_'. $row['id_category']))
                                                $zbozi_categories[$row['id_category']] = $zbozi_categories[$row['id_parent']] . " | " . Configuration::get('ADMINXML_ZBOZI_'. $row['id_category']);
                                        else
                                                $zbozi_categories[$row['id_category']] = $zbozi_categories[$row['id_parent']] . " | " . Category::hideCategoryPosition($row['name']);
                                }
                        }
                        unset($result);
                        $listCategories = implode(',', $listCategories);

                        // only active id products
                        $id_products = Db::getInstance()->ExecuteS('
                                SELECT p.id_product
		                FROM '._DB_PREFIX_.'product p
		                LEFT JOIN '._DB_PREFIX_.'category_product cp ON (cp.id_product = p.id_product)
		                LEFT JOIN '._DB_PREFIX_.'category c ON (c.id_category = cp.id_category)
		                WHERE p.active = 1 AND c.active = 1 AND c.id_category IN ('. $listCategories .')'. (Configuration::get('ADMINXML_NOPRODUCTS')?' AND p.id_product NOT IN ('. Configuration::get('ADMINXML_NOPRODUCTS').')':'') .'
                                '. (Configuration::get('ADMINXML_STOCK')?' AND p.quantity > 0':''). '
                                GROUP BY p.id_product
		                ORDER BY p.id_product DESC
		        ');

                        foreach ($xml_server as $server) {

                                // XML headers
                                $xml = AdminXMLfunc::header();
                                $server = substr(strtolower($server), 0, -3);
                                if ($server == 'monitor') $xml .= "<OFFERS>\n";
                                elseif ($server == 'zbozi') $xml .= "<SHOP xmlns='http://www.zbozi.cz/ns/offer/1.0'>\n";
                                else $xml .= "<SHOP>\n";
                                
                                // XML bodies
                                foreach ($id_products as $id_product) {
                                        echo "\n";
                                        $product = new Product($id_product['id_product'], true, $id_lang);

                                        if (!$product->active) continue;

                                        // parent category
                                        $cat = Db::getInstance()->ExecuteS('
                                                SELECT cp.id_category, cl.link_rewrite AS link
                                                FROM '._DB_PREFIX_.'category_product cp
                                                LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (cp.id_category = cl.id_category AND cl.id_lang = '. $id_lang .')
                                                LEFT JOIN '._DB_PREFIX_.'category c ON (c.id_category = cp.id_category)
                                                WHERE cp.id_product = '. $product->id .' AND c.active = 1
                                                ORDER BY cp.id_category DESC
                                                LIMIT 1
                                        ');

                                        // Kategorie
                                        $cat_id = $cat[0]['id_category'];
                                        $cat_link = $cat[0]['link'];
                                        if ($categories[$product->id_category_default]) $category = str_replace('&', '&amp;', $categories[$product->id_category_default]);
                                        else $category = str_replace('&', '&amp;', $categories[$cat_id]);

                                        // Heureka kategorie
                                        if ($heureka_categories[$product->id_category_default] AND Configuration::get('ADMINXML_HEUREKA_'. $product->id_category_default))
                                                $category_heureka = str_replace('&', '&amp;', Configuration::get('ADMINXML_HEUREKA_'. $product->id_category_default));
                                        elseif ($heureka_categories[$product->id_category_default])
                                                $category_heureka = str_replace('&', '&amp;', $heureka_categories[$product->id_category_default]);
                                        else
                                                $category_heureka = str_replace('&', '&amp;', $heureka_categories[$cat_id]);

                                        // Zbozi kategorie
                                        if ($zbozi_categories[$product->id_category_default] AND Configuration::get('ADMINXML_ZBOZI_'. $product->id_category_default))
                                                $category_zbozi = str_replace('&', '&amp;', Configuration::get('ADMINXML_ZBOZI_'. $product->id_category_default));
                                        elseif ($zbozi_categories[$product->id_category_default])
                                                $category_zbozi = str_replace('&', '&amp;', $zbozi_categories[$product->id_category_default]);
                                        else
                                                $category_zbozi = str_replace('&', '&amp;', $zbozi_categories[$cat_id]);

                                        unset($cat);

                                        $img = Product::getCover($product->id);
                                        $name = htmlspecialchars(strip_tags(html_entity_decode($product->name, ENT_COMPAT, 'utf-8'))) . Configuration::get('ADMINXML_EXTRATEXT');
                                        $description = htmlspecialchars(strip_tags(html_entity_decode((Configuration::get('ADMINXML_DESCRIPTION')?$product->description:$product->description_short), ENT_COMPAT, 'utf-8')));
                                        $manufacturer = htmlspecialchars(strip_tags(html_entity_decode($product->manufacturer_name, ENT_COMPAT, 'utf-8')));

                                        // dostupnost
                                        $available_now = $available_later = '';
                                        $now = AdminXMLfunc::multiexplode(array(",","-"," "), $product->available_now);
                                        $later = AdminXMLfunc::multiexplode(array(",","-"," "), $product->available_later);
                                        foreach ($now as $part)
                                                if (is_numeric($part)) $available_now = $part;
                                        foreach ($later as $part)
                                                if (is_numeric($part)) $available_later = $part;
                                        
                                        if (floatval(_PS_VERSION_) >= 1.4) {
                                                $picture = Link::getImageLink('', $product->id .'-'. $img['id_image']);
                                                $picture_small = Link::getImageLink('', $product->id .'-'. $img['id_image'], 'home');
                                        }
                                        else {
                                                $picture = file_exists(_PS_PROD_IMG_DIR_ . $product->id .'-'. $img['id_image'] .'.jpg')?$shopurl . 'img/p/'. $product->id .'-'. $img['id_image'] .'.jpg':'';
                                                $picture_small = file_exists(_PS_PROD_IMG_DIR_ . $product->id .'-'. $img['id_image'] .'-home.jpg')?$shopurl . 'img/p/'. $product->id .'-'. $img['id_image'] .'-home.jpg':'';
                                        }

                                        if (!strstr($picture, 'http://') AND !strstr($picture, 'https://')) $picture = 'http://' . $picture;
                                        if (!strstr($picture_small, 'http://') AND !strstr($picture_small, 'https://')) $picture_small = 'http://' . $picture_small;
                                        if ($picture == 'http://' OR strstr($picture, '/.jpg')) $picture = '';
                                        if ($picture_small == 'http://' OR strstr($picture_small, '/.jpg')) $picture_small = '';

                                        $price_vat = number_format($product->getPrice(true, NULL), 2, '.', '');
                                        $price = number_format($product->getPrice(false, NULL), 2, '.', '');
                                        $quantity = $product->getQuantity($product->id);

                                        if (Configuration::get('ADMINXML_CATEG') == 1) {
                                                $url = (Configuration::get('PS_REWRITING_SETTINGS') == 1?
                                                $shopurl . (strstr(_PS_VERSION_, '1.4.')?'':$cat_id .'-') . $cat_link .'/'. $product->id .'-'. $product->link_rewrite . ($product->ean13 ? '-'. $product->ean13: ''). '.html' :
                                                $shopurl . 'product.php?id_product='. $product->id);
                                        }
                                        else {
                                                $url = (Configuration::get('PS_REWRITING_SETTINGS') == 1?
                                                $shopurl . $product->id .'-'. $product->link_rewrite .($product->ean13 ? '-'. $product->ean13: '').'.html' :
        		                        $shopurl . 'product.php?id_product='. $product->id);
                                        }

                                        // jednotlive servery
                                        if ($server == 'hyperzbozi') {
                                                $category_hyperzbozi = str_replace(' - ', ' &gt; ', $category);
                                                $xml .= "<SHOPITEM>\n";
                                                $xml .= "<PRODUCT>". $name ."</PRODUCT>\n";
                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                $xml .= "<URL>". $url ."</URL>\n";
                                                $xml .= ($picture?"<IMGURL>". $picture ."</IMGURL>\n":"");
                                                $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                $xml .= ($product->ean13?"<EAN>". $product->ean13 ."</EAN>\n":"");
                                                $xml .= "<CATEGORYTEXT>". $category_hyperzbozi ."</CATEGORYTEXT>\n";
                                                $xml .= ($quantity > 0?"<AVAILABILITY>S</AVAILABILITY>\n":"<AVAILABILITY>N</AVAILABILITY>\n");

                                                // Attributes
                                                if (Configuration::get('ADMINXML_VARIANTS')) {
                                                        $groups = AdminXMLfunc::getAttribs($product->id, $id_lang);
                                                        if ($groups) {
                                                                foreach ($groups as $group) {
                                                                        if ($group['attributes']) {
                                                                                $xml .= "<PARAM>\n";
                                                                                $xml .= "<PARAM_NAME>". htmlspecialchars($group["name"]) ."</PARAM_NAME>\n";
                                                                                $xml .= "<VAL>";
        		              		                                $g = 0;
                                                                                foreach ($group['attributes'] as $group_attribute) {
                                                                                        if ($g>0)       $xml .= ";";
                                                                                        $xml .= htmlspecialchars($group_attribute["name"]);
                                                                                        $g++;
                                                                                }
                                                                                $xml .= "</VAL>\n";
                                                                                $xml .= "</PARAM>\n";
                                                                        }
                                                                }
                                                        }
                                                        unset($groups);
                                                }
                                                $xml .= "</SHOPITEM>\n";
                                        }
                                        if (
                                                $server == 'cenyzbozi' ||
                                                $server == 'srovname' ||
                                                $server == 'najdislevu' ||
                                                $server == 'zalevno' ||
                                                $server == 'naakup' ||
                                                $server == 'nejnakup'
                                        ) {
                                                $xml .= "<SHOPITEM>\n";
                                                $xml .= "<PRODUCT>". $name ."</PRODUCT>\n";
                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                $xml .= "<URL>". $url ."</URL>\n";
                                                $xml .= ($picture?"<IMGURL>". $picture ."</IMGURL>\n":"");
                                                $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                $xml .= "<CATEGORY>". $category ."</CATEGORY>\n";
                                                $xml .= ($product->ean13?"<EAN>". $product->ean13 ."</EAN>\n":"");
                                                $xml .= ($quantity > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":""));
                                                $xml .= "</SHOPITEM>\n";
                                        }
                                        if ($server == 'heureka') {
                                                if (Configuration::get('ADMINXML_VARIANTS')) $params = AdminXMLfunc::getParams($product->id, $id_lang);

                                                // Attributes
                                                if ($params) {
				                        foreach ($params AS $id_product_attribute => $product_attribute) {
                                                                $quantity_attribute = $product->getQuantity($product->id, $id_product_attribute);
                                                                $realprice = Product::getPriceStatic($product->id, true, $id_product_attribute, 6, NULL, false, true);
                                                                $attribute_image = Db::getInstance()->GetRow('
			                                                SELECT id_image
		                                                        '. (!strstr(_PS_VERSION_, '1.1.')?'FROM `'._DB_PREFIX_.'product_attribute_image`':'FROM `'._DB_PREFIX_.'product_attribute`'). '
			                                                WHERE `id_product_attribute` = '. $id_product_attribute .'
                                                                ');
						                $list = '';
						                foreach ($product_attribute['attributes'] as $attribute)
							                $list .= ' '.addslashes(htmlspecialchars($attribute[1]));

                                                                $xml .= "<SHOPITEM>\n";
                                                                $xml .= "<ITEM_ID>". $product->id . $id_product_attribute ."</ITEM_ID>\n";
                                                                $xml .= "<ITEMGROUP_ID>". $product->id ."</ITEMGROUP_ID>\n";
                                                                $xml .= "<PRODUCT>". $name . stripslashes($list) ."</PRODUCT>\n";
                                                                $xml .= "<PRODUCTNAME>". $name . stripslashes($list) ."</PRODUCTNAME>\n";
                                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                                $xml .= "<URL>". $url ."#". $id_product_attribute ."</URL>\n";

                                                                if ((floatval(_PS_VERSION_) >= 1.4) && !strstr(_PS_VERSION_, '1.4.0') && !strstr(_PS_VERSION_, '1.4.1') && !strstr(_PS_VERSION_, '1.4.2')) {
                                                                        $image = new Image($attribute_image['id_image']);
                                                                        if (file_exists(_PS_PROD_IMG_DIR_ . $image->getImgPath() .'.jpg')) {
                                                                                $image = Link::getImageLink('', $product->id .'-'. $attribute_image['id_image']);
                                                                                if (!strstr($image, 'http://') AND !strstr($image, 'https://')) $image = "http://" . $image;
                                                                                $xml .= "<IMGURL>". $image ."</IMGURL>\n";
                                                                        }
                                                                        else $xml .= ($picture?"<IMGURL>".$picture."</IMGURL>\n":"");
                                                                }
                                                                else {
                                                                        if (file_exists(_PS_PROD_IMG_DIR_ . $product->id . '-'. $attribute_image['id_image'] . '.jpg'))
                                                                                $xml .= "<IMGURL>". $shopurl . "img/p/" . $product->id . "-". $attribute_image['id_image'] .".jpg</IMGURL>\n";
                                                                        elseif (file_exists(_PS_COL_IMG_DIR_ . $id_product_attribute . '.jpg'))
                                                                                $xml .= "<IMGURL>". $shopurl . "img/co/" . $id_product_attribute .".jpg</IMGURL>\n";
                                                                        else $xml .= ($picture?"<IMGURL>".$picture."</IMGURL>\n":"");
                                                                }

                                                                // accessory
                                                                $accessories = $product->getAccessoriesLight($id_lang, $product->id);
                                                                if ($accessories) {
                                                                        foreach ($accessories as $accessory)
                                                                                $xml .= "<ACCESSORY>". $accessory['id_product'] ."</ACCESSORY>\n";
                                                                }
                                                                unset($accessories);

                                                                $xml .= ($product->condition == "new"?"<ITEM_TYPE>new</ITEM_TYPE>\n":"");
                                                                $xml .= ($product->condition == "used"?"<ITEM_TYPE>bazar</ITEM_TYPE>\n":"");
                                                                if ($product_attribute['price'] && $realprice) $xml .= "<PRICE_VAT>". number_format($realprice, 2, '.', '') . "</PRICE_VAT>\n";
                                                                else $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                                if ($product_attribute['ean13']) $xml .= "<EAN>". $product_attribute["ean13"] ."</EAN>\n";
                                                                else $xml .= ($product->ean13?"<EAN>".$product->ean13."</EAN>\n":"");
                                                                $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                                $xml .= "<CATEGORYTEXT>". $category_heureka ."</CATEGORYTEXT>\n";
                                                                $xml .= (Configuration::get('ADMINXML_HEUREKA_CPC')?"<HEUREKA_CPC>". Configuration::get('ADMINXML_HEUREKA_CPC') ."</HEUREKA_CPC>\n":"");
                                                                $xml .= ($quantity_attribute > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":""));
                                                                
                                                                // Delivery
                                                                if (Configuration::get('ADMINXML_HEUREKA_DELIVERY') AND Configuration::get('ADMINXML_HEUREKA_PRICE')) {
                                                                        $xml .= "<DELIVERY>\n";
                                                                        $xml .= "<DELIVERY_ID>". Configuration::get('ADMINXML_HEUREKA_DELIVERY') ."</DELIVERY_ID>\n";
                                                                        $xml .= "<DELIVERY_PRICE>". Configuration::get('ADMINXML_HEUREKA_PRICE') ."</DELIVERY_PRICE>\n";
                                                                        if (Configuration::get('ADMINXML_HEUREKA_PRICE_COD'))
                                                                                $xml .= "<DELIVERY_PRICE_COD>". Configuration::get('ADMINXML_HEUREKA_PRICE_COD') ."</DELIVERY_PRICE_COD>\n";
                                                                        $xml .= "</DELIVERY>\n";
                                                                }

                                                                // Free delivery
                                                                if (Configuration::get('ADMINXML_HEUREKA_FREEDELIVERY') AND Configuration::get('PS_SHIPPING_FREE_PRICE') > 0 AND Configuration::get('PS_SHIPPING_FREE_PRICE') <= $realprice) {
                                                                        foreach ($this->heureka_delivery as $value) {
                                                                                if (strstr(Configuration::get('ADMINXML_HEUREKA_FREEDELIVERY'), $value)) {
                                                                                        $xml .= "<DELIVERY>\n";
                                                                                        $xml .= "<DELIVERY_ID>". $value ."</DELIVERY_ID>\n";
                                                                                        $xml .= "<DELIVERY_PRICE>0</DELIVERY_PRICE>\n";
                                                                                        $xml .= "<DELIVERY_PRICE_COD>0</DELIVERY_PRICE_COD>\n";
                                                                                        $xml .= "</DELIVERY>\n";
                                                                                }
                                                                        }
                                                                }

                                                                // Attributes
                                                                foreach ($product_attribute['attributes'] as $attribute) {
                                                                        $xml .= "<PARAM>\n";
                                                                        $xml .= "<PARAM_NAME>". htmlspecialchars($attribute[0]) ."</PARAM_NAME>\n";
		              		                                $xml .= "<VAL>". htmlspecialchars($attribute[1]) ."</VAL>\n";
                                                                        $xml .= "</PARAM>\n";
                                                                }

                                                                // Features
                                                                if (Configuration::get('ADMINXML_FEATURES')) {
                                                                        $features = $product->getFrontFeatures($id_lang);
                                                                        if ($features) {
                                                                                foreach ($features as $feature) {
                                                                                        $xml .= "<PARAM>\n";
                        		              		                        $xml .= "<PARAM_NAME>". htmlspecialchars($feature["name"]) ."</PARAM_NAME>\n";
                        		              		                        $xml .= "<VAL>". htmlspecialchars($feature["value"]) ."</VAL>\n";
                        		              		                        $xml .= "</PARAM>\n";
                        		              		                }
                        		              		                unset($features);
                                                                        }
                                                                }

                                                                $xml .= "</SHOPITEM>\n";
                                                        }
                                                        unset($params);
                                                }

                                                // Normal product
                                                else {
                                                        $xml .= "<SHOPITEM>\n";
                                                        $xml .= "<ITEM_ID>". $product->id ."</ITEM_ID>\n";
                                                        $xml .= "<PRODUCT>". $name ."</PRODUCT>\n";
                                                        $xml .= "<PRODUCTNAME>". $name ."</PRODUCTNAME>\n";
                                                        $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                        $xml .= "<URL>". $url ."</URL>\n";
                                                        $xml .= ($picture?"<IMGURL>". $picture ."</IMGURL>\n":"");

                                                        // other images
                                                        $images = Db::getInstance()->ExecuteS('
                                                                SELECT i.`cover`, i.`id_image`
                		                                FROM `'._DB_PREFIX_.'image` i
                		                                WHERE i.`id_product` = '. $product->id .'
                		                                ORDER BY `position`
                                                        ');
                                                        if ($images) {
                                                                foreach ($images as $img) {
                                                                        if ($img['cover'] != 1) {
                                                                                if (floatval(_PS_VERSION_) >= 1.4)
                                                                                        $other_picture = Link::getImageLink('', $product->id .'-'. $img['id_image']);
                                                                                else
                                                                                        $other_picture = file_exists(_PS_PROD_IMG_DIR_ . $product->id .'-'. $img['id_image'] .'.jpg')?$shopurl . 'img/p/'. $product->id .'-'. $img['id_image'] .'.jpg':'';
                                                                                if (!strstr($other_picture, 'http://') AND !strstr($other_picture, 'https://')) $other_picture = 'http://' . $other_picture;
                                                                                if ($other_picture == 'http://') $other_picture = '';
                                                                                $xml .= ($other_picture?"<IMGURL_ALTERNATIVE>". $other_picture ."</IMGURL_ALTERNATIVE>\n":"");
                                                                        }
                                                                }
                                                                unset($images);
                                                        }

                                                        // accessory
                                                        $accessories = $product->getAccessoriesLight($id_lang, $product->id);
                                                        if ($accessories) {
                                                                foreach ($accessories as $accessory)
                                                                        $xml .= "<ACCESSORY>". $accessory['id_product'] ."</ACCESSORY>\n";
                                                        }
                                                        unset($accessories);

                                                        $xml .= ($product->condition == "new"?"<ITEM_TYPE>new</ITEM_TYPE>\n":"");
                                                        $xml .= ($product->condition == "used"?"<ITEM_TYPE>bazar</ITEM_TYPE>\n":"");
                                                        $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                        $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                        $xml .= ($product->ean13?"<EAN>". $product->ean13 ."</EAN>\n":"");
                                                        $xml .= "<CATEGORYTEXT>". $category_heureka ."</CATEGORYTEXT>\n";
                                                        $xml .= (Configuration::get('ADMINXML_HEUREKA_CPC')?"<HEUREKA_CPC>". Configuration::get('ADMINXML_HEUREKA_CPC') ."</HEUREKA_CPC>\n":"");
                                                        $xml .= ($quantity > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":""));

                                                        // Delivery
                                                        if (Configuration::get('ADMINXML_HEUREKA_DELIVERY') AND Configuration::get('ADMINXML_HEUREKA_PRICE')) {
                                                                $xml .= "<DELIVERY>\n";
                                                                $xml .= "<DELIVERY_ID>". Configuration::get('ADMINXML_HEUREKA_DELIVERY') ."</DELIVERY_ID>\n";
                                                                $xml .= "<DELIVERY_PRICE>". Configuration::get('ADMINXML_HEUREKA_PRICE') ."</DELIVERY_PRICE>\n";
                                                                if (Configuration::get('ADMINXML_HEUREKA_PRICE_COD'))
                                                                        $xml .= "<DELIVERY_PRICE_COD>". Configuration::get('ADMINXML_HEUREKA_PRICE_COD') ."</DELIVERY_PRICE_COD>\n";
                                                                $xml .= "</DELIVERY>\n";
                                                        }

                                                        // Free delivery
                                                        if (Configuration::get('ADMINXML_HEUREKA_FREEDELIVERY') AND Configuration::get('PS_SHIPPING_FREE_PRICE') > 0 AND Configuration::get('PS_SHIPPING_FREE_PRICE') <= $price_vat) {
                                                                foreach ($this->heureka_delivery as $value) {
                                                                        if (strstr(Configuration::get('ADMINXML_HEUREKA_FREEDELIVERY'), $value)) {
                                                                                $xml .= "<DELIVERY>\n";
                                                                                $xml .= "<DELIVERY_ID>". $value ."</DELIVERY_ID>\n";
                                                                                $xml .= "<DELIVERY_PRICE>0</DELIVERY_PRICE>\n";
                                                                                $xml .= "<DELIVERY_PRICE_COD>0</DELIVERY_PRICE_COD>\n";
                                                                                $xml .= "</DELIVERY>\n";
                                                                        }
                                                                }
                                                        }
                                                        
                                                        // Features
                                                        if (Configuration::get('ADMINXML_FEATURES')) {
                                                                $features = $product->getFrontFeatures($id_lang);
                                                                if ($features) {
                                                                        foreach ($features AS $feature) {
                                                                                $xml .= "<PARAM>\n";
                		              		                        $xml .= "<PARAM_NAME>". htmlspecialchars($feature["name"]) ."</PARAM_NAME>\n";
                		              		                        $xml .= "<VAL>". htmlspecialchars($feature["value"]) ."</VAL>\n";
                		              		                        $xml .= "</PARAM>\n";
                		              		                }
                		              		                unset($features);
                                                                }
                                                        }
                                                
                                                        $xml .= "</SHOPITEM>\n";
                                                }
                                        }
                                        if ($server == 'monitor') {
                                                $xml .= "<OFFER>\n";
                                                $xml .= "<NAME>". $name ."</NAME>\n";
                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                $xml .= "<URL>". $url ."</URL>\n";
                                                $xml .= ($picture?"<IMGURL>". $picture ."</IMGURL>\n":"");
                                                $xml .= "<CATEGORY>". $category ."</CATEGORY>\n";
                                                $xml .= "</OFFER>\n";
                                        }
                                        if ($server == 'zbozi') {
                                                if (Configuration::get('ADMINXML_VARIANTS')) $params = AdminXMLfunc::getParams($product->id, $id_lang);

                                                // Attributes
                                                if ($params) {
				                        foreach ($params AS $id_product_attribute => $product_attribute) {
                                                                $quantity_attribute = $product->getQuantity($product->id, $id_product_attribute);
                                                                $realprice = Product::getPriceStatic($product->id, true, $id_product_attribute, 6, NULL, false, true);
                                                                $attribute_image = Db::getInstance()->GetRow('
			                                                SELECT id_image
		                                                        '. (!strstr(_PS_VERSION_, '1.1.')?'FROM `'._DB_PREFIX_.'product_attribute_image`':'FROM `'._DB_PREFIX_.'product_attribute`'). '
			                                                WHERE `id_product_attribute` = '. $id_product_attribute .'
                                                                ');
						                $list = '';
						                foreach ($product_attribute['attributes'] as $attribute)
							                $list .= ' '.addslashes(htmlspecialchars($attribute[1]));

                                                                $xml .= "<SHOPITEM>\n";
                                                                $xml .= "<ITEM_ID>". $product->id . $id_product_attribute ."</ITEM_ID>\n";
                                                                $xml .= "<ITEMGROUP_ID>". $product->id ."</ITEMGROUP_ID>\n";
                                                                $xml .= "<PRODUCTNAME>". $name . stripslashes($list) ."</PRODUCTNAME>\n";
                                                                $xml .= "<CATEGORYTEXT>". $category_zbozi ."</CATEGORYTEXT>\n";
                                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                                $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                                $xml .= "<URL>". $url ."</URL>\n";
                                                                if ($product_attribute['price'] && $realprice) $xml .= "<PRICE_VAT>". number_format($realprice, 2, '.', '') . "</PRICE_VAT>\n";
                                                                else $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";

                                                                foreach ($product_attribute['attributes'] as $attribute) {
                                                                        $xml .= "<PARAM>\n";
                                                                        $xml .= "<PARAM_NAME>". htmlspecialchars($attribute[0]) ."</PARAM_NAME>\n";
		              		                                $xml .= "<VAL>". htmlspecialchars($attribute[1]) ."</VAL>\n";
                                                                        $xml .= "</PARAM>\n";
                                                                }

                                                                // Features
                                                                if (Configuration::get('ADMINXML_FEATURES')) {
                                                                        $features = $product->getFrontFeatures($id_lang);
                                                                        if ($features) {
                                                                                foreach ($features AS $feature) {
                                                                                        $xml .= "<PARAM>\n";
                        		              		                        $xml .= "<PARAM_NAME>". htmlspecialchars($feature["name"]) ."</PARAM_NAME>\n";
                        		              		                        $xml .= "<VAL>". htmlspecialchars($feature["value"]) ."</VAL>\n";
                        		              		                        $xml .= "</PARAM>\n";
                        		              		                }
                        		              		                unset($features);
                                                                        }
                                                                }

                                                                if ((floatval(_PS_VERSION_) >= 1.4) && !strstr(_PS_VERSION_, '1.4.0') && !strstr(_PS_VERSION_, '1.4.1') && !strstr(_PS_VERSION_, '1.4.2')) {
                                                                        $image = new Image($attribute_image['id_image']);
                                                                        if (file_exists(_PS_PROD_IMG_DIR_ . $image->getImgPath() .'.jpg')) {
                                                                                $image = Link::getImageLink('', $product->id .'-'. $attribute_image['id_image']);
                                                                                if (!strstr($image, 'http://') AND !strstr($image, 'https://')) $image = "http://" . $image;
                                                                                $xml .= "<IMGURL>". $image ."</IMGURL>\n";
                                                                        }
                                                                        else $xml .= ($picture?"<IMGURL>".$picture."</IMGURL>\n":"");
                                                                }
                                                                else {
                                                                        if (file_exists(_PS_PROD_IMG_DIR_ . $product->id . '-'. $attribute_image['id_image'] . '.jpg'))
                                                                                $xml .= "<IMGURL>". $shopurl . "img/p/" . $product->id . "-". $attribute_image['id_image'] .".jpg</IMGURL>\n";
                                                                        elseif (file_exists(_PS_COL_IMG_DIR_ . $id_product_attribute . '.jpg'))
                                                                                $xml .= "<IMGURL>". $shopurl . "img/co/" . $id_product_attribute .".jpg</IMGURL>\n";
                                                                        else $xml .= ($picture?"<IMGURL>".$picture."</IMGURL>\n":"");
                                                                }

                                                                if ($product_attribute['reference']) $xml .= "<PRODUCTNO>". htmlspecialchars($product_attribute["reference"]) ."</PRODUCTNO>\n";
                                                                if ($product_attribute['ean13']) $xml .= "<EAN>". $product_attribute["ean13"] ."</EAN>\n";
                                                                $xml .= ($quantity_attribute > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>-1</DELIVERY_DATE>\n"));
                                                                $xml .= ($product->condition == "new"?"<ITEM_TYPE>new</ITEM_TYPE>\n":"");
                                                                $xml .= (Configuration::get('ADMINXML_MAX_CPC')?"<MAX_CPC>". Configuration::get('ADMINXML_MAX_CPC') ."</MAX_CPC>\n":"");
                                                                $xml .= (Configuration::get('ADMINXML_MAX_CPC_SEARCH')?"<MAX_CPC_SEARCH>". Configuration::get('ADMINXML_MAX_CPC_SEARCH') ."</MAX_CPC_SEARCH>\n":"");

                                                                // SHOP DEPOTS
                                                                if ($depots = explode(';', Configuration::get('ADMINXML_DEPOTS')))
                                                                        foreach ($depots as $depot)
                                                                                if ($depot) $xml .= "<SHOP_DEPOTS>". $depot ."</SHOP_DEPOTS>\n";

                                                                // Extra message
                                                                if (Configuration::get('ADMINXML_EXTRA_MESSAGE'))
                                                                        $xml .= "<EXTRA_MESSAGE>". Configuration::get('ADMINXML_EXTRA_MESSAGE') ."</EXTRA_MESSAGE>\n";

                                                                // Free delivery
                                                                elseif (Configuration::get('PS_SHIPPING_FREE_PRICE') > 0 AND Configuration::get('PS_SHIPPING_FREE_PRICE') <= $price_vat)
                                                                        $xml .= "<EXTRA_MESSAGE>free_delivery</EXTRA_MESSAGE>\n";

                                                                $xml .= "</SHOPITEM>\n";
					                }
                                                        unset($params);
                                                }

                                                // Normal product
                                                else {
                                                        $xml .= "<SHOPITEM>\n";
                                                        $xml .= "<ITEM_ID>". $product->id ."</ITEM_ID>\n";
                                                        $xml .= "<PRODUCTNAME>". $name ."</PRODUCTNAME>\n";
                                                        $xml .= "<CATEGORYTEXT>". $category_zbozi ."</CATEGORYTEXT>\n";
                                                        $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                        $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                        $xml .= "<URL>". $url ."</URL>\n";
                                                        $xml .= ($picture?"<IMGURL>".$picture."</IMGURL>\n":"");
                                                        $xml .= "<PRICE_VAT>".$price_vat."</PRICE_VAT>\n";
                                                        $xml .= ($product->reference?"<PRODUCTNO>". htmlspecialchars($product->reference) ."</PRODUCTNO>\n":"");
                                                        $xml .= ($product->ean13?"<EAN>".$product->ean13."</EAN>\n":"");
                                                        $xml .= ($product->condition == "new"?"<ITEM_TYPE>new</ITEM_TYPE>\n":"");
                                                        $xml .= (Configuration::get('ADMINXML_MAX_CPC')?"<MAX_CPC>". Configuration::get('ADMINXML_MAX_CPC') ."</MAX_CPC>\n":"");
                                                        $xml .= (Configuration::get('ADMINXML_MAX_CPC_SEARCH')?"<MAX_CPC_SEARCH>". Configuration::get('ADMINXML_MAX_CPC_SEARCH') ."</MAX_CPC_SEARCH>\n":"");
                                                        $xml .= ($quantity > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>-1</DELIVERY_DATE>\n"));

                                                        // SHOP DEPOTS
                                                        if ($depots = explode(';', Configuration::get('ADMINXML_DEPOTS')))
                                                                foreach ($depots as $depot)
                                                                        if ($depot) $xml .= "<SHOP_DEPOTS>". $depot ."</SHOP_DEPOTS>\n";

                                                        // Extra message
                                                        if (Configuration::get('ADMINXML_EXTRA_MESSAGE'))
                                                                $xml .= "<EXTRA_MESSAGE>". Configuration::get('ADMINXML_EXTRA_MESSAGE') ."</EXTRA_MESSAGE>\n";

                                                        // Free delivery
                                                        elseif (Configuration::get('PS_SHIPPING_FREE_PRICE') > 0 AND Configuration::get('PS_SHIPPING_FREE_PRICE') <= $price_vat)
                                                                $xml .= "<EXTRA_MESSAGE>free_delivery</EXTRA_MESSAGE>\n";

                                                        // Features
                                                        if (Configuration::get('ADMINXML_FEATURES')) {
                                                                $features = $product->getFrontFeatures($id_lang);
                                                                if ($features) {
                                                                        foreach ($features AS $feature) {
                                                                                $xml .= "<PARAM>\n";
                		              		                        $xml .= "<PARAM_NAME>". htmlspecialchars($feature["name"]) ."</PARAM_NAME>\n";
                		              		                        $xml .= "<VAL>". htmlspecialchars($feature["value"]) ."</VAL>\n";
                		              		                        $xml .= "</PARAM>\n";
                		              		                }
                		              		                unset($features);
                                                                }
                                                        }

                                                        $xml .= "</SHOPITEM>\n";
                                                }
                                        }
                                        
                                        if ($server == 'hledejceny') {
                                                $xml .= "<SHOPITEM>\n";
                                                $xml .= "<ID>". $product->id ."</ID>\n";
                                                $xml .= "<PRODUCT>". $name ."</PRODUCT>\n";
                                                $xml .= "<DESCRIPTION>". $description ."</DESCRIPTION>\n";
                                                $xml .= "<URL>". $url ."</URL>\n";
                                                $xml .= ($picture?"<IMGURL>". $picture ."</IMGURL>\n":"");
                                                $xml .= "<PRICE_VAT>". $price_vat ."</PRICE_VAT>\n";
                                                $xml .= "<CATEGORYTEXT>". $category ."</CATEGORYTEXT>\n";
                                                $xml .= ($manufacturer?"<MANUFACTURER>". $manufacturer ."</MANUFACTURER>\n":"");
                                                $xml .= ($product->ean13?"<EAN>". $product->ean13 ."</EAN>\n":"");
                                                $xml .= ($quantity > 0?($available_now?"<DELIVERY_DATE>". $available_now. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>0</DELIVERY_DATE>\n"):($available_later?"<DELIVERY_DATE>". $available_later. "</DELIVERY_DATE>\n":"<DELIVERY_DATE>-1</DELIVERY_DATE>\n"));
                                                $xml .= "</SHOPITEM>\n";
                                        }
                                }

                                // XML footers
                                $errors = false;
                                if ($server == 'monitor') $xml .= "</OFFERS>\n";
                                else $xml .= "</SHOP>\n";

                                // file save
                                if ($xml != '') {
                                        $file = fopen($path.'/'. $server . '.xml','w');
	                                fwrite($file, $xml);
	                                fclose($file);
	                                unset($xml);
	                        }
                                else $errors = true;
                        }
                        if ($errors)
                                echo '<div class="conf warning"><img src="../img/admin/warning.gif" /> Nastala chyba!</div>';
                        else
                                echo '<div class="conf confirm"><img src="../img/admin/ok.gif" /> Zvolené exporty vygenerovány úspěšně</div>';
                }

                if (!isset($_GET['cron']) && !$_GET['cron']) {
                        echo '<p><a class="button" href="'.$_SERVER['REQUEST_URI'] .'&submitXML=1">Vygenerovat XML exporty všech zvolených serverů</a></p><br />
                        
                        <form action="'. $_SERVER['REQUEST_URI'] .'" method="post">
                        <fieldset>
                        <legend>Nastavení</legend>
                        
                        <p>
                                <input id="adminxml_variants" name="adminxml_variants" type="checkbox" value="1"'. (Tools::getValue('adminxml_variants', Configuration::get('ADMINXML_VARIANTS')) == 1 ? 'checked="checked" ' : '') .' /> <label for="adminxml_variants" style="cursor:pointer;float:none;font-weight:normal">generovat kombinace</label>
                                <input id="adminxml_features" style="margin-left:5px" name="adminxml_features" type="checkbox" value="1"'. (Tools::getValue('adminxml_features', Configuration::get('ADMINXML_FEATURES')) == 1 ? 'checked="checked" ' : '') .' /> <label for="adminxml_features" style="cursor:pointer;float:none;font-weight:normal">generovat vlastnosti</label>
                                <input id="adminxml_categ" style="margin-left:5px" name="adminxml_categ" type="checkbox" value="1"'. (Tools::getValue('adminxml_categ', Configuration::get('ADMINXML_CATEG')) == 1 ? 'checked="checked" ' : '') .' /> <label for="adminxml_categ" style="cursor:pointer;float:none;font-weight:normal">zobrazovat název kategorie v URL</label>
                                <input id="adminxml_stock" style="margin-left:5px" name="adminxml_stock" type="checkbox" value="1"'. (Tools::getValue('adminxml_stock', Configuration::get('ADMINXML_STOCK')) == 1 ? 'checked="checked" ' : '') .' /> <label for="adminxml_stock" style="cursor:pointer;float:none;font-weight:normal">pouze produkty skladem</label>
                                <input class="button" name="settings" type="submit" value="Uložit nastavení a vybrané servery" />
                        </p>
                        <p>
                                <input id="adminxml_description" name="adminxml_description" type="checkbox" value="1"'. (Tools::getValue('adminxml_description', Configuration::get('ADMINXML_DESCRIPTION')) == 1 ? 'checked="checked" ' : '') .' /> <label for="adminxml_description" style="cursor:pointer;float:none;font-weight:normal">generovat podrobný popis produktu</label>
                                <span style="margin-left:25px">Libovolný text za název každého produktu:</span> <input name="adminxml_extratext" type="text" size="70" value="'. Configuration::get('ADMINXML_EXTRATEXT') .'" />
                        </p>
                        
	                <table class="table" style="width:100%">
                        <tr><th></th><th>Server</th><th>Stav</th><th>Poslední změna</th></tr>';
                       
                        $i = 0;
                        if ($tmp_server) $xml_server = $tmp_server;
                        foreach ($xml_server AS $value) {
                                $i++;
                                $server = substr(strtolower($value), 0, -3);
                                echo '<tr'. ($i % 2 == 0?' class="alt_row"':'') .'>';
                                        echo '<td>
                                                <input name="adminxml_'. $server .'" type="checkbox" value="1"'. (Tools::getValue('adminxml_'. strtoupper($server), Configuration::get('ADMINXML_'. strtoupper($server))) == 1 ? 'checked="checked" ' : '') .' />
                                        </td>
                                        <td>'.$value.'</td>
                                        <td>';
                                                if (file_exists($path.'/'. $server . '.xml')) {
                        	                       echo '<a style="text-decoration:underline" href="'. $shopurl .'xml/'. $server .'.xml" target="_blank">'. $shopurl .'xml/'. $server .'.xml</a>';
                        	                }
                                                else {
                                                        echo 'Soubor nutno vygenerovat!';
                                                }
                                        echo '</td>';
                                        echo '<td>';
                                                if (file_exists($path.'/'. $server . '.xml')) {
                                                        $fp = fopen($path.'/'. $server . '.xml', 'r');
                                                        $fstat = fstat($fp);
                                                        fclose($fp);
                                                        echo '<p>' .date('H:i:s d-m-Y', $fstat['mtime']). '</p>';
                                                }
                                        echo '</td>';
                                echo '</tr>';
                        }
	                echo '</table>
	                </fieldset>
                        </form><br />';
                        
                        // Nastaveni Heureka.cz
                        echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">
                        <fieldset>
                                <legend>Nastavení pro Heureka.cz</legend>
                                <p>
                                        <span>Dopravce:</span>
                                        <select name="delivery">
                                        <option value=""'. (!Configuration::get('ADMINXML_HEUREKA_DELIVERY') ? ' selected="selected"' : '') .' />------</option>';
                                        foreach ($this->heureka_delivery as $key => $value)
                                                echo '<option value="'. $value .'"'. (Configuration::get('ADMINXML_HEUREKA_DELIVERY') == $value ? ' selected="selected"' : '') .' />'. $key .'</option>';
                                        echo '</select>
                                        <span style="margin-left:10px">Cena dopravy:</span> <input name="delivery_price" type="text" size="3" value="'. Configuration::get('ADMINXML_HEUREKA_PRICE') .'" /> '. $kc->sign .'
                                        <span style="margin-left:10px">Cena dopravy s dobírkou:</span> <input name="delivery_price_cod" type="text" size="3" value="'. Configuration::get('ADMINXML_HEUREKA_PRICE_COD') .'" /> '. $kc->sign .'
                                        <span style="margin-left:10px">Heureka CPC:</span> <input name="adminxml_heureka_cpc" type="text" size="3" value="'. Configuration::get('ADMINXML_HEUREKA_CPC') .'" /> '. $kc->sign .'
                                        <span style="margin-left:10px"><input class="button" name="submit_heureka" type="submit" value="Nastavit" /></span>
                                </p>

                                <p><b>Dopravci zdarma:</b></p><p>';
                                foreach ($this->heureka_delivery as $key => $value)
                                        echo '<span style="width:230px;display:block;float:left"><input id="freedelivery_'. $value .'" name="freedelivery_'. $value .'" type="checkbox" value="'. $value .'"'. (strstr(Configuration::get('ADMINXML_HEUREKA_FREEDELIVERY'), $value) ? ' checked="checked"' : '') .' /> <label for="freedelivery_'. $value .'" style="cursor:pointer;float:none;font-weight:normal">'. $key .'</label></span>';

                                echo '</p>

                                <p class="clear"><br /><b style="cursor:pointer; text-decoration:underline" onclick="$(\'#cats\').toggle()">Párování Heureka kategorií</b></p>
                                <div id="cats" style="display:none">
                                        <table cellspacing="0" cellpadding="0" class="table space" style="width:100%">
        					<tr>
                                                        <th>Název kategorie</th>
        						<th>Název Heureka kategorie</th>
        					</tr>
                                                '. AdminXMLfunc::getCategories(1, $id_lang, 1) .'
                                        </table>
                                        <p>Správné názvy lze zjistit ve stromu aktivních kategorií viz. <a style="text-decoration:underline" href="http://www.heureka.cz/direct/xml-export/shops/heureka-sekce.xml">http://www.heureka.cz/direct/xml-export/shops/heureka-sekce.xml</a></p>
                                </div>

                        </fieldset>
                        </form><br />';

                        // Nastaveni Zbozi.cz
                        echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">
                        <fieldset>
                                <legend>Nastavení pro Zboží.cz</legend>
                                <p>
                                        <span>MAX CPC:</span> <input name="adminxml_max_cpc" type="text" size="3" value="'. Configuration::get('ADMINXML_MAX_CPC') .'" /> '. $kc->sign .'
                                        <span style="margin-left:70px">MAX CPC SEARCH:</span> <input name="adminxml_max_cpc_search" size="3" type="text" value="'. Configuration::get('ADMINXML_MAX_CPC_SEARCH') .'" /> '. $kc->sign .'
                                        <span style="margin-left:70px">EXTRA MESSAGE:</span>
                                        <select name="extra_message">
                                                <option value=""'. (!Configuration::get('ADMINXML_EXTRA_MESSAGE') ? ' selected="selected"' : '') .' />------</option>';
                                                foreach ($this->extra_message as $key => $value)
                                                        echo '<option value="'. $value .'"'. (Configuration::get('ADMINXML_EXTRA_MESSAGE') == $value ? ' selected="selected"' : '') .' />'. $key .'</option>';
                                        echo '</select>
                                        <span style="margin-left:70px"><input class="button" name="submit_zbozi" type="submit" value="Nastavit" /></span>
                                </p>
                                <p>
                                        SHOP DEPOTS: <input name="adminxml_depots" type="text" size="50" value="'. Configuration::get('ADMINXML_DEPOTS') .'" /> <span style="color:#7f7f7f;font-size:11px">(identifikátory výdejních míst oddělené středníkem)</span>
                                </p>

                                <p class="clear"><br /><b style="cursor:pointer; text-decoration:underline" onclick="$(\'#cats2\').toggle()">Párování Zboží.cz kategorií</b></p>
                                <div id="cats2" style="display:none">
                                        <table cellspacing="0" cellpadding="0" class="table space" style="width:100%">
        					<tr>
                                                        <th>Název kategorie</th>
        						<th>Název Zboží.cz kategorie</th>
        					</tr>
                                                '. AdminXMLfunc::getZboziCategories(1, $id_lang, 1) .'
                                        </table>
                                        <p>Správné názvy lze zjistit v přehledu kategorií viz. <a style="text-decoration:underline" href="http://www.napoveda.seznam.cz/soubory/Zbozi.cz/category_ID.xls">http://www.napoveda.seznam.cz/soubory/Zbozi.cz/category_ID.xls</a></p>
                                </div>
                        </fieldset>
                        </form><br />';
                        
                        // Nezobrazovane produkty
                        echo '<form action="'. $_SERVER['REQUEST_URI'] .'" method="post">
                        <fieldset>
                                <legend>Nezobrazované produkty v XML</legend>';
                        
                        $noproducts = explode(',', Configuration::get('ADMINXML_NOPRODUCTS'));
                        $products = Db::getInstance()->ExecuteS('
		                SELECT p.id_product, pl.name
		                FROM '._DB_PREFIX_.'product p
		                LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '. intval($cookie->id_lang) .')
		                WHERE p.active = 1
                                '. (Configuration::get('ADMINXML_STOCK')?' AND p.quantity > 0':''). '
                                GROUP BY p.id_product
		                ORDER BY pl.name
		        ');
                        echo '<select name="noproducts[]" style="width:100%;height:200px" multiple="multiple">';
		        foreach ($products as $product) {
                                echo '<option value="'. $product['id_product'] .'"'. (in_array($product['id_product'], $noproducts)?' selected="selected"':'') .'>'. $product['name'] .'</option>';
                        }
                        echo '</select>
                        <p style="color:#7f7f7f;font-size:11px">Výběr více produktů pomocí klávesy SHIFT / CTRL a levého tlačítka myši</p>
                        <p><input class="button" name="submit_noproducts" type="submit" value="Nastavit" /></p>
                        </fieldset>
                        </form>
                        <p class="clear"></p>';
               }
        }
}

if (isset($_GET['cron']) && $_GET['cron']) {
        $xml = new AdminXML();
        $xml->DisplayForm();
}

?>
