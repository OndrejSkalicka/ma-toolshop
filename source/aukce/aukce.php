<?php 
/* overeni uzivatele */
require_once ("fce.php");
if (!CheckLogin () || !MaPrava ("aukce")) {
	LogOut();
}
/* ------ */

$_SESSION['aukce'] = $_POST['aukce'] ? $_POST['aukce'] : $_SESSION['aukce'];
$_SESSION['sort'] = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : (isset($_SESSION['sort']) ? $_SESSION['sort'] : 'pwr');
$_SESSION['asc'] = isset($_REQUEST['asc']) ? $_REQUEST['asc'] : (isset($_SESSION['asc']) ? $_SESSION['asc'] : '1');

if (!IsSet($_SESSION['P'])) {
	$_SESSION['P'] = "checked";
	$_SESSION['L'] = "checked";
	$_SESSION['B'] = "checked";
	$_SESSION['S'] = "checked";
} elseif ($_POST['send'] == 1) {
	if ($_POST['P'] == "on") {
		$_SESSION['P'] = "checked";
	} else {
		$_SESSION['P'] = "";
	}
	if ($_POST['L'] == "on") {
		$_SESSION['L'] = "checked";
	} else {
		$_SESSION['L'] = "";
	}
	if ($_POST['B'] == "on") {	
		$_SESSION['B'] = "checked";
	} else {
		$_SESSION['B'] = "";
	} 
	if ($_POST['S'] == "on") {
		$_SESSION['S'] = "checked";
	} else {
		$_SESSION['S'] = "";
	}
	if (Is_Numeric ($_POST['cost_2']) || ($_POST['cost_2'] == "")) {
		$_SESSION['cost_2'] = $_POST['cost_2'];
	}
	if (Is_Numeric ($_POST['min_pwr']) || ($_POST['min_pwr'] == "")) {
		$_SESSION['min_pwr'] = $_POST['min_pwr'];
	}
}
$P = $_SESSION['P'];
$L = $_SESSION['L'];
$B = $_SESSION['B'];
$S = $_SESSION['S'];
$cost_2 = $_SESSION['cost_2'];
$min_pwr = $_SESSION['min_pwr'];
?>
<form action="main.php" method="post">
<input type="hidden" name="akce" value="aukce">
<div id="aukce_left">
<?php 
echo "
<table>
<tr>
	<td>
		<input type=\"checkbox\" name=\"P\" ID=\"P\" $P><label for=\"P\">Pozemní</label>
	</td>
	<td>
		<input type=\"checkbox\" name=\"B\" ID=\"B\" $B><label for=\"B\">Bojová</label>
	</td>
</tr>
<tr>
	<td>
		<input type=\"checkbox\" name=\"L\" ID=\"L\" $L><label for=\"L\">Letecká</label>
	</td>
	<td>
		<input type=\"checkbox\" name=\"S\" ID=\"S\" $S><label for=\"S\">Støelecká</label>
	</td>
</tr>
<tr>
	<td>
		Max. cost: 
	</td>
	<td>
		<input name=\"cost_2\" value=\"$cost_2\">
	</td>
</tr>
<tr>
	<td>
		Min. pwr:
	</td><td>
		<input name=\"min_pwr\" value=\"$min_pwr\">
	</td>
</tr>
</table>";
?>
<br>
<input type="submit" value="Odeslat">
<input type="hidden" name="send" value="1">
</div>
<div id="aukce_right">
	(CTRL+A, CTRL+C, CTRL+V z aukce, pokud nemáte, tak <a href="aukce/obchod.html" target="_blank" style="color: red">tady</a> na test)
	<textarea name="aukce" rows="8" cols="50"><?php echo $_SESSION['aukce'];?></textarea><br>
</div>
</form>
<div class="clear"></div>
<div id="aukce_bottom">
<table class="aukce_tabulka">
<tr class="top">
<?php
function miniLink ($sort, $text) {
  echo '
  <td'.($_SESSION['sort'] == $sort ? ' class="b"' : '').'>
		<a href="main.php?akce=aukce&amp;sort='.$sort.'&amp;asc='.($_SESSION['sort'] == $sort && $_SESSION['asc'] == 1 ? '0' : '1').'"'.($_SESSION['sort'] == $sort ? ($_SESSION['asc'] == 0 ? ' class="desc"' : ' class="asc"') : '').'>'.$text.'</a>
	</td>
  ';
}
miniLink ('jmeno', 'Jméno');
miniLink ('barva', 'B.');
miniLink ('pocet', 'Poèet');
miniLink ('ini', 'Ini.');
miniLink ('xp', 'XP');
miniLink ('pwr', 'Pwr');
miniLink ('typ', 'Typ');
miniLink ('druh', 'Druh');
miniLink ('zl_tu', 'zl/tu');
miniLink ('mn_tu', 'mn/tu');
miniLink ('pp_tu', 'pp/tu');
miniLink ('sila', 'Síla');
miniLink ('cenaZaK', 'Cena za <br>1 síly');
miniLink ('nabidka', 'Min. nabídka');

echo '</tr>';

require "./aukce/fce.php";

$aukceVstup = $_POST['aukce'];

if (!$aukceVstup && $_SESSION['aukce'] && $_GET['sort'])
  $aukceVstup = $_SESSION['aukce'];

if ($aukceVstup != "") {
	IncDB("aukce_count");

  $povolani = preg_replace ('/(?:.*)\((Amazonka|Vìdma|Iluzionista|Barbar|Klerik|Váleèník|Mág|Alchymista|Hranièáø|Druid|Nekromant|Theurg),  ID \d+\)(?:.*)/s', '$1', $aukceVstup);
  
  switch ($povolani) {
    case 'Iluzionista':
    case 'Barbar':
      $barva = 'S';
    break;
    case 'Klerik':
    case 'Váleèník':
      $barva = 'B';
    break;
    case 'Mág':
    case 'Alchymista':
      $barva = 'M';
    break;
    case 'Hranièáø':
    case 'Druid':
      $barva = 'Z';
    break;
    case 'Nekromant':
    case 'Theurg':
      $barva = 'C';
    break;
    case 'Amazonka':
    case 'Vìdma':
      $barva = 'G';
    break;
    default:
      $barva = 'N';
  	break;
  }
    
  if ($barva == 'N') echo '<span class="error">Pozor, nebylo správnì zjištìno povolání, takže ceny jednotek budou bez 50% postihu!</span><br />';
    else echo "({$povolani})<br />";
  //echo $barva;

  $vekId = mysql_fetch_array(mysql_query("SELECT `ID` FROM `veky` ORDER BY `priorita`")); $vekId = $vekId[0];
  
	preg_match_all ('/
      (.*?)\s+                      #1. jmeno                        
      (?:\[\s(\d+)\s\]\s*)?         #2. kdo bere                     
      ([ZSMCNBF])\s+                #3. barva                           
      (\d+)\s+                      #4. pocet                           
      (\d+(?:\.\d+)?)%\s+           #5. xp                              
      (\d+(?:\.\d+)?)\s+            #6. pwr                            
      (Poz\.|Let\.)\s+              #7. druh        	
      (Boj\.|Str\.)\s+              #8. typ         	
      (Žádná\snabídka|\d+:\d+)\s+   #9. cas         	                   
      (\d+)                         #10. nabidka     	
      /x', $aukceVstup, $matches);
  
  $jednotky = array ();
	
	for ($i = 0; $matches[1][$i]; $i ++) {
		$temp = array (
      'jmeno'     => $matches[1][$i],
      'bere'      => $matches[2][$i],
      'barva'     => $matches[3][$i],
      'pocet'     => $matches[4][$i],
      'xp'        => $matches[5][$i],
      'pwr'       => $matches[6][$i],
      'druh'      => $matches[7][$i],
      'typ'       => $matches[8][$i],
      'cas'       => $matches[9][$i],
      'nabidka'   => $matches[10][$i],
      'sila'      => $matches[5][$i] * $matches[4][$i] * $matches[6][$i] / 100,
      'cenaZaK'   => $matches[10][$i] / ($matches[5][$i] * $matches[4][$i] * $matches[6][$i] / 100) /* * 1000*/
    );
		
		//echo "SELECT * FROM `MA_units` WHERE `jmeno` = '{$temp[jmeno]}' AND `ID_veky` = '{$vekId}'  ";
		
		$jednotkaDb = mysql_query("SELECT * FROM `MA_units` WHERE `jmeno` = '{$temp[jmeno]}' AND `ID_veky` = '{$vekId}'");
		if ($jednotkaDb = mysql_fetch_array($jednotkaDb)) {
		  if (($jednotkaDb['barva'] == 'N') || ($jednotkaDb['barva'] == $barva) || ($barva == 'N')) $mod = 1; 
        else $mod = 1.5;
      //echo "$mod <br />";
      $temp['zl_tu'] = round ($temp['pocet'] * $jednotkaDb['plat_zl'] * $mod);
      $temp['mn_tu'] = round ($temp['pocet'] * $jednotkaDb['plat_mn'] * $mod);
      $temp['pp_tu'] = round ($temp['pocet'] * $jednotkaDb['plat_lidi'] * $mod); 
      $temp['ini']   = $jednotkaDb['ini'];
    } else {
      $temp['zl_tu'] = '---';
      $temp['mn_tu'] = '---';
      $temp['pp_tu'] = '---'; 
      $temp['ini']   = '---';
    }
		
		
		$jednotky [] = $temp;
	}
	
	if ($_SESSION['sort']) {
    if ($_SESSION['asc']) {
      $jednotky = array_key_multi_sort ($jednotky,$_SESSION['sort']);
    } else {
      $jednotky = array_key_multi_sort_d ($jednotky,$_SESSION['sort']);
    }
    
  }
	
	
	foreach ($jednotky as $jednotka)
    VytiskniRadek ($jednotka);
	
}
?>
</table>
