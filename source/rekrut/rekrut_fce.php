<?php
function cislo ($cislo)
{

	if (round ($cislo) < 0) { //kvuli "- 0"
		$prefix = "- ";
	}
	if ($cislo < 0) {
		$cislo *= -1;
	}

	return $prefix.number_format($cislo, 0, '', ' ');
}

function RekrutujJednotku ($rekrut, &$hospodareni) {	
	$nakrouceno = 0;
	$rekrut_overflow = 0;
	
	$jednotka_db = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '".$rekrut['jmeno']."' AND `brankar` = '0' AND `ID_veky` = '{$_POST['rekrut_vek']}'");
	if (MySQL_Num_Rows ($jednotka_db) == 0) $jednotka_db = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '%".$rekrut['jmeno']."%' AND `brankar` = '0' AND `ID_veky` = '{$_POST['rekrut_vek']}'");
	if (!$jednotka = MySQL_Fetch_Array ($jednotka_db)) return 0;
	
	
	
	if ($rekrut['typ_tu'] == "#") $rekrut['rekrut_tu'] = ($rekrut['rekrut_tu'] / 0.4 / $jednotka['pwr']); //pokud zadava, kolik sily chce za TU a ne kolk jednotek
	if ($rekrut['typ_pocet'] == "#") $rekrut['pocet'] = round ($rekrut['pocet'] / 0.4 / $jednotka['pwr']);	//pokud rekrutuje na silu, tak nastavim pocet jako sila / .4 / pwr jednokty
	if ($rekrut['typ_pocet'] == "/") $rekrut['pocet'] = floor ($rekrut['pocet'] * $rekrut['rekrut_tu']);	//pokud rekrutuje na pocet tu
	
	if ($_POST['stats'] == "on") echo "<strong>Kroutím jednotku ".$jednotka['jmeno']." v poètu ".$rekrut['pocet']." rychlostí ".number_format($rekrut['rekrut_tu'], 2, ".", " ") ." jednotek za TU</strong><br>";
	
	while ($nakrouceno < $rekrut['pocet']) { //dokud nemam nakrouceno dost jendotek, tak kroutim
		$rekrut_overflow += $rekrut['rekrut_tu'];
		$nakrouceno_tu = floor ($rekrut_overflow);	//kolik se nakroutilo za toto kolo
		$nakrouceno_tu = min ($nakrouceno_tu, $rekrut['pocet'] - $nakrouceno);
		$nakrouceno += $nakrouceno_tu; $hospodareni['jednotek_celkem'] += $nakrouceno_tu;	//nakroutim jednotky
		
		OdehrajTU ($nakrouceno_tu, $nakrouceno, $jednotka, $hospodareni);
		
		$rekrut_overflow -= $nakrouceno_tu; //snizim overflow na < 1
	}
	
	/* Pridam jednotky do pole aktivnich jednotek */
	/* Musim nejdriv zjistit, jestli nejaka jednotka stejneho jmena existuje */
	$j_id = -1;
	foreach ($hospodareni['jednotky'] as $key => $jednotka_test)
		if (strtolower($jednotka_test->jmeno) == strtolower($jednotka['jmeno'])) $j_id = $key;
	if ($j_id == -1) {
		$hospodareni['jednotky'][] = new CJednotka ($jednotka['jmeno'], $nakrouceno, 40, -$jednotka['plat_zl'] * $nakrouceno, -$jednotka['plat_mn'] * $nakrouceno, -$jednotka['plat_lidi'] * $nakrouceno, 2);
	} else {
		$hospodareni['jednotky'][$j_id]->PripojJednotku ($nakrouceno, 0.40, $jednotka['plat_zl'] * $nakrouceno, $jednotka['plat_mn'] * $nakrouceno, $jednotka['plat_lidi'] * $nakrouceno);
	}
	$temp['pocet'] = $nakrouceno;
	$temp['pwr'] = $jednotka['pwr'];
	$temp['plat_zl'] = $jednotka['plat_zl'];
	$temp['plat_mn'] = $jednotka['plat_mn'];
	$temp['plat_lidi'] = $jednotka['plat_lidi'];
	$temp['jmeno'] = $jednotka['jmeno'];
	$temp['zl_zbyva'] = $hospodareni['zl_akt'];
	$temp['mn_zbyva'] = $hospodareni['mn_akt'];
	$temp['lidi_zbyva'] = $hospodareni['lidi_akt'];
	$hospodareni['nakrouceno'][] = $temp;
	
	return 1;
}
function OdehrajTU ($rekrut_pocet, $rekrut_celkem, $jednotka, &$hospodareni) {
	$hospodareni['OT'] ++;
	$hospodareni['seslano_text'] = "";
	
	$sesli_id = -1;

	if (count ($hospodareni['kouzla']) > 0) {
		foreach ($hospodareni['kouzla'] as $key => $value) {
			if ($value['pocet'] > 0) {
				$sesli_id = $key;
				break;
			}
		}
	}
	if ($sesli_id >= 0) {
		$hospodareni['kouzla']['predkouzleno'] ++;
		if ($hospodareni['kouzla']['predkouzleno'] == $hospodareni['kouzla'][$sesli_id]['doba']) {
			$hospodareni['kouzla'][$sesli_id]['pocet'] --;
			$hospodareni['kouzla']['predkouzleno'] = 0;
			//echo "Sesláno ".$hospodareni['kouzla_base'][$hospodareni['kouzla'][$sesli_id]['id']]->jmeno."<br>";
			$hospodareni['seslano_text'] = $hospodareni['kouzla_base'][$hospodareni['kouzla'][$sesli_id]['id']]->sesli($hospodareni, $hospodareni['kouzla'][$sesli_id]['xp']);
		}
	}
	
	
	/* prepocitani vydaju za armadu */
	$hospodareni['zl_armada'] -= ($jednotka['plat_zl'] * $rekrut_pocet);
	$hospodareni['mn_armada'] -= ($jednotka['plat_mn'] * $rekrut_pocet);
	$hospodareni['lidi_armada'] -= ($jednotka['plat_lidi'] * $rekrut_pocet);
	
	/* prepocitam vydaje za lidi */
	$hospodareni['lidi_poddani'] = round ($hospodareni['lidi_akt'] / 1000 * 3);
	$hospodareni['zl_poddani'] = round ($hospodareni['lidi_akt'] / 1000 * 5.46);
	
	/* prepocitam vydaje za manaveze, co stavim */
	if (($_POST['manveze_in'] > 0) && (Is_Numeric($_POST['manveze_in']))) {
	  $_POST['manveze_in'] --;
		$hospodareni['mn_budovy'] += 250;
		$hospodareni['mn_max'] += 5000;
		$hospodareni['seslano_text']['mana_veze'] = 1;
	}
	/* ---prepocty */
	/* upraveni zlata many lidu */
	$hospodareni['zl_akt'] += $hospodareni['zl_armada'] + $hospodareni['zl_budovy'] + $hospodareni['zl_poddani'] - $jednotka['cena_zl'] * $rekrut_pocet + $hospodareni['seslano_text']['zl'];
	$hospodareni['mn_akt'] = min ($hospodareni['mn_max'], $hospodareni['mn_akt'] + $hospodareni['mn_armada'] + $hospodareni['mn_budovy'] + $hospodareni['mn_poddani'] - $jednotka['cena_mn'] * $rekrut_pocet + $hospodareni['seslano_text']['mn']);
	$hospodareni['lidi_akt'] = min ($hospodareni['lidi_max'] - 3 * $hospodareni['jednotek_celkem'], $hospodareni['lidi_akt'] + $hospodareni['lidi_armada'] + $hospodareni['lidi_budovy'] + $hospodareni['lidi_poddani'] - $jednotka['cena_lidi'] * $rekrut_pocet + $hospodareni['seslano_text']['lidi']);
	VypisTabulku_OdehrajTU ($hospodareni, $jednotka, $rekrut_pocet, $rekrut_celkem);
	
	if ($hospodareni['zl_akt'] < 0) $hospodareni['zl_hit_zero'] ++;
	if ($hospodareni['mn_akt'] < 0) $hospodareni['mn_hit_zero'] ++;
	if ($hospodareni['lidi_akt'] < 0) $hospodareni['lidi_hit_zero'] ++;
}
function Sort_By_Pwr ($a, $b) {
   if ($a->Sila() == $b->Sila()) return 0;
   return ($a->Sila() > $b->Sila()) ? -1 : 1;
}
?>
