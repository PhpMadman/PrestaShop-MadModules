<?php
	include_once(dirname(__FILE__) . '/../../config/config.inc.php');
	include_once(dirname(__FILE__) . '/../../init.php');

	if (Configuration::get('PS_MOD_INFOFILE_EXPORT'))
	{
		if (@$_GET['key'] == Configuration::get('PS_MOD_INFOFILE_HASH'))
		{
			$id_lang = Configuration::get('PS_MOD_INFOFILE_LANG');
			$products = Product::getProducts($id_lang, 1, 0, 'id_product', 'desc',false,true);
// 			$csvHeader = 'id;"name";"reference";price_without_tax;price_tax;"description";"description_short";stock;images';
			$csvHeader = '"name";"reference";stock';
			$csvString = '';
			foreach ($products as $product)
			{
				$productOk = false;
				$comboName = array();
				$p = new Product($product['id_product'],true,$id_lang);
				$combos = $p->getAttributeCombinations($id_lang);
				// Hack to add warehouse support, combos not supported
				$whList = Warehouse::getProductWarehouseList($product['id_product'],0);
				if (array_key_exists (0,$whList ))
					foreach ($whList as $wh)
						if ($wh['id_warehouse'] == 1)
							$productOk = true;

				if ($productOk)
				{
					if ($combos)
					{
						/*
						$comboImages = $p->getCombinationImages($id_lang);
						foreach ($combos as $combo)
						{
							$comboName[$combo['id_product_attribute']]['name'][] = $combo['attribute_name'];
							$comboName[$combo['id_product_attribute']]['reference'] = $combo['reference'];
							$comboName[$combo['id_product_attribute']]['quantity'] = $combo['quantity'];
							if ($comboImages)
								if (array_key_exists($combo['id_product_attribute'],$comboImages))
									$comboName[$combo['id_product_attribute']]['images'] = $comboImages[$combo['id_product_attribute']];
						}
						foreach ($comboName as $id => $att)
						{
							$productImages = '';
							$csvProductString = '';
							if ($comboImages)
							{
								foreach ($comboImages as $id_img => $attImg)
								{
									if ($id == $id_img)
									{
										foreach ($attImg as $img)
										{
											$imgLink = 'http://'.($link->getImageLink($p->link_rewrite, $p->id.'-'.$img['id_image'], 'large_default')).';';
											$imgLink = str_replace('http://http://','http://',$imgLink);
											$productImages .= $imgLink;
										}
										break;
									}
								}
							}
							$attName = implode(' - ',$att['name']);

							$csvProductString = '"'.$p->name.' - '.$attName.','.($att['reference'] ?: $p->reference).','.$p->getPrice(false,$id,6).','.$p->getPrice(true,$id,6).
							','.$p->description.','.$p->description_short.
							','.$att['quantity'].','.rtrim($productImages,';').'";';
							$csvString .= $csvProductString."\n";
						}
						*/
					}
					else
					{
						$images = $p->getImages($id_lang);
						$link = new Link();
						$productImages = '';
						foreach ($images as $img)
						{
							$imgLink = 'http://'.($link->getImageLink($p->link_rewrite, $p->id.'-'.$img['id_image'], 'large_default')).',';
							$imgLink = str_replace('http://http://','http://',$imgLink);
							$productImages .= $imgLink;
						}
	// 					$csvProductString = $p->id.';"'.$p->name.'";"'.$p->reference.'";'.$p->getPrice(false,false,6).';'.$p->getPrice(true,false,6).
	// 					';"'.Tools::nl2br($p->description).'";"'.Tools::nl2br($p->description_short).
	// 					'";'.$p->quantity.';'.rtrim($productImages,',');
						$csvProductString = '"'.$p->name.'";"'.$p->reference.
						'";'.$p->quantity;
						$csvString .= $csvProductString."\n";
					}
				}

			}
			$csv = $csvHeader."\n".$csvString;
// 			$file = fopen(dirname(__FILE__).'/infofile'.Configuration::get('PS_MOD_INFOFILE_HASH').'.csv','w');
			$file = fopen(dirname(__FILE__).'/infofile.csv','w');
			fwrite($file,rtrim($csv));
			fclose($file);
		}
		else
		{
			die('The force is weak with this one');
		}
	}
	else
	{
		die('Export is not enabled on this server');
	}
?>
