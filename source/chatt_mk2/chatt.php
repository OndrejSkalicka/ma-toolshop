<?php
/**
 * @author Ondrej Skalicka <savannah@seznam.cz>
 * @version 1.0
 * @package MA_Toolshop
 */
/**
 * Hlavni fce chattu
 */ 
function chatt_main () {
  echo 'chat je vyøazen kvùli èastém pøetìžování serveru. Je mi líto, ale dokud nenajdu èas, kdy ho budu moct opravit, tak se nevrátí. Savannah';
  return;
  global $user_info;
  
  require_once '../bin/smarty/Smarty.class.php';
  require_once './chatt_mk2/ChattUzivatel.class.php';
  require_once './chatt_mk2/ChattMistnost.class.php';
  require_once './chatt_mk2/ChattPrispevek.class.php';
  require_once './chatt_mk2/smajly.php';
  
  $zpravNaStranuMoznosti = array (1 => 5, 10, 20, 50, 100);
  $refreshMoznosti       = array (5 => '5 s', 15 => '15 s', 30 => '30 s', 60 => '1 min', 300 => '5 min', 900 => '15 min', 999999999 => 'nikdy');
  $bananyMoznosti        = array (300 => '5 minut', 600 => '10 minut', 1800 => '30 minut', 3600 => '1 hodina', 3600*6 => '6 hodin', 3600*24 => '1 den', 3600*24*7 => 'týden');
    
    
  
  $chattUzivatel = new ChattUzivatel ($user_info['ID']);
  
  $smarty = new Smarty ();
  
  $smarty -> assign ('chattUzivatel', $chattUzivatel);  
  
  $smarty -> template_dir = '/templates/chatt2'; 
  
  // robi novou mistnost
  if ($_POST['novaMistnost']) {
    $chyba = array ();
    
    if ($_POST['novaHeslo'] != $_POST['novaHeslo2'])
      $chyba['heslaMismatch'] = true;
    
    if ($_POST['novaHeslo'] != '' && !preg_match('/^[A-z0-9 _-]{5,20}$/', $_POST['novaHeslo']))
      $chyba['hesloSpatne'] = true;
      
    if (!preg_match('/^[A-z0-9 _-]{3,20}$/', $_POST['novaJmeno']))
      $chyba['jmenoSpatne'] = true;
      
    // posral to
    if ($chyba) {
      $smarty -> assign ('chyba', $chyba);
      $smarty -> display ('novaMistnost.tpl');
      return 0;
    }
    
    // zkusim vlozit
    mysql_query("INSERT INTO `chatt_mistnost` 
                (`idVlastnik`, `heslo`, `jmeno`) 
                VALUES('".(int) $user_info['ID']."' , '".md5($_POST['novaHeslo'])."', '".mysql_escape_string($_POST['novaJmeno'])."')");
    
    if (mysql_affected_rows() < 1) { // nevyslo mu vlozeni, duplicita jmena
    	$chyba['jmenoDuplicita'] = true;
    	$smarty -> assign ('chyba', $chyba);
      $smarty -> display ('novaMistnost.tpl');
      return 0;
    }
    
    $smarty -> display ('novaMistnost.tpl');
    
    return 1;
  }
  
  // sprava mistnosti
  if ($_REQUEST['chid'] && $_REQUEST['spravovat']) {
    $mistnost = new ChattMistnost ((int)$_REQUEST['chid']);
    
    // neexistuje
    if (!$mistnost -> loaded()) {
      $smarty -> display ('nepovolenyPristup.tpl');
      
      return 0;
    }
    
    // nema prava
    if ($mistnost -> maNarok ($user_info['ID'], md5($_REQUEST['vstupHeslo'])) != 'vlastnik') {
      $smarty -> display ('nepovolenyPristup.tpl');
      
      return 0;
    }
  
    // ma prava na mistnost
    
    // rusi mistnost
    if ($_POST['zrus_mistnost']) {
      $mistnost -> smazMistonost ();
      $chattUzivatel -> nactiMistnosti ();
      $smarty -> assign ('chattUzivatel', $chattUzivatel);  
      $smarty -> display ('seznam_mistnosti.tpl');
      return 1;
    }
    
    // meni heslo
    if ($_POST['zmenaHesla']) {
      if ((md5($_POST['old']) != $mistnost -> getHeslo ()) || ($_POST['new1'] != $_POST['new2'])) {
        echo "hesla se neshodují!<br />";      
      } elseif ($_POST['new1'] != '' && !preg_match('/^[A-z0-9 _-]{5,20}$/', $_POST['new1'])) {
        echo "heslo musí být prázdné nebo 5 alfanum. znakù + mezera, pomlèka, podtržítko.<br />";
      } else {
        mysql_query("UPDATE `chatt_mistnost`
                     SET `heslo` = '".md5($_POST['new1'])."'
                     WHERE `idMistnost` = '".(int)$mistnost -> getId()."'");
                     
        $mistnost -> heslo = md5 ($_POST['new1']);
      }
    }
    
    // rusi spravce
    if ($_POST['zrusSpravce'] && is_array ($_POST['zrusSpravceLogin'])) {
      $tmp = array ();
      foreach ($_POST['zrusSpravceLogin'] as $key => $value)
        if ($value) // chci ho smazat
          $tmp [] = $key; // pridam na black list
          
      if ($tmp)
        $mistnost -> zrusSpravce ($tmp); 
    }
    
    // pridava spravce
    if ($_POST['newSpravce']) {
      if ((int)$_POST['newSpravceId'] == $user_info['login']) {
        echo 'Nemùžete být správcem vlastní místnosti';
      } else {
        $tmp = mysql_fetch_array(mysql_query("SELECT `ID` FROM `users` WHERE `login` = '".(int)$_POST['newSpravceId']."'"));
        if ($id = $tmp[0]) { // id existuje
          $mistnost -> pridejSpravce ($id, 'grant');
        } else {
          echo 'Chyba - takovy uzivatel neni!'; 
        }
      }
    }
    
    // rusi ban
    if ($_POST['zrusBany'] && is_array ($_POST['zrusBanId'])) {
      foreach ($_POST['zrusBanId'] as $key => $value)
        if ($value) // chci ho smazat
          $mistnost -> zrusBan ($key);
    }
    
    if ($_POST['pridejBan']) {
      echo "bananuju";
      $tmp = mysql_fetch_array(mysql_query("SELECT `ID` FROM `users` WHERE `login` = '".(int)$_POST['newBanLogin']."'"));
      if ($id = $tmp[0]) { // id existuje
        $mistnost -> banUser ($id, $user_info['ID'], $_POST['newBanReason'], time() + $_POST['newBanExpire']);
      } else {
        echo "Neexistující uživatel!";
      }
    }    
    
    $smarty -> assign ('bananyMoznosti', $bananyMoznosti);
    $smarty -> assign ('mistnost', $mistnost);
    $smarty -> display ('spravaMistnosti.tpl');
    return 1;
  }
  
  // chce seznam mistnosti
  if (!isset($_REQUEST['chid']) && !isset($_REQUEST['vstupJmeno'])) {
    $chattUzivatel -> nactiMistnosti ();
    $smarty -> assign ('chattUzivatel', $chattUzivatel);  
    $smarty -> display ('seznam_mistnosti.tpl');
    return 1;
  }

  // chce vstoupit do mistnosti
  if (is_numeric($_REQUEST['chid']) || $_REQUEST['vstupJmeno']) {
    // kouknu jestli existuje
    if ($_REQUEST['vstupJmeno']) // zkousi to pres jmeno
      $mistnost = new ChattMistnost ($_REQUEST['vstupJmeno']);
    else // ma ID
      $mistnost = new ChattMistnost ((int)$_REQUEST['chid']);
    
    // neexistuje
    if (!$mistnost -> loaded()) {
      $smarty -> display ('nepovolenyPristup.tpl');
      
      return 0;
    }
    
    // nema prava
    if (!$narok = $mistnost -> maNarok ($user_info['ID'], md5($_REQUEST['vstupHeslo']))) {
      $smarty -> display ('nepovolenyPristup.tpl');
      
      return 0;
    }
    
    
    // ma BAN
    if ($narok == 'ban') {
      $smarty -> assign ('mistnost', $mistnost);
      $smarty -> display ('banned.tpl');
      
      return 0;
    }
    
    // VSTOUPIL DO MISTNOSTI
    // smazu stary banany
    mysql_query("DELETE FROM `chatt_ban` WHERE `expire` <= '". time () . "'");

    $chattUzivatel -> updateLastVisit ($mistnost -> getId (), $mistnost -> getHeslo ()); 
    
    if ($_POST['ch2_zpravNaStranu'])
      $user_info['chatt_ppp'] = $_POST['ch2_zpravNaStranu'];
    if ($_POST['ch2_refresh'])
      $user_info['chatt_refresh'] = $_POST['ch2_refresh'];
    if (!in_array ($user_info['chatt_ppp'], $zpravNaStranuMoznosti))
      $user_info['chatt_ppp'] = $zpravNaStranuMoznosti[3];
    if (!array_key_exists($user_info['chatt_refresh'], $refreshMoznosti))
      $user_info['chatt_refresh'] = 900;
     
    $smarty -> assign ('chattUzivatel', $chattUzivatel); 
    $smarty -> assign ('zpravNaStranuMoznosti', $zpravNaStranuMoznosti);
    $smarty -> assign ('zpravNaStranu', $user_info['chatt_ppp']);
    $smarty -> assign ('refreshMoznosti', $refreshMoznosti);
    $smarty -> assign ('refresh', $user_info['chatt_refresh']);
    $smarty -> assign ('bananyMoznosti', $bananyMoznosti);
    $smarty -> assign ('smileList', $smileList);
    
    
    
    mysql_query("UPDATE `users` 
                 SET `chatt_refresh` = '".(int)$user_info['chatt_refresh']."',
                     `chatt_ppp` = '".(int)$user_info['chatt_ppp']."'
                 WHERE `ID` = '".(int)$user_info['ID']."'");
                 
    
    
    // bananuje!
    if ($_POST['banan'] 
        && ($narok == 'vlastnik' || $narok == 'spravce')) { // smi banovat
      // kontrola jestli je dost vysoko dat mu banana
      $bananRank = $mistnost -> maNarok ($_POST['bananId']);
      
      if (array_key_exists($_POST['expire'], $bananyMoznosti))
        $doba = time () + $_POST['expire'];
      else $doba = 0; 
      
      if ($narok == 'vlastnik' && $bananRank != 'vlastnik' && $doba) {
        if ($mistnost -> banUser($_POST['bananId'], 
                                 $chattUzivatel -> getId (),
                                 $_POST['reason'],
                                 $doba))
          echo "Vlastnik banuje $bananRank<br />";
        else echo "Whoops, bug, ban nepridan";
      }
      elseif ($narok == 'spravce' && $doba && $bananRank != 'vlastnik' && $bananRank != 'spravce') {
        if ($mistnost -> banUser($_POST['bananId'], 
                                 $chattUzivatel -> getId (),
                                 $_POST['reason'],
                                 $doba))
          echo "Spravce banuje  $bananRank<br />";
        else echo "Whoops, bug, ban nepridan";
      } 
    } elseif ($_POST['sprava'] && $narok == 'vlastnik') {// spravuje a smi
      // nesmi byt sam spravce
      if ($_POST['spravaId'] != $chattUzivatel -> getId()) {
        if ($mistnost -> pridejSpravce ($_POST['spravaId'], strtolower($_POST['grantDeny'])))
          echo "Vlastnik pridal spravu<br />";
        else echo "Whoops, bug, sprava nepridana";
      }
    }
    
    // vklada zpravu
    if ($_POST['send']) {
      $mistnost -> addMessage ($_POST['message'], $chattUzivatel -> getId(), $smilePreg);
    }
    
    $mistnost -> nactiPrispevky ($user_info['chatt_ppp']);
    
    $smarty -> assign ('mistnost', $mistnost);
    $smarty -> assign ('uzivatelNarok', $narok);
    $smarty -> display ('main.tpl');
    
    return 1;
  }
  
  return 1;
}
?>