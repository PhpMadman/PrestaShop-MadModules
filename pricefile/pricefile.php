<?php
	if (!defined('_PS_VERSION_'))
	exit;

class PriceFile extends Module
{
	public function __construct() {
		$this->name = 'pricefile';
		$this->tab = 'administration';
		$this->version = '0.3';
		$this->author = 'Madman';

// 		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Price File');
		$this->description = $this->l('Export product information to pricefile.csv');
	}

	public function install()
	{
		if (parent::install() == false)
			return false;
// Something is broken @ install. It just wont install OK. Cache isue?
			
		// The list with settings needed to module
// 		$config = array(
// 			'PS_MOD_PRICEFILE_HASH' => Tools::passwdGen(12,'NUMERIC'),
// 			'PS_MOD_PRICEFILE_EXPORT' => 0,
// 			'PS_MOD_PRICEFILE_IMPORT' => 0,
// 		);

// 		if (!Configuration::get('PS_MOD_PRICEFILE_HASH'))
// 				Configuration::updateValue('PS_MOD_PRICEFILE_HASH',Tools::passwdGen(12,'NUMERIC'));

// 		if (!Configuration::get('PS_MOD_PRICEFILE_EXPORT'))
// 				Configuration::updateValue('PS_MOD_PRICEFILE_EXPORT',0);
// 
// 		if (!Configuration::get('PS_MOD_PRICEFILE_IMPORT'))
// 				Configuration::updateValue('PS_MOD_PRICEFILE_IMPORT',0);

		// This forloop does not work!
		// Check config @ install, add with default value if needed.
// 		foreach ($config as $key => $value)
// 		{
// 			if (!Configuration::get($key))
// 				Configuration::updateValue($key,$value);
// 		}

		return true;
	}
	/*
	public function getContent()
	{
		$output = '';
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
						'desc' => $this->l('Activate export function for this server'),
						'values' => array(
							array(
								'id' => 'export_on',
								'value' => 1,
								'label' => $this->l('Enabled')
							),
							array(
								'id' => 'export_off',
								'values' => 0,
								'label' => $this->l('Disabled')
							)
						)
					)
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
		$helper->submit_action = 'submitPriceFile';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
			.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		return $helper->generateForm(array($fields_form));
	}
	*/
}
?>


