<?php
function Vypis_Vlasni_Ali () {
	global $user_info;
	
	if ($_GET['subakce'] == "vystoupit") {
		if (MySQL_Query ("UPDATE `users` SET `ID_ali_".($_GET['t'] == 0 ? "v" : "t")."` = '0' WHERE `ID` = '".$user_info['ID']."'")) {
			echo "Vystoupil jsi.<br>";
			$user_info["ID_ali_".($_GET['t'] == 0 ? "v" : "t")] = 0;
		} else {
			echo "Chyba pøi vystupování z aliance!<br>";
		}	
	}
	
	if ($user_info['ID_ali_v'] + $user_info['ID_ali_v'] == 0) {
		echo "Nejste èlenem žádné aliance.<br>";
	}
	
	if ($user_info['ID_ali_v'] > 0) {
		echo "<strong>Veøejná ali: ";
		VypisTabulkuAli ($user_info['ID_ali_v'], 0);
	}
	if ($user_info['ID_ali_t'] > 0) {
		echo "<strong>Tajná ali: ";
		VypisTabulkuAli ($user_info['ID_ali_t'], 1);
	}
}
function VypisTabulkuAli ($id, $t) {
	if (!$ali = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `ali` WHERE `ali`.`ID` = '$id'"))) {
		echo "Neexistující ali!<br>";
	} else {
		echo $ali['jmeno']."</strong><br><br>";
		$clenove_db = MySQL_Query ("SELECT * FROM `users` WHERE `ID_ali_v` = '$id' OR `ID_ali_t` = '$id' ORDER BY `login`");
		$i = 0;
		while ($clenove = MySQL_Fetch_Array ($clenove_db)) {
			$i ++;
			echo "$i. ".$clenove['regent']." (".$clenove['login'].")<br>\n";
		}
		echo '<br><a href="main.php?akce=aliance&amp;typ=vlastni&amp;subakce=vystoupit&amp;t='.$t.'" class="other" onClick=\'return window.confirm("Opravdu chcete vystoupit z aliance \"'.$ali['jmeno'].'\"?")\'>VYSTOUPIT</a><br><br>';
	}
}
?>