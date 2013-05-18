<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

function kouzli ($hrac, $kouzlo)
{
	echo '<div class="spellcast">'.($hrac == 1 ? "Útoèník ":"Obránce ")."sesílá ";
	global $vojak, $HK_BOOST;
	switch ($kouzlo)
	{
		case 1:
			echo "\"Superior\".<br>\nJednotky se skryly v krajinì a nepøítel upadl do léèky.<br>\n";
			$pocet = 0;
			$max_pwr = 0;
			$max_pwr_jmeno = "";
			$total_pwr = 0;			
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if ($value->hrac == $hrac) 
				{
					if (($value->typ == "B")&&($value->phb == 1))
					{
						$vojak[$key]->phb = 2;
						$pocet ++;
						$pwr = $vojak[$key]->pwr*$vojak[$key]->xp/100*$vojak[$key]->pocet;
						$total_pwr += $pwr;
						if ($pwr > $max_pwr)
						{
							$max_pwr = $vojak[$key]->pwr;
							$max_pwr_jmeno = $vojak[$key]->jmeno;
						}
					}
					$vojak[$key]->ini += 10;
				}
			}
			echo "Kouzlo zvıšilo pohyblivost na 2 nìkterım ($pocet) druhùm jednotek sesilatele a po velikost jednotky $max_pwr_jmeno o celkové síle ".cislo($total_pwr)." a všem zvıšilo pro toto kolo iniciativu o 10<br>";
		break;
		case 14:
		case 2:
			echo "\"Berserk\".<br>\n";
			SeradDlePwr();
			if ($kouzlo == 14) $train = 0.5;
			else $train = 1;
			global $power;
			$naslo = 0;
			foreach ($power as $p => $x)
			{
				if (($naslo == 0)&&($vojak[$p]->hrac == $hrac)&&($vojak[$p]->phb == 1)&&($vojak[$p]->typ == "B")&&($vojak[$p]->pwr * $vojak[$p]->pocet < 500000 * $train))
				{
					echo "Pud sebezáchovy? Co to je ? Urrrááááá!!!<br>\n
							Kouzlo (s vyuitım úèinkem ".($train*100)."%) zvıšilo pohyblivost jednotky sesilatele ".$vojak[$p]->jmeno." na 2.<br>\n";
					$vojak[$p]->phb = 2;
					$naslo = 1;
				}
			}
			if ($naslo == 0) echo "Kouzlo nenašlo svùj cíl<br>\n";		
		break;
		case 3:
			echo "\"Køídla\".<br>\n";
			$pocet = 0;
			$max_pwr = 0;
			$max_pwr_jmeno = "";
			$total_pwr = 0;	
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac == $hrac)&&($value->druh == "P"))
				{
					$vojak[$key]->druh = "L";
					$pocet ++;
					$pwr = $vojak[$key]->pwr*$vojak[$key]->xp/100*$vojak[$key]->pocet;
					$total_pwr += $pwr;
					if ($pwr > $max_pwr)
					{
						$max_pwr = $vojak[$key]->pwr;
						$max_pwr_jmeno = $vojak[$key]->jmeno;
					}
				}
			}
			if ($pocet > 0)
			echo "Elitní jednotky si nasazují zvláštní zaøízení... a letají !!.<br>\n
					Kouzlo nauèilo létat nìkolik ($pocet) druhù jednotek sesilatele a po sílu uskupení jednotky $max_pwr_jmeno o celkové síle ".cislo($total_pwr).".<br>";
			else echo "Kouzlo nenašlo svùj cíl<br>\n";
		break;
		case 4:
			echo "\"Vichøice\".<br>\n";
			$pocet = 0;
			$max_pwr = 0;
			$max_pwr_jmeno = "";
			$total_pwr = 0;	
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac <> $hrac)&&($value->druh == "L"))
				{
					$vojak[$key]->druh = "P";
					$pocet ++;
					$pwr = $vojak[$key]->pwr*$vojak[$key]->xp/100*$vojak[$key]->pocet;
					$total_pwr += $pwr;
					if ($pwr > $max_pwr)
					{
						$max_pwr = $vojak[$key]->pwr;
						$max_pwr_jmeno = $vojak[$key]->jmeno;
					}
				}
			}
			if ($pocet > 0)
			echo "Nebe potemnìlo a zdvihl se strašlivı vítr... vichr, co vyvrací i staré stromy.<br>\n
					Kouzlo znemonilo létat nìkterım ($pocet) druhùm jednotek nepøítele a po velikost jednotky $max_pwr_jmeno o celkové síle ".cislo($total_pwr).".<br>\n";
			else echo "Kouzlo nenašlo svùj cíl<br>\n";
		break;
		case 12:
		case 13:
		case 5:
			if ($kouzlo == 5) $train = 0.6;
			elseif ($kouzlo == 12) $train = 0.7;
			else $train = 0.8;
			echo "\"Píseèná bouøe\"<br>\nZvedá se píseèná bouøe... na boj ani pomyšlení, bojovníci se tisknou k zemi, jen obøi se ještì snaí.<br>\n";
			$pocet = 0;
			$max_pwr = 0;
			$max_pwr_jmeno = "";
			$total_pwr[1] = 0;	
			$total_pwr[2] = 0;	
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
				if (!preg_match('/(Tempest|Stín|Kulovı blesk|Spektra|Pøízrak|Hrùza|Archandìl|Fantom|Dìs|Noèní mùra|Andìl|Stín)/i', $value->jmeno) && (pow ($value->xp/100, 3) * $value->pwr * ($value->druh == 'L' ? 0.5 : 1) < pow($train,3) * 500)) {
					$vojak[$key]->turn_dam_mod = 0;
					$pocet ++;
					$pwr = $vojak[$key]->pwr*$vojak[$key]->xp/100*$vojak[$key]->pocet;
					$total_pwr[$value->hrac] += $pwr;
					if ($pwr > $max_pwr)
					{
						$max_pwr = $vojak[$key]->pwr;
						$max_pwr_jmeno = $vojak[$key]->jmeno;
					}
				}
			echo "Kouzlo zaslepilo $pocet druhù všech jednotek a po velikost jednotky $max_pwr_jmeno o celkové síle ".cislo($total_pwr[1])." armády útoèníka a ".cislo($total_pwr[2])." obránce.<br>\n";
		break;
		case 6:
			echo "\"Ledovı déš\"<br>\nDéš mrzne na nestvùrách i lidech... tìko se i pohnout.<br>\n";
			$pocet = 0;
			$max_pwr = "";
			$max_pwr_jmeno = "";
			$total_pwr = 0;			
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if ($value->hrac != $hrac) 
				{
					if ($value->phb > 1)
					{
						$vojak[$key]->phb = 1;
						$pocet ++;
						$pwr = $vojak[$key]->pwr*$vojak[$key]->xp/100*$vojak[$key]->pocet;
						$total_pwr += $pwr;
						if ($pwr > $max_pwr)
						{
							$max_pwr = $vojak[$key]->pwr;
							$max_pwr_jmeno = $vojak[$key]->jmeno;
						}
					}
					$vojak[$key]->ini = max (1, ($value->ini - 10));
				}
			}
			echo "Kouzlo sníilo pohyblivost na minimum nìkterım ($pocet) druhùm jednotek nepøítele a po velikost jednotky $max_pwr_jmeno o celkové síle ".cislo($total_pwr)." a všem pro toto kolo sníilo iniciativu o 10.<br>\n";
		break;
		case 7:
			echo "\"Snìná slepota\"<br>\nKouzlo sníilo iniciativu všem nepøátelskım jednotkám o 10<br>\n";
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if ($value->hrac != $hrac)
				{
					$vojak[$key]->ini = max (1, ($value->ini - 10));
				}
			}
		break;
		case 8:
			echo "\"Astrální pøelud\"<br>\n";
			$max_pwr = "";
			$max_pwr_id = -1;
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac != $hrac) && ($value->phb > 1))
				{
					$pwr = $vojak[$key]->pwr * $vojak[$key]->xp / 100 * $vojak[$key]->pocet;
					if ($pwr > $max_pwr)
					{
						$max_pwr = $pwr;
						$max_pwr_id = $key;
					}
				}
			}
			if ($max_pwr_id == -1) 
			{
				echo "Kouzlo nenašlo svùj cíl<br>\n";
			}
			else
			{
				echo "Kouzlo sníilo phb jednotky ".$vojak[$max_pwr_id]->jmeno." o síle ".cislo($max_pwr)." pohyblivost na 1";
				$vojak[$max_pwr_id]->phb = 1;
			}
		break;
		case 9:
			echo "\"Hlas krve\"<br>\n";
			$max_pwr = "";
			$max_pwr_id = -1;
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac == $hrac) && ($value->typ == "B"))
				{
					$pwr = $vojak[$key]->pwr * $vojak[$key]->xp / 100 * $vojak[$key]->pocet;
					if ($pwr > $max_pwr)
					{
						$max_pwr = $pwr;
						$max_pwr_id = $key;
					}
				}
			}
			if ($max_pwr_id == -1) 
			{
				echo "Kouzlo nenašlo svùj cíl<br>\n";
			}
			else
			{
				echo "Bohové promluvili... a jejich hlas kadému opakuje dávnou pøísahu vìrnosti rodu.<br>
Kouzlo zvıšilo ivoty a nièivou sílu jednotky sesilatele ".($vojak[$max_pwr_id]->jmeno)." o ".($HK_BOOST*100)."%.";
				$vojak[$max_pwr_id]->hlas_krve = min (1.5, $vojak[$max_pwr_id]->hlas_krve + $HK_BOOST);
			}
		break;
		case 10:
			echo "\"Okouzlení\"<br>\n";
			$pocet = 0;
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac == $hrac) && ($value->typ == "S"))
				{
					$vojak[$key]->turn_dam_mod *= 1.3;
					$pocet ++;
				}
			}
			if ($pocet == 0) 
			{
				echo "Kouzlo nenašlo svùj cíl<br>\n";
			}
			else
			{
				echo " Kadı vystøelenı šíp se ve vzduchu ztrojnásobí....je to jen iluze, ale uhnout není kam.<br> Kouzlo zvıšilo zranìní zpùsobované støeleckımi jednotkami sesilatele o 30%. \n";
			}
		break;
		case 11:
			echo "\"Randomizér iniciativy\"<br>\n";
			$pocet = 0;
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if (($value->hrac != $hrac))
				{
					$vojak[$key]->ini = rand(1,20);
					$pocet ++;
				}
			}
			if ($pocet == 0) 
			{
				echo "Kouzlo nenašlo svùj cíl<br>\n";
			}
			else
			{
				echo "Pohled na samotné stráce mìsta je mimoøádnı, morálka poklesla - nikdo nechce jít první, nikdo nechce bıt jako první sraen k zemi.<br> Kouzlo zmìnilo náhodnì iniciativu všem jednotkám nepøítele \n";
			}
		break;
	}
	echo "</div>\n";
}
?>
