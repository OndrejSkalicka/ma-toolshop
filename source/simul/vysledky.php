<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

$max_ = '';
$surv_ = '';
foreach ($vojak as $value)
{
	$max = $value->xp*$value->pocet_max*$value->pwr/100;
   $surv = $value->xp*$value->pocet*$value->pwr/100;
	if ($SHOW_STATS == 1) echo '<div class="'.($value->hrac == 1?"utok":"obrana").'">'.$value->jmeno." - ".$value->pocet."/".$value->pocet_max."<br>\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pwr: ".cislo($surv)."/".cislo($max)."</div>\n";

   $max_[$value->hrac] += $max;
	$surv_[$value->hrac] += $surv;
}
//echo "Pwr útoèníka: ".cislo($surv_[1])."/".cislo($max_[1]).". Pwr obránce: ".cislo($surv_[2])."/".cislo($max_[2])."<br>\n";
echo "<br><h1>Vaše ztráty jsou ".number_format(100-($surv_[1]/$max_[1]*100), 2, '.', ' ')." % síly armády, obránce ".number_format(100-($surv_[2]/$max_[2]*100), 2, '.', ' ')." % síly armády</h1><br><br>\n";

echo '<table width="100%" border=1>
<tr>
	<td width="50%">Armáda útoèníka</td>
	<td width="50%">Armáda obránce</td>
</tr>
<tr><td valign="top"><table width="100%">'."\n".'<tr><td align="left"><b>Jméno</b></td><td align="left">Typ</td><td align="right"><b>XP</b></td><td align="right"><b>Poèet</b></td><td align="right"><b>Poèet Max</b></td><td align="right"><b>Síla</b></td><td align="right"><b>Síla Max</b></td></tr>';
$pwr1 = 0;
$pwr1rest = 0;
SeradDlePwr();
foreach ($power as $p => $x)
{
	if ($vojak[$p]->hrac == 1) {
		echo '<tr><td align="left">'.VojakSLinkem($vojak[$p]).'</td><td align="left">'.($vojak[$p]->druh).($vojak[$p]->typ).($vojak[$p]->phb > 1?"".$vojak[$p]->phb:"").'</td><td align="right">'.number_format($vojak[$p]->xp, 2, '.', ' ').' %</td><td align="right">'.cislo($vojak[$p]->pocet).'</td><td align="right">'.cislo($vojak[$p]->pocet_max).'</td><td align="right">'.cislo($vojak[$p]->pwr*$vojak[$p]->pocet*$vojak[$p]->xp/100).'</td><td align="right">'.cislo($vojak[$p]->pwr*$vojak[$p]->pocet_max*$vojak[$p]->xp/100).'</td></tr>'."\n";
		$pwr1 += $vojak[$p]->pwr*$vojak[$p]->pocet_max*$vojak[$p]->xp/100;
		$pwr1rest += $vojak[$p]->pwr*$vojak[$p]->pocet*$vojak[$p]->xp/100;
	}
}

echo '</table></td><td valign="top"><table width="100%">'."\n".'<tr><td align="left"><b>Jméno</b></td><td align="left">Typ</td><td align="right"><b>XP</b></td><td align="right"><b>Poèet</b></td><td align="right"><b>Poèet Max</b></td><td align="right"><b>Síla</b></td><td align="right"><b>Síla Max</b></td></tr>';

SeradDlePwr();
$pwr2 = 0;
$pwr2rest = 0;
foreach ($power as $p => $x)
{
	if ($vojak[$p]->hrac == 2) {
		echo '<tr><td align="left">'.VojakSLinkem($vojak[$p]).'</td><td align="left">'.($vojak[$p]->druh).($vojak[$p]->typ).($vojak[$p]->phb > 1?"".$vojak[$p]->phb:"").'</td><td align="right">'.number_format($vojak[$p]->xp, 2, '.', ' ').' %</td><td align="right">'.cislo($vojak[$p]->pocet).'</td><td align="right">'.cislo($vojak[$p]->pocet_max).'</td><td align="right">'.cislo($vojak[$p]->pwr*$vojak[$p]->pocet*$vojak[$p]->xp/100).'</td><td align="right">'.cislo($vojak[$p]->pwr*$vojak[$p]->pocet_max*$vojak[$p]->xp/100).'</td></tr>'."\n";
		$pwr2 += $vojak[$p]->pwr*$vojak[$p]->pocet_max*$vojak[$p]->xp/100;		
		$pwr2rest += $vojak[$p]->pwr*$vojak[$p]->pocet*$vojak[$p]->xp/100;
	}
}
echo '</table></td></tr><tr><td align="right"><b>'.cislo($pwr1rest).' / '.cislo($pwr1).'</b></td><td align="right"><b>'.cislo($pwr2rest).' / '.cislo($pwr2).'</b></td></tr></table><br><br>';
?>
