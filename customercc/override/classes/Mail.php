<?php
class Mail extends MailCore
{
	public static function Send($id_lang, $template, $subject, $template_vars, $to,
		$to_name = null, $from = null, $from_name = null, $file_attachment = null, $mode_smtp = null,
		$template_path = _PS_MAIL_DIR_, $die = false, $id_shop = null, $bcc = null)
	{
		$id_customer = Customer::customerExists($to, true);
		if ($id_customer)
		{
			$cc_email = Db::getInstance()->getValue('SELECT `cc_email`
			FROM '._DB_PREFIX_.'customer_cc
			WHERE id_customer = '.$id_customer);
			if ($cc_email != '')
			{
				$bcc = $cc_email;
			}
		}

		if (!is_null($bcc) && !is_array($bcc) && !Validate::isEmail($bcc))
		{
			Tools::dieOrLog(Tools::displayError('Error: parameter "bcc" is corrupted'), $die);
			return false;
		}
		return parent::Send($id_lang, $template, $subject, $template_vars, $to,
		$to_name, $from, $from_name, $file_attachment, $mode_smtp,
		$template_path, $die, $id_shop, $bcc);
	}
}