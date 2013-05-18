<?php
function rozloz_text ($text) {
								// ID
	if (preg_match_all ('/(\d{4,})\s*\n'.
								// jmeno	datum
							  '(.*?)\s*(\d{1,2}\.\s+\d{1,2}\.)\s*\n'.
							  //cas					  akt. sila,avg.pwr,brana,hlina, OT, 	, IX
							  '(\d{1,2}:\d{1,2})\s+(\d+)\s+(\d+)\s+(\d)\s+(\d+)\s+(\d+)\s+(\d+(\.\d+)?)\s+/', $text, $matches)) { 
		
		foreach ($matches[0] as $key => $value) {			
		  $pack = array ();
			$pack['id'] = $matches[1][$key];
			$pack['jmeno'] = $matches[2][$key];
			$pack['avg_pwr'] = $matches[6][$key];
			$pack['brana'] = $matches[7][$key];
			$pack['rozloha'] = $matches[8][$key];
			$pack['ix'] = $matches[10][$key];
			$hraci [] = $pack;
		}
		
		$serazeno['avg_pwr'] = arrayColumnSortDesc($hraci, "avg_pwr");
    $serazeno['brana'] = arrayColumnSortDesc($hraci, "brana");
    $serazeno['rozloha'] = arrayColumnSortDesc($hraci, "rozloha");
    $serazeno['ix'] = arrayColumnSortDesc($hraci, "ix");
		
		echo '<table>
		<tr>
			<td>no</td>
			<td>ID</td>
			<td>jmeno</td>
			<td>hodnota</td>
		</tr>
		<tr>
			<td colspan=4 style="text-align: center"><br>***Podle prùmìrné síly***<br><br></td>
		</tr>';
		foreach ($serazeno['avg_pwr'] as $key => $value) {
			echo '<tr>
				<td>'.($key+1).'.</td>
				<td>'.$value['id'].'</td>
				<td>'.$value['jmeno'].'</td>
				<td class="right">'.$value['avg_pwr'].'</td>			
			</tr>';
		}
		echo '<tr>
			<td colspan=4 style="text-align: center"><br>***Podle brány***<br><br></td>
		</tr>';
		foreach ($serazeno['brana'] as $key => $value) {
			echo '<tr>
				<td>'.($key+1).'.</td>
				<td>'.$value['id'].'</td>
				<td>'.$value['jmeno'].'</td>
				<td class="right">'.$value['brana'].'</td>			
			</tr>';
		}
		echo '<tr>
			<td colspan=4 style="text-align: center"><br>***Podle rozlohy***<br><br></td>
		</tr>';
		foreach ($serazeno['rozloha'] as $key => $value) {
			echo '<tr>
				<td>'.($key+1).'.</td>
				<td>'.$value['id'].'</td>
				<td>'.$value['jmeno'].'</td>
				<td class="right">'.$value['rozloha'].'</td>			
			</tr>';
		}
		echo '<tr>
			<td colspan=4 style="text-align: center"><br>***Podle IX***<br><br></td>
		</tr>';
		foreach ($serazeno['ix'] as $key => $value) {
			echo '<tr>
				<td>'.($key+1).'.</td>
				<td>'.$value['id'].'</td>
				<td>'.$value['jmeno'].'</td>
				<td class="right">'.$value['ix'].'</td>			
			</tr>';
		}
		echo "</table>";
	}
}

/*function arrayColumnSort()
{
	$n = func_num_args();
	$ar = func_get_arg($n-1);
	if(!is_array($ar))
		return false;
	
	for($i = 0; $i < $n-1; $i++)
		$col[$i] = func_get_arg($i);
	
	foreach($ar as $key => $val)
		foreach($col as $kkey => $vval)
			if(is_string($vval))
			${"subar$kkey"}[$key] = $val[$vval];
	
	$arv = array();
	foreach($col as $key => $val)
		$arv[] = (is_string($val) ? ${"subar$key"} : $val);
	$arv[] = $ar;
	
	call_user_func_array("array_multisort", $arv);
	return $ar;
}*/
/**
 * @param array pole
 * @param char sloupec
 */ 
function arrayColumnSortDesc($pole, $sloupec) {
  $sorting_table = array ();
  foreach ($pole as $key => $val) {
    $sorting_table[$key] = $val[$sloupec];    
  }
  
  arsort($sorting_table);
  
  $new = array ();
  
  foreach ($sorting_table as $key => $foo) {
    $new [] = $pole[$key];
  }
  
  return $new;
}
?>
