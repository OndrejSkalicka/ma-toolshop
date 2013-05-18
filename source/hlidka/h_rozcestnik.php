<?php
function Rozcestnik () {
  global $user_info;
	
	echo '<div id="hlidka">';
	
	require "h_zadavani.php";
	require "h_rozklad.php";
	require "h_zmeny.php";
	require "h_fce.php";
	require "h_log.php";
	require "h_stats.php";
	require "h_config.php";
	
	echo '
	<div id="mini_menu">
		<div class="polozka"><a href="main.php?akce=hlidka"><span'.($_REQUEST['typ'] == "" ? ' class="selected"' : "").'>Zad�v�n�</span></a></div>
		<div class="polozka"><a href="main.php?akce=hlidka&amp;typ=stats"><span'.($_REQUEST['typ'] == "stats" ? ' class="selected"' : "").'>Statistiky</span></a></div>';
	if (MaPrava ("hlidka_personal_stats"))
		echo '<div class="polozka"><a href="main.php?akce=hlidka&amp;typ=per_stats"><span'.($_REQUEST['typ'] == "per_stats" ? ' class="selected"' : "").'>Statistiky Hr���</span></a></div>';
	if (MaPrava ("hlidka_zlato"))
		echo '<div class="polozka"><a href="main.php?akce=hlidka&amp;typ=zlato"><span'.($_REQUEST['typ'] == "zlato" ? ' class="selected"' : "").'>Zlato Hr���</span></a></div>';
		
	echo '</div><div class="clear">&nbsp;</div>';
	
	@$last['v'] = MySQL_Fetch_Array (MySQL_Query ("SELECT `hlidka_log`.`cas`, `users`.`regent` FROM `hlidka_log` 
															INNER JOIN `users` ON `users`.`ID` = `hlidka_log`.`users_ID`
															WHERE (`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0)
															ORDER BY `hlidka_log`.`ID` DESC
															LIMIT 1"));
	@$last['t'] = MySQL_Fetch_Array (MySQL_Query ("SELECT `hlidka_log`.`cas`, `users`.`regent` FROM `hlidka_log` 
															INNER JOIN `users` ON `users`.`ID` = `hlidka_log`.`users_ID`
															WHERE (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0)
															ORDER BY `hlidka_log`.`ID` DESC
															LIMIT 1"));
	
				
	switch ($_REQUEST['typ']) {
		case "stats":
			ShowStats ();
		break;
		case "per_stats":
			if (MaPrava ("hlidka_personal_stats"))
				ShowPerStats ();
		break;
		case "zlato":
			if (MaPrava ("hlidka_zlato"))
				ShowZlato('v');
				ShowZlato('t');
		break;
		default:
			$celkem = MySQL_Fetch_Array (MySQL_Query ("SELECT MAX(`ID`) FROM `hlidka_log`"));
			echo "<br><table>
			<tr>
				<td>Celkocelkem: </td>
				<td colspan=\"2\">".cislo($celkem[0])." hl�dkov�n� v�ech hr��� od za��tku v�ku</td>
			</tr>
			<tr>
				<td>Naposled upravil (ve�ejn�): </td><td>".($last['v']['regent'] ? "'{$last['v']['regent']}' v ".date("H:i:s (d.m.y)", $last['v']['cas']).'</td><td><span id="verejna" style="background-color: '.percent_to_hex_color(1 - min (1, (time() - $last['v']['cas'])/MAX_CAS_BARVA_POSLEDNI_HLIDKA)).'; color:'.((time() - $last['v']['cas'])/MAX_CAS_BARVA_POSLEDNI_HLIDKA > 0.75 ? 'white' : 'black').'">p�ed '.gmdate('G \h i \m s \s', time() - $last['v']['cas']) : 'nikdo' )."</span></td>
			</tr><tr>
				<td>Naposled upravil (tajn�): </td><td>".($last['t']['regent'] ? "'{$last['t']['regent']}' v ".date("H:i:s (d.m.y)", $last['t']['cas']).'</td><td><span id="tajna" style="background-color: '.percent_to_hex_color(1 - min (1, (time() - $last['t']['cas'])/MAX_CAS_BARVA_POSLEDNI_HLIDKA)).'; color:'.((time() - $last['t']['cas'])/MAX_CAS_BARVA_POSLEDNI_HLIDKA > 0.75 ? 'white' : 'black').'">p�ed '.gmdate('G \h i \m s \s', time() - $last['t']['cas']) : 'nikdo' )."</span></td>
			</tr>
			</table>
			<br><br>";
			
			vypisZadavaciTabulku();
			$ma_cas = najdi_ma_cas ($_POST['h_vstup']);
			
			
			if ($_POST['submit'] == "Zadej") {
				if (aktualni_update ($ma_cas)) {
					if (rozloz_text ($_POST['h_vstup'])) {
						PridejDoLogu($_POST['h_vstup'], $ma_cas);
						PridejDoLoguHodin($user_info['ID']);
						IncDB ("hlidka_count");
					} else {
            echo '<span style="color: red">Chyba p�i rozkladu (pamatujte - mus� se vkl�dat NASTAVEN� aliance, nikoli v�pis)</span><br>';
          }
				} else {
					echo '<span style="color: red">V� update je bohu�el neaktu�ln�.</span><br>';
				}
			}
			
		break;
	}
												
	
	echo "</div>";
}
?>
