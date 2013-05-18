<?php
function ma_skupina_prava ($id_skupina, $id_prava) {
	if (!$ma = MySQL_Fetch_Array(MySQL_Query ("SELECT * FROM `prava_skupiny` WHERE `ID_uziv_skupiny` = '$id_skupina' AND `ID_pravo` = '$id_prava'"))) {
		return -1; //neni upravovano
	}
	
	return $ma['typ']; //1 - grant, 0 - deny
}
function ma_ali_prava ($id_ali, $id_prava) {
	if (!$ma = MySQL_Fetch_Array(MySQL_Query ("SELECT * FROM `prava_ali` WHERE `ID_ali` = '$id_ali' AND `ID_pravo` = '$id_prava'"))) {
		return -1; //neni upravovano
	}
	
	return $ma['typ']; //1 - grant, 0 - deny
}
?>