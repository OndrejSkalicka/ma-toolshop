<?php
function Rozcestnik () {

	echo '
	<div id="mini_menu">
		<div class="polozka"><a href="main.php?akce=aliance"><span'.($_REQUEST['typ'] == "" ? ' class="selected"' : "").'>P�ehled</span></a></div>
		<div class="polozka"><a href="main.php?akce=aliance&amp;typ=vlastni"><span'.($_REQUEST['typ'] == "vlastni" ? ' class="selected"' : "").'>Vlastn�</span></a></div>
		'.(MaPrava('aliance_nova') ? '<div class="polozka"><a href="main.php?akce=aliance&amp;typ=new"><span'.($_REQUEST['typ'] == "new" ? ' class="selected"' : "").'>Nov�</span></a></div>' : '').'
		<div class="polozka"><a href="main.php?akce=aliance&amp;typ=sprava"><span'.($_REQUEST['typ'] == "sprava" ? ' class="selected"' : "").'>Spravov�n�</span></a></div>
	</div><div class="clear">&nbsp;</div>';
	echo '<div id="ali">
	<br><span style="color: red;">POZN.: POKUD SE N�KDO BUDE HL�SIT DO ALIANC� VE KTER�CH VE SKUTE�NOSTI NENI, TAK HO MO�N� SMA�U!</span><br><br>';
	
	switch ($_REQUEST['typ']) {
		case "new":
			require "a_nova.php";
			NovaAli ();
		break;
		case "sprava":
			require "a_sprava.php";
			Vypis_Spravovanych_ali();
		break;
		case "vlastni":
			require "a_vlastni.php";
			Vypis_Vlasni_Ali ();
		break;
		default:
			require "a_vypisy.php";
			if ($_GET['a_akce'] == "poslat_zadost") {
				PridejZadost();
			}
			echo "<table>
		<tr>
			<td>N�zev</td>
			<td>�len�</td>
			<td>Vstoupit</td>
		</tr>";
			SeznamVerejnychAli ();
			SeznamTajnychAli ();
			echo "</table>";
		break;
	}
	
	echo "</div><!-- ali -->\n";
}
?>
