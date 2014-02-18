<?php
	if (!defined('_PS_VERSION_'))
	exit;

	class PriceFile extends Module
	{
		public function __construct() {
			$this->name = 'pricefile';
			$this->tab = 'administration';
			$this->version = 0.2;
			$this->author = 'Madman';

			parent::__construct();

			$this->displayName = $this->l('Price File');
			$this->description = $this->l('Export product information to pricefile.csv');
		}

		public function install()
		{
			if (parent::install() == false)
				return false;

			if (!Configuration::get('PS_MOD_PRICEFILE_HASH'))
				Configuration::updateValue('PS_MOD_PRICEFILE_HASH',Tools::passwdGen(12,'NUMERIC'));

			return true;
		}
	}
?>
