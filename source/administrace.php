<?php
/* overeni uzivatele */
require_once ("fce.php");
require_once ("prava_fce.php");
if ((!CheckLogin ()) || !MaPrava ("ucty")) {
	LogOut();
}
/* ------ */
OverWritePOSTGET('typ');
OverWritePOSTGET('zmen_id');
?>
	<div id="admin_left">
		<br>
		<?php
			if ($_GET['typ'] == "zmen_ciziho_usera") {
				if (!get_magic_quotes_gpc ()) {
					$_POST['ch_popis'] = AddSlashes ($_POST['ch_popis']);
				}
				$err = 0;
				if ($_POST['ch_pwd'] != $_POST['ch_pwd2']) {
					echo "Hesla se neshodují.<br>";
					$err = 1;
				}
				if ($err == 0) {
					$update = "UPDATE `users` SET 
								`ID_skupina` = '".$_POST['ch_id_skupina']."',
								`login` = '".$_POST['ch_login']."',
								`regent` = '".$_POST['ch_regent']."',
								`provi` = '".$_POST['ch_provi']."',
								`popis` = '".$_POST['ch_popis']."', 
								`overen` = '".($_POST['ch_overen'] == "on" ? "1" : "0")."', 
								`super` = '".($_POST['ch_super'] == "on" ? "1" : "0")."',
								`ID_ali_v` = '".$_POST['ch_ali_v']."',
								`ID_ali_t` = '".$_POST['ch_ali_t']."',
								`icq` = '".(int)$_POST['ch_icq']."',
								`hlidka_pwr_abs` = '".$_POST['ch_hlidka_abs']."',
								`hlidka_pwr_rel` = '".$_POST['ch_hlidka_rel']."',
								`hlidka_pwr_need_both` = '".($_POST['ch_hlidka_both'] == "on" ? "1" : "0")."',
								`hlidka_mail` = '".$_POST['ch_hlidka_mail']."',
								`hlidka_phone` = '".$_POST['ch_hlidka_phone']."',
								`hlidka_od` = '".min(23,max(0,(int)$_POST['ch_hlidka_od']))."',
								`hlidka_do` = '".min(23,max(0,(int)$_POST['ch_hlidka_do']))."',
                `vzdy_prozvonit` = '".($_POST['ch_vzdy_prozvonit'] == "on" ? "1" : "0")."',
								`prozvani` = '".($_POST['ch_prozvani'] == "on" ? "1" : "0")."',
								`custom_hlidka_msg` = '".$_POST['ch_custom_hlidka_msg']."'";
					
					if ($_POST['ch_pwd'] != "") 
						$update .= ", `heslo` = '".md5($_POST['ch_pwd'])."' ";
					$update .= "WHERE `ID` = '".$_POST['zmen_id']."'";
					/* obecne zmeny */
					if (MySQL_Query ($update)) {
						echo "Údaje zmìnìny.<br>";
						if ($_POST['ch_pwd'] != "" && $_POST['zmen_id'] == $user_info['ID']) Prihlas ($user_info['login'], md5($_POST['ch_pwd']));
						NactiUdajeOUserovi();
					} else {
						echo "Nastala závažná chyba.<br>";
					}
					/* prava */
					$prava = MySQL_Query ("SELECT * FROM `pravo_text`");
					$uspech = 1;
					while ($pravo = MySQL_Fetch_Array ($prava)) {
						if ($_POST['ch_prava'][$pravo['ID']] == "on") {
							$uspech *= PridejPravo($_POST['zmen_id'],$pravo['text']);
						} else {
							$uspech *= SeberPravo($_POST['zmen_id'],$pravo['text']);
						}
					}
					if ($uspech) {
						echo "Práva nastavena úspìšnì!<br>";
					} else {
						echo "CHYBA PØI NASTAVOVÁNÍ PRÁV!<br>";
					}
					/* spravce ali */
					if (($_POST['ch_ali_v_spravce'] == "on") && ($_POST['ch_ali_v'] > 0)) {
						if (MySQL_Query ("UPDATE `ali` SET `ID_spravce` = '".$_POST['zmen_id']."' WHERE `ID` = '".$_POST['ch_ali_v']."'") ) {
							echo "Správce veøejné aliance zmìnìn.<br>";
						} else {
							echo "Nastala chyba pøi zmìnì správce veøejné aliance.<br>";
						}
					}
					if (($_POST['ch_ali_t_spravce'] == "on") && ($_POST['ch_ali_t'] > 0)) {
						if (MySQL_Query ("UPDATE `ali` SET `ID_spravce` = '".$_POST['zmen_id']."' WHERE `ID` = '".$_POST['ch_ali_t']."'") ) {
							echo "Správce tajné aliance zmìnìn.<br>";
						} else {
							echo "Nastala chyba pøi zmìnì správce tajné aliance.<br>";
						}
					}
				}
			}
			if ($_POST['delete'] == "Odstranit") {
				if (MySQL_Query ("DELETE FROM `users` WHERE `ID` = '".$_POST['zmen_id']."'")) {
					echo "Smazán.<br>";
					$_GET['zmen_id'] = "";
					$_GET['typ'] = "";
				} else {
					echo "Chyba!<br>";
				}
			} 
			$uzivatele_db = MySQL_Query ("SELECT * FROM `users` ORDER BY `regent`");
			while ($uzivatele = MySQL_Fetch_Array ($uzivatele_db)) {
				$btn = "off.jpg";
				if ($uzivatele['overen']) $btn = "on.jpg";
				if ($uzivatele['super']) $btn = "sup.jpg";
				echo '<img src="./img/'.$btn.'" alt="" width="12" height="12"> <a href="main.php?akce=administrace&amp;zmen_id='.$uzivatele['ID'].'&amp;typ=nahled">'.$uzivatele['regent'].', '.$uzivatele['provi'].' ('.$uzivatele['login'].')</a><br>
				';
			}
		?>
	</div>
	<div id="admin_right">
		<?php
			
			
			if ($_GET['zmen_id'] != "") {
				$detail_uzivatele_db = MySQL_Query ("SELECT * FROM `users` WHERE `ID` = '".$_GET['zmen_id']."'");
				if ($detail_uzivatele = MySQL_Fetch_Array ($detail_uzivatele_db)) {
					echo '<form action="main.php" method="post" name="formular">
					<input type="hidden" name="typ" value="zmen_ciziho_usera">
					<input type="hidden" name="akce" value="administrace">
					<input type="hidden" name="zmen_id" value="'.$_GET['zmen_id'].'">
					<table>
					<tr>
						<td>
							<input type="submit" value="Ulož zmìny"><br><br>
						</td>
						<td>
							<input type="submit" name="delete" value="Odstranit" onClick=\'return window.confirm("Opravdu chcete smazat?")\'><br><br>
						</td>
					</tr>
					<tr>
						<td>
							Login:
						</td>
						<td>
							<input name="ch_login" value="'.$detail_uzivatele['login'].'">
						</td>
					</tr>
					<tr>
						<td>
							Regent:
						</td>
						<td>
							<input name="ch_regent" value="'.htmlspecialchars($detail_uzivatele['regent']).'">
						</td>
					</tr>
					<tr>
						<td>
							Provincie:
						</td>
						<td>
							<input name="ch_provi" value="'.htmlspecialchars($detail_uzivatele['provi']).'">
						</td>
					</tr>
					<tr>
						<td>
							Popis:
						</td>
						<td>
							<input name="ch_popis" value="'.htmlspecialchars($detail_uzivatele['popis']).'">
						</td>
					</tr>
					<tr>
						<td>
							Zmìna hesla:
						</td>
						<td>
							<input name="ch_pwd" type="password">
						</td>
					</tr>
					<tr>
						<td>
							Heslo znovu:
						</td>
						<td>
							<input name="ch_pwd2" type="password">
						</td>
					</tr>
					<tr>
						<td>
							Ovìøen:
						</td>
						<td>
							<input name="ch_overen" type="checkbox"'.($detail_uzivatele['overen'] == 1 ? " checked" : "").' id="ch_overen"> (funguje pouze jako flag, pro <br>prihlaseni je treba prava na LOGIN)
						</td>
					</tr>
					<tr>
						<td>
							Super user:
						</td>
						<td>
							<input name="ch_super" type="checkbox"'.($detail_uzivatele['super'] == 1 ? " checked" : "").' id="ch_super_user"> (funguje pouze jako flag, pro <br>prihlaseni je treba prava na LOGIN)
						</td>
					</tr>
					<tr>
						<td>
							Práva:
						</td>
						<td>
						';
						$prava = MySQL_Query ('SELECT * FROM `pravo_text` ORDER BY `text`');
						$pro_js_user = 'document.formular.ch_overen.checked = true; document.formular.ch_super_user.checked = false;';
						$pro_js_admin = 'document.formular.ch_overen.checked = true; document.formular.ch_super_user.checked = true;';
						$pro_js_none = 'document.formular.ch_overen.checked = false; document.formular.ch_super_user.checked = false;';
						while ($pravo = MySQL_Fetch_Array ($prava)) {
							echo '<input type="checkbox" name="ch_prava['.$pravo['ID'].']" id="ch_prava_'.$pravo['ID'].'"'.(MaPrava ($pravo['text'], $_REQUEST['zmen_id'], 0) ? " checked" : "").'><label for="ch_prava_'.$pravo['ID'].'">'.$pravo['text']."</label><br>\n";
							$pro_js_admin .= 'document.formular.ch_prava_'.$pravo['ID'].'.checked = true;';
							$pro_js_none .= 'document.formular.ch_prava_'.$pravo['ID'].'.checked = false;';
							if ($pravo['pro_usery']) {
								$pro_js_user .= 'document.formular.ch_prava_'.$pravo['ID'].'.checked = true;';
							} else {
								$pro_js_user .= 'document.formular.ch_prava_'.$pravo['ID'].'.checked = false;';
							}
						}
						echo '
						</td><td>
						<a href="#" onClick="'.$pro_js_user.'" class="other">Uživatel</a><br>
						<a href="#" onClick="'.$pro_js_admin.'" class="other">Admin</a><br>
						<a href="#" onClick="'.$pro_js_none.'" class="other">None</a><br>
						<br>
						</td>
					</tr>
					<tr>
						<td>
							Analýz aukce:
						</td>
						<td>
							'.$detail_uzivatele['aukce_count'].'
						</td>
					</tr>
					<tr>
						<td>
							Simulací boje:
						</td>
						<td>
							'.$detail_uzivatele['simul_count'].'
						</td>
					</tr>
					<tr>
						<td>
							Chat:
						</td>
						<td>
							'.$detail_uzivatele['chat_count'].'
						</td>
					</tr>
					<tr>
						<td>
							Veøejná ali:
						</td>
						<td>
							<select name="ch_ali_v">
							<option value="0"'.($detail_uzivatele['ID_ali_v'] == 0 ? " selected" : "").'>Žádná aliance</option>';
						$aliance_db = MySQL_Query ("SELECT * FROM `ali` WHERE `tajna` = '0' ORDER BY `jmeno`");
						while ($aliance = MySQL_Fetch_Array ($aliance_db)) {
							echo '<option value="'.$aliance['ID'].'"'.($detail_uzivatele['ID_ali_v'] == $aliance['ID'] ? " selected" : "").'>'.$aliance['jmeno'].'</option>';
						}
						echo '
							</select><br>
							<input type="checkbox" name="ch_ali_v_spravce" id="ch_ali_v_spravce"><label for="ch_ali_v_spravce">Správce</label>
						</td>
					</tr>
					<tr>
						<td>
							Tajná ali:
						</td>
						<td>
							<select name="ch_ali_t">
							<option value="0"'.($detail_uzivatele['ID_ali_t'] == 0 ? " selected" : "").'>Žádná aliance</option>';
						$aliance_db = MySQL_Query ("SELECT * FROM `ali` WHERE `tajna` = '1' ORDER BY `jmeno`");
						while ($aliance = MySQL_Fetch_Array ($aliance_db)) {
							echo '<option value="'.$aliance['ID'].'"'.($detail_uzivatele['ID_ali_t'] == $aliance['ID'] ? " selected" : "").'>'.$aliance['jmeno'].'</option>';
						}
						echo '
							</select><br>
							<input type="checkbox" name="ch_ali_t_spravce" id="ch_ali_t_spravce"><label for="ch_ali_t_spravce">Správce</label>
						</td>
					</tr>
					<tr>
						<td>Spravuje:</td>
						<td>';
					$aliance_db = MySQL_Query ("SELECT * FROM `ali` WHERE `ID_spravce` = '".$detail_uzivatele['ID']."'");
					while ($aliance = MySQL_Fetch_Array ($aliance_db)) {
						echo "'".$aliance['jmeno']."' (".($aliance['tajna'] == 1 ? "tajná" : "veøejná").")<br>\n";
					}
					echo '</td>
					</tr>
					<tr>
						<td>Skupina (práva):</td>
						<td><select name="ch_id_skupina">
							<option value="0"'.($detail_uzivatele['ID_ali_t'] == 0 ? " selected" : "").'>Žádná skupina</option>';
						$skupiny_db = MySQL_Query ("SELECT * FROM `uziv_skupiny` ORDER BY `nazev`");
						while ($skupina = MySQL_Fetch_Array ($skupiny_db)) {
							echo '<option value="'.$skupina['ID'].'"'.($detail_uzivatele['ID_skupina'] == $skupina['ID'] ? " selected" : "").'>'.$skupina['nazev'].'</option>';
						}
						
						
					echo '</select></td>
					</tr>
					<tr>
						<td>Pwr hranice abs:</td>
						<td><input name="ch_hlidka_abs" value="'.$detail_uzivatele['hlidka_pwr_abs'].'" style="text-align: right;"></td>
					</tr>
					<tr>
						<td>Pwr hranice rel:</td>
						<td><input name="ch_hlidka_rel" value="'.$detail_uzivatele['hlidka_pwr_rel'].'" style="text-align: right;"> %</td>
					</tr>
					<tr>
						<td>obe zaroven:</td>
						<td><input type="checkbox" name="ch_hlidka_both"'.($detail_uzivatele['hlidka_pwr_need_both'] == 1 ? ' checked' : '').'></td>
					</tr>
					<tr>
						<td>Mail:</td>
						<td><input name="ch_hlidka_mail" value="'.htmlspecialchars($detail_uzivatele['hlidka_mail']).'" style="text-align: right;"></td>
					</tr>
					<tr>
						<td>Telefon:</td>
						<td><input name="ch_hlidka_phone" value="'.htmlspecialchars($detail_uzivatele['hlidka_phone']).'" style="text-align: right;"></td>
					</tr>
					<tr>
						<td>ICQ:</td>
						<td><input name="ch_icq" value="'.htmlspecialchars($detail_uzivatele['icq']).'" style="text-align: right;"></td>
					</tr>
          <tr>
            <td>VŽDY prozvánìt:</td>
            <td><input type="checkbox" name="ch_vzdy_prozvonit"'.($detail_uzivatele['vzdy_prozvonit'] == 1 ? ' checked' : '').'></td>
          </tr>
					<tr>
						<td>Sám prozvání:</td>
						<td><input type="checkbox" name="ch_prozvani"'.($detail_uzivatele['prozvani'] == 1 ? ' checked' : '').'></td>
					</tr>
					<tr>
						<td>Vlastní zpráva:</td>
						<td><input name="ch_custom_hlidka_msg" value="'.$detail_uzivatele['custom_hlidka_msg'].'"></td>
					</tr>
					<tr>
						<td>Hlídka od:</td>
						<td><input name="ch_hlidka_od" value="'.$detail_uzivatele['hlidka_od'].'"></td>
					</tr>
					<tr>
						<td>Hlídka do:</td>
						<td><input name="ch_hlidka_do" value="'.$detail_uzivatele['hlidka_do'].'"></td>
					</tr>
					<tr>
						<td>
							<br><input type="submit" value="Ulož zmìny">
						</td>
						<td>
							<br><input type="submit" name="delete" value="Odstranit" onClick=\'return window.confirm("Opravdu chcete smazat?")\'>
						</td>
					</tr>
					</table>
					</form>';
				} else {
					echo "Nastala závažná chyba!<br>";
				}
			}
		?>
	</div>
<div class="clear">&nbsp;</div>
