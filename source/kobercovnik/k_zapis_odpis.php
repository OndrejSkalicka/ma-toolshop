<?php

function ZapisTepich ($users_id, $tepich_id, $poradi) {
	if (MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `koberce_users` WHERE `ID_koberce` = '$tepich_id' AND `ID_users` = '$users_id' AND `poradi` = '$poradi'"))) {
		echo "Ji� jsi zaps�n!<br>";
		return 0;
	}
	if (MySQL_Query ("INSERT INTO `koberce_users` ( `ID` , `ID_users` , `ID_koberce` , `poradi` ) 
											VALUES ('', '$users_id', '$tepich_id', '$poradi');")) 
	{
		echo "Zaps�no!<br><br>";
	} else {
		echo "Nastala chyba p�i zapisov�n�!<br><br>";
	}
}
function OdepisTepich ($users_id, $tepich_id, $poradi) {
	if (MySQL_Query ("DELETE FROM `koberce_users` WHERE `ID_users` = '$users_id' AND `ID_koberce` = '$tepich_id' AND `poradi` = '$poradi'")) {
		echo "Odeps�no!<br><br>";
	} else {
		echo "Nastala chyba p�i odepisov�n�!<br><br>";
	}
	return 1;
}
?>