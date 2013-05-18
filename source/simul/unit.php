<html>
<head>
	<?php
function GetPrefix ()
{
	global $branka;
	$prefix = MySQL_Query ("SELECT * FROM `prefixy` WHERE `branka` = $branka");
	if (!$prefix_db = MySQL_Fetch_Array ($prefix)) {return "";}
	return $prefix_db['prefix']." ";
}
		require ("../dblogin.php");
//		require ("./fce.php");
		
		$id = $_GET['id'];
		$branka = $_GET['branka'];
		
		$unit = MySQL_Query ("SELECT * FROM `MA_units` WHERE `ID` = '$id' AND `brankar` = '".($branka > 0? 1 : 0)."'");

		if (!$unit_db = MySQL_Fetch_Array ($unit))
		{
			die ("</head><body>Neexistující jednotka</body>");
		}
		if ($unit_db['brankar'] == 1) $unit_db['jmeno'] = GetPrefix ().$unit_db['jmeno'];
		echo "<title>".$unit_db['jmeno']."</title>";
	?>
  
  <LINK REL='STYLESHEET' TYPE='text/css' HREF='../style.css'>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
</head>
<body>
<?php
echo '<h1 class="detail" style="text-align: left;">'.$unit_db['jmeno'].'</h1><table>
<tr>
	<td>Síla:</td>
	<td class="detail">'.$unit_db['pwr'].'</td>
	<td>Dmg:</td>
	<td class="detail">'.$unit_db['dmg'].'</td>
	<td>Dmg/pwr:</td>
	<td class="detail">'.number_format($unit_db['dmg']/$unit_db['pwr'], 2, '.', ' ').'</td>
</tr><tr>
	<td>Typ:</td>
	<td class="detail">'.$unit_db['druh'].$unit_db['typ'].'</td>
	<td>Žvt:</td>
	<td class="detail">'.$unit_db['zvt'].'</td>
	<td>Žvt/pwr:</td>
	<td class="detail">'.number_format($unit_db['zvt']/$unit_db['pwr'], 2, '.', ' ').'</td>
</tr><tr>
	<td>Phb:</td>
	<td class="detail">'.$unit_db['phb'].'</td>
	<td>Brň:</td>
	<td class="detail">'.$unit_db['brn'].'</td>
	<td>Brň/pwr:</td>
	<td class="detail">'.number_format($unit_db['brn']/$unit_db['pwr'], 2, '.', ' ').'</td>
</tr><tr>
	<td>Ini:</td>
	<td class="detail">'.$unit_db['ini'].'</td>	
	<td> </td>
	<td> </td>
	<td>Ini/Pwr:</td>
	<td class="detail">'.number_format($unit_db['ini']/$unit_db['pwr'], 2, '.', ' ').'</td>	
</tr>
</table>';
?>
</body>
</html>