<?php
function smaz_skupinu ($id) {
	MySQL_Query("DELETE FROM `uziv_skupiny` WHERE `ID` = '$id'");
	MySQL_Query ("DELETE FROM `prava_skupiny` WHERE `ID_uziv_skupiny` = '$id'");
}
function smaz_ali ($id) {
	MySQL_Query("DELETE FROM `ali` WHERE `ID` = '$id'");
	MySQL_Query ("DELETE FROM `prava_ali` WHERE `ID_ali` = '$id'");
	MySQL_Query ("UPDATE `users` SET `ID_ali_v` = '0' WHERE `ID_ali_v` = '$id'");
	MySQL_Query ("UPDATE `users` SET `ID_ali_t` = '0' WHERE `ID_ali_t` = '$id'");
}
?>