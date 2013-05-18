<?php
function ShowStats ($id = 0, $procenta = 1) {
	global $user_info;

	$staty_celkem = MySQL_Fetch_Array (MySQL_Query ("SELECT SUM(hod_0) as hod_0,SUM(hod_1) as hod_1,SUM(hod_2) as hod_2,SUM(hod_3) as hod_3,SUM(hod_4) as hod_4,SUM(hod_5) as hod_5,
																		SUM(hod_6) as hod_6,SUM(hod_7) as hod_7,SUM(hod_8) as hod_8,SUM(hod_9) as hod_9,SUM(hod_10) as hod_10,SUM(hod_11) as hod_11,
																		SUM(hod_12) as hod_12,SUM(hod_13) as hod_13,SUM(hod_14) as hod_14,SUM(hod_15) as hod_15,SUM(hod_16) as hod_16,SUM(hod_17) as hod_17,
																		SUM(hod_18) as hod_18,SUM(hod_19) as hod_19,SUM(hod_20) as hod_20,SUM(hod_21) as hod_21,SUM(hod_22) as hod_22,SUM(hod_23) as hod_23		
																 FROM `hlidka_hodiny`
																 INNER JOIN `users` ON `users`.`ID` = `users_ID` 
																 WHERE (`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0) OR (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0)"));
	
	if ($id > 0) {
		if (!$staty_hrac = MySQL_Fetch_Array (MySQL_Query ("SELECT hod_0, hod_1, hod_2, hod_3, hod_4, hod_5, hod_5, hod_6, hod_7, hod_8, hod_9, hod_10, hod_11
																	, hod_12, hod_13, hod_14, hod_15, hod_16, hod_17, hod_18, hod_19, hod_20, hod_21, hod_22, hod_23
																FROM `hlidka_hodiny` 
																INNER JOIN `users` ON `users`.`ID` = `users_ID`
																WHERE `users_ID` = '$id' AND
																((`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0) OR (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0))"))) return 0;
	}
	
	echo '<table>
  <tr><td colspan="3">Poèet hlídek všech èlenù vaší aliance (souèet za obì) v daných hodinách</td></tr>';
	
	
	
	$max = -1;
	for ($i = 0; $i < 24; $i ++) {
		if ($staty_celkem["hod_$i"] > $max) 
			$max = $staty_celkem["hod_$i"];
	}
	
	if ($id == 0) {
		$staty = $staty_celkem;
		for ($i = 0; $i < 24; $i ++) {
			$barva = percent_to_hex_color ($staty["hod_$i"] / $max);
			echo "<tr>
				<td>
					$i:00 - $i:59
				</td>
				<td class=\"right\">
					".$staty["hod_$i"]." (".round($staty["hod_$i"] / $max * 100)." %)
				</td>
				<td>
					<div style=\"background-color: $barva; width: ".round(max(1,$staty["hod_$i"] / $max * 300))."px; height: 10px; font-size: 0px;\"></div>
				</td>
			</tr>";
		}
	} else { // $id > 0
		$staty = $staty_hrac;
		for ($i = 0; $i < 24; $i ++) {
			@$pomer = $staty["hod_$i"] / $staty_celkem["hod_$i"];
			$barva = percent_to_hex_color ($pomer);
			
			echo "<tr>
				<td>
					$i:00 - $i:59
				</td>
				<td class=\"right\">
					".$staty["hod_$i"]." (".round($pomer * 100)." %)
				</td>
				<td>
					<div style=\"background-color: $barva; width: ".round(max(1,$pomer * 300))."px; height: 10px; font-size: 0px;\"></div>
				</td>
			</tr>";
		}
	}
	echo "</table>";
}
function ShowPerStats () {
	/* uvodni tabulka v 'Statistiky Hráèù' */
	global $user_info;
	$uzivatel_db = MySQL_Query ("SELECT `hlidka_hodiny`.*, `users`.`regent`, `users`.`hlidka_pocet_zachran`, `users`.`icq`, `users`.`login` as `us_id`, `users`.`ID` as `users_ID`,  `users`.`hlidka_last_update`
                      FROM `hlidka_hodiny` 
											INNER JOIN `users` ON `users`.`ID` = `hlidka_hodiny`.`users_ID`
											WHERE (`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0) OR (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0)
											ORDER BY `users`.`regent`");
	
	$staty_celkem = MySQL_Fetch_Array (MySQL_Query ("SELECT SUM(hod_0) as hod_0,SUM(hod_1) as hod_1,SUM(hod_2) as hod_2,SUM(hod_3) as hod_3,SUM(hod_4) as hod_4,SUM(hod_5) as hod_5,
																		SUM(hod_6) as hod_6,SUM(hod_7) as hod_7,SUM(hod_8) as hod_8,SUM(hod_9) as hod_9,SUM(hod_10) as hod_10,SUM(hod_11) as hod_11,
																		SUM(hod_12) as hod_12,SUM(hod_13) as hod_13,SUM(hod_14) as hod_14,SUM(hod_15) as hod_15,SUM(hod_16) as hod_16,SUM(hod_17) as hod_17,
																		SUM(hod_18) as hod_18,SUM(hod_19) as hod_19,SUM(hod_20) as hod_20,SUM(hod_21) as hod_21,SUM(hod_22) as hod_22,SUM(hod_23) as hod_23		
																 FROM `hlidka_hodiny`
																 INNER JOIN `users` ON `users`.`ID` = `hlidka_hodiny`.`users_ID`
																 WHERE (`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0) OR (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0)"));
	
	echo '<table>
	<tr>
		<td>n.</td>
		<td>ID</td>
		<td>Regent</td>
		<td>ICQ</td>
		<td>Poslední update</td>
		<td>Celkem</td>
		<!--<td class="right">&Oslash;*100</td>-->
		<!--<td class="right">&#968;</td>-->
		<td>Záchran</td>
		<td class="right">Bodù</td>
		<td>Graf</td>
	</tr>';
	
	$vysledky = "";
	
	while ($uziv = MySQL_Fetch_Array ($uzivatel_db)) {
		$regent = text_s_teckama($uziv['regent']);
		$max = 0;
		$celkem = 0;
		$cinitel = 0;
		$body = 0;
		
		for ($i = 0; $i < 24; $i ++) {
			$celkem += $uziv["hod_$i"];
			if ($uziv["hod_$i"] > $max) 
				$max = $uziv["hod_$i"];
			if ($staty_celkem["hod_$i"] > 0) {
				$cinitel_temp =  sqrt ($uziv["hod_$i"] / $staty_celkem["hod_$i"]);
				$cinitel += $cinitel_temp;
				$body += $uziv["hod_$i"] * $cinitel_temp;
			}
			
		}
		
		//$body += $uziv['hlidka_pocet_zachran'];
		
		$cinitel /= 24;
		$prumer = round($celkem * 100 / ((time() - 1137397044)/(60*60)));
		//$body = $cinitel*$celkem;
		
		$out_text = '
					<td>'.$uziv['us_id'].'</td>
					<td><a href="main.php?akce=hlidka&amp;typ=per_stats&amp;user_details='.$uziv['users_ID'].'" class="other">'.$regent.'</a></td>
					<td>'.icq($uziv['icq']).'</td>
					<td>'.date('d.m.y H:i:s', $uziv['hlidka_last_update']).'</td>
					<td class="right">'.cislo($celkem).'</td>
					<!--<td class="right">'.cislo($prumer).'</td>-->
					<td class="right">'.cislo($uziv['hlidka_pocet_zachran']).'</td>
					<td class="right">'.cislo($body).'</td>';
				
		$ret_pack['body'] = $body;
		$ret_pack['text'] = $out_text;
		$vysledky [] = $ret_pack;
	}
	
	rsort ($vysledky);
	$max = $vysledky[0]['body'];
	$poradi = 0;
	foreach ($vysledky as $value) {
		$poradi ++;
		echo '<tr>
			<td>'.$poradi.'.</td>'.$value['text'];
		$barva = percent_to_hex_color ($value['body'] / $max);
		echo "<td>
			<div style=\"background-color: $barva; width: ".round(max(1,$value['body'] / $max * 100))."px; height: 10px; font-size: 0px;\"></div>
		</td>
		</tr>";
	}
	echo '</table>';
	
	
	if ($_GET['user_details'] > 0) {
		echo "<br>User's details/timetable:<br><br>";
		ShowStats($_GET['user_details'], 0);
	}
}
function ShowZlato ($typ_ali) {
	global $user_info;
	
	if ($typ_ali != 'v' AND $typ_ali != 't') return 0;
	
	if ($user_info["ID_ali_{$typ_ali}"] == 0) return 0;
	
	if ($typ_ali == 'v') echo "V rámci veøejné aliance: <br><br>"; 
		else echo "V rámci tajné aliance: <br><br>"; 
	
	$zlato_db = MySQL_Query ("SELECT * FROM `users` WHERE `zlato` > 0 AND (`users`.`ID_ali_{$typ_ali}` = '".$user_info["ID_ali_{$typ_ali}"]."') ORDER BY `zlato` DESC");
	
	echo '
	<i>(statistiky jsou založené na hlídkování jednotlivých hráèù)</i>
	<br><br><table>
		<tr><td>No</td><td>ID</td><td>Regent</td><td>ICQ</td><td class="right">Last update</td><td class="right">Zlato</td><td>Graf</td></tr>';
	
	$max = 0;	
	$sum = 0;
	$pos = 0;
	
	while ($zlato = MySQL_Fetch_Array ($zlato_db)) {
		if ($max <= 0) 
			$max = $zlato['zlato'];
			
		$pos ++;
		$sum += $zlato['zlato'];
		echo "<tr><td>$pos.</td><td>".$zlato['login']."</td><td>".text_s_teckama($zlato['regent']).'</td><td>'.icq($zlato['icq']).'</td><td class="right">'.date('d.m.y - H:i', $zlato['hlidka_last_update']).'</td><td class="right">$ '.cislo($zlato['zlato'])."</td>";
		$barva = percent_to_hex_color ($zlato['zlato'] / $max);
		echo "<td>
			<div style=\"background-color: $barva; width: ".round(max(1,$zlato['zlato'] / $max * 200))."px; height: 10px; font-size: 0px;\"></div>
		</td>";
		
		echo "</tr>";
	}
	
	echo '
	<tr><td colspan="6"><hr></td></tr>
	<tr>
		<td colspan="3">Celkem zlata v ali</td>
		<td class="right">'.cislo($sum).'</td>
		<td>&nbsp;</td>
	</tr>
	</table><br><br>';
}
function percent_to_hex_color ($x) {
	if ($x > 1) $x = 1;
	if ($x < 0) $x = 0;
	return "#".substr("000000".dechex(65536*round (min (512 - $x * 512, 255)) + 256*round (min ($x * 512, 255))),-6);
}

?>
