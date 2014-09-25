<?php
class ProductController extends ProductControllerCore
{
	public function assignPriceAndTax()
	{
		parent::assignPriceAndTax();
		$id_customer = (isset($this->context->customer) ? (int)$this->context->customer->id : 0);
		$id_currency = (int)$this->context->cookie->id_currency;
		$id_product = (int)$this->product->id;
		$id_shop = $this->context->shop->id;
		$id_group = (int)Group::getCurrent()->id;
		$id_country = (int)$id_customer ? Customer::getCurrentCountry($id_customer) : Configuration::get('PS_COUNTRY_DEFAULT');

		$product_price = $this->product->getPrice(Product::$_taxCalculationMethod == PS_TAX_INC, false);
		$quantity_discounts = SpecificPrice::getQuantityDiscounts($id_product, $id_shop, $id_currency, $id_country, $id_group, null, true, (int)$this->context->customer->id);
		$lowest_price = $product_price;
		foreach ($quantity_discounts as &$quantity_discount)
		{
			if ($quantity_discount['id_product_attribute'])
			{
				$combination = new Combination((int)$quantity_discount['id_product_attribute']);
				$attributes = $combination->getAttributesName((int)$this->context->language->id);
				foreach ($attributes as $attribute)
					$quantity_discount['attributes'] = $attribute['name'].' - ';
				$quantity_discount['attributes'] = rtrim($quantity_discount['attributes'], ' - ');
			}
			if ((int)$quantity_discount['id_currency'] == 0 && $quantity_discount['reduction_type'] == 'amount')
				$quantity_discount['reduction'] = Tools::convertPriceFull($quantity_discount['reduction'], null, Context::getContext()->currency);

				if ($quantity_discount['reduction_type'] == 'amount')
					 $low = $product_price - $quantity_discount['reduction'];
				else
					$low = $product_price-($product_price*$quantity_discount['reduction']);

				if ($lowest_price > $low)
					$lowest_price = $low;
		}
		if ($lowest_price ==  $product_price)
			$lowest_price = null;

		if (isset($lowest_price))
			$this->context->smarty->assign('lowest_price', $lowest_price);
	}
}
?>