<?php
//2do expy jednotek
function VypisTabulku_OdehrajTU ($hospodareni, $jednotka, $rekrut_pocet, $rekrut_celkem) {
	if ($_POST['stats'] == "on") {
		echo "
		
		<table cellpadding=\"\" cellspacing=\"0\" border=\"0\" style=\"width: 400px;\">
		<tr>
			<td colspan=\"4\">Tah c. ".$hospodareni['OT']."</td>
		</tr>
		<tr>
			<td colspan=\"4\">Narekrutováno $rekrut_pocet x ".$jednotka['jmeno'].", celkem $rekrut_celkem<br>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class=\"right\">zl</td>
			<td class=\"right\">mn</td>
			<td class=\"right\">lidi</td>
		</tr>
		<tr>
			<td>cena jednotek</td>
			<td class=\"right\">".cislo($jednotka['cena_zl'] * $rekrut_pocet)."</td>
			<td class=\"right\">".cislo($jednotka['cena_mn'] * $rekrut_pocet)."</td>
			<td class=\"right\">".cislo($jednotka['cena_lidi'] * $rekrut_pocet)."</td>
		</tr>
		<tr>
			<td>upkeep za armu</td>
			<td class=\"right\">".cislo($hospodareni['zl_armada'])."</td>
			<td class=\"right\">".cislo($hospodareni['mn_armada'])."</td>
			<td class=\"right\">".cislo($hospodareni['lidi_armada'])."</td>
		</tr>
		<tr>
			<td>upkeep za budovy</td>
			<td class=\"right\">".cislo($hospodareni['zl_budovy'])."</td>
			<td class=\"right\">".cislo($hospodareni['mn_budovy'])."</td>
			<td class=\"right\">".cislo($hospodareni['lidi_budovy'])."</td>
		</tr>
		<tr>
			<td>upkeep za lidi</td>
			<td class=\"right\">".cislo($hospodareni['zl_poddani'])."</td>
			<td class=\"right\">".cislo($hospodareni['mn_poddani'])."</td>
			<td class=\"right\">".cislo($hospodareni['lidi_poddani'])."</td>
		</tr>";
    if ($hospodareni['seslano_text']['mana_veze']) {
      echo "<tr>
				<td>Upkeep za 5x manavìže</td>
				<td class=\"right\">-??</td>
				<td class=\"right\">250</td>
				<td class=\"right\">0</td>
			</tr>";
		}
		echo "<tr>
			<td>upkeep celkem</td>
			<td class=\"right\">".cislo($hospodareni['zl_armada'] + $hospodareni['zl_budovy'] + $hospodareni['zl_poddani'])."</td>
			<td class=\"right\">".cislo($hospodareni['mn_armada'] + $hospodareni['mn_budovy'] + $hospodareni['mn_poddani'])."</td>
			<td class=\"right\">".cislo($hospodareni['lidi_armada'] + $hospodareni['lidi_budovy'] + $hospodareni['lidi_poddani'])."</td>
		</tr>";
		if ($hospodareni['seslano_text']['text'])
			echo "<tr>
			<td>".$hospodareni['seslano_text']['text']."</td>
			<td class=\"right\">".cislo($hospodareni['seslano_text']['zl'])."</td>
			<td class=\"right\">".cislo($hospodareni['seslano_text']['mn'])."</td>
			<td class=\"right\">".cislo($hospodareni['seslano_text']['lidi'])."</td>
		</tr>";
		echo "<tr>
			<td>zustatek</td>
			<td class=\"right\">".cislo($hospodareni['zl_akt'])."</td>
			<td class=\"right\">".cislo($hospodareni['mn_akt'])."</td>
			<td class=\"right\">".cislo($hospodareni['lidi_akt'])."</td>
		</tr>
		</table>	
		<br>";
	}
}
function vysledky ($hospodareni) {
	echo "
	<strong>CELKEM TU</strong>: ".cislo ($hospodareni['OT'])."<br><br>
	<strong>Provi</strong>:
	<br><br>
	<table>
		<tr>
			<td>Zlato:</td>
			<td class=\"right\">".cislo($hospodareni['zl_akt'])."</td>
		</tr>
		<tr>
			<td>Mana:</td>
			<td class=\"right\">".cislo($hospodareni['mn_akt'])."</td>
			<td class=\"right\">".@cislo($hospodareni['mn_akt'] / $hospodareni['mn_max'] * 100)."%</td>
		</tr>
		<tr>
			<td>Mana max:</td>
			<td class=\"right\">".cislo($hospodareni['mn_max'])."</td>
		</tr>
		<tr>
			<td>Populace:</td>
			<td class=\"right\">".cislo($hospodareni['lidi_akt'])."</td>
			<td class=\"right\">".@cislo($hospodareni['lidi_akt'] / $hospodareni['lidi_max'] * 100)."%</td>
		</tr>
		<tr>
			<td>Populace max:</td>
			<td class=\"right\">".cislo($hospodareni['lidi_max'])."</td>
		</tr>
		<tr><td colspan=\"99\"><hr></td></tr>
	</table>
	";

//Jednotka	Zkuš.%	Phb.	Druh	Typ Ut.	Síla	Poèet	zl/TU	mn/TU	pp/TU

	echo '
	
	<strong>Jednotky:</strong>
	<br><br>
	<table cellspacing="0" cellpadding="0" class="result_table">
	<tr class="nadpis">
		<td class="jmeno">Jméno</td>
		<td>XP %</td>
		<td>Phb</td>
		<td>Typ</td>
		<td>Síla</td>
		<td>Poèet</td>
		<td>zl/TU</td>
		<td>mn/TU</td>
		<td>pp/TU</td>
	</tr>';

	usort ($hospodareni['jednotky'], "Sort_By_Pwr");
	
	$ceklem = '';
	$pro_simul = '';
	foreach ($hospodareni['jednotky'] as $jednotka) {
		$celkem['pocet'] += $jednotka->pocet;
		$celkem['sila'] += $jednotka->Sila();
		$celkem['zl_armada'] += $jednotka->zl_tu_celkem;
		$celkem['mn_armada'] += $jednotka->mn_tu_celkem;
		$celkem['pp_armada'] += $jednotka->pp_tu_celkem;
		echo $jednotka->VypisRadek();
		if ($pro_simul != "") $pro_simul .= "\n";
		$pro_simul .= $jednotka->jmeno."|".$jednotka->pocet."|".number_format($jednotka->xp * 100, 2, ".", " ");
	}
	
	
	foreach ($hospodareni['nakrouceno'] as $jednotka) {
		/*$celkem['pocet'] += $jednotka['pocet'];
		$celkem['sila'] += $jednotka['pocet'] * $jednotka['pwr'] * 0.40;
		$celkem['zl_armada'] += $jednotka['plat_zl'] * $jednotka['pocet'];
		$celkem['mn_armada'] += $jednotka['plat_mn'] * $jednotka['pocet'];
		$celkem['pp_armada'] += $jednotka['plat_lidi'] * $jednotka['pocet'];
		
		echo '<tr>
		<td class="jmeno">'.$jednotka['jmeno'].'</td>
		<td>'.cislo(40).'</td>
		<td>'.cislo(-1).'</td>
		<td>PS</td>
		<td>'.cislo($jednotka['pocet'] * $jednotka['pwr'] * 0.40).'</td>
		<td>'.cislo($jednotka['pocet']).'</td>
		<td>'.cislo(-$jednotka['plat_zl'] * $jednotka['pocet']).'</td>
		<td>'.cislo(-$jednotka['plat_mn'] * $jednotka['pocet']).'</td>
		<td>'.cislo(-$jednotka['plat_lidi'] * $jednotka['pocet']).'</td>
	</tr>';*/
	}

	echo '
	<tr>
		<td colspan="4" class="jmeno">
			<br>Celkem
		</td>
		<td>'.cislo($celkem['sila']).'</td>
		<td>'.cislo($celkem['pocet']).'</td>
		<td>'.cislo(-$celkem['zl_armada']).'</td>
		<td>'.cislo(-$celkem['mn_armada']).'</td>
		<td>'.cislo(-$celkem['pp_armada']).'</td>
	</tr>
	<tr><td colspan="99"><hr></td></tr>
	<tr><td class="jmeno"><strong>Hospodaøení: </strong><br><br></td></tr>
		<tr>
			<td class="jmeno" colspan="6">Zdroj</td>
			<td>zl/TU</td>
			<td>mn/TU</td>
			<td>pp/TU</td>
		</tr>
		<tr>
			<td class="jmeno" colspan="6">Stavby a budovy</td>
			<td>'.cislo($hospodareni['zl_budovy']).'</td>
			<td>'.cislo($hospodareni['mn_budovy']).'</td>
			<td>'.cislo($hospodareni['lidi_budovy']).'</td>
		</tr>
		<tr>
			<td class="jmeno" colspan="6">Poddaní</td>
			<td>'.cislo($hospodareni['zl_poddani']).'</td>
			<td>'.cislo($hospodareni['mn_poddani']).'</td>
			<td>'.cislo($hospodareni['lidi_poddani']).'</td>
		</tr>
		<tr>
			<td class="jmeno" colspan="6">Armáda</td>
			<td>'.cislo($hospodareni['zl_armada']).'</td>
			<td>'.cislo($hospodareni['mn_armada']).'</td>
			<td>'.cislo($hospodareni['lidi_armada']).'</td>
		</tr>
		<tr><td colspan="99"><hr></td></tr>
		<tr>
			<td class="jmeno" colspan="6">Celkem za TU</td>
			<td>'.cislo($hospodareni['zl_budovy'] + $hospodareni['zl_poddani'] + $hospodareni['zl_armada']).'</td>
			<td>'.cislo($hospodareni['mn_budovy'] + $hospodareni['mn_poddani'] + $hospodareni['mn_armada']).'</td>
			<td>'.cislo($hospodareni['lidi_budovy'] + $hospodareni['lidi_poddani'] + $hospodareni['lidi_armada']).'</td>
		</tr>
		<tr>
		<td colspan="99">
			<form action="main.php" method="post">
				<input type="hidden" name="akce" value="simul">
				<input type="hidden" name="z_rekrutu" value="'.$pro_simul.'">
				<center><input type="submit" value="Pøenést do simulu" style="width: 200px"></center>
			</form>
		</td>
		</tr>
	</table>
	';
	
	if ($hospodareni['zl_hit_zero'] + $hospodareni['mn_hit_zero'] + $hospodareni['lidi_hit_zero'] > 0) {
		echo "Pozor, bìhem rekrutu dosáhla jedna ze tøí vitálních hodnot nuly nebo nižší! (zlato ".$hospodareni['zl_hit_zero']." x, mn ".$hospodareni['mn_hit_zero']." x, lidi ".$hospodareni['lidi_hit_zero']." x)";
	
	};
}
?>

