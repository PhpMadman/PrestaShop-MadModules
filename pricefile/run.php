<?php
		include_once(dirname(__FILE__) . '/../../config/config.inc.php');
		include_once(dirname(__FILE__) . '/../../init.php');
		echo('running around<br>');
		$id_lang = 1; // This should be in config later on
		$products = Product::getProducts($id_lang, 1, 0, 'id_product', 'desc');
		foreach($products as $product)
		{
			echo($product['name'].'<br>');
		}
?>