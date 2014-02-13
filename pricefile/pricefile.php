<?php
	if (!defined('_PS_VERSION_'))
	exit;

	class PriceFile extends Module
	{
		public function __construct() {
			$this->name = 'pricefile';
			$this->tab = 'administration';
			$this->version = 0.1;
			$this->author = 'Madman';

			parent::__construct();

			$this->displayName = $this->l('Price File');
			$this->description = $this->l('Bla bla');
		}

		public function install()
		{
			if (parent::install() == false)
				return false;
			return true;
		}
	}
?>