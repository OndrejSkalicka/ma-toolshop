<?php
function rozloz_text ($text) {
  global $user_info;
  //             poradi    -1-. barva 2. ID   -3- hvezdy jmeno 4.povolani                                                    5. sila
  preg_match_all ('/\n[^\d]*[0-9]+\.\s+([SMBCZF]\s+)?([0-9]{4,})\s+(\*)*.*(Amazonka|Vìdma|Mág|Alchymista|Váleèník|Klerik|Hranièáø|Druid|Nekromant|Theurg|Iluzionista|Barbar)\s+([0-9]+)/', $text, $hraci);

  preg_match ('/Zlato:\s+(\d+)/', $text, $zlato);
    
  if ($zlato[1] > 0) {
    MySQL_Query ("UPDATE `users` SET `zlato` = '".$zlato[1]."' WHERE `ID` = '".$user_info['ID']."'");
  }
  
  echo '<table>
  <tr>
    <td>ID</td>
    <td>Jmeno</td>
    <td>Provi</td>
    <td>ICQ</td>';
  if (HLIDKUJ_A_BUDES_HLIDAN) {
    echo '<td class="right">Zbyva</td>';
  }
  echo '
    <td class="right">Sila pred</td>
    <td class="right">Sila po</td>
    <td class="right">Zmena ABS</td>
    <td class="right">Zmena REL</td>
    <td><strong>STAV</strong></td>
  </tr>
  ';
  
  $upraveno = 0;
  $poklesu = 0;
  
  //$poklesyHracuOdeslano = ''; // debug 
  
  for ($i = 0; $hraci[0][$i]; $i ++) {
    $upraveno = 1;
    $poklesu += updatuj_hrace($hraci[2][$i], $hraci[5][$i]);
    
  }
  
  echo "</table>";
  
  if ($poklesu > 0) {
    if ($user_info['ID_ali_v'] == 16) {
      // Strazci
      echo '<script>
          alert("Celkem '.$poklesu.' strážcùm poklesl, prohoò je!!!");
          </script>';
    } /*elseif ($user_info['id_ali_v'] == -1) {
      // Atlantis
    }*/ else {
      echo '<script>
          alert("Celkem '.$poklesu.' hráèù, kteøí si vyžádali prozvonìní (nebo jim nedošel mail), pokleslo, prozvoò je!");
          </script>';
    }
  }
  
  return $upraveno;
}
function najdi_ma_cas ($text) {
  preg_match ('#\d{4}/\d{2}/\d{2} \d{2}:\d{2}:\d{2}#', $text, $vysledek);
  
  $cas = strtotime ($vysledek[0]);
  
  return $cas;
}
?>

