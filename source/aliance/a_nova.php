<?php
function NovaAli () {
	global $user_info;
	
// 	if ($user_info['super'] == 0) {
  if (!MaPrava('aliance_nova')) {
		echo "Pouze administrátoøi mohou zakládat nové aliance. Kontaktujte si je pøes MAštu.<br>";
	} else {
		if ($_POST['na_submit'] == "Založit") {
			if (($_POST['na_spravce_id'] < 1) || ($_POST['na_jmeno'] == "")) {
				echo "Vyplòte všechna pole!<br>";
			} else {
				if (MySQL_Query ("INSERT INTO `ali` (`ID`, `ID_spravce`, `jmeno`, `tajna`)
														VALUES ('', '".$_POST['na_spravce_id']."', '".$_POST['na_jmeno']."', '".$_POST['na_tajna']."')")) 
				{
					echo "Aliance '".$_POST['na_jmeno']."' založena.<br>";	
					$id = mysql_insert_id();
					if (!MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '17', '1');") ||
              !MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '19', '1');") ||
              !MySQL_Query ("INSERT INTO `prava_ali` ( `ID` , `ID_ali` , `ID_pravo` , `typ` ) 
                                          VALUES ('', '$id', '22', '1');")) 
            echo "Chyba pøi nastavování práv aliance.<br />"; 
            else echo "Práva nastavena úspìšnì<br />";
          
          // nastavi spravce i clenem, pokud nema jinou ali
          mysql_query("UPDATE `users` SET `ID_ali_".($_POST['na_tajna'] ? 't' : 'v')."` = '{$id}' WHERE `ID` = '{$_POST['na_spravce_id']}' AND `ID_ali_".($_POST['na_tajna'] ? 't' : 'v')."` = '0'");
				} else { 
					echo "Chyba pøi vytváøení!<br>";
				}
			}
		}
	
		echo '<div class="nova_ali">
		<form ation="main.php" method="post">
		<input type="hidden" name="akce" value="aliance">
		<input type="hidden" name="typ" value="new">
		<table>
		<tr>
			<td>Název:</td>
			<td><input name="na_jmeno"></td>
		</tr>
		<tr>
			<td>Správce:</td>
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
					<option value="0" selected>Veøejná</option>
					<option value="1">Tajná</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="na_submit" value="Založit">
			</td>
		</tr>
		</table>
		</form>
		
		</div>';
	}
}
?>
