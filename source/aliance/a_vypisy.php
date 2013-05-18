<?php
function SeznamVerejnychAli () {
	global $user_info;
	$ali_db = MySQL_Query ("SELECT * FROM `ali` WHERE `tajna` = '0' ORDER BY `jmeno`");
	
	if (MySQL_Num_Rows ($ali_db) < 1) {
		echo "��dn� ve�ejn� aliance nebyly zalo�eny.<br>";
	} else {
		echo "<tr><td colspan=5><strong><center>Ve�ejn�</center></strong></td></tr>";
		while ($ali = MySQL_Fetch_Array ($ali_db)) {
			$pocet = MySQL_Fetch_Row (MySQL_Query("SELECT COUNT(*) FROM `users` WHERE `ID_ali_v` = '".$ali['ID']."'"));
			echo '<tr class="prvek_seznamu">
				<td>'.$ali['jmeno'].'</td>
				<td class="right">'.$pocet[0].'</td>
				<td>'.((ZkontrolujZadosti($ali['ID']) && $user_info['ID_ali_v'] == 0) ? '<a href="main.php?akce=aliance&amp;a_akce=poslat_zadost&amp;id_ali='.$ali['ID'].'" class="other">VSTOUPIT</a>' : "X").'</td>
			</tr>';
		}
	}
}
function SeznamTajnychAli () {
	global $user_info;
	$ali_db = MySQL_Query ("SELECT * FROM `ali` WHERE `tajna` = '1' ORDER BY `jmeno`");
	
	if (MySQL_Num_Rows ($ali_db) < 1) {
		echo "��dn� ve�ejn� aliance nebyly zalo�eny.<br>";
	} else {
		echo "<tr><td colspan=5><strong><center>Tajn�</center></strong></td></tr>";
		while ($ali = MySQL_Fetch_Array ($ali_db)) {
			$pocet = MySQL_Fetch_Row (MySQL_Query("SELECT COUNT(*) FROM `users` WHERE `ID_ali_t` = '".$ali['ID']."'"));
			echo '<tr class="prvek_seznamu">
				<td>'.$ali['jmeno'].'</td>
				<td class="right">'.(MaPrava('aliance_view_all') ? $pocet[0] : '???').'</td>
				<td>'.((ZkontrolujZadosti($ali['ID']) && $user_info['ID_ali_t'] == 0) ? '<a href="main.php?akce=aliance&amp;a_akce=poslat_zadost&amp;id_ali='.$ali['ID'].'" class="other">VSTOUPIT</a>' : "X").'</td>
			</tr>';
		}
	}
}
function PridejZadost () {
	global $user_info;
	
	if (ZkontrolujZadosti ($_GET['id_ali'])) {
		if (MySQL_Query ("INSERT INTO `zadatele_ali` (`ID`, `ID_ali`, `ID_users`)
															VALUES ('', '".$_GET['id_ali']."', '".$user_info['ID']."')")) {
			echo "��dost o vstup do aliance byla zasl�na.<br>";
		} else {
			echo "Chyba p�i zas�l�n� ��dosti o vstup do aliance!<br>";
		}
	} else {
		echo "Do t�to aliance jste ji� o vstup za��dal nebo jste jej�m �lenem.<br>";
	}
}
function ZkontrolujZadosti ($id_ali) {
	global $user_info;
	
	$ret = MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `zadatele_ali` WHERE `ID_ali` = '$id_ali' AND `ID_users` = '".$user_info['ID']."'"));
	$ret += MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `users` WHERE `ID` = '".$user_info['ID']."' AND (`ID_ali_v` = '$id_ali' OR `ID_ali_t` = '$id_ali')"));
	
	if ($ret == 0) return 1;
	
	return 0;
}
?>
