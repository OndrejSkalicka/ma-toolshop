<?php
  function novaBrana () {
    if ($_POST['prekopiruj_vsechny']) {
      $toCopy = MySQL_Query ("SELECT * FROM `Branky` WHERE `ID_veky` = '{$_POST['nova']['vek_old']}'");
    } else {
      $toCopy = MySQL_Query ("SELECT * FROM `Branky` WHERE `ID_veky` = '{$_POST['nova']['vek_old']}' 
                                                       AND `cislo` = '{$_POST['nova']['cislo']}'
                                                       AND `strana` = '{$_POST['nova']['strana']}'");
    }
    
    $error = 0;
    
    while ($new = mysql_fetch_array($toCopy)) {
      if (!MySQL_Query ("DELETE FROM `Branky` WHERE `ID_veky` = '{$_POST['vek']}' 
                                                AND `cislo` = '{$new['cislo']}'
                                                AND `strana` = '{$new['strana']}'")) 
        $error ++;
      
      $query = "INSERT INTO `Branky` ( `ID` , `cislo` , `strana` , `obranci` , `ID_veky` , `zobraz_prefix` ) 
                                          VALUES ( '' , '{$new['cislo']}', '{$new['strana']}', '{$new['obranci']}', '{$_POST['vek']}', '{$new['zobraz_prefix']}');";
      
      if (!MySQL_Query ($query)) {
        $error ++;
      }
    }
  }
?>
