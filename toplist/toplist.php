<?php
	if (!defined('_PS_VERSION_'))
	exit;

	class Toplist extends Module {
		public function __construct() {
			$this->name = 'toplist';
			$this->tab = 'Test';
			$this->version = 0.2;
			$this->author = 'Madman';
			$this->need_instance = 0;

			parent::__construct();

			$this->displayName = $this->l('Toplist');
			$this->description = $this->l('Adds a toplist to home page');
		}

		// this also works, and is more future-proof
		public function install() {
			if (parent::install() == false
// 				|| !$this->registerHook("home")
			)
				return false;
			return true;

		}

		public function hookDisplayHome() {
			$week = file_get_contents(dirname(__FILE__) . "/week.txt");
			$this->smarty->assign('toplist_week', $week);
			Tools::addCSS($this->_path.'toplist.css', 'all');
			return $this->display(__FILE__, 'toplist.tpl');
		}

	}
?>