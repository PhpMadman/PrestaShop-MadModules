<?php
	if (!defined('_PS_VERSION_'))
	exit;

	class OurPartner extends Module {
		public function __construct() {
			$this->name = 'ourpartner';
			$this->tab = 'FrontOffice';
			$this->version = 1.0;
			$this->author = 'Madman';
			$this->need_instance = 0;

			parent::__construct();

			$this->displayName = $this->l('Our Partner');
			$this->description = $this->l('Adds a our partner block in right column');
		}

		// this also works, and is more future-proof
		public function install() {
			if (parent::install() == false  OR !$this->registerHook('rightColumn'))
				return false;
			return true;
		}

		public function hookRightColumn($params) {
			global $smarty;
			return $this->display(__FILE__, 'ourpartner.tpl');
		}
	}
?>