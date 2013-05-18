<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

$kouzla [0] = "- -";
$kouzla [1] = "Superior";
$kouzla [2] = "Berserk (100%)";
$kouzla [3] = "Køídla";
$kouzla [4] = "Vichøice";
$kouzla [5] = "Píseèná bouøe (60%)";
$kouzla [6] = "Ledový déš";
$kouzla [7] = "Snìžná slepota";
$kouzla [8] = "Astrální pøelud";
$kouzla [9] = "Hlas krve";
$kouzla [10] = "Okouzlení";
$kouzla [11] = "Ini randomizer";
$kouzla [12] = "Píseèná bouøe (70%)";
$kouzla [13] = "Píseèná bouøe (80%)";
$kouzla [14] = "Berserk (50%)";

$kolo = array(array(1,2,14,6,7,8,10,11),
					array(1,2,14,3,4,6,7,8,9,10),
					array(3,4,5,12,13,7,9),
					array(5,12,13,7));

echo 'Magie v boji (veškerá na 100%)<br><br>
		<table id="kouzla">
		<tr>
			<td> </td>
			<td>Útoèník</td>
			<td>Obránce</td>
		</tr>'."\n";
for ($i = 1; $i <= 4; $i++)
{
	echo '		<tr>
			<td>seslat v TU: '.$i.'</td>
			<td><select name="ut['.$i.']">
					<option value="0" selected>- -</option>';
	foreach ($kolo[$i-1] as $value)
	{
		echo "\t\t\t\t\t<option value=\"$value\">$kouzla[$value]</option>\n";
	}
	echo "\t\t\t</select></td>\n";
	echo '			<td><select name="ob['.$i.']">
					<option value="0" selected>- -</option>';
	foreach ($kolo[$i-1] as $value)
	{
		echo "\t\t\t\t\t<option value=\"$value\">$kouzla[$value]</option>\n";
	}
	echo "\t\t\t</select></td>\n\t\t</tr>\n";
}
echo '		</table>'."\n";
?>
