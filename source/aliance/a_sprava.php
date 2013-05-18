<?php
function Vypis_Spravovanych_Ali () {
	global $user_info;
	$ali_db = MySQL_Query ("SELECT * FROM `ali` WHERE `ID_spravce` = '".$user_info['ID']."' ORDER BY `jmeno`");
	switch ($_GET['subakce']) {
		case "vyhod":
			if (MySQL_Query ("UPDATE `users` SET `ID_ali_".($_GET['t'] == 0 ? 'v' : 't')."` = '0' WHERE `ID` = '".$_GET['u_id']."'")) {
				echo "U�ivatel byl vyhozen z aliance.<br>";
			} else {
				echo "Chyba p�i vyhazov�n� z aliance!<br>";
			}
		break;
		case "prijmi":
			PrijmiCekatele($_GET['z_id']);
		break;
		case "zamitni":
			ZamitniCekatele($_GET['z_id']);
		break;
	}
	
	if (MySQL_Num_Rows ($ali_db) < 1) {
		echo "Nespravujete ��dn� aliance.<br>";
	} else {
		echo '<div class="levy">
		V�mi spravovan� aliance:<br><br>';
		while ($ali = MySQL_Fetch_Array ($ali_db)) {
			$pocet = MySQL_Fetch_Array (MySQL_Query ("SELECT COUNT(*) FROM `zadatele_ali` WHERE `ID_ali` = '{$ali['ID']}'"));
			echo '<a href="main.php?akce=aliance&amp;typ=sprava&amp;a_id='.$ali['ID'].'" class="other">'.$ali['jmeno'].($pocet[0] > 0 ? " (+ {$pocet[0]})" : "" )."</a><br>\n";
		}
		echo '</div> <!-- levy -->
		<div class="pravy">';
		if ($ali = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `ali` WHERE `ID` = '".$_GET['a_id']."'"))) {
			echo '<table class="ali_sprava">
			<tr>
				<td>N�zev:</td>
				<td>'.$ali['jmeno'].'</td>
			</tr>
			<tr>
				<td>Typ:</td>
				<td>'.($ali['tajna'] == 1 ? "tajn�" : "ve�ejn�").'</td>
			</tr>
			<tr>
				<td>�lenov�:</td>
				<td>';
			$clenove = MySQL_Query ("SELECT * FROM `users` WHERE `ID_ali_v` = '".$ali['ID']."' OR `ID_ali_t` = '".$ali['ID']."' ORDER BY `users`.`regent` ASC");
			if (MySQL_Num_Rows ($clenove) == 0) {
				echo "��dn�";
			}
			while ($clen = MySQL_Fetch_Array ($clenove)) {
				echo $clen['regent']." (".$clen['login'].') <a href="main.php?akce=aliance&amp;typ=sprava&amp;a_id='.$ali['ID'].'&amp;u_id='.$clen['ID'].'&amp;subakce=vyhod&amp;t='.$ali['tajna'].'" class="other" onClick=\'return window.confirm("Opravdu chcete vyhodit u�ivatele \"'.$clen['regent'].'\"?")\'>VYHODIT</a><br>';
			}				
			echo '</td>
			</tr>
			<tr>
				<td>�ekatel�:</td>
				<td>';
			$zadatele_db = MySQL_Query ("SELECT `zadatele_ali`.*, `users`.`regent`, `users`.`login` FROM `zadatele_ali` 
													INNER JOIN `users` ON `users`.`ID` = `zadatele_ali`.`ID_users`
													WHERE `zadatele_ali`.`ID_ali` = '".$ali['ID']."'
													ORDER BY `users`.`regent` ASC");
			if (MySQL_Num_Rows ($zadatele_db) == 0) {
				echo "��dn�";
			}
			while ($zadatel = MySQL_Fetch_Array ($zadatele_db)) {
				echo $zadatel['regent']." (".$zadatel['login'].') <a href="main.php?akce=aliance&amp;typ=sprava&amp;a_id='.$ali['ID'].'&amp;z_id='.$zadatel['ID'].'&amp;subakce=prijmi&amp;t='.$ali['tajna'].'" class="other" onClick=\'return window.confirm("Opravdu chcete p�ijmout u�ivatele \"'.$zadatel['regent'].'\"?")\'>P�IJMOUT</a> || <a href="main.php?akce=aliance&amp;typ=sprava&amp;a_id='.$ali['ID'].'&amp;z_id='.$zadatel['ID'].'&amp;subakce=zamitni&amp;t='.$ali['tajna'].'" class="other" onClick=\'return window.confirm("Opravdu chcete zam�tnout u�ivatele \"'.$zadatel['regent'].'\"?")\'>ZAM�TNOUT</a><br>';
			}
			echo' </td>
			</tr>
			</table>
			';
		} else {
			echo "Vyberte alianci.<br>";
		}		
		echo "</div><!-- pravy -->\n";
		
	}
}
function PrijmiCekatele ($z_id) {
	global $user_info;
	if (!$zadost = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `zadatele_ali` WHERE `ID` = '$z_id'"))) {
		echo "Nab�dka ji� neplat�!<br>";
		return 0;
	}
	/* KONTROLA JESTLI JE UZIVATEL SKUTECNE SPRAVCE! */
	if ($spravce = MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `ali` WHERE `ID_spravce` = '".$user_info['ID']."' AND `ID` = '".$zadost['ID_ali']."'")) == 0) {
		echo "Nejste spr�vcem t�to ali!<br>";
		return 0;
	}
	MySQL_Query ("DELETE FROM `zadatele_ali` WHERE `ID` = '$z_id'");
	MySQL_Query ("UPDATE `users` SET `ID_ali_".($_GET['t'] == 0 ? "v" : "t")."` = '".$zadost['ID_ali']."' WHERE `ID` = '".$zadost['ID_users']."'");
	
	echo "U�ivatel p�ijat.<br>";
}

function ZamitniCekatele ($z_id) {
	global $user_info;
	if (!$zadost = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `zadatele_ali` WHERE `ID` = '$z_id'"))) {
		echo "Nab�dka ji� neplat�!<br>";
		return 0;
	}	
	/* KONTROLA JESTLI JE UZIVATEL SKUTECNE SPRAVCE! */
	if ($spravce = MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `ali` WHERE `ID_spravce` = '".$user_info['ID']."' AND `ID` = '".$zadost['ID_ali']."'")) == 0) {
		echo "Nejste spr�vcem t�to ali!<br>";
		return 0;
	}
	MySQL_Query ("DELETE FROM `zadatele_ali` WHERE `ID` = '$z_id'");
	
	echo "U�ivatel zam�tnut.<br>";
}
?>