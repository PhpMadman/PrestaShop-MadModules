<?php

class BookVisitBookVisit_FormModuleFrontController extends ModuleFrontController
{

  public function initContent()
  {
    parent::initContent();
    global $smarty;
    $BookVisit = new BookVisitClass; // Get modules class and make it object
    $smarty->assign("bookvisit",$BookVisit); // asign the object to a smarty var
// 	$this->_prepareHook();
    $this->setTemplate('bookvisit_form.tpl'); // set template
  }

}
?>
<?php
// The functions for this page.
class BookVisitClass {

	public function DoForm($post) {
		if($post["bookit"])  {
			$msg = "F&ouml;rfrågan om s&auml;ljbes&ouml;k<br>";
$msg .= "<table>";
$msg .= "<tr>";
$msg .= "<td>Företag</td>";
$msg .= "<td>$post[company]</td>";
$msg .= "</td>";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>Kontaktperson&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
$msg .= "<td>$post[contact]</td>";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>Adress</td>";
$msg .= "<td>$post[address]</td>";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>Postnr</td>";
$msg .= "<td>$post[postal_code]</td>";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>Stad</td>";
$msg .= "<td>$post[city]</td>";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>Telefon</td>";
$msg .= "<td>$post[phone]";
$msg .= "</tr>";
$msg .= "<tr>";
$msg .= "<td>E-Mail</td>";
$msg .= "<td>$post[email]</td>";
$msg .= "</tr>";
$msg .= "</table>";
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: ' . $post["email"] . "\r\n";
			mail("salj@nldistribution.se","Förfrågan om säljbesök från $post[company]",$msg,$headers);
			return "<div style='background-color:#DFF2BF; border: 1px solid #4F8A10; font-size:14px; height:20px; padding:5px; color: #4F8A10; '>Din förfrågan har skickats, vi återkommer via mail/telefon så snart vi kan</div>";
		}
	}
}
?>