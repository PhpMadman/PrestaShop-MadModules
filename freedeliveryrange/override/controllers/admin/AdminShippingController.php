<?php
class AdminShippingController extends AdminShippingControllerCore
{
	public function __construct()
	{
		parent::__construct();
		$this->fields_options['handling']['fields']['PS_SHIPPING_FREE_PRICE_END'] = array(
						'title' => $this->l('Free shipping ends at (tax incl)'),
						'suffix' => $this->context->currency->getSign(),
						'cast' => 'floatval',
						'type' => 'text',
						'validation' => 'isPrice');
		$this->fields_options['handling']['fields']['PS_SHIPPING_FREE_WEIGHT_END'] = array(
						'title' => $this->l('Free shipping ends at'),
						'suffix' => Configuration::get('PS_WEIGHT_UNIT'),
						'cast' => 'floatval',
						'type' => 'text',
						'validation' => 'isUnsignedFloat');
		$this->fields_options['handling']['fields']['PS_SHIPPING_FREE_TYPE'] = array(
						'title' => $this->l('Free shiping type'),
						'desc' => $this->l('How to determin free shipping'),
						'type' => 'select',
						'list' => array(
							array('id' => '0', 'name' => $this->l('Price or Weight ')),
							array('id' => '1', 'name' => $this->l('Price and Weight ')),
						),
						'identifier' => 'id'
					);
		$this->fields_options['handling']['description'] =
					'<ul>
						<li>'.$this->l('If you set these parameters to 0, they will be disabled.').'</li>
						<li>'.$this->l('Coupons are not taken into account when calculating free shipping.').'</li>
						<li>'.$this->l('End price and end weight is included in calculation of free shipping').'</li>
					</ul>';
	}
}
?>