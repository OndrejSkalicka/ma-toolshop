<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

$MAX_SLOV = 10;

function PocetNaPwr ($jmeno, $xp, $vek)
{
	$jmeno = preg_replace (NA_POCET, '', $jmeno);
	$jednotka = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '$jmeno' AND `brankar` = '0' AND `ID_veky` = '{$vek}'");
	if (MySQL_Num_Rows ($jednotka) == 0) $jednotka = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '%$jmeno%' AND `brankar` = '0' AND `ID_veky` = '{$vek}'");
	if ($jednotka_db = MySQL_Fetch_Array ($jednotka))
	{
		$result = 1 / ($jednotka_db['pwr'] * $xp / 100);
		return $result;
	}
	else
	{
		return 0;
	}
}

$branka = $_POST['branka'];
$utok = $_POST['utok'];
$obrana = $_POST['obrana'];
$akce = $_POST['akce'];
$ut = $_POST['ut'];
$ob = $_POST['ob'];

if ($branka > 0)
{
	$branky = MySQL_Query ("SELECT * FROM `Branky` WHERE `cislo` = '$branka' AND `ID_veky` = '".$_POST['vek']."'");
	if (($branky_db = MySQL_Fetch_Array ($branky)) && ($branky_db['obranci'] != ""))
	{
		$obrana = $branky_db['obranci'];
		$zobraz_prefix = $branky_db['zobraz_prefix'];
	}
	else
	{
		die ('Nemùžu najít pøíslušnou bránu v databázi (je možné, že nebyla dosud zjištìna)<br>');
	}
	
	if ($branky_db ['zamcena'] && !MaPrava('simul_super')) {
   die ('Tato brána nebyla pro tento vìk ještì dokonèena. Vyèekejte prosím, usilovnì se na ní (urèitì :-) pracuje. Zatím mùžete použít nìjakou z minulých vìkù.');
  }	
}

UnSet ($utok_j);
UnSet ($obrana_j);

$radky = preg_split("/\n/", $utok);
foreach ($radky as $radek)
{
	
	$test = explode ("|", $radek); 
	if (($test[0] != "") && (Is_Numeric($test[1])) && (IsSet($test[2]))) // zjistim zda je vstup z hospodareni nebo ruco
	{
		$utok_j[] = $radek;
	}
	else
	{
		$test = explode("#", $radek);
		if (($test[0] != "") && (Is_Numeric($test[1])) && (IsSet($test[2])))
		{
			$utok_j[] = $test[0]."|".$test[1]."|".$test[2]."|pwr";
		}
		else
		{
		  $nacist_hospodareni = 1;
			/*if ($radek != "")
			{
				$casti = preg_split ("/\t/", $radek);
				if (($casti[6] != "") && ($casti[1] != "")) // IE bug
				{
					$utok_j[] = "$casti[0]|$casti[6]|$casti[1]";
				}
				else
				{
					$slov = 1; //kolik slov ma nazev jednotky
					$casti  = preg_split ("/ /", $radek);
					while (((!Is_Numeric ($casti[$slov])) || (!Is_Numeric ($casti[$slov+5]))) && ($slov < $MAX_SLOV))
					{
						$casti[0] = $casti[0]." ".$casti[$slov];
						$slov ++;
					}
					$utok_j[] = $casti[0]."|".$casti[$slov+5]."|".$casti[$slov];
				}
			}*/
		}
	}
}

if ($nacist_hospodareni) {
	preg_match_all ('/(.*?)\s+([-+]?\d+(\.\d+)?)\s+(\d)\s+((Poz\.|Let\.)\s+(Str\.|Boj\.)|[PL][BS])\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)/', $utok, $matches);
	foreach ($matches[1] as $key => $value) {
		if ($matches[1] != "")
			$utok_j[] = $matches[1][$key]."|".$matches[10][$key]."|".$matches[2][$key];
	}
}

$radky = preg_split("/\n/", $obrana);
foreach ($radky as $radek)
{
	$test = explode ("|", $radek); 
	if (($test[0] != "") && ($test[1] != "") && ($test[2] != "")) // zjistim zda je vstup z hospodareni nebo ruco
	{
		$obrana_j[] = $radek;
	}
	else
	{
		$test = explode("#", $radek);
		if (($test[0] != "") && ($test[1] != "") && ($test[2] != ""))
		{
			$obrana_j[] = $test[0]."|".$test[1]."|".$test[2]."|pwr";
		}
		else
		{
			if ($radek != "")
			{
				$casti = preg_split ("/\t/", $radek);
				if (($casti[6] != "") && ($casti[1] != "")) // IE bug
				{
					$obrana_j[] = "$casti[0]|$casti[6]|$casti[1]";
				}
				else
				{
					$slov = 1; //kolik slov ma nazev jednotky
					$casti  = preg_split ("/ /", $radek);
					while (((!Is_Numeric ($casti[$slov])) || (!Is_Numeric ($casti[$slov+5]))) && ($slov < $MAX_SLOV))
					{
						$casti[0] = $casti[0]." ".$casti[$slov];
						$slov ++;					
					}
					if ($slov < $MAX_SLOV) $obrana_j[] = $casti[0]."|".$casti[$slov+5]."|".$casti[$slov];
				}
			}
		}
	}
}


/*$test = explode ("|", $obrana);
$obrana_j = preg_split("/\n/", $obrana);*/

if ((!IsSet ($utok_j)) || (!IsSet($obrana_j))) die ("Na jedné stranì konfliktu nejsou jednotky!<br><br><br><br><br><center><a href=\"main.php?akce=simul\" class=\"other\">Back</a></center>");

foreach ($utok_j as $jednotka)
{
	if ($jednotka != "")
	{
		$j = explode("|", $jednotka);
		if ($j[3] == "pwr") $j[1] = round(PocetNaPwr($j[0], $j[2], $_POST['vek_hrac']) * $j[1]);
    	$vojak[] = new CJednotka($j[0],$j[1],$j[2],1);
	}
}
foreach ($obrana_j as $jednotka)
{
	if ($jednotka != "")
	{
		$j = explode("|", $jednotka);
		if ($j[3] == "pwr") $j[1] = round(PocetNaPwr($j[0], $j[2], $_POST['vek']) * $j[1]);
    	$vojak[] = new CJednotka($j[0],$j[1],$j[2],2);
	}
}

foreach ($vojak as $key => $value)
{
	if ($value->error != 0)
	{
		echo "Chybné zadání jednotky ($value->error; jmeno '".$value->jmeno."')!<br>\n";
		unset ($vojak[$key]);		
	}
}

?>
