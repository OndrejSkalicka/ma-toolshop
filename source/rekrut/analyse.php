<?php
function zanalizuj_hosp_auto ($text, $stripJednotky) {
	/* DESETINNE CISLO:
		[-+]?\d+(\.\d+)?
	
	 */
	
	if (preg_match ('/Zlato:\s*(\d+)/', $text, $matches)) { /* zlato */
		$ret['zl_akt'] = $matches[1];
	}
	if (preg_match ('/Mana:\s*(\d*)\s*\((\d+)\s*%\)/', $text, $matches)) { /* mana */
		$ret['mn_akt'] = $matches[1];
		$ret['mn_max'] = floor ($matches[1] / ($matches[2] / 100));
	}
	if (preg_match ('/Populace:\s*(\d*)/', $text, $matches)) { /* lidi */
		$ret['lidi_akt'] = $matches[1];
	}
	if (preg_match ('/Síla K.:\s*(\d*)/', $text, $matches)) { /* lidi */
		$ret['SK'] = $matches[1];
	}
	if (preg_match ('/Nálada:\s*((velmi\s*)?(špatná|dobrá|neutrální))/', $text, $matches)) { /* nalada */
		$ret['nalada'] = $matches[1];
	}
	if (preg_match ('/Farmy - zdroj potravin\s*(\d+)/', $text, $matches)) { /* farmy */
		$ret['farmy'] = $matches[1];
	}
	if (preg_match ('/Mìsta - pracovní síla\s*(\d+)/', $text, $matches)) { /* farmy */
		$ret['mesta'] = $matches[1];
		if ($ret['farmy'] / $ret['mesta'] > 30) {	/* max. populace */
			$ret['lidi_max'] = $ret['mesta'] * 15000;
		} else {
			$ret['lidi_max'] = $ret['farmy'] * 500;
		}
	}
	if (preg_match ('/Stavby a Budovy \*\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)/', $text, $matches)) { /* budovy */
		$ret['zl_budovy'] = $matches[1];
		$ret['mn_budovy'] = $matches[3];
		$ret['lidi_budovy'] = $matches[5];
	}
	if (preg_match ('/Poddaní\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)/', $text, $matches)) { /* poddani */
		$ret['zl_poddani'] = $matches[1];
		$ret['mn_poddani'] = $matches[3];
		$ret['lidi_poddani'] = $matches[5];
	}
	
	$ret['jednotek_celkem'] = 0;
	$ret['jednotky'] = array ();
	if ($stripJednotky) {
    $ret['zl_armada'] = 0;
		$ret['mn_armada'] = 0;
		$ret['lidi_armada'] = 0;
  } else {  
  	if (preg_match ('/Armáda\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)\s*([-+]?\d+(\.\d+)?)/', $text, $matches)) { /* armada_upkeep */
  		$ret['zl_armada'] = $matches[1];
  		$ret['mn_armada'] = $matches[3];
  		$ret['lidi_armada'] = $matches[5];
  	}
  	/* 						  1.jmeno 2-3. XP             4. phb 5.druh          6. druh        7-8. sila             9-10. pocet           11-12.zl_tu             13-14. mn_tu           15-16. pp_tu  */
  	if (preg_match_all ('/(.*?)\s+([-+]?\d+(\.\d+)?)\s+(\d)\s+(Poz\.|Let\.)\s+(Str\.|Boj\.)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)/', $text, $matches)) { /* army */
  		foreach ($matches[9] as $key => $jednotka) {
  			$ret['jednotek_celkem'] += $jednotka;
  			$ret['jednotky'][] = new CJednotka ($matches[1][$key],$matches[9][$key], $matches[2][$key], $matches[11][$key], $matches[13][$key], $matches[15][$key], 0);
  		}
  	}
	}
	return $ret;
}

