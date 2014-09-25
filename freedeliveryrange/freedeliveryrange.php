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

class FreeDeliveryRange extends Module
{
	public function __construct()
	{
		$this->name = 'freedeliveryrange';
		$this->tab = 'Other';
		$this->version = '0.1';
		$this->author = 'Madman';
		$this->need_instance = 0;
		$this->bootstrap =  true;

		parent::__construct();

		$this->displayName = $this->l('Free Delivery Range');
		$this->description = $this->l('Adds free delivery range');
	}

	public function install()
	{
		return parent::install();
	}
}
?>
