<?php

function ZapisTepich ($users_id, $tepich_id, $poradi) {
	if (MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `koberce_users` WHERE `ID_koberce` = '$tepich_id' AND `ID_users` = '$users_id' AND `poradi` = '$poradi'"))) {
		echo "Již jsi zapsán!<br>";
		return 0;
	}
	if (MySQL_Query ("INSERT INTO `koberce_users` ( `ID` , `ID_users` , `ID_koberce` , `poradi` ) 
											VALUES ('', '$users_id', '$tepich_id', '$poradi');")) 
	{
		echo "Zapsáno!<br><br>";
	} else {
		echo "Nastala chyba pøi zapisování!<br><br>";
	}
}
function OdepisTepich ($users_id, $tepich_id, $poradi) {
	if (MySQL_Query ("DELETE FROM `koberce_users` WHERE `ID_users` = '$users_id' AND `ID_koberce` = '$tepich_id' AND `poradi` = '$poradi'")) {
		echo "Odepsáno!<br><br>";
	} else {
		echo "Nastala chyba pøi odepisování!<br><br>";
	}
	return 1;
}
?>