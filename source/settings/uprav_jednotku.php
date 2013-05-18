<?php 
/* overeni uzivatele */
require_once ("fce.php");
if ((!CheckLogin ()) || !MaPrava("nastaveni")) {
	LogOut();
}
/* ------ */
function UpravJednotku ($id_jednotky, $brankar, $step, $upravy) {
  // minimenu
  echo '<div id="mini_menu">'."\n";
	$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
	while ($vek = MySQL_Fetch_Array ($veky)) {
		echo '<div class="polozka"><a href="main.php?akce=settings&amp;typ='.($brankar == 1 ? "brankari" : "jednotky").'&amp;vek='.$vek['ID'].'"><span'.($vek['ID'] == $_REQUEST['vek'] ? ' class="selected"' : "").'>'.$vek['jmeno']."</span></a></div>\n";
	}
	echo '</div><div class="clear">&nbsp;</div>';


	if ($_POST['step'] == 2) {
		if ($_POST['akce_btn'] == "Upravit") {
			$upravy = $_POST['upravy'];
			$query = "UPDATE `MA_units` SET `jmeno` = '".$upravy['jmeno']."', 
														`druh` = '".$upravy['druh']."', 
														`typ` = '".$upravy['typ']."', 
														`phb` = '".$upravy['phb']."', 
														`ini` = '".$upravy['ini']."', 
														`dmg` = '".$upravy['dmg']."', 
														`brn` = '".$upravy['brn']."', 
														`zvt` = '".$upravy['zvt']."', 
														`pwr` = '".$upravy['pwr']."',
														`barva` = '".$upravy['barva']."',
														`cena_zl` = '".$upravy['cena_zl']."',
														`cena_mn` = '".$upravy['cena_mn']."',
														`cena_lidi` = '".$upravy['cena_ld']."',
														`plat_zl` = '".$upravy['plat_zl']."',
														`plat_mn` = '".$upravy['plat_mn']."',
														`plat_lidi` = '".$upravy['plat_ld']."'";											
			$query .= "	WHERE `ID` = '".$upravy['ID']."' AND `brankar` = '$brankar'";
		}
		if ($_POST['akce_btn'] == "Smazat") {
			$query = "DELETE FROM `MA_units` WHERE `ID` = '".$_POST['upravy']['ID']."'";
		}
		if (MySQL_Query ($query)) {
			echo "Upraveno/smazáno.<br><br>";
		} else {
			echo "Nastala chyba!<br><br>";
		}
	}
	
	/* leve menu */
// 	if ($brankar == 1) {
	$za_vek = " AND `ID_veky` = '".$_REQUEST['vek']."'";
// 	} else {
// 		$za_vek = "";
// 	}

	$query = "SELECT `ID`, `jmeno` FROM `MA_units` WHERE `brankar` = '{$brankar}'{$za_vek} ORDER BY `jmeno`";
	$jednotky_db = MySQL_Query ($query);
	$pocet_na_sloupec = max (1, round (MySQL_Num_Rows ($jednotky_db) / 4) + 2);
	echo '<div id="set_left">';
	$pocet = 0;
	echo '<div class="set_sloupek">'."\n";
	if (MySQL_Num_Rows($jednotky_db) == 0) {
		echo "&nbsp;";
	}
	while ($jednotky = MySQL_Fetch_Array ($jednotky_db)) {
		$pocet ++;
		$typ = $brankar == 1 ? "brankari" : "jednotky";
		echo '	<div class="set_polozka">'.($jednotky['ID']==$id_jednotky? '<span class="selected">' : "").'<a href="main.php?akce=settings&amp;typ='.$typ.'&amp;ch_id='.$jednotky['ID'].'&amp;vek='.$_REQUEST['vek'].'">'.$jednotky['jmeno']."</a>".($jednotky['ID']==$id_jednotky? '</span>' : "")."</div>\n";
		if ($pocet == $pocet_na_sloupec) {
			$pocet = 0;
			echo "\n</div>\n".'<div class="set_sloupek">';
		}
	}
	echo "</div>\n</div>\n<div id=\"set_right\">";
	
	
	
	// vypis tabulku
	$jednotka_db = MySQL_Query ("SELECT * FROM `MA_units` WHERE `brankar` = '$brankar' AND `ID` = '$id_jednotky'");
	if ($jednotka = MySQL_Fetch_Array ($jednotka_db)) {
		echo '
		<form action="main.php" method="post">
		<input type="hidden" name="akce" value="settings">
		<input type="hidden" name="typ" value="nova_jednotka">
		<input type="hidden" name="step" value="2">
		<select name="nova[vek]">';
					$veky = MySQL_Query ("SELECT `jmeno`,`ID` FROM `veky` ORDER BY `priorita`");
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['ID'].'">'.$vek['jmeno'].'</option>';
					}
		echo '</select>
		<select name="nova[brankar]">
			<option value="0"'.($jednotka['brankar'] ? "" : " selected").'>Hráèi</option>
			<option value="1"'.($jednotka['brankar'] ? " selected" : "").'>Brány</option>
		</select>
			<input type="hidden" name="nova[jmeno]" value="'.$jednotka['jmeno'].'">
			<input type="hidden" name="nova[druh]" value="'.$jednotka['druh'].'">
			<input type="hidden" name="nova[typ]" value="'.$jednotka['typ'].'">
			<input type="hidden" name="nova[phb]" value="'.$jednotka['phb'].'">
			<input type="hidden" name="nova[ini]" value="'.$jednotka['ini'].'">
			<input type="hidden" name="nova[dmg]" value="'.$jednotka['dmg'].'">
			<input type="hidden" name="nova[brn]" value="'.$jednotka['brn'].'">
			<input type="hidden" name="nova[zvt]" value="'.$jednotka['zvt'].'">
			<input type="hidden" name="nova[pwr]" value="'.$jednotka['pwr'].'">
			<input type="hidden" name="nova[barva]" value="'.$jednotka['barva'].'">
			<input type="hidden" name="nova[brankar_old]" value="'.$jednotka['brankar'].'">
			<input type="hidden" name="nova[cena_zl]" value="'.$jednotka['cena_zl'].'">
			<input type="hidden" name="nova[cena_mn]" value="'.$jednotka['cena_mn'].'">
			<input type="hidden" name="nova[cena_ld]" value="'.$jednotka['cena_lidi'].'">
			<input type="hidden" name="nova[plat_zl]" value="'.$jednotka['plat_zl'].'">
			<input type="hidden" name="nova[plat_mn]" value="'.$jednotka['plat_mn'].'">
			<input type="hidden" name="nova[plat_ld]" value="'.$jednotka['plat_lidi'].'">
			<input type="hidden" name="nova[vek_old]" value="'.$jednotka['ID_veky'].'">
		<input type="submit" value="Pøekopíruj do vìku"><br />
    <input type="checkbox" name="prekopiruj_vsechny" id="prekopiruj_vsechny"><label for="prekopiruj_vsechny">Pøekopíruj všechny jednotky</label>	
		</form>
		<hr>';
		echo '
		<form action="main.php" method="post">
		<input type="hidden" name="akce" value="settings">
		<input type="hidden" name="typ" value="'.$typ.'">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="ch_id" value="'.$id_jednotky.'">
		<input type="hidden" name="vek" value="'.$_REQUEST['vek'].'">
		
		<table id="upravy">
		<tr>
			<td>
				ID
			</td>
			<td>
				<input name="upravy[ID]" value="'.$jednotka['ID'].'" readonly>
			</td>
		</tr>
		<tr>
			<td>
				jmeno
			</td>
			<td>
				<input name="upravy[jmeno]" value="'.$jednotka['jmeno'].'">
			</td>
		</tr>
		<tr>
			<td>
				druh
			</td>
			<td>
				<select name="upravy[druh]" size="2">
					<option value="P"'.($jednotka['druh'] == P ? " selected" : "").'>Pozemní</option>
					<option value="L"'.($jednotka['druh'] == L ? " selected" : "").'>Letecká</option>
				</select>
				<!-- <input name="upravy[druh]" value="'.$jednotka['druh'].'"> -->
			</td>
		</tr>
		<tr>
			<td>
				typ
			</td>
			<td>
				<select name="upravy[typ]" size="2">
					<option value="B"'.($jednotka['typ'] == 'B' ? " selected" : "").'>Bojová</option>
					<option value="S"'.($jednotka['typ'] == 'S' ? " selected" : "").'>Støelecká</option>
				</select>
				<!-- <input name="upravy[typ]" value="'.$jednotka['typ'].'"> -->
			</td>
		</tr>
		<tr>
      <td>
        barva
      </td>
      <td>
        <select name="upravy[barva]" size="7">
          <option value="N"'.($jednotka['barva'] == 'N' ? " selected" : "").'>Neutrální</option>
          <option value="B"'.($jednotka['barva'] == 'B' ? " selected" : "").'>Bílá</option>
          <option value="C"'.($jednotka['barva'] == 'C' ? " selected" : "").'>Èerná</option>
          <option value="M"'.($jednotka['barva'] == 'M' ? " selected" : "").'>Modrá</option>
          <option value="S"'.($jednotka['barva'] == 'S' ? " selected" : "").'>Šedá</option>
          <option value="F"'.($jednotka['barva'] == 'F' ? " selected" : "").'>Fialová</option>
          <option value="Z"'.($jednotka['barva'] == 'Z' ? " selected" : "").'>Zelená</option>
        </select>
      </td>
    </tr>
		<tr>
			<td>
				phb
			</td>
			<td>
				<input name="upravy[phb]" value="'.$jednotka['phb'].'">
			</td>
		</tr>
		<tr>
			<td>
				ini
			</td>
			<td>
				<input name="upravy[ini]" value="'.$jednotka['ini'].'">
			</td>
		</tr>
		<tr>
			<td>
				dmg
			</td>
			<td>
				<input name="upravy[dmg]" value="'.$jednotka['dmg'].'">
			</td>
		</tr>
		<tr>
			<td>
				brn
			</td>
			<td>
				<input name="upravy[brn]" value="'.$jednotka['brn'].'">
			</td>
		</tr>
		<tr>
			<td>
				zvt
			</td>
			<td>
				<input name="upravy[zvt]" value="'.$jednotka['zvt'].'">
			</td>
		</tr>
		<tr>
			<td>
				pwr
			</td>
			<td>
				<input name="upravy[pwr]" value="'.$jednotka['pwr'].'">
			</td>
		</tr>
		<tr>
		<td colspan="2">
				<hr>
			</td>
		</tr>
		<tr>
			<td>
				cena zlata
			</td>
			<td>
				<input name="upravy[cena_zl]" value="'.$jednotka['cena_zl'].'">
			</td>
		</tr>
		<tr>
			<td>
				cena many
			</td>
			<td>
				<input name="upravy[cena_mn]" value="'.$jednotka['cena_mn'].'">
			</td>
		</tr>
		<tr>
			<td>
				cena lidí
			</td>
			<td>
				<input name="upravy[cena_ld]" value="'.$jednotka['cena_lidi'].'">
			</td>
		</tr>
		<tr>
			<td>
				upkeep zlata
			</td>
			<td>
				<input name="upravy[plat_zl]" value="'.$jednotka['plat_zl'].'">
			</td>
		</tr>
		<tr>
			<td>
				upkeep many
			</td>
			<td>
				<input name="upravy[plat_mn]" value="'.$jednotka['plat_mn'].'">
			</td>
		</tr>
		<tr>
			<td>
				upkeep lidí
			</td>
			<td>
				<input name="upravy[plat_ld]" value="'.$jednotka['plat_lidi'].'">
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
		echo "Nebyla vybrána žádná existující jednotka<br>";
	}
	echo "</div>"; 	
	echo '<div class="clear">&nbsp;</div>';
}
?>
