<?php
	if (!defined('_PS_VERSION_'))
	exit;

class PriceFile extends Module
{
	public function __construct() {
		$this->name = 'pricefile';
		$this->tab = 'administration';
		$this->version = '0.6.1';
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
		$this->description = $this->l('Export product information to pricefile.csv');
	}

	public function install()
	{
		if (parent::install() == false)
			return false;

		// Check config @ install, add with default value if needed.
		foreach ($this->config as $key => $value)
		{
			if (!Configuration::get($key))
			Configuration::updateValue($key,$value[0]);
		}

		return true;
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

	public function getContent()
	{
		$output = '';
		if (Tools::isSubmit('submitUpdateConfig'))
		{
			$output .= $this->_updateConfig();
		}
		elseif (Tools::isSubmit('submitImport'))
		{
			require_once(dirname(__FILE__).'/class/csv.php');
			$csv = new CSV(';');
			$csv->SetCsvFromFile(dirname(__FILE__).'/pricefile_652696073734.csv');
			$output .= '<pre>'.print_r($csv->GetArray()).'</pre>';
		}

		if (Configuration::get('PS_MOD_PRICEFILE_EXPORT'))
		{
			$output .= $this->l('Price File security key').': '.Configuration::get('PS_MOD_PRICEFILE_HASH').'<br>';
			$output .= $this->l('Price File run link').': ' .
				'<a target="_blank" href="'.Tools::getHttpHost(true).__PS_BASE_URI__.'modules/pricefile/run.php?key='.Configuration::get('PS_MOD_PRICEFILE_HASH').'">' .
				Tools::getHttpHost(true).__PS_BASE_URI__.'modules/pricefile/run.php?key='.Configuration::get('PS_MOD_PRICEFILE_HASH').
				'</a><br>';
		}
		$output .= '<br>';
		$output .= $this->renderImportForm();
		$output .= $this->renderForm();

		return $output;
	}

	public function renderForm()
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
	
	public function renderImportForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Importer'),
					'icon' => 'icon-cogs',
				),
				'submit' => array(
					'title' => $this->l('Import'),
				),
			)
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitImport';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

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
