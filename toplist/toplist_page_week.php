<?php
include('../../config/config.inc.php');
include('../../header.php');
global $smarty;
	?>
		<a style="font-size:14px;" href="toplist_page.php">Återgå till nuvarande lista</a><br><br>
	<?php
	$skip =  array(".","..");
	$tpls = array_diff(scandir(dirname(__FILE__) . "/prev"),$skip);
	$i = 0;
	foreach($tpls as $tpl) {
		$i++;
		$name = str_replace("toplist_week_","",$tpl);
		$date = str_replace(".tpl","",$name);
		$name = str_replace("_"," v",$date);
		?>
			<a style="font-size:14px;" href="?w=<?php echo($date) ?>"><?php echo($name); ?></a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
				if($i >= 8) {
					$i = 0;
					echo("<br>");
				}
			?>
		<?
	}

	if($_GET["w"]) {
		?>
			<br><hr><br>
		<?php
		$smarty->display(dirname(__FILE__).'/prev/toplist_week_' .$_GET["w"]. '.tpl');
	}

include('../../footer.php');
?>