<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

function kouzli ($hrac, $kouzlo)
{
	echo '<div class="spellcast">'.($hrac == 1 ? "�to�n�k ":"Obr�nce ")."ses�l� ";
	global $vojak, $HK_BOOST;
	switch ($kouzlo)
	{
		case 1:
			echo "\"Superior\".<br>\nJednotky se skryly v krajin� a nep��tel upadl do l��ky.<br>\n";
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
			echo "Kouzlo zv��ilo pohyblivost na 2 n�kter�m ($pocet) druh�m jednotek sesilatele a� po velikost jednotky $max_pwr_jmeno o celkov� s�le ".cislo($total_pwr)." a v�em zv��ilo pro toto kolo iniciativu o 10<br>";
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
					echo "Pud sebez�chovy? Co to je ? Urrr�����!!!<br>\n
							Kouzlo (s vyu�it�m ��inkem ".($train*100)."%) zv��ilo pohyblivost jednotky sesilatele ".$vojak[$p]->jmeno." na 2.<br>\n";
					$vojak[$p]->phb = 2;
					$naslo = 1;
				}
			}
			if ($naslo == 0) echo "Kouzlo nena�lo sv�j c�l<br>\n";		
		break;
		case 3:
			echo "\"K��dla\".<br>\n";
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
			echo "Elitn� jednotky si nasazuj� zvl�tn� za��zen�... a letaj� !!.<br>\n
					Kouzlo nau�ilo l�tat n�kolik ($pocet) druh� jednotek sesilatele a� po s�lu uskupen� jednotky $max_pwr_jmeno o celkov� s�le ".cislo($total_pwr).".<br>";
			else echo "Kouzlo nena�lo sv�j c�l<br>\n";
		break;
		case 4:
			echo "\"Vich�ice\".<br>\n";
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
			echo "Nebe potemn�lo a zdvihl se stra�liv� v�tr... vichr, co vyvrac� i star� stromy.<br>\n
					Kouzlo znemo�nilo l�tat n�kter�m ($pocet) druh�m jednotek nep��tele a� po velikost jednotky $max_pwr_jmeno o celkov� s�le ".cislo($total_pwr).".<br>\n";
			else echo "Kouzlo nena�lo sv�j c�l<br>\n";
		break;
		case 12:
		case 13:
		case 5:
			if ($kouzlo == 5) $train = 0.6;
			elseif ($kouzlo == 12) $train = 0.7;
			else $train = 0.8;
			echo "\"P�se�n� bou�e\"<br>\nZved� se p�se�n� bou�e... na boj ani pomy�len�, bojovn�ci se tisknou k zemi, jen ob�i se je�t� sna��.<br>\n";
			$pocet = 0;
			$max_pwr = 0;
			$max_pwr_jmeno = "";
			$total_pwr[1] = 0;	
			$total_pwr[2] = 0;	
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
				if (!preg_match('/(Tempest|St�n|Kulov� blesk|Spektra|P��zrak|Hr�za|Archand�l|Fantom|D�s|No�n� m�ra|And�l|St�n)/i', $value->jmeno) && (pow ($value->xp/100, 3) * $value->pwr * ($value->druh == 'L' ? 0.5 : 1) < pow($train,3) * 500)) {
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
			echo "Kouzlo zaslepilo $pocet druh� v�ech jednotek a� po velikost jednotky $max_pwr_jmeno o celkov� s�le ".cislo($total_pwr[1])." arm�dy �to�n�ka a ".cislo($total_pwr[2])." obr�nce.<br>\n";
		break;
		case 6:
			echo "\"Ledov� d隝\"<br>\nD隝 mrzne na nestv�r�ch i lidech... t�ko se i pohnout.<br>\n";
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
			echo "Kouzlo sn�ilo pohyblivost na minimum n�kter�m ($pocet) druh�m jednotek nep��tele a� po velikost jednotky $max_pwr_jmeno o celkov� s�le ".cislo($total_pwr)." a v�em pro toto kolo sn�ilo iniciativu o 10.<br>\n";
		break;
		case 7:
			echo "\"Sn�n� slepota\"<br>\nKouzlo sn�ilo iniciativu v�em nep��telsk�m jednotk�m o 10<br>\n";
			foreach ($vojak as $key => $value) // je dulezite mit key, protoze menit $value nema cenu - je to docasna promenna, menit musim $vojak[$key]
			{
				if ($value->hrac != $hrac)
				{
					$vojak[$key]->ini = max (1, ($value->ini - 10));
				}
			}
		break;
		case 8:
			echo "\"Astr�ln� p�elud\"<br>\n";
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
				echo "Kouzlo nena�lo sv�j c�l<br>\n";
			}
			else
			{
				echo "Kouzlo sn�ilo phb jednotky ".$vojak[$max_pwr_id]->jmeno." o s�le ".cislo($max_pwr)." pohyblivost na 1";
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
				echo "Kouzlo nena�lo sv�j c�l<br>\n";
			}
			else
			{
				echo "Bohov� promluvili... a jejich hlas ka�d�mu opakuje d�vnou p��sahu v�rnosti rodu.<br>
Kouzlo zv��ilo �ivoty a ni�ivou s�lu jednotky sesilatele ".($vojak[$max_pwr_id]->jmeno)." o ".($HK_BOOST*100)."%.";
				$vojak[$max_pwr_id]->hlas_krve = min (1.5, $vojak[$max_pwr_id]->hlas_krve + $HK_BOOST);
			}
		break;
		case 10:
			echo "\"Okouzlen�\"<br>\n";
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
				echo "Kouzlo nena�lo sv�j c�l<br>\n";
			}
			else
			{
				echo " Ka�d� vyst�elen� ��p se ve vzduchu ztrojn�sob�....je to jen iluze, ale uhnout nen� kam.<br> Kouzlo zv��ilo zran�n� zp�sobovan� st�eleck�mi jednotkami sesilatele o 30%. \n";
			}
		break;
		case 11:
			echo "\"Randomiz�r iniciativy\"<br>\n";
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
				echo "Kouzlo nena�lo sv�j c�l<br>\n";
			}
			else
			{
				echo "Pohled na samotn� str�ce m�sta je mimo��dn�, mor�lka poklesla - nikdo nechce j�t prvn�, nikdo nechce b�t jako prvn� sra�en k zemi.<br> Kouzlo zm�nilo n�hodn� iniciativu v�em jednotk�m nep��tele \n";
			}
		break;
	}
	echo "</div>\n";
}
?>
