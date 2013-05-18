<?php 
/* overeni uzivatele */
require_once ("fce.php");
if ((!CheckLogin ()) || !MaPrava("nastaveni")) {
	LogOut();
}
/* ------ */
function Nova_jednotka () {
  if ($_POST['prekopiruj_vsechny']) {
    $jednotky = mysql_query("SELECT * FROM `MA_units` WHERE `ID_veky` = '{$_POST['nova']['vek_old']}' AND `brankar` = {$_POST['nova']['brankar_old']}");
    $error = 0;
    mysql_query("DELETE FROM `MA_units` WHERE `ID_veky` = '{$_POST['nova']['vek']}' AND `brankar` = {$_POST['nova']['brankar_old']}");
    while ($jednotka = mysql_fetch_array($jednotky)) {
      $query = "INSERT INTO `MA_units` ( `ID` , `jmeno` , `druh` , `typ` , `phb` , 
          														  `ini` , `dmg` , `brn` , `zvt` , 
          														  `pwr` , `brankar`, `cena_zl`, `cena_mn`, 
                                        `cena_lidi`, `plat_zl`, `plat_mn`, `plat_lidi`, `ID_veky`, `barva`) 
          											VALUES ('', '{$jednotka['jmeno']}', '{$jednotka['druh']}', '{$jednotka['typ']}', '{$jednotka['phb']}', 
                													  '{$jednotka['ini']}', '{$jednotka['dmg']}', '{$jednotka['brn']}', '{$jednotka['zvt']}', 
                													  '{$jednotka['pwr']}', '{$_POST['nova']['brankar']}', '{$jednotka['cena_zl']}', '{$jednotka['cena_mn']}',
                													  '{$jednotka['cena_lidi']}', '{$jednotka['plat_zl']}', '{$jednotka['plat_mn']}', '{$jednotka['plat_lidi']}', '{$_POST['nova']['vek']}', '{$jednotka['barva']}');";
      if (!mysql_query($query)) $error ++;
    }
    if ($error) 
      echo "Chyba pøi kopírování - {$error} špatných výsledkù<br />";
    else echo "Pøekopírování probìhlo úspìšnì.";
    
    return 1;
  }
	if ($_POST['step'] == 2) {
		$nova = $_POST['nova'];
		$err = 0;
		foreach ($nova as $key => $value) {
			if ($value == "") {
				echo "Nevyplnili jste pole {$key}<br>";
				$err = 1;
			}
		}
		if (!$err) {
			$query = "INSERT INTO `MA_units` ( `ID` , `jmeno` , `druh` , `typ` , `phb` , 
														  `ini` , `dmg` , `brn` , `zvt` , 
														  `pwr` , `brankar`, `cena_zl`, `cena_mn`, `cena_lidi`, `plat_zl`, `plat_mn`, `plat_lidi`, `ID_veky`, `barva`) 
											VALUES ('', '".$nova['jmeno']."', '".$nova['druh']."', '".$nova['typ']."', '".$nova['phb']."', 
													  '".$nova['ini']."', '".$nova['dmg']."', '".$nova['brn']."', '".$nova['zvt']."', 
													  '".$nova['pwr']."', '".$nova['brankar']."', '".$nova['cena_zl']."', '".$nova['cena_mn']."'
													  , '".$nova['cena_ld']."', '".$nova['plat_zl']."', '".$nova['plat_mn']."', '".$nova['plat_ld']."', '".$nova['vek']."', '{$nova['barva']}');";
			if (MySQL_Query ($query)) {
				echo "Pøidáno.<br><br>";
			} else {
				echo "Nastala chyba!<br><br>";
			}
		}
	}
	
	echo "<div id=\"set_right\">";
	
	
	
	// vypis tabulku
	?>
	<form action="main.php" method="post">
	<input type="hidden" name="akce" value="settings">
	<input type="hidden" name="typ" value="nova_jednotka">
	<input type="hidden" name="step" value="2">
	
	
	<table id="upravy">
	<tr>
		<td>
			ID
		</td>
		<td>
			<input name="nova[ID]" value="- - -" readonly>
		</td>
	</tr>
	<tr>
		<td>
			jmeno
		</td>
		<td>
			<input name="nova[jmeno]" value="">
		</td>
	</tr>
	<tr>
		<td>
			druh
		</td>
		<td>
			<select name="nova[druh]" size="2">
				<option value="P" selected>Pozemní</option>
				<option value="L">Letecká</option>
			</select>
			<!-- <input name="nova[druh]" value=""> -->
		</td>
	</tr>
	<tr>
		<td>
			typ
		</td>
		<td>
			<select name="nova[typ]" size="2">
				<option value="B" selected>Bojová</option>
				<option value="S">Støelecká</option>
			</select>
			<!-- <input name="nova[typ]" value=""> -->
		</td>
	</tr>
	<tr>
    <td>
      barva
    </td>
    <td>
      <select name="nova[barva]" size="7">
        <option value="N" selected>Neutrální</option>
        <option value="B">Bílá</option>
        <option value="C">Èerná</option>
        <option value="M">Modrá</option>
        <option value="S">Šedá</option>
        <option value="Z">Zelená</option>
        <option value="F">Fialová</option>
      </select>
    </td>
  </tr>
	<tr>
		<td>
			phb
		</td>
		<td>
			<input name="nova[phb]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			ini
		</td>
		<td>
			<input name="nova[ini]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			dmg
		</td>
		<td>
			<input name="nova[dmg]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			brn
		</td>
		<td>
			<input name="nova[brn]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			zvt
		</td>
		<td>
			<input name="nova[zvt]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			pwr
		</td>
		<td>
			<input name="nova[pwr]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			brankar
		</td>
		<td>
			<select name="nova[brankar]" size="2">
				<option value="0" selected>Normální</option>
				<option value="1">Brankáø</option>
			</select>
			<!-- <input name="nova[brankar]" value=""> -->
		</td>
	</tr>
	<tr>
		<td>
			vek (brankari)
		</td>
		<td>
			<select name="nova[vek]">
				<?php
					$veky = MySQL_Query ("SELECT `jmeno`,`ID` FROM `veky` ORDER BY `priorita`");
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['ID'].'">'.$vek['jmeno'].'</option>';
					}
				?>
			</select>
			<!-- <input name="nova[brankar]" value=""> -->
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
			<input name="nova[cena_zl]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			cena many
		</td>
		<td>
			<input name="nova[cena_mn]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			cena lidí
		</td>
		<td>
			<input name="nova[cena_ld]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			upkeep zlata
		</td>
		<td>
			<input name="nova[plat_zl]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			upkeep many
		</td>
		<td>
			<input name="nova[plat_mn]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			upkeep lidí
		</td>
		<td>
			<input name="nova[plat_ld]" value="0">
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<input type="submit" value="Pøidat" name="akce_btn"> 
		</td>
	</tr>
	</table>
	</form>
	<?php
}
?>
