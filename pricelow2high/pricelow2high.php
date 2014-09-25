<?php
/**
* 2014 Madman
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author Madman
* @copyright 2014 Madman
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
	if (!defined('_PS_VERSION_'))
	exit;

class PriceLow2High extends Module
{
	public function __construct()
	{
		$this->name = 'pricelow2high';
		$this->tab = 'Other';
		$this->version = '0.1';
		$this->author = 'Madman';
		$this->need_instance = 0;
		$this->bootstrap =  true;

		parent::__construct();

		$this->displayName = $this->l('Price Low 2 High');
		$this->description = $this->l('Overrides / Hooks to override price to display low to high');
	}

	public function install()
	{
		if (!parent::install() && !$this->registerHook('actionProductListModifier'))
			return false;

		return true;
	}
	
	public function hookActionProductListModifier($params)
	{
		foreach ($params['cat_products'] as &$product)
		{
				$product_price = Product::getPriceStatic($product['id_product'], Product::$_taxCalculationMethod == PS_TAX_INC);
				$id_customer = (isset($this->context->customer) ? (int)$this->context->customer->id : 0);
				$id_group = (int)Group::getCurrent()->id;
				$id_country = (int)$id_customer ? Customer::getCurrentCountry($id_customer) : Configuration::get('PS_COUNTRY_DEFAULT');
				$id_shop = $this->context->shop->id;
				$id_currency = (int)$this->context->cookie->id_currency;
				$quantity_discounts = SpecificPrice::getQuantityDiscounts($product['id_product'], $id_shop, $id_currency, $id_country, $id_group, null, true, (int)$this->context->customer->id);
				$lowest_price = $product_price;

				foreach ($quantity_discounts as &$quantity_discount)
				{
					if ($quantity_discount['reduction_type'] == 'amount')
						$low = $product_price - $quantity_discount['reduction'];
					else
						$low = $product_price-($product_price*$quantity_discount['reduction']);

					if ($lowest_price > $low)
						$lowest_price = $low;
				}

				if ($lowest_price != $product_price)
					$product['lowest_price'] = $lowest_price;
		}
	}
}
?>
