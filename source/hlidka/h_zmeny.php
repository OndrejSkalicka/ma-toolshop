<?php
function updatuj_hrace ($id, $pwr) {
	global $user_info;

	$default_msg = "Pokles na %sila_po%, o %zmena_abs% (%zmena_rel% %). Cas: %cas%. Thx to: %zachrance%";
	
	$ret_val = 0;
	
	
	/* kontrola jestli dane ID je v databazi + jestli sdili s hlidkarem ali */
	if (!$hrac = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `users` WHERE `login` = '$id' AND 
			((`ID_ali_v` = '{$user_info['ID_ali_v']}' AND `ID_ali_v` > 0)
			OR 
			(`ID_ali_t` = '{$user_info['ID_ali_t']}' AND `ID_ali_t` > 0))			
			"))) {
		return 0;
	} 
	
	if (!MaPrava ('hlidka', $hrac['ID']))
		return 0;

  $potrebuje_prozvonit = $hrac['potrebuje_prozvonit'];
  if (Ma_Narok_Na_Hlidku($hrac['ID'])) {
		$rozdil_abs = - $pwr + $hrac['last_pwr'];
		@$rozdil_rel = $rozdil_abs / $hrac['last_pwr'] * 100;
		
		
		$delka = 25;
		
		
		$text_regent = text_s_teckama($hrac['regent'], $delka);
		$text_provi = text_s_teckama($hrac['provi'], $delka);
		
		// kontrola jestli uzivatel nema casove omezeni hlidky. V tom pripade se ani nezapisuje do DB, aby pokles prisel jakmile se dostane do spravenho casu
		$od = $hrac['hlidka_od'];
		$do = $hrac['hlidka_do'];
		$cas = date('G');
		if (($od <= $do && ($cas < $od || $cas > $do)) || ($od > $do && $cas > $do && $cas < $od)) {
      $nehlidat = 1;
    } else $nehlidat = 0;
		
		
		echo '
		<tr>
			<td>
				'.$hrac['login'].'
			</td>
			<td>
				'.htmlspecialchars($text_regent).'
			</td>
			<td>
				'.htmlspecialchars($text_provi).'
			</td>
			<td>
				'.icq($hrac['icq']).'
			</td>';
			if (HLIDKUJ_A_BUDES_HLIDAN) {
				echo '<td class="right">'.(gmdate ("H:i", max (0, HLIDKUJ_A_BUDES_HLIDAN_TIMEOUT - time() + $hrac['hlidka_last_update'] - 60*60))).' h</td>';
			}
			echo '
			<td class="right">
				'.($nehlidat ? '-' : cislo($hrac['last_pwr'])).'
			</td>
			<td class="right">
				'.($nehlidat ? '-' : cislo($pwr)).'
			</td>
			<td class="right">
				'.($nehlidat ? '-' : cislo(-$rozdil_abs)).'
			</td>
			<td class="right">
				'.($nehlidat ? '-' : cislo(-$rozdil_rel).'  %').'
			</td>
		';
		
		// kontrola jestli uzivatel nema casove omezeni hlidky. V tom pripade se ani nezapisuje do DB, aby pokles prisel jakmile se dostane do spravenho casu
		if ($nehlidat) {
      echo '<td class="hlidka_off">Uživatel má na tuto hodinu vypnutou hlídku</td>
            </tr>';
      return 0;
    }
		
		$pokles = 0;
		$rel_bool = 0;
		$abs_bool = 0;
		
		if (($hrac['hlidka_pwr_rel'] <= $rozdil_rel) && ($hrac['hlidka_pwr_rel'] != 0)) 
			$rel_bool = 1;
		if (($hrac['hlidka_pwr_abs'] <= $rozdil_abs) && ($hrac['hlidka_pwr_abs'] != 0)) 
			$abs_bool = 1;
		
		
		if ($hrac['hlidka_pwr_need_both'] == 1) {
			if ($rel_bool && $abs_bool)
				$pokles = 1;
		} else {
			if ($rel_bool || $abs_bool)
				$pokles = 1;
		}
		
		if ($pokles) {
			if ($hrac['ID'] != $user_info['ID']) IncDB ('hlidka_pocet_zachran');
			$status = 0;
			if ($hrac['hlidka_mail'] != "") {
				
				$mail = $hrac['custom_hlidka_msg'] ? $hrac['custom_hlidka_msg'] : $default_msg;
				
				$mail = preg_replace ('/%zmena_abs%/i', cislo($rozdil_abs), $mail);
				$mail = preg_replace ('/%zmena_rel%/i', cislo($rozdil_rel), $mail);
				$mail = preg_replace ('/%sila_pred%/i', cislo($hrac['last_pwr']), $mail);
				$mail = preg_replace ('/%sila_po%/i', cislo($pwr), $mail);
				$mail = preg_replace ('/%cas%/i', Date("H:i"), $mail);
				$mail = preg_replace ('/%zachrance%/i', $user_info['regent'], $mail);
				
				if (@Mail ($hrac['hlidka_mail'], "", $mail,"From: hlidka@snh.eu")) {
					$status = 1;
				} 
				
			}
			
			
			if ($status) { //podarilo se odeslat mail
				if ($hrac['hlidka_phone'] == "") {
					$phone_msg = "Uživatel nemá vyplnìný telefon.";
				} elseif ($hrac['vzdy_prozvonit']) {
					$phone_msg = "Uživatel si vyžádal prozvonìní: ".$hrac['hlidka_phone']."!";
					$ret_val = 1;
				} else {
					$phone_msg = 'Pokud i tak chcete prozvonit: '.$hrac['hlidka_phone'];
				}
				echo '<td class="'.($ret_val == 1 ? "red_ko" : "red_ok").'">Pokles, email odeslán.<br> '.$phone_msg.'</td>';
			} else { // email nedosel
				if ($hrac['hlidka_phone'] == "") {
					$phone_msg = "Uživatel nemá vyplnìný telefon!";
				} else {
					$phone_msg = 'Prozvánìjte na: '.$hrac['hlidka_phone']."!!";
					$ret_val = 1;
				}
				echo '<td class="red_ko">Pokles, email NEBYL odeslán. <br>'.$phone_msg.'</td>';
			}
      
      if ($hrac['vzdy_prozvonit'] && $user_info['prozvani'] == false) {
        $potrebuje_prozvonit = true;
      }
		} elseif ($potrebuje_prozvonit && $user_info['prozvani']) {
      $potrebuje_prozvonit = false;
      echo '<td class="red_ko">Nebyl prozvonìn od posledního poklesu!<br>' . "Uživatel si vyžádal prozvonìní: ".$hrac['hlidka_phone']."!</td>";
      $ret_val = 1;
    } elseif ($potrebuje_prozvonit) {
      echo '<td class="red_ko" style="color: orange">Døíve poklesl, ale zatím nebyl prozvonìn. (' .$hrac['hlidka_phone']. ')</td>';
    }else {
			echo '<td class="green">OK</td>';
		}
	}
	
	MySQL_Query ("UPDATE `users` SET `last_pwr` = '$pwr', `potrebuje_prozvonit` = '" . ((bool)$potrebuje_prozvonit) . "' WHERE `ID` = '".$hrac['ID']."'");

	echo "	</tr>";
	
	
	return $ret_val;
}
function Ma_Narok_Na_Hlidku ($id) {
	if (!HLIDKUJ_A_BUDES_HLIDAN) return 1;
	
	$hrac = MySQL_Fetch_Row(MySQL_Query ("SELECT `hlidka_last_update` FROM `users` WHERE `ID` = '$id'"));
	if ((time() - $hrac[0]) < HLIDKUJ_A_BUDES_HLIDAN_TIMEOUT)
		return 1;
	
	return 0;
}
?>
