<?php
	if (!defined('_PS_VERSION_'))
	exit;

class PriceFile extends Module
{
	public function __construct() {
		$this->name = 'pricefile';
		$this->tab = 'administration';
		$this->version = '0.10';
		$this->author = 'Madman';
		$this->bootstrap = true;
		$this->config = array(
			'PS_MOD_PRICEFILE_HASH' => array(Tools::passwdGen(12,'NUMERIC'),false),
			'PS_MOD_PRICEFILE_EXPORT' => array(0,'Export'),
			'PS_MOD_PRICEFILE_IMPORT' => array(0,'Import'),
			'PS_MOD_PRICEFILE_SERVER' => array('','Server address'),
			'PS_MOD_PRICEFILE_SERVER_HASH' => array('','Server security key'),
			'PS_MOD_PRICEFILE_LANG' => array(Configuration::get('PS_LANG_DEFAULT'),false),
		);

		parent::__construct();

		$this->displayName = $this->l('Price File');
		$this->description = $this->l('Export/Import a pricefile in csv format');
	}

	public function install()
	{
		if (!parent::install()
			|| !$this->_installDB()
			|| !$this->_populateDB()
			|| !$this->_checkConfig()
			|| !$this->registerHook('actionAdminControllerSetMedia')
			)
			return false;

		return true;
	}

	private function _checkConfig()
	{
		$result = true;
		// Check config @ install, add with default value if needed.
		foreach ($this->config as $key => $value)
			if (!Configuration::get($key))
			{
				if (!Configuration::updateValue($key,$value[0]))
					$result &= false;
			}
		return $result;
	}