/* --------------------------------------- */
function zanalizuj_hosp_manual (&$hospodareni) {
	if (Is_Numeric($_POST['man_zl_tu'])) {
		$hospodareni['zl_armada'] = $_POST['man_zl_tu'];
		$hospodareni['zl_budovy'] = - $hospodareni['zl_poddani'];
		$hospodareni['zl_poddani'] = 0;
	}
	if (Is_Numeric($_POST['man_zl_akt'])) {
		$hospodareni['zl_akt'] = $_POST['man_zl_akt'];
	}
	if (Is_Numeric($_POST['man_mn_tu'])) {
		$hospodareni['mn_armada'] = $_POST['man_mn_tu'];
		$hospodareni['mn_budovy'] = - $hospodareni['mn_poddani'];
		$hospodareni['mn_poddani'] = 0;
	}
	if (Is_Numeric($_POST['man_mn_akt'])) {
		$hospodareni['mn_akt'] = $_POST['man_mn_akt'];
	}
	if (Is_Numeric($_POST['man_mn_max'])) {
		$hospodareni['mn_max'] = $_POST['man_mn_max'];
	}
	if (Is_Numeric($_POST['man_lidi_akt'])) {
		$hospodareni['lidi_akt'] = $_POST['man_lidi_akt'];
	}
	if (Is_Numeric($_POST['man_lidi_max'])) {
		$hospodareni['lidi_max'] = $_POST['man_lidi_max'] + 3 * $hospodareni['jednotek_celkem'];		
	}
	if (Is_Numeric($_POST['man_sk'])) {
		$hospodareni['SK'] = $_POST['man_sk'];		
	}
	
	return 1;
}
function RozdelVstupNaCasti ($text) {
	$radky = preg_split ('/\n/', $text);
	
	foreach ($radky as $radek) {
		$radek = chop ($radek);
		if (!preg_match('/^\s*$/', $radek)) {
			if (preg_match('/^(.*)([|#\/])([-+]?\d+(\.\d+)?)([|#\/])([-+]?\d+)$/', $radek, $match)) {
				$temp['jmeno'] = $match[1];
				$temp['rekrut_tu'] = $match[3];
				$temp['pocet'] = $match[6];
				$temp['typ_tu'] = $match[2];
				$temp['typ_pocet'] = $match[5];

				$ret[] = $temp;
			} else {
				echo "<span style=\"color: red;\">chybné zadání jednotky $radek</span><br>";
			}
		}
	}
	
	//echo nl2br(var_export ($ret, 1));
	
	return $ret;
}
function RozdelKouzlaNaCasti ($text, $kouzla) {
	$radky = preg_split ('/\n/', $text);
	foreach ($radky as $radek) {
		$radek = chop ($radek);
		if (!preg_match('/^\s*$/', $radek)) {
			/*                 1.-2. x pocet   3.jmeno 4.-6. xp         	7.-8. ses_tu*/
			if (preg_match('/^((\d+)\s*?x\s+?)?(.*?)(\|([-+]?\d+(\.\d+)?))?(\|([-+]?\d+))?$/', $radek, $match)) {
				/* zjistim jmeno */
				$id_kouzla = -1;
				foreach ($kouzla as $key => $value) {
					if ($value->sedi_nazev($match[3])) {
						$id_kouzla = $key;
					}
				}
				if ($id_kouzla >= 0) {
					if ($match[2] < 1) $match[2] = 1;
					
					
					$temp['id'] = $id_kouzla;
					$temp['pocet'] = $match[2];
					$temp['xp'] = $match[5] > 0 ? $match[5] / 100 : 1;
					$temp['doba'] = $match[7] > 0 ? $match[8] : $kouzla[$id_kouzla]->default_TU;
					
					$ret[] = $temp;
				}
			} else {
				echo "<span style=\"color: red;\">chybné zadání kouzla $radek</span><br>";
			}
		}
	}
	
	//echo nl2br(var_export ($ret, 1));
	
	return $ret;
}
?>
