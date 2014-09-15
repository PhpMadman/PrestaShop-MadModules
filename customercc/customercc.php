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

class CustomerCC extends Module
{
	public function __construct()
	{
		$this->name = 'customercc';
		$this->tab = 'Other';
		$this->version = '0.1';
		$this->author = 'Madman';
		$this->need_instance = 0;
		$this->bootstrap =  true;

		parent::__construct();

		$this->displayName = $this->l('Customer CC');
		$this->description = $this->l('Allows customer to add secondary e-mail address');
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('actionObjectCustomerUpdateBefore') || !$this->registerHook('displayCustomerIdentity') || !$this->installDB())
			return false;

		return true;
	}

	public function installDB()
	{
		return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'customer_cc` (
		`id_customer` int(10) NOT NULL,
		`cc_email` varchar(128) DEFAULT NULL,
		PRIMARY KEY (`id_customer`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;');
	}

	public function hookActionObjectCustomerUpdateBefore($params)
	{
		if (isset($_POST['cc_email']) && $params['object']->id)
			$this->saveCCEmail($params['object']->id, $_POST['cc_email']);
		elseif (isset($params['object']->cc_email))
			$this->saveCCEmail($params['object']->id, $params['object']->cc_email);
	}

	private function saveCCEmail($id_customer, $cc_email)
	{
		$exists = Db::getInstance()->executeS('
		SELECT id_customer
		FROM `'._DB_PREFIX_.'customer_cc`
		WHERE `id_customer` = '.$id_customer);

		$what = array('cc_email' => (empty($cc_email) ? '' : $cc_email ));

		if (!empty($exists))
			Db::getInstance()->update('customer_cc', $what, '`id_customer` = '.$id_customer, 0, true);
		else
			if ($cc_email != '') // Don't insert empty value
				Db::getInstance()->insert('customer_cc', array_merge($what, array('id_customer' => $id_customer)), true);
	}

	public function hookDisplayCustomerIdentity()
	{
		$id_customer = $this->context->cookie->id_customer;
		$cc_email = Db::getInstance()->getValue('SELECT `cc_email`
		FROM '._DB_PREFIX_.'customer_cc
		WHERE id_customer = '.$id_customer);
		$this->context->smarty->assign('cc_email',$cc_email);
		return $this->display(__FILE__,'cc_email.tpl');
	}
	

}
?>
