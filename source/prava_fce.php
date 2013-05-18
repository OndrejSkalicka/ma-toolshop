<?php
/*function MaPrava ($id_user, $text) {
	
	$prava = MySQL_Query ("SELECT * FROM `prava` 
								INNER JOIN `pravo_text` ON `pravo_text`.`ID` = `prava`.`ID_pravo_text`
								WHERE `prava`.`ID_users`= '$id_user' AND `pravo_text`.`text` LIKE '$text'");
	
	if (MySQL_Num_Rows ($prava) > 0) return 1;
	return 0;
}*/
function MaPrava ($text = "LOGIN", $id_user = -1, $pouzij_skupiny = 1) { // 1 ma, 0 nema
	if ($text == "") 
		return 1;
	if ($id_user == -1)
		$id_user = $GLOBALS['user_info']['ID'];
	
	if ($pouzij_skupiny) {
		MySQL_Query ("skupina");
		$skup_prava_db = MySQL_Query ("SELECT `prava_skupiny`.`typ` FROM `users`
											INNER JOIN `prava_skupiny` ON `users`.`ID_skupina` = `prava_skupiny`.`ID_uziv_skupiny`
											INNER JOIN `pravo_text` ON `prava_skupiny`.`ID_pravo` = `pravo_text`.`ID`
											WHERE `users`.`ID`= '$id_user' AND `pravo_text`.`text` LIKE '$text'");
		if ($skup_prava = MySQL_Fetch_Array ($skup_prava_db)) {
			return $skup_prava['typ'];		
		}
		/* verejka */
		$ali_prava_db = MySQL_Query ("SELECT `prava_ali`.`typ` FROM `users`
											INNER JOIN `prava_ali` ON `users`.`ID_ali_v` = `prava_ali`.`ID_ali`
											INNER JOIN `pravo_text` ON `prava_ali`.`ID_pravo` = `pravo_text`.`ID`
											WHERE `users`.`ID`= '$id_user' AND `pravo_text`.`text` LIKE '$text'");
		if ($ali_prava = MySQL_Fetch_Array ($ali_prava_db)) {
			return $ali_prava['typ'];		
		}
		/* tajna */
		$ali_prava_db = MySQL_Query ("SELECT `prava_ali`.`typ` FROM `users`
											INNER JOIN `prava_ali` ON `users`.`ID_ali_t` = `prava_ali`.`ID_ali`
											INNER JOIN `pravo_text` ON `prava_ali`.`ID_pravo` = `pravo_text`.`ID`
											WHERE `users`.`ID`= '$id_user' AND `pravo_text`.`text` LIKE '$text'");
		if ($ali_prava = MySQL_Fetch_Array ($ali_prava_db)) {
			return $ali_prava['typ'];		
		}
	}
	
	$prava = MySQL_Query ("SELECT * FROM `prava` 
								INNER JOIN `pravo_text` ON `pravo_text`.`ID` = `prava`.`ID_pravo_text`
								WHERE `prava`.`ID_users`= '$id_user' AND `pravo_text`.`text` LIKE '$text'");
	
	if (MySQL_Num_Rows ($prava) > 0) return 1;
	return 0;
}
function Pravo_TextToID ($text) {
	if (!$pravo = MySQL_Fetch_Array (MySQL_Query ("SELECT `ID` FROM `pravo_text` WHERE `text` LIKE '$text'"))) 
		return 0;
	return $pravo['ID'];
}
function SeberPravo ($id_user, $text) {
	$id = Pravo_TextToID ($text);
	if ($id > 0) {
		if (MySQL_Query ("DELETE FROM `prava` WHERE `ID_users` = '$id_user' AND `ID_pravo_text` = '$id'"))
			return 1;
	}
	return 0;
}
function PridejPravo ($id_user, $text) {
	$id = Pravo_TextToID ($text);
	if ($id > 0) {
		if (MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `prava` WHERE `ID_users` = '$id_user' AND `ID_pravo_text` = '$id'")) > 0) {
			return 1;
		}		
		if (MySQL_Query ("INSERT INTO `prava` (`ID`, `ID_users`, `ID_pravo_text`)
												VALUES ('', '$id_user', '$id')"))
			return 1;
	}
	return 0;
}
?>