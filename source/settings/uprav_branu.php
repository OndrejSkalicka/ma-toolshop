<?php 
/* overeni uzivatele */
require_once ("fce.php");
if ((!CheckLogin ()) || !MaPrava("nastaveni")) {
	LogOut();
}
/* ------ */
function UpravBranu ($id_brany) {

	echo '<div id="mini_menu">'."\n";
		$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
		while ($vek = MySQL_Fetch_Array ($veky)) {
			echo '<div class="polozka"><a href="main.php?akce=settings&amp;typ=brany&amp;vek='.$vek['ID'].'"><span'.($vek['ID'] == $_REQUEST['vek'] ? ' class="selected"' : "").'>'.$vek['jmeno']."</span></a></div>\n";
		}
		echo '</div><div class="clear">&nbsp;</div>';

	if ($_POST['step'] == 2) {
		if ($_POST['akce_btn'] == "Upravit") {
			$upravy = $_POST['upravy'];
			$query = "UPDATE `Branky` SET `cislo` = '".$upravy['cislo']."', 
													`strana` = '".$upravy['strana']."', 
													`obranci` = '".$upravy['obranci']."',
													`zobraz_prefix` = '".($upravy['zobraz_prefix'] == "on" ? "1" : "0")."',
													`zamcena` = '".($upravy['zamcena'] == "on" ? "1" : "0")."'
						WHERE `ID` = '".$upravy['ID']."'";
		}
		if ($_POST['akce_btn'] == "Smazat") {
			$query = "DELETE FROM `Branky` WHERE `ID` = '".$_POST['upravy']['ID']."'";
		}
		if (MySQL_Query ($query)) {
			echo "Upraveno/smazáno.<br><br>";
		} else {
			echo "Nastala chyba! '$query'<br><br>";
		}
	}
	
	/* leve menu */
	$brany_db = MySQL_Query ("SELECT `ID`, `cislo`, `strana` FROM `Branky` WHERE `ID_veky` = '".$_REQUEST['vek']."' ORDER BY `cislo`");
	echo '<div id="set_left">';
	$pocet = 0;
	echo '<div class="set_sloupek">'."\n";
	while ($brany = MySQL_Fetch_Array ($brany_db)) {
		$pocet ++;
		echo '	<div class="set_polozka">'.($brany['ID']==$id_brany ? '<span class="selected">' : "").'<a href="main.php?akce=settings&amp;typ=brany&amp;ch_id='.$brany['ID'].'&amp;vek='.$_REQUEST['vek'].'">br'.($brany['cislo']%10)."na ".$brany['strana']."</a>".($brany['ID']==$id_brany? '</span>' : "")."</div>\n";
		if ($pocet == 9) {
			$pocet = 0;
			echo "\n</div>\n".'<div class="set_sloupek">';
		}
	}
	echo "</div>\n</div>\n<div id=\"set_right\">";
	
	
	
	// vypis tabulku
	$brana_db = MySQL_Query ("SELECT * FROM `Branky` WHERE `ID` = '$id_brany'");
	if ($brana = MySQL_Fetch_Array ($brana_db)) {
		echo '
		<form action="main.php" method="post">
		<input type="hidden" name="akce" value="settings">
		<input type="hidden" name="typ" value="brany">
		
		<select name="vek">';
					$veky = MySQL_Query ("SELECT `jmeno`,`ID` FROM `veky` ORDER BY `priorita`");
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['ID'].'">'.$vek['jmeno'].'</option>';
					}
		echo '</select>
			<input type="hidden" name="nova[cislo]" value="'.$brana['cislo'].'">
			<input type="hidden" name="nova[strana]" value="'.$brana['strana'].'">
			<input type="hidden" name="nova[vek_old]" value="'.$brana['ID_veky'].'">
		<input type="submit" name="nova_brana" value="Pøekopíruj do vìku"><br />
    <input type="checkbox" name="prekopiruj_vsechny" id="prekopiruj_vsechny"><label for="prekopiruj_vsechny">Pøekopíruj všechny brány</label>	
		</form>
		<hr>';
		echo '
		<form action="main.php" method="post">
		<input type="hidden" name="akce" value="settings">
		<input type="hidden" name="typ" value="brany">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="ch_id" value="'.$id_brany.'">
		<input type="hidden" name="vek" value="'.$_REQUEST['vek'].'">
		
		<table id="upravy">
		<tr>
			<td>
				ID
			</td>
			<td>
				<input name="upravy[ID]" value="'.$brana['ID'].'" readonly>
			</td>
		</tr>
		<tr>
			<td>
				Èíslo
			</td>
			<td>
				<input name="upravy[cislo]" value="'.$brana['cislo'].'">
			</td>
		</tr>
		<tr>
			<td>
				Strana
			</td>
			<td>
				 <select name="upravy[strana]" size="3">
                <option'.($brana['strana'] == 'D' ? " selected" : "").'>D</option>                
                <option'.($brana['strana'] == 'Z' ? " selected" : "").'>Z</option>
                <option'.($brana['strana'] == 'N' ? " selected" : "").'>N</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Zobraz prefix</td>
			<td><input type=checkbox name="upravy[zobraz_prefix]"'.($brana['zobraz_prefix'] ? " checked" : "").'></td>
		</tr>
		<tr>
			<td>Zamèená</td>
			<td><input type=checkbox name="upravy[zamcena]"'.($brana['zamcena'] ? " checked" : "").'></td>
		</tr>
		<tr>
			<td>
				Obránci
			</td>
			<td>
				<textarea name="upravy[obranci]">'.$brana['obranci'].'</textarea>
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
			<td>
				<input type="submit" value="Upravit" name="akce_btn" class="half_button">
				<input type="submit" value="Smazat" name="akce_btn" onClick=\'return window.confirm("Opravdu chcete smazat?");\' class="half_button">
			</td>
		</tr>
		</table>
		</form>';
	} else {
		echo "Nebyla vybrána žádná existující brána<br>";
	}
	echo "</div>"; 	
	echo '<div class="clear">&nbsp;</div>';
}
?>
