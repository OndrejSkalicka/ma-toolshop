<?php
function seznam_skupin () {
	$ret_val = "";

	$skupiny_db = MySQL_Query ("SELECT * FROM `uziv_skupiny` ORDER BY `nazev`");
	if (MySQL_Num_Rows ($skupiny_db) == 0) {
		$ret_val .= "Žádné uživatelské skupiny.<br>";
	} else {
		$ret_val .= "<strong>SKUPINY: </strong><br><br>";
	}
	
	while ($skupina = MySQL_Fetch_Array ($skupiny_db)) {
		$ret_val .= '&nbsp;&nbsp;* <a href="main.php?akce=skupiny&amp;skup_id='.$skupina['ID'].'"'.($_REQUEST['skup_id'] == $skupina['ID'] ? ' class="other"':"").'>'.HtmlSpecialChars($skupina['nazev']).'</a><br>';
	}
	
	
	/* aliance */
	$ali_db = MySQL_Query ("SELECT * FROM `ali` ORDER BY `jmeno`");
	if (MySQL_Num_Rows ($skupiny_db) == 0) {
		$ret_val .= "Žádné aliance.<br>";
	} else {
		$ret_val .= "<br><strong>ALIANCE:</strong><br><br>";
	}
	while ($ali = MySQL_Fetch_Array ($ali_db)) {
		$ret_val .= '&nbsp;&nbsp;* <a href="main.php?akce=skupiny&amp;ali_id='.$ali['ID'].'"'.($_REQUEST['ali_id'] == $ali['ID'] ? ' class="other"':"").'>'.HtmlSpecialChars($ali['jmeno']).'</a><br>';
	}
	
	return $ret_val;
}
?>