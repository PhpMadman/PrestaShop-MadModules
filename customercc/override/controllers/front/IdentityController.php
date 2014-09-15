<?php
class IdentityController extends IdentityControllerCore
{
	public function initContent()
	{
		parent::initContent();
		$this->context->smarty->assign('HOOK_CUSTOMER_IDENTITY', Hook::exec('displayCustomerIdentity'));
	}
}