	private function _installDB()
	{
		$result = true;

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pricefile_import` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`id_server` int(10) NOT NULL,
		`name` varchar(128) NOT NULL,
		`include` int(1) NOT NULL DEFAULT \'0\',
		`new` int(1) NOT NULL DEFAULT \'1\',
		`exclude` int(1) NOT NULL DEFAULT \'0\',
		PRIMARY KEY (`id`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		$result &= Db::getInstance()->execute($sql);

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pricefile_export` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`id_server` int(10) NOT NULL,
		`name` varchar(128) NOT NULL,
		`include` int(1) NOT NULL DEFAULT \'0\',
		`new` int(1) NOT NULL DEFAULT \'1\',
		`exclude` int(1) NOT NULL DEFAULT \'0\',
		PRIMARY KEY (`id`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		$result &= Db::getInstance()->execute($sql);

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pricefile_ids` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  `id_server` int(10) NOT NULL,
		  `id_client` int(10) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		$result &= Db::getInstance()->execute($sql);

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pricefile_indata` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`name` varchar(128) NOT NULL,
		`value` tinyint(1) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';
		$result &= Db::getInstance()->execute($sql);

		return $result;
	}

	private function _populateDB()
	{
		$result = true;

		$indata = array(
			'name'=> 1,
			'reference'=> 1,
			'price_without_tax'=> 1,
			'price_with_tax'=> 0, // this can be removed
			'description'=> 1,
			'description_short'=> 0,
			'stock'=> 1,
			'images'=> 0,
		);

		foreach ($indata as $name => $value)
			$result &= Db::getInstance()->insert('pricefile_indata',array(
					'name' => $name,
					'value' => $value));

		return $result;
	}

	private function _updateConfig()
	{
		$output = '';
		foreach ($this->config as $key => $value)
			if ($value[1])
			{
				$opt = Tools::getValue($key);
				if ($key == 'PS_MOD_PRICEFILE_LANG')
					Configuration::updateValue($key,$opt);
				else
					if ($opt != 0 && $opt != 1)
						$output .= $this->displayError($this->l($this->config[$key][1]).': '.$this->l('Invaild choice'));
					else
						Configuration::updateValue($key,$opt);
			}

			$output .= $this->displayConfirmation($this->l('Settings updated'));
		return $output;
	}

	public function hookActionAdminControllerSetMedia($params)
	{
		// add necessary javascript to back office
		if($this->context->controller->controller_name == 'AdminModules' && Tools::getValue('configure') == $this->name
		&& Tools::getValue('tab_module') == 'administration' && Tools::getValue('module_name') == $this->name)
		{
			$this->context->controller->addJS($this->_path.'views/templates/_configure/helpers/form/form.js');
		}
	}

	public function getContent()
	{
		$output = '';

		if (Tools::isSubmit('submitUpdateConfig'))
			$output .= $this->_updateConfig();
		elseif (Tools::isSubmit('submitUpdateImport'))
			$output .= $this->_updateConfig();
			/* Was test data...
			require_once(dirname(__FILE__).'/classes/csv.php');
			$csv = new CSV(';');
			$csv->SetCsvFromFile(dirname(__FILE__).'/pricefile_652696073734.csv');
			$csvArray = $csv->GetArray();
			*/

		$output .= '
		<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K7W88XESNRSW"  target="_blank" style="font-weight:bold;text-decoration:none;color:#000000">
		<img src="'.Tools::getHttpHost(true).__PS_BASE_URI__.'modules/pricefile/cookiemonster_small.jpg">
		Give Madman a cookie!
		</a><br><br>';

		if (Configuration::get('PS_MOD_PRICEFILE_EXPORT'))
		{
			$output .= $this->l('Price File security key').': '.Configuration::get('PS_MOD_PRICEFILE_HASH').'<br>';
			$output .= $this->l('Price File run link').': ' .
				'<a target="_blank" href="'.Tools::getHttpHost(true).__PS_BASE_URI__.'modules/pricefile/run.php?key='.Configuration::get('PS_MOD_PRICEFILE_HASH').'">' .
				Tools::getHttpHost(true).__PS_BASE_URI__.'modules/pricefile/run.php?key='.Configuration::get('PS_MOD_PRICEFILE_HASH').
				'</a><br>';
		}
		$output .= '<br>';
		$output .= $this->renderSettingsForm();
		if (Configuration::get('PS_MOD_PRICEFILE_IMPORT'))
			$output .= $this->renderImportForm();
		if (Configuration::get('PS_MOD_PRICEFILE_EXPORT'))
			$output .= $this->renderExportForm();

		return $output;
	}

	public function renderSettingsForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Export'),
						'name' => 'PS_MOD_PRICEFILE_EXPORT',
						'is_bool' => true,
						'desc' => $this->l('Activate export function'),
						'values' => array(
							array(
								'id' => 'export_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'export_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
					),
					array(
						/*
						 * This should no be moved to export settings, beacuse if this is allready set
						 * then we can retrive product list for export settings in correct language
						*/
						'type' => 'select',
						'label' => $this->l('Language to export'),
						'name' => 'PS_MOD_PRICEFILE_LANG',
						'default_value' => Configuration::get('PS_MOD_PRICEFILE_LANG'),
						'options' => array(
							'query' => Language::getLanguages(true),
							'id' => 'id_lang',
							'name' => 'name'
						)
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Import'),
						'name' => 'PS_MOD_PRICEFILE_IMPORT',
						'is_bool' => true,
						'desc' => $this->l('Activate import function'),
						'values' => array(
							array(
								'id' => 'import_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'import_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						)
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
	
	public function renderExportForm()
	{
		$productList = Db::getInstance()->executeS('
		SELECT *
		FROM `'._DB_PREFIX_.'pricefile_export`
		');
		if (empty($productList))
		{
			$id_lang = Configuration::get('PS_MOD_PRICEFILE_LANG');
			$products = Product::getProducts($id_lang, 1, 0, 'id_product', 'asc',false,true);
			$productArray = array_chunk($products,100);
			foreach ($productArray as $products)
			{
				$values = '';
				foreach ($products as $product)
					$values .= '('.$product['id_product'].',\''.$product['name'].'\'),';

				Db::getInstance()->execute('
					INSERT INTO `'._DB_PREFIX_.'pricefile_export`
					(`id_server`,`name`)
					VALUES '.rtrim($values,',').'
				');
			}
		}

		$includeProductList = array();
		$excludeProductList = array();
		$unlistedProductList = array();

		foreach ($productList as $product)
		{
			if ($product['include'])
				$includeProductList[] = $product;
			elseif ($product['exclude'])
				$excludeProductList[] = $product;
			elseif ($product['new'])
				$unlistedProductList[] = $product;
		}

		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Export settings'),
					'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'three_list',
						'name' => 'export_list',
						'lists' => array(
							array(
								'id' => 'include',
								'label' => 'Included products',
								'selectlist' => $includeProductList,
								'option_syntax' => array(
									array(
										'key' => 'id_server',
										'seperator' => ' - ',
									),
									array(
										'key' => 'name',
										'seperator' => '',
									),
								),
								'move' => array(
									array(
										'direction' => 'right',
										'to_id' => 'exclude',
										'text' => 'Exclude Product',
									),
								),
							),
							array(
								'id' => 'new',
								'label' => 'New, unlisted products',
								'selectlist' => $unlistedProductList,
								'option_syntax' => array(
									array(
										'key' => 'id_server',
										'seperator' => ' - ',
									),
									array(
										'key' => 'name',
										'seperator' => '',
									),
								),
								'move' => array(
									array(
										'direction' => 'left',
										'to_id' => 'include',
										'text' => 'Include Product',
									),
									array(
										'direction' => 'right',
										'to_id' => 'exclude',
										'text' => 'Exclude Product',
									),
								),
							),
							array(
								'id' => 'exclude',
								'label' => 'Excluded products',
								'selectlist' => $excludeProductList,
								'option_syntax' => array(
									array(
										'key' => 'id_server',
										'seperator' => ' - ',
									),
									array(
										'key' => 'name',
										'seperator' => '',
									),
								),
								'move' => array(
									array(
										'direction' => 'right',
										'to_id' => 'exclude',
										'text' => 'Exclude Product',
									),
								),
							),
						),
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
		$helper->submit_action = 'submitUpdateExport';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		$helper->base_folder = 'helpers/form/';
		$helper->module = $this;

		return $helper->generateForm(array($fields_form));
	}

	public function renderImportForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Import settings'),
					'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Server address'),
						'name' => 'PS_MOD_PRICEFILE_SERVER',
						'desc' => $this->l('Enter the address to get pricefile from. (http:// is required)'),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Server security key'),
						'name' => 'PS_MOD_PRICEFILE_SERVER_HASH',
						'desc' => $this->l('Enter the security for the server to get pricefile from'),
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
		$helper->submit_action = 'submitUpdateImport';
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
		foreach($this->config as $key => $value)
		{
			$fields_value[$key] = Tools::getValue($key,Configuration::get($key));
		}
		return $fields_value;
	}
}
?>
