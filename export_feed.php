<?php

include('./config/config.inc.php'); // cesta ke config souboru

$shopUrl = 'http://'.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__;

$defaultLang = Configuration::get('PS_LANG_DEFAULT');


global $cookie;
$cookie = new Cookie('ps');

$oddel = "\n";

$_GET['s'] = 'heureka';

// zbozi.cz

if ($_GET['s']=='zbozi') {

    $res = mysql_query('SELECT pl.id_product, pl.name, pl.description, pl.available_now, pl.available_later, pl.available_later, pl.link_rewrite, p.id_manufacturer, p.ean13, p.id_category_default, p.price, p.id_tax, p.quantity FROM ps_product_lang pl, ps_product p WHERE pl.id_product > 0 AND pl.id_lang = '.$defaultLang.' AND p.active = 1 AND pl.id_product = p.id_product;');
    
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<SHOP>'.$oddel;
    while ($row = mysql_fetch_array($res)) {
    
    ob_start();    
    echo '<SHOPITEM>'.$oddel;                     
        echo '<PRODUCT><![CDATA['.$row['name'].']]></PRODUCT>'.$oddel;
        echo '<DESCRIPTION><![CDATA['.mb_substr(strip_tags($row['description']), 0, 200, "UTF-8").']]></DESCRIPTION>'.$oddel;
        echo '<URL><![CDATA['.$shopUrl.'/'.$row['id_product'].'-'.$row['link_rewrite'].'.html]]></URL>'.$oddel;                                              
        $img=Product::getCover($row['id_product']);
        echo '<IMGURL><![CDATA['.$shopUrl.'/img/p/'.$row['id_product'].'-'.$img['id_image'].'-large.jpg]]></IMGURL>'.$oddel;                                              
        $man = mysql_query('SELECT name FROM ps_manufacturer WHERE id_manufacturer = '.$row['id_manufacturer'].' LIMIT 1;');
        if ($man && mysql_num_rows($man)>0) {
          $my_man = mysql_fetch_row($man);        
          echo '<MANUFACTURER><![CDATA['.$my_man[0].']]></MANUFACTURER>'.$oddel;
        } else {                                  
          echo '<MANUFACTURER></MANUFACTURER>'.$oddel;
        }                                                                                       

        if ($row['quantity'] > 0) $delivery = 0;
        else {
          //$delivery = trim(mb_substr(trim($row['available_later']), 0, -4, "UTF-8"));
          //$delivery_arr = explode(" ", trim($row['available_later']));
          //$delivery = $delivery_arr[0];
          $delivery = '-1';               
        }
                 
        echo '<DELIVERY_DATE>'.$delivery.'</DELIVERY_DATE>'.$oddel;        
        $cattext = "";                            
        $parents = getParentsCategories($defaultLang, $row['id_category_default']);
        for ($i=count($parents); $i--; $i>0) {    
          $cattext .= $parents[$i]['name'].' | '; 
        }                                         
        echo '<CATEGORYTEXT><![CDATA['.mb_substr($cattext, 0, -3, "UTF-8").']]></CATEGORYTEXT>'.$oddel;
        echo '<EAN>'.$row['ean13'].'</EAN>'.$oddel;      
        echo '<PRICE>'.Product::getPriceStatic($row['id_product'], false).'</PRICE>'.$oddel;                
        echo '<VAT>'.($row['id_tax']==4 ? 21 : 15).'</VAT>'.$oddel;
        
        $vat = ($row['id_tax']==4 ? 1.21 : 1.15);
        $attributtes = getAttributes($defaultLang, true, $row['id_product']);                                                  
      	if (sizeof($attributtes)>1) {      			  			
            foreach($attributtes as $attribute) {
            $jednotka = $attribute['group_name']=='objem' ? ' ml' : '';            
            echo '<VARIANT>'.$oddel;              
              //echo '<PRODUCTNAME><![CDATA['.$attribute['group_name'].']]></PRODUCTNAME>'.$oddel;        
              echo '<PRODUCTNAMEEXT><![CDATA['.$attribute['name'].$jednotka.']]></PRODUCTNAMEEXT>'.$oddel;
              echo '<PRICE>'.(round($row['price']+($attribute['price']/$vat), 2)).'</PRICE>'.$oddel;
            echo '</VARIANT>'.$oddel;             
            }       	                             
      	}
        
    echo '</SHOPITEM>'.$oddel;                                                                        
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
    
    }                                             
    echo '</SHOP>'.$oddel;
}
                 


if ($_GET['s']=='jyxo' || $_GET['s']=='heureka' || $_GET['s']=='hyperzbozi') {
  

    $res = mysql_query('SELECT pl.id_product, pl.name, pl.description, pl.available_now, pl.available_later, pl.available_later, pl.link_rewrite, p.id_manufacturer, p.id_category_default, p.price, p.ean13, p.id_tax, p.quantity, p.customsize FROM ps_product_lang pl, ps_product p WHERE pl.id_product > 0 AND pl.id_lang = '.$defaultLang.' AND p.active = 1 AND pl.id_product = p.id_product;');
        
    
    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<SHOP>'.$oddel;
    while ($row = mysql_fetch_array($res)) 
    {
    
      $nasobit = ($row['customsize']==1 ? 100 : 1);
    
    ob_start();    
    
    if ($_GET['s']=='jyxo') $oddelovac = ''; //nema 
    if ($_GET['s']=='heureka') $oddelovac = ' / ';
    if ($_GET['s']=='hyperzbozi') $oddelovac = ' > ';    
    
    //$attributtes = getAttributes($defaultLang, true, $row['id_product']);
  	if (sizeof($attributtes)>0) {
  	    $counter = 0;
        foreach($attributtes as $attribute) {
        $counter++;
        
          $jednotka = $attribute['group_name']=='objem' ? ' ml' : '';  
          
          echo '<SHOPITEM>'.$oddel;                     
              echo '<PRODUCT><![CDATA['.$row['name'].($attribute['name']!='' ? (' '.$attribute['name']) : '').']]></PRODUCT>'.$oddel;
              echo '<DESCRIPTION><![CDATA['.mb_substr(strip_tags($row['description']), 0, 200, "UTF-8").']]></DESCRIPTION>'.$oddel;
              echo '<URL><![CDATA['.$shopUrl.'/'.$row['id_product'].'-'.$row['link_rewrite'].'.html'.($counter>1 ? '#'.$attribute['name'] : '').']]></URL>'.$oddel;                                              
              $img=Product::getCover($row['id_product']);
              echo '<IMGURL><![CDATA['.$shopUrl.'/img/p/'.$row['id_product'].'-'.$img['id_image'].'-large.jpg]]></IMGURL>'.$oddel;                                              
              $man = mysql_query('SELECT name FROM ps_manufacturer WHERE id_manufacturer = '.$row['id_manufacturer'].' LIMIT 1;');
              if ($man && mysql_num_rows($man)>0) {
                $my_man = mysql_fetch_row($man);        
                echo '<MANUFACTURER><![CDATA['.$my_man[0].']]></MANUFACTURER>'.$oddel;
              } else {                                  
                echo '<MANUFACTURER></MANUFACTURER>'.$oddel;
              }
                                                                                                                                 
              if ($attribute['quantity'] > 0) $delivery = 0;
              else {
                //$delivery = trim(mb_substr(trim($row['available_later']), 0, -4, "UTF-8"));
                //$delivery_arr = explode(" ", trim($row['available_later']));
                //$delivery = $delivery_arr[0];
                $delivery = 30;               
              }      
              
              $hyper_delivery = 'S';
              
              if ($_GET['s']=='hyperzbozi') {
                            
                if ($delivery==0) $hyper_delivery = 'S'; //skladem
                else $hyper_delivery = 'N'; //docasne nedostupne                                
                echo '<AVAILABILITY>'.$hyper_delivery.'</AVAILABILITY>'.$oddel;
                                            
              } else {
                echo '<DELIVERY_DATE>'.$delivery.'</DELIVERY_DATE>'.$oddel;
                
              }
              
              if ($_GET['s']!='jyxo') {    
                $cattext = "";                            
                $parents = getParentsCategories($defaultLang, $row['id_category_default']);
                for ($i=count($parents); $i--; $i>0) {    
                  $cattext .= $parents[$i]['name'].$oddelovac; 
                }                                         
                echo '<CATEGORYTEXT><![CDATA['.mb_substr($cattext, 0, -3, "UTF-8").']]></CATEGORYTEXT>'.$oddel;
              }                      
              $vat = ($row['id_tax']==4 ? 1.21 : 1.15);
              //echo '<PRICE>'.round($row['price']+($attribute['price']/$vat), 2).'</PRICE>'.$oddel;
              echo '<PRICE>'.((Product::getPriceStatic($row['id_product'], false)+($attribute['price']/$vat))*$nasobit).'</PRICE>'.$oddel;
              echo '<VAT>'.($row['id_tax']==4 ? 21 : 15).'</VAT>'.$oddel;              
              echo '<EAN>'.$row['ean13'].'</EAN>'.$oddel;
              
          echo '</SHOPITEM>'.$oddel;                                                                        
          $content = ob_get_contents();
          ob_end_clean();
          echo $content;                         
        }
  	} else {        
        echo '<SHOPITEM>'.$oddel;                     
            echo '<PRODUCT><![CDATA['.$row['name'].']]></PRODUCT>'.$oddel;
            echo '<DESCRIPTION><![CDATA['.mb_substr(strip_tags($row['description']), 0, 200, "UTF-8").']]></DESCRIPTION>'.$oddel;
            echo '<URL><![CDATA['.$shopUrl.'/'.$row['id_product'].'-'.$row['link_rewrite'].'.html]]></URL>'.$oddel;                                              
            $img=Product::getCover($row['id_product']);
            echo '<IMGURL><![CDATA['.$shopUrl.'/img/p/'.$row['id_product'].'-'.$img['id_image'].'-large.jpg]]></IMGURL>'.$oddel;                                              
            $man = mysql_query('SELECT name FROM ps_manufacturer WHERE id_manufacturer = '.$row['id_manufacturer'].' LIMIT 1;');
            if ($man && mysql_num_rows($man)>0) {
              $my_man = mysql_fetch_row($man);        
              echo '<MANUFACTURER><![CDATA['.$my_man[0].']]></MANUFACTURER>'.$oddel;
            } else {                                  
              echo '<MANUFACTURER></MANUFACTURER>'.$oddel;
            }                     
            if ($row['quantity'] > 0) $delivery = 0;
            else {
              //$delivery = trim(mb_substr(trim($row['available_later']), 0, -4, "UTF-8"));
              //$delivery_arr = explode(" ", trim($row['available_later']));
              //$delivery = $delivery_arr[0];
              $delivery = 30;               
            }                
            
            
            $hyper_delivery = 'S';
            
            if ($_GET['s']=='hyperzbozi') {
                          
              if ($delivery==0) $hyper_delivery = 'S'; //skladem
              else $hyper_delivery = 'N'; //docasne nedostupne                                
              echo '<AVAILABILITY>'.$hyper_delivery.'</AVAILABILITY>'.$oddel;
                                          
            } else {
              echo '<DELIVERY_DATE>'.$delivery.'</DELIVERY_DATE>'.$oddel;
              
            }            
            
            if ($_GET['s']!='jyxo') {
              $cattext = "";                            
              $parents = getParentsCategories($defaultLang, $row['id_category_default']);
              for ($i=count($parents); $i--; $i>0) {    
                $cattext .= $parents[$i]['name'].$oddelovac; 
              }                                                                     
              echo '<CATEGORYTEXT><![CDATA['.mb_substr($cattext, 0, -3, "UTF-8").']]></CATEGORYTEXT>'.$oddel;
            }            
          
            //echo '<PRICE>'.$row['price'].'</PRICE>'.$oddel;
            echo '<PRICE>'.(Product::getPriceStatic($row['id_product'], false)*$nasobit).'</PRICE>'.$oddel;
            echo '<VAT>'.($row['id_tax']==4 ? 21 : 15).'</VAT>'.$oddel;              
            echo '<EAN>'.$row['ean13'].'</EAN>'.$oddel;
            
        echo '</SHOPITEM>'.$oddel;                                                                        
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;                
    }
    }
    echo '</SHOP>'.$oddel;
}
                                                                                                                      

function getParentsCategories($idLang = 3, $idCurrent) {	                                             
	$categories = null;		                        
	while (true)                                 
	{
		$query = '                                  
			SELECT c.id_parent, cl.name                
			FROM `'._DB_PREFIX_.'category` c           
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND `id_lang` = '.intval($idLang).')
			WHERE c.`id_category` = '.$idCurrent.' AND c.`id_parent` != 0
		';                                          
		$result = Db::s($query);                    
	                                             
		$categories[] = $result[0];                 
		if(!$result OR $result[0]['id_parent'] == 1)
			return $categories;
		$idCurrent = $result[0]['id_parent'];       
	}                                            
}                                             

function getAttributes($id_lang, $nameOnly = false, $id_product = null) {                                            
		return Db::getInstance()->ExecuteS('        
		SELECT '.($nameOnly ? 'al.name, al.id_attribute, agl.`name` AS group_name, pa.quantity, price' : 'pa.*, ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, al.`name` AS attribute_name, a.`id_attribute`').'
		FROM `'._DB_PREFIX_.'product_attribute` pa  
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
		LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.intval($id_lang).')
		WHERE pa.`id_product` = '.(isset($id_product) ? $id_product : intval($this->id)).'		
		ORDER BY pa.`id_product_attribute`');       
	}	                                           
                                              
?>