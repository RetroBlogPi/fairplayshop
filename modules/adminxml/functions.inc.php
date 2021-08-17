<?php

class AdminXMLfunc extends AdminXML {

        public function getAttribs($id_product, $id_lang) {
		$attributesGroups = Db::getInstance()->ExecuteS('
		SELECT ag.`id_attribute_group`, agl.`public_name` AS group_name, agl.`public_name` AS public_group_name, a.`id_attribute`,
                al.`name` AS attribute_name, pa.`id_product_attribute`, pa.`quantity`, pa.`price`, pa.`default_on`
		FROM `'._DB_PREFIX_.'product_attribute` pa
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
		LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`
		LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON ag.`id_attribute_group` = agl.`id_attribute_group`
		WHERE pa.`id_product` = '.intval($id_product).'
		AND al.`id_lang` = '.intval($id_lang).'
		AND agl.`id_lang` = '.intval($id_lang).'
		ORDER BY ag.`id_attribute_group`, pa.`id_product_attribute`');

                foreach ($attributesGroups as $k => $row) {
				$groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']]['name'] = $row['attribute_name'];
				$groups[$row['id_attribute_group']]['name'] = $row['public_group_name'];
				if ($row['default_on'])
					$groups[$row['id_attribute_group']]['default'] = intval($row['id_attribute']);
				if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']]))
					$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
				$groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += intval($row['quantity']);
		}

		$washed = array();
		//wash attributes list (if some attributes are unavailables and if allowed to wash it)
		if (Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0 && $groups) {
			foreach ($groups as &$group) {
				foreach ($group['attributes_quantity'] AS $key => &$quantity)
					if (!$quantity){
						unset($group['attributes'][$key]);
		              			$washed[] = $key;
					}
                        }
                }
                unset($attributesGroups);
                return $groups;
	}

        public function getParams($id_product, $id_lang) {
	        $combinations = Db::getInstance()->ExecuteS('
                       SELECT pa.`id_product_attribute`, pa.`quantity`, pa.`price`, pa.`ean13`, pa.`reference`, ag.`id_attribute_group`, ag.`is_color_group`,
                       agl.`public_name` AS group_name, al.`name` AS attribute_name, a.`id_attribute`
		       FROM `'._DB_PREFIX_.'product_attribute` pa
		       LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
		       LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
		       LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
		       LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.intval($id_lang).')
		       LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.intval($id_lang).')
		       WHERE pa.`id_product` = '. $id_product .'
		       ORDER BY ag.`id_attribute_group`, pa.`id_product_attribute`
                ');
		if (is_array($combinations)) {
		        foreach ($combinations AS $k => $combination) {
				$combArray[$combination['id_product_attribute']]['price'] = $combination['price'];
				$combArray[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
				$combArray[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
			}
			unset($combinations);
		        return $combArray;
                }
	}

        public function multiexplode($delimiters,$string) {
                $ready = str_replace($delimiters, $delimiters[0], $string);
                $launch = explode($delimiters[0], $ready);
                return $launch;
        }

        public function getCategories($id_parent, $id_lang, $row) {
                global $result2;

                $categories = Db::getInstance()->ExecuteS('
		        SELECT * FROM '._DB_PREFIX_.'category c
		        LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (c.id_category = cl.id_category AND id_lang = '. $id_lang .')
		        WHERE id_parent = '. $id_parent .'
                        GROUP BY c.id_category
		        ORDER BY cl.name
                ');
                foreach ($categories as $category) {
                        $space = '';
                        for ($i=0;$i<$category['level_depth'];$i++) {
                                $space .= '&nbsp;&nbsp;';
                        }
			$result2 .= '<tr'. ($row%2==0?' class="alt_row"':'') .'>
				<td>'. $space . $category['name']. '</td>
                                <td style="padding:1px"><input type="text" value="'. Configuration::get('ADMINXML_HEUREKA_'. $category['id_category']) .'" name="categories['. $category['id_category'] .']" size="70" /></td>
			</tr>';
                        $row++;
                        AdminXMLfunc::getCategories($category['id_category'], $id_lang, $row);
                }
                return $result2;
        }

        public function getZboziCategories($id_parent, $id_lang, $row) {
                global $result3;

                $categories = Db::getInstance()->ExecuteS('
		        SELECT * FROM '._DB_PREFIX_.'category c
		        LEFT JOIN '._DB_PREFIX_.'category_lang cl ON (c.id_category = cl.id_category AND id_lang = '. $id_lang .')
		        WHERE id_parent = '. $id_parent .'
                        GROUP BY c.id_category
		        ORDER BY cl.name
                ');
                foreach ($categories as $category) {
                        $space = '';
                        for ($i=0;$i<$category['level_depth'];$i++) {
                                $space .= '&nbsp;&nbsp;';
                        }
			$result3 .= '<tr'. ($row%2==0?' class="alt_row"':'') .'>
				<td>'. $space . $category['name']. '</td>
                                <td style="padding:1px"><input type="text" value="'. Configuration::get('ADMINXML_ZBOZI_'. $category['id_category']) .'" name="categories['. $category['id_category'] .']" size="70" /></td>
			</tr>';
                        $row++;
                        AdminXMLfunc::getZboziCategories($category['id_category'], $id_lang, $row);
                }
                return $result3;
        }

	public function header() {
                return "<?xml version='1.0' encoding='utf-8'?>\n";
        }
}

?>
