<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */
?>

				<table id="brany">
					<tr>
						<td>
<?php
$nazvy_branek = Array ( "Custom (použije vstup obránce)",
								"D br1na", "D br2na", "D br3na", "D br4na", "D br5na", "D br6na", "D br7na", "D br8na", "D mìsto", "",
								"Z br1na", "Z br2na", "Z br3na", "Z br4na", "Z br5na", "Z br6na", "Z br7na", "Z br8na", "Z mìsto", "",
								"N br1na", "N br2na", "N br3na", "N br4na", "N br5na", "N br6na", "N br7na", "N br8na", "N mìsto (OLD)");
if (!IsSet($_SESSION['last_brana'])) $_SESSION['last_brana'] = 1;
for ($i = 1; $i <= 9; $i++)
{
	echo "\t\t\t\t\t\t\t".'<input type="radio" name="branka" id="br_'.$i.'" value="'.($i).'"'.($_SESSION['last_brana'] == $i?" CHECKED":"")."><label for=\"br_$i\">$nazvy_branek[$i]</label><br>\n";
}
?>						
						</td>
						<td>
<?php
for ($i = 11; $i <= 19; $i++)
{
	echo "\t\t\t\t\t\t\t".'<input type="radio" name="branka" id="br_'.$i.'" value="'.($i).'"'.($_SESSION['last_brana'] == $i?" CHECKED":"")."><label for=\"br_$i\">$nazvy_branek[$i]</label><br>\n";
}
?>
						</td>
						<td>
<?php
for ($i = 21; $i <= 29; $i++)
{
	echo "\t\t\t\t\t\t\t".'<input type="radio" name="branka" id="br_'.$i.'" value="'.($i).'"'.($_SESSION['last_brana'] == $i?" CHECKED":"")."><label for=\"br_$i\">$nazvy_branek[$i]</label><br>\n";
}
?>
						</td>
					</tr>
					<tr>
						<td colspan="3">
<?php
echo "\t\t\t\t\t\t\t".'<input type="radio" name="branka" value="0"'.($_SESSION['last_brana'] == 0?" CHECKED":"").">$nazvy_branek[0]<br>\n";
?>
						</td>
					</tr>					
				</table>