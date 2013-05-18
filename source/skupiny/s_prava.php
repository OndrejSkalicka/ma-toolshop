<?php
function uprav_skupinu ($id_skupina, $prava) {
	foreach ($prava as $id_pravo => $typ) {
		if ($typ == -1) {
			anuluj_prava_skupine ($id_skupina, $id_pravo);
		} else {
			uprav_prava_skupine ($id_skupina, $id_pravo, $typ);
		}
	}
}
function uprav_prava_skupine ($id_skupina, $id_pravo, $typ) { //$typ : 0 - deny, 1 - grant
	if ($id_pravo > 0) {
		if (MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `prava_skupiny` WHERE `ID_uziv_skupiny` = '$id_skupina' AND `ID_pravo` = '$id_pravo'")) > 0) {
			MySQL_Query ("UPDATE `prava_skupiny` SET `typ` = '$typ' WHERE `ID_uziv_skupiny` = '$id_skupina' AND `ID_pravo` = '$id_pravo'");
			return 1;
		}		
		if (MySQL_Query ("INSERT INTO `prava_skupiny` (`ID`, `ID_uziv_skupiny`, `ID_pravo`, `typ`)
												VALUES ('', '$id_skupina', '$id_pravo', '$typ')"))
			return 1;
	}
	return 0;
}
function anuluj_prava_skupine ($id_skupina, $id_pravo) {
	if ($id_pravo > 0) {
		if (MySQL_Query ("DELETE FROM `prava_skupiny` WHERE `ID_uziv_skupiny` = '$id_skupina' AND `ID_pravo` = '$id_pravo'"))
			return 1;
	}
	return 0;
}
function uprav_ali ($id_ali, $prava) {
	foreach ($prava as $id_pravo => $typ) {
		if ($typ == -1) {
			anuluj_prava_ali ($id_ali, $id_pravo);
		} else {
			uprav_prava_ali ($id_ali, $id_pravo, $typ);
		}
	}
}
function uprav_prava_ali ($id_ali, $id_pravo, $typ) { //$typ : 0 - deny, 1 - grant
	if ($id_pravo > 0) {
		if (MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `prava_ali` WHERE `ID_ali` = '$id_ali' AND `ID_pravo` = '$id_pravo'")) > 0) {
			MySQL_Query ("UPDATE `prava_ali` SET `typ` = '$typ' WHERE `ID_ali` = '$id_ali' AND `ID_pravo` = '$id_pravo'");
			return 1;
		}		
		if (MySQL_Query ("INSERT INTO `prava_ali` (`ID`, `ID_ali`, `ID_pravo`, `typ`)
												VALUES ('', '$id_ali', '$id_pravo', '$typ')"))
			return 1;
	}
	return 0;
}
function anuluj_prava_ali ($id_ali, $id_pravo) {
	if ($id_pravo > 0) {
		if (MySQL_Query ("DELETE FROM `prava_ali` WHERE `ID_ali` = '$id_ali' AND `ID_pravo` = '$id_pravo'"))
			return 1;
	}
	return 0;
}
?>