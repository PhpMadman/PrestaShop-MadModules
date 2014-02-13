<?php
include('../../config/config.inc.php');
include('../../header.php');

?>
	<div style="width:100%;text-align:center;"><a href="toplist_page_week.php" style="font-size:14px;">Lista alla veckor</a></div>
	<br><hr><br>
<?php

global $smarty;

$smarty->display(dirname(__FILE__).'/toplist_page.tpl');

include('../../footer.php');
?>