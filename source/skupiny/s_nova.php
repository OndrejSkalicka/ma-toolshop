<?php
function nova_skupina ($nazev) {
	return MySQL_Query ("INSERT INTO `uziv_skupiny` (`ID`,`nazev`)
															VALUES ('', '$nazev')");
}

?>