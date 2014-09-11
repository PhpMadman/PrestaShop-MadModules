<?php

class OrderState extends OrderStateCore
{
	/** @var boolean Attach PDF Invoice */
	public $pdf_invoice;

	/** @var boolean Attach PDF Delivery Slip */
	public $pdf_delivery;

	public static $definition = array(
		'table' => 'order_state',
		'primary' => 'id_order_state',
		'multilang' => true,
		'fields' => array(
			'send_email' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'module_name' => array('type' => self::TYPE_STRING, 'validate' => 'isModuleName'),
			'invoice' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'color' => 		array('type' => self::TYPE_STRING, 'validate' => 'isColor'),
			'logable' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'shipped' => 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'unremovable' =>array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'delivery' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'hidden' =>		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'paid' =>		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pdf_delivery' =>		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'pdf_invoice' =>		array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'deleted' =>	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),

			// Lang fields
			'name' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 64),
			'template' => 	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isTplName', 'size' => 64),
		),
	);
}
