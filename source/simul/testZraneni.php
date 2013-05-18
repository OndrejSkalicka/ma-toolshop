<?php 
  require 'zraneni.class.php';
  
  $zraneni = new Zraneni ();
  
  for ($i = 1; $i < 100; $i ++) 
    echo ($i*10)." => ".round($zraneni->zjistiZraneni ($i / 100) * 1000)."<br />";
?>
