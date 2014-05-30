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
*  @author Madman
*  @copyright  2014 Madman
*  @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
**/

?>
<?php

	if (!defined('_PS_VERSION_'))
	exit;

class CartLimiter extends Module
{


	/** Construction of module  **/
	public function __construct()
	{
		$this->name = 'cartlimiter';
		$this->tab = 'checkout';
		$this->version = '1.0';
		$this->author = 'Madman';
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('CartLimiter');
		$this->description = $this->l('Limit cart to a specific category');
	}

	public function install()
	{
		if (!parent::install()
			|| !$this->registerHook('actionCartSave')
		)
			return false;

		return true;
	}

	/** Module functions  **/

	public function getContent()
	{	
		$output = '';
		if (Tools::isSubmit('submitUpdateConfig'))
			$output .= $this->_updateConfig();

		$output .= $this->renderSettingsForm();
		return $output;
	}
	
	private function _updateConfig()
	{
		if (Configuration::updateValue('PS_CART_LIMIT_IDS', Tools::getValue('PS_CART_LIMIT_IDS')))
			return $this->displayConfirmation($this->l('Settings updated'));
		else
			return $this->displayError('PS_CART_LIMIT_IDS: '.$this->l('Invaild choice'));
	}

	public function hookActionCartSave($params)
	{
		$cart = $params['cart'];
		$last = $cart->getLastProduct();
		$products = $cart->getProducts();
		$anyProductInLimit = false;
		$ids = Configuration::get('PS_CART_LIMIT_IDS');
		$ids = explode(',',$ids);
		foreach ($products as $product)
		{
			if ($product['id_product'] != $last['id_product']) // only check if id does not match latest product added to cart
			{
				$categories = Product::getProductCategories($product['id_product']);
				foreach ($categories as $category)
				{
					foreach ($ids as $id)
						if ($category == $id)
							$anyProductInLimit = true;
// 					if ($category == 11) // If a product is in the category we want to limit the cart to
// 					{
// 						$anyProductInLimit = true; 
// 					}
				}
			}
		}
		$categoriesLast = Product::getProductCategories($last['id_product']);
		$lastInLimit = false;
		foreach ($categoriesLast as $categoryLast)
		{
			foreach ($ids as $id)
				if ($categoryLast == $id)
					$lastInLimit = true;
		}
		if ($anyProductInLimit && !$lastInLimit)
		{
			$cart->deleteProduct($last['id_product']);
			// check if last is not in limt, then delete
		}
		elseif (!$anyProductInLimit && $lastInLimit && count($products) >= 2)
		{
			$cart->deleteProduct($last['id_product']);
			// check if last is in limit, then delete
		}
	}

	/** Settings  **/
	public function renderSettingsForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Category ID`s'),
						'name' => 'PS_CART_LIMIT_IDS',
						'size' => 5,
						'hint' => 'The category ids to limit as a comma separated list',
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			)
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitUpdateConfig';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		$fields_value = array();
				$fields_value['PS_CART_LIMIT_IDS'] = Configuration::get('PS_CART_LIMIT_IDS');

		return $fields_value;
	}

}
?>
