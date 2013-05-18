<?php
/**
 * @author Ondrej Skalicka <savannah@seznam.cz>
 * @version 1.0
 * @package MA_Toolshop
 */
/**
 * Trida mistnosti chattu  
 */ 
class ChattMistnost {
  /**#@+
   * @ignore
   */  
  var $dbId,
      $loaded = false,
      $heslo,
      $prispevky = array(),
      $onlineUsers = null,
      $banReason = '', $banExpire = 0, $banAuthor = '', // pouzije se jenom kdyz se kontroluje narok uzivatel na forum. Sem se ulozi text banu 
      $spravci = null,
      $newMsg
      ;
  /**#@-*/
  
  /**
   * Vytvori instanci uzivatele.
   * 
   * @param mixed $loadKey bud ID u�ivatele (pokud je cislo), nebo jmeno mistnosti
   * @param int $newMsg pocet novych zprav v mistnosti   
   */
  function ChattMistnost ($loadKey, $newMsg = 0) {
    $this -> newMsg = $newMsg;
    $this -> loaded = $this -> nactiDb ($loadKey);
  }
  
  
  /**
   * Nacte info o mistnosti z DB
   * 
   * @access private   
   * @param mixed $loadKey bud ID u�ivatele (pokud je cislo), nebo jmeno mistnosti   
   * @return bool nacteni se zdarilo      
   */      
  function nactiDb ($loadKey) {
    if (is_numeric($loadKey))
      $mistnost = mysql_query("SELECT * 
                               FROM `chatt_mistnost`
                               WHERE `idMistnost` = '".mysql_escape_string($loadKey)."'");
    else 
      $mistnost = mysql_query("SELECT * 
                               FROM `chatt_mistnost`
                               WHERE `jmeno` = '".mysql_escape_string(strtolower($loadKey))."'");
                               
    if (!$m = mysql_fetch_array($mistnost))
      return false;
    
    $this -> idVlastnik = $m['idVlastnik'];
    $this -> heslo      = $m['heslo'];
    $this -> jmeno      = $m['jmeno'];
    $this -> dbId       = $m['idMistnost'];
    
    return true;
  }
  
  /**
   * Zjisti jestli ma uzivatel na mistnost narok
   * 
   * vraci
   * - '' pokud nema pristup
   * - 'ban' - ma ban   
   * - 'uzivatel' pokud ma na cteni/psani
   * - 'spravce' pokud je spravce
   * - 'vlastnik' pokud je vlastnik                  
   * 
   * @param integer $uId ID uzivatele
   * @param string $heslo heslo uzivatele (po md5)
   * @return string uroven prav/naroku         
   */     
  function maNarok ($uId, $heslo = '') {
    // SUPER USER
    if (mysql_num_rows(mysql_query("SELECT * 
                                    FROM `prava` 
                                    INNER JOIN `pravo_text` 
                                      ON `pravo_text`.`ID` = `prava`.`ID_pravo_text` 
                                      AND `prava`.`ID_users` = '".mysql_escape_string($uId)."'
                                    WHERE `pravo_text`.`text` = 'chat_superuser'")))
      return 'vlastnik';
    
  
    // vlastni
    if ($this -> getIdVlastnik() == $uId) 
      return 'vlastnik';
      
    // spravuje
    if (mysql_num_rows(mysql_query("SELECT * 
                                    FROM `chatt_spravce` 
                                    WHERE `idUsers` = '".mysql_escape_string($uId)."'
                                    AND `idMistnost` = '".$this -> getId() ."'")))
      return 'spravce';

    // BAN!
    if ($ban = mysql_fetch_array(mysql_query("SELECT `chatt_ban`.*, `users`.`regent`, `users`.`login`
                                              FROM `chatt_ban` 
                                              LEFT JOIN `users`
                                                ON `users`.`ID` = `chatt_ban`.`idSpravce`
                                              WHERE `idUsers` = '".mysql_escape_string($uId)."'
                                                AND `idMistnost` = '".$this -> getId() ."'
                                                AND `expire` > '". time () ."'
                                              ORDER BY `expire` DESC"))) {

      $this -> banReason = $ban['reason'];
      $this -> banExpire = $ban['expire'];
      $this -> banAuthor = $ban['regent'] . ' (' . $ban['login'] . ')';
      return 'ban';
    }

    // ma heslo od minule 
    if (mysql_num_rows(mysql_query("SELECT * 
                                    FROM `chatt_last_visit` 
                                    WHERE `idMistnost` = '".mysql_escape_string($this -> getId()) ."'
                                    AND `idUsers` = '".mysql_escape_string($uId)."'
                                    AND `heslo` = '".mysql_escape_string($this -> getHeslo()) ."'")))
      return 'uzivatel';
      
    // ma spravne heslo z formulare
    if ($this -> getHeslo () == $heslo) 
      return 'uzivatel';
    
    return '';
  }
  
  /**
   * Prida zpravu do diskuse
   * 
   * @param string $text text zpravy, bez zadneho formatovani
   * @param integer $uId ID usera ktery pridava, bez kontroly jestli smi!
   * @return bool pridani se provedlo            
   */     
  function addMessage ($text, $uId, $smiles = array ()) {
    if (preg_match ('/^\s*$/', $text))
      return false;
      
    
    // vytvorim smajly
    $text = stripslashes($text);
    
    $text = htmlspecialchars($text);

    foreach ($smiles as $key => $value) {
      $text = preg_replace($value, "<<$key>>", $text);
    }
    
    // check jestli nevklada 2x stejnou zpravu
    $last = 
      mysql_fetch_array(
        mysql_query("SELECT `text`
                     FROM `chatt_prispevek`
                     WHERE `idUsers` = '".(int)$uId."'
                       AND `idMistnost` = '".(int)$this -> getId()."'
                     ORDER BY `time` DESC
                     LIMIT 1"
                    )
                  );
    
     
    if ($last[0] == $text) // duplikat
      return false;
  
    mysql_query("INSERT INTO `chatt_prispevek` 
                (`idUsers`, `idMistnost`, `text`, `time`) 
                VALUES('".(int)$uId."', '".(int)$this -> getId()."', '".mysql_escape_string($text)."', '".time()."');");
    
  }
  
  /**
   * Vrati vsechny bany
   * 
   * vysledek je pole asoc. poli, majici tyto hodnoty:
   * - p_regent 	
   * - p_id 	
   * - p_login 	
   * - p_provi
   * - expire
   * - reason 
   * - s_regent 
   * - s_login 
   * - s_provi
   * - b_id   
   *
   * sortovano je podle:
   * 1. p_login
   * 2. expire           
   *   
   * @return mixed bany     
   */   
  function getBany () {
    $q = mysql_query("SELECT `provinilec`.`regent` as `p_regent`, `provinilec`.`ID` as `p_id`, 
                             `provinilec`.`login` as `p_login`, `provinilec`.`provi` as `p_provi`,
                             `chatt_ban`.`expire`, `chatt_ban`.`reason`, `spravce`.`regent` as `s_regent`, 
                             `spravce`.`login` as `s_login`, `spravce`.`provi` as `s_provi`, `chatt_ban`.`idchatt_ban` as `b_id`
                      FROM `chatt_ban` 
                      INNER JOIN `users` as `provinilec`
                        ON `provinilec`.`ID` = `chatt_ban`.`idUsers`
                      INNER JOIN `users` as `spravce`
                        ON `spravce`.`ID` = `chatt_ban`.`idSpravce`
                      WHERE `idMistnost` = '".(int)$this -> getId()."'
                      ORDER BY `p_login`, `expire` DESC");
          
    $result = array ();
                      
    while ($r = mysql_fetch_array($q)) {
      $result [] = $r;
    }
    
    return $result;
  }
  
  /**
   * Zrusi ban se zadanym ID
   * 
   * @param int $banId ID banu (nikoli uzivatele!)      
   */     
  function zrusBan ($banId) {
    mysql_query("DELETE FROM `chatt_ban`
                 WHERE `idchatt_ban` = '".(int)$banId."'
                   AND `idMistnost` = '".$this -> getId () ."'");
  }
  
  /**
   * Smaze mistnost
   *
   */
  function smazMistonost () {
    if (($id = $this -> getId()) <= 0)
      return false;
      
    mysql_query("DELETE FROM `chatt_ban`
                 WHERE `idMistnost` = '{$id}'");
    mysql_query("DELETE FROM `chatt_last_visit`
                 WHERE `idMistnost` = '{$id}'");
    mysql_query("DELETE FROM `chatt_mistnost`
                 WHERE `idMistnost` = '{$id}'");
    mysql_query("DELETE FROM `chatt_prispevek`
                 WHERE `idMistnost` = '{$id}'");
    mysql_query("DELETE FROM `chatt_spravce`
                 WHERE `idMistnost` = '{$id}'");
    
  }
  
  /**
   * Natahne z databaze prispevky
   * 
   * @param integer $limit limit prispevku      
   */     
  function nactiPrispevky ($limit) {
    $this -> prispevky = array ();
    
    $pdb = mysql_query ("SELECT `chatt_prispevek`.*, 
                          `users`.`regent`, `users`.`provi`, `users`.`login`, `users`.`last_pwr` 
                         FROM `chatt_prispevek` 
                         LEFT JOIN `users`
                          ON `users`.`ID` = `chatt_prispevek`.`idUsers`
                         WHERE `idMistnost` = '".mysql_escape_string($this -> getId())."'
                         ORDER BY `time` DESC
                         LIMIT ". (int)$limit);
                       
    while ($p = mysql_fetch_array($pdb)) {
      $this -> prispevky[$p['idPrispevek']] = new ChattPrispevek ($p);
    }
      
  }
  
  /**
   * Zjisti kdo je v dane mistnosti online, vrati pole, pokud se jeste nenacetlo, tak natahne z DB
   * 
   * Vysledek je asociativni pole:
   * - 'nick' (varchar)
   * - 'icq' (integer)
   * - 'id' (integer)
   * - 'spravce' (true/false)
   * - 'vlastni' (true/false)         
   *      
   * @return array pole online uzivatelu
   */        
  function whoIsOnline () {
    if (is_null($this -> onlineUsers)) {
      $q = mysql_query("SELECT `users`.`ID`, `users`.`regent`, `users`.`provi`, `users`.`login`, `users`.`last_pwr`,`users`.`icq`, `chatt_spravce`.`idUsers` as `spravce`, `chatt_mistnost`.`idMistnost` as `vlastnik`, `prava`.`ID_pravo_text` as `super_user`, `chatt_last_visit`.`since`
                        FROM `chatt_last_visit` 
                        INNER JOIN `users`
                          ON `users`.`ID` = `chatt_last_visit`.`idUsers`
                        LEFT JOIN `chatt_spravce`
                          ON `users`.`ID` = `chatt_spravce`.`idUsers`
                          AND `chatt_spravce`.`idMistnost` = `chatt_last_visit`.`idMistnost`
                        LEFT JOIN `chatt_mistnost`
                          ON `chatt_mistnost`.`idMistnost` = `chatt_last_visit`.`idMistnost`
                          AND `chatt_mistnost`.`idVlastnik` = `users`.`ID`
                        LEFT JOIN `prava`
                          ON `prava`.`ID_users` = `users`.`ID`
                          AND `prava`.`ID_pravo_text` = '26'
                        WHERE `chatt_last_visit`.`idMistnost` = '".mysql_escape_string($this -> getId())."'
                          AND `chatt_last_visit`.`since` >= '".(time() - 60*5)."'
                        ORDER BY `users`.`last_pwr` DESC, `users`.`login`");
    
      $this -> onlineUsers = array ();
      
      while ($p = mysql_fetch_array($q)) {
        $idle_time = time () - $p['since'];
        
        if ($idle_time > 24*60*60)
          $idle_time_s = '24h+';
        else {
          $idle_time_s = '';
          $h = (int)($idle_time / 3600);
          $m = sprintf ("%02.0f", (int)(($idle_time/60) % 60));
          $s = sprintf ("%02.0f", (int)($idle_time%60));
          if ((int)$h)
            $idle_time_s .= "{$h}h ";
          if ((int)$m)
            $idle_time_s .= "{$m}m ";
          if ($idle_time_s)
            $idle_time_s .= "{$s}s";
          else $idle_time_s = (int)$s . 's';
        }
           
        
        $this -> onlineUsers [] = array ('regent'     => htmlspecialchars($p['regent']),
                                         'provi'      => htmlspecialchars($p['provi']),
                                         'icq'        => (int)$p['icq'],
                                         'pwr'        => (int)$p['last_pwr'],
                                         'idle_time'  => $idle_time_s,
                                         'login'      => (int)$p['login'],
                                         'id'         => (int)$p['ID'],
                                         'spravce'    => ($p['spravce'] ? true : false),
                                         'vlastnik'   => (($p['vlastnik'] || $p['super_user']) ? true : false),
                                         'superUser'  => ($p['super_user'] ? true : false));
      }
    }
    
    
    return $this -> onlineUsers;
  }
  
  /**
   * Prida uzivateli banana. Zadne kontroly se neprovadi
   * 
   * @param integer $uId ID uzivatele na zablokovani
   * @param integer $spravceId ID spravce ktery vydal rozkaz
   * @param string $reason duvod, string, bez {@link htmlspecialchars()}
   * @param integer $expire cas {@link time()} kdy ban vyprsi   
   * @return boolean zdarilo se
   */     
  function banUser ($uId, $spravceId, $reason, $expire) {
    // smazu pripadne spravcovsti
    mysql_query("DELETE FROM `chatt_spravce`
                 WHERE `idMistnost` = '".(int)$this -> getId()."'
                   AND `idUsers` = '".(int)$uId."';");
                   
    // vyhodim ho z mistnosti
    mysql_query("DELETE FROM `chatt_last_visit`
                 WHERE `idMistnost` = '".(int)$this -> getId()."'
                   AND `idUsers` = '".(int)$uId."';");
    
    // pridam bananek
    mysql_query("INSERT INTO `chatt_ban` 
                (`idSpravce`, `idUsers`, `idMistnost`, `expire`, `reason`) 
                VALUES('".(int)$spravceId."', 
                       '".(int)$uId."', 
                       '".(int)$this -> getId()."', 
                       '".(int)$expire."', 
                       '".mysql_escape_string(htmlspecialchars($reason))."');");
                       
    return (bool)mysql_affected_rows();
  }
  
  /**
   * Prida/odebere mistnosti spravce. Zadne kontroly se neprovadi
   * 
   * @param integer $uId ID uzivatele na spravovani
   * @param string $grantDeny pridat nebo odebrat prava (moznosti 'grant' a 'deny')
   * @return boolean zdarilo se   
   */     
  function pridejSpravce ($uId, $grantDeny) {
    if ($grantDeny == 'grant') {
      // pridavam spravcovsti
      mysql_query("INSERT INTO `chatt_spravce` 
                   (`idUsers`, `idMistnost`) 
                   VALUES('".(int)$uId."', '".(int)$this -> getId()."');");
    
      return (bool)mysql_affected_rows();
    } elseif ($grantDeny == 'deny') {
      // smazu spravcovsti
      mysql_query("DELETE FROM `chatt_spravce`
                   WHERE `idMistnost` = '".(int)$this -> getId()."'
                     AND `idUsers` = '".(int)$uId."';");
    
      return (bool)mysql_affected_rows();
    } else return false;
                       
    
  }
  
  /**
   * Zrusi jednoho nebo vice spravcu
   * 
   * klic k ruseni je ID spravce
   *      
   * @param mixed $spravce pokud je parametr pole, zrusi spravce ktere jsou zadane prvky pole, jinak zrusi konkretniho
   */
  function zrusSpravce ($spravce) {
    $q = "DELETE FROM `chatt_spravce`
          WHERE `idMistnost` = '".(int)$this -> getId()."' ";
    if (is_array ($spravce)) {
      $q .= "AND (0 ";
      foreach ($spravce as $value)
        $q .= "OR `idUsers` = '".(int)$value."' ";
        
      $q .= ")";
    } else {
      $q .= "AND `idUsers` = '".(int)$spravce."'";
    }
    
    mysql_query($q);
  } 
  
  /**
   * Vrati seznam spravcu jako pole asoc. poli, obsahujici
   * - 'login'
   * - 'regent'
   * - 'provi'      
   * - 'id'   
   * 
   * volani se bufferuje   
   *   
   * @return array seznam spravcu   
   */        
  function getSpravci () {
    if (!is_array($this -> spravci)) {
      $this -> spravci = array ();
      
      $q = mysql_query("SELECT `users`.*
                        FROM `chatt_spravce`
                        INNER JOIN `users`
                          ON `chatt_spravce`.`idUsers` = `users`.`ID`
                        WHERE `chatt_spravce`.`idMistnost` = '".(int)$this -> getId()."'");
      
      while ($u = mysql_fetch_array($q)) {
        $this -> spravci[] = array ('login' => $u['login'],
                                    'regent' => $u['regent'],
                                    'provi' => $u['provi'],
                                    'id' => $u['ID']);
      }
    }
    
    return $this -> spravci;
  }
  
  /**
   * Mistnost je nactena
   * 
   * @return bool mistnost je nactena      
   */     
  function loaded () {
    return $this -> loaded;
  }
  
  /**
   * ID mistnosti v DB
   * 
   * @return integer ID      
   */     
  function getId () {
    return $this -> dbId;
  }
  
  /**
   * ID vlastnika v DB
   * 
   * @return integer ID      
   */     
  function getIdVlastnik () {
    return $this -> idVlastnik;
  }
  
  /**
   * Jmeno mistnosti
   * 
   * @return string Jmeno mistnosti, v {@link htmlspecialchars()}
   */     
  function getJmeno () {
    return htmlspecialchars($this -> jmeno);
  }
  
  /**
   * heslo mistnosti v md5
   * 
   * @return string heslo mistnosti
   */     
  function getHeslo () {
    return $this -> heslo;
  }
  
  /**
   * duvod k banu
   *
   * pouzije se jenom kdyz se kontroluje narok uzivatel na forum. Sem se ulozi text banu
   *        
   * @return string ban reason, prekodovano pomoci {@link htmlspecialchars()}
   */
  function getBanReason () {
    return htmlspecialchars($this -> banReason);
  }
  
  /**
   * expire
   *
   * pouzije se jenom kdyz se kontroluje narok uzivatel na forum. Sem se ulozi text banu
   *        
   * @param string $format format, viz {@link date()}   
   * @return mixed ban expire, pokud je $format nastaven, tak vraci string, jinak integer
   */
  function getBanExpire ($format = '') {
    if ($format)
      return date($format, $this -> banExpire);
    return $this -> banExpire;
  }
  
  /**
   * autor banu
   *
   * pouzije se jenom kdyz se kontroluje narok uzivatel na forum. Sem se ulozi text banu
   *        
   * @return string autor banu, prekodovano pomoci {@link htmlspecialchars()}
   */
  function getBanAuthor () {
    return htmlspecialchars($this -> banAuthor);
  }
  
  /**
   * Vrati prispevky teto mistnosti
   * 
   * @return array pole prvku {@link ChattPrispevek}      
   */     
  function getPrispevky () {
    return $this -> prispevky;
  }
  
  /**
   * Vrati pocet novych prispevku teto mistnosti
   * 
   * @return int pocet prispevku      
   */     
  function getNewMsg () {
    return $this -> newMsg;
  }
  
  
}
?>
