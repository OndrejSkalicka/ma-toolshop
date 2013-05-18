<?php
function VytiskniRadek ($jednotka) {
  $typ = ($jednotka['druh'] == "Poz." ? "P" : "L");
	$druh = ($jednotka['typ'] == "Boj." ? "B" : "S");
	global $$typ, $$druh, $cost_2, $min_pwr;
	if (($$typ == "checked") && ($$druh == "checked") && 
			(($cost_2 == 0) || ($cost_2 >= $jednotka['cenaZaK'])) &&
			(($min_pwr == 0) || ($min_pwr <= $jednotka['sila']))
		)
  	echo '<tr>
  		<td'.($_SESSION['sort'] == 'jmeno' ? ' class="b"' : '').'>'.$jednotka['jmeno'].($jednotka['bere'] ? ' [ '.$jednotka['bere'].' ]' : '').'</td>
  		<td class="c"><div class="'.$jednotka['barva'].'">'.$jednotka['barva'].'</div></td>
  		<td class="r'.($_SESSION['sort'] == 'pocet' ? '  b' : '').'">'.cislo ($jednotka['pocet']).'</td>
  		<td class="c'.($_SESSION['sort'] == 'ini' ? '  b' : '').'">'.$jednotka['ini'].'</td>
  		<td class="r'.($_SESSION['sort'] == 'xp' ? '  b' : '').'">'.$jednotka['xp'].' %</td>
  		<td class="r'.($_SESSION['sort'] == 'pwr' ? '  b' : '').'">'.$jednotka['pwr'].'</td>
  		<td'.($_SESSION['sort'] == 'typ' ? ' class="b"' : '').'>'.$jednotka['typ'].'</td>
      <td'.($_SESSION['sort'] == 'druh' ? ' class="b"' : '').'>'.$jednotka['druh'].'</td>
      <td class="r'.($_SESSION['sort'] == 'zl_tu' ? '  b' : '').'">'.cislo ($jednotka['zl_tu']).'</td>
      <td class="r'.($_SESSION['sort'] == 'mn_tu' ? '  b' : '').'">'.cislo ($jednotka['mn_tu']).'</td>
      <td class="r'.($_SESSION['sort'] == 'pp_tu' ? '  b' : '').'">'.cislo ($jednotka['pp_tu']).'</td>
  		<td class="r'.($_SESSION['sort'] == 'sila' ? '  b' : '').'">'.cislo ($jednotka['sila']).'</td>
      <td class="r'.($_SESSION['sort'] == 'cenaZaK' ? '  b' : '').'">'.cislo ($jednotka['cenaZaK'], 1).'</td>
     	<td class="r'.($_SESSION['sort'] == 'nabidka' ? '  b' : '').'">'.cislo ($jednotka['nabidka']).' ('.(preg_match('/:/',$jednotka['cas']) ? $jednotka['cas'] : '0:00').')</td>
  		</tr>';
}
function cislo ($cislo, $float = 0)
{
  if (is_numeric($cislo))
    return number_format($cislo, $float, '.', ',');
  else return $cislo;
}
function array_key_multi_sort($arr, $l , $f='strnatcasecmp')
{
   usort($arr, create_function('$a, $b', "return $f(\$a['$l'], \$b['$l']);"));
   return($arr);
}
function array_key_multi_sort_d($arr, $l , $f='strnatcasecmp')
{
   usort($arr, create_function('$a, $b', "return $f(\$b['$l'], \$a['$l']);"));
   return($arr);
}
?>
