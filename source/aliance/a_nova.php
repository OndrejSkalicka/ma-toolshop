<?php
function NovaAli () {
	global $user_info;
	
// 	if ($user_info['super'] == 0) {
  if (!MaPrava('aliance_nova')) {
		echo "Pouze administr�to�i mohou zakl�dat nov� aliance. Kontaktujte si je p�es MA�tu.<br>";
	} else {
		if ($_POST['na_submit'] == "Zalo�it") {
			if (($_POST['na_spravce_id'] < 1) || ($_POST['na_jmeno'] == "")) {
				echo "Vypl�te v�echna pole!<br>";
			} else {
				if (MySQL_Query ("INSERT INTO `ali` (`ID`, `ID_spravce`, `jmeno`, `tajna`)
														VALUES ('', '".$_POST['na_spravce_id']."', '".$_POST['na_jmeno']."', '".$_POST['na_tajna']."')")) 
				{
					echo "Aliance '".$_POST['na_jmeno']."' zalo�ena.<br>";	
					$id = mysql_insert_id();
					if (!MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '17', '1');") ||
              !MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '19', '1');") ||
              !MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '22', '1');")) 
            echo "Chyba p�i nastavov�n� pr�v aliance.<br />"; 
            else echo "Pr�va nastavena �sp�n�<br />";
          
          // nastavi spravce i clenem, pokud nema jinou ali
          mysql_query("UPDATE `users` SET `ID_ali_".($_POST['na_tajna'] ? 't' : 'v')."` = '{$id}' WHERE `ID` = '{$_POST['na_spravce_id']}' AND `ID_ali_".($_POST['na_tajna'] ? 't' : 'v')."` = '0'");
				} else { 
					echo "Chyba p�i vytv��en�!<br>";
				}
			}
		}
	
		echo '<div class="nova_ali">
		<form ation="main.php" method="post">
		<input type="hidden" name="akce" value="aliance">
		<input type="hidden" name="typ" value="new">
		<table>
		<tr>
			<td>N�zev:</td>
			<td><input name="na_jmeno"></td>
		</tr>
		<tr>
			<td>Spr�vce:</td>
			<td>
				<select name="na_spravce_id">';
				$uzivatele_db = MySQL_Query ("SELECT * FROM `users` WHERE `overen` = '1' ORDER BY `regent`");
				while ($uzivatele = MySQL_Fetch_Array ($uzivatele_db)) {
					echo '<option value="'.$uzivatele['ID'].'"'.($uzivatele['ID'] == $user_info['ID'] ? ' selected' : '').'>'.$uzivatele['regent'].'</option>'."\n";
				}
				echo '</select>
			</td>
		</tr>
		<tr>
			<td>Typ:</td>
			<td>
				<select name="na_tajna" size="2">
					<option value="0" selected>Ve�ejn�</option>
					<option value="1">Tajn�</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="na_submit" value="Zalo�it">
			</td>
		</tr>
		</table>
		</form>
		
		</div>';
	}
}
?>
