<?php
	include_once(dirname(__FILE__) . '/../../config/config.inc.php');
	include_once(dirname(__FILE__) . '/../../init.php');

	if (Configuration::get('PS_MOD_PRICEFILE_EXPORT'))
	{
		if (@$_GET['key'] == Configuration::get('PS_MOD_PRICEFILE_HASH'))
		{
			$id_lang = Configuration::get('PS_MOD_PRICEFILE_LANG');
			$products = Product::getProducts($id_lang, 1, 0, 'id_product', 'desc',false,true);
			$csvHeader = '"name";"reference";price_without_tax;price_tax;"description";"description_short";stock;images';
			$csvString = '';
			foreach ($products as $product)
			{
				$comboName = array();
				$p = new Product($product['id_product'],true,$id_lang);
				$combos = $p->getAttributeCombinations($id_lang);
				if ($combos)
				{
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
						/*
						$csvProductString = '"'.$p->name.' - '.$attName.','.($att['reference'] ?: $p->reference).','.$p->getPrice(false,$id,6).','.$p->getPrice(true,$id,6).
						','.$p->description.','.$p->description_short.
						','.$att['quantity'].','.rtrim($productImages,';').'";';
						$csvString .= $csvProductString."\n";
						*/
					}
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
					$csvProductString = '"'.$p->name.'";"'.$p->reference.'";'.$p->getPrice(false,false,6).';'.$p->getPrice(true,false,6).
					';"'.Tools::nl2br($p->description).'";"'.Tools::nl2br($p->description_short).
					'";'.$p->quantity.';'.rtrim($productImages,',');
					$csvString .= $csvProductString."\n";
				}

			}
			$csv = $csvHeader."\n".$csvString;
			$file = fopen(dirname(__FILE__).'/pricefile_'.Configuration::get('PS_MOD_PRICEFILE_HASH').'.csv','w');
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
