<?php
  function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return round (1000*((float)$usec + (float)$sec));
  }
  
  $scriptStart = microtime_float ();
  
  require 'dblogin.php';
  require '../bin/smarty/Smarty.class.php';
  
  $smarty = new Smarty ();
  
  $jednotky_q = mysql_query("SELECT * FROM MA_units 
                             WHERE ID_veky = 9
                               AND brankar = 0
                               AND dmg > 1
                             ORDER BY barva, pwr");
                           
  $jednotky = array ();
  while ($jednotka = mysql_fetch_array($jednotky_q)) {
    $jednotky [] = $jednotka;
  }       
  
  $detail_q = mysql_query("SELECT * FROM MA_units 
                             WHERE ID_veky = 9
                               AND brankar = 0
                               AND dmg > 1
                               AND jmeno = '".mysql_escape_string($_GET['detail'])."'
                             ORDER BY barva, pwr");
                             
  $detail = mysql_fetch_array($detail_q);
  
  $smarty -> assign ('detail', $detail);
  $smarty -> assign ('jednotky', $jednotky);
  $smarty -> assign ('totalTime', microtime_float () - $scriptStart);
  $smarty -> display ('jednotky/index.tpl');
?>