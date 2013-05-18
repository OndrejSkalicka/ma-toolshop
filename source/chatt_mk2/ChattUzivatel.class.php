<?php
/**
 * @author Ondrej Skalicka <savannah@seznam.cz>
 * @version 1.0
 * @package MA_Toolshop
 */
/**
 * Trida uzivatele chattu  
 */ 
class ChattUzivatel {
  /**#@+
   * @ignore
   */  
  var $userId,
      $vlastniMistnosti = array (),
      $spravovaneMistnosti = array (),
      $oblibeneMistnosti = array (),
      $lastVisit = 0
      ;
  /**#@-*/
  
  /**
   * Vytvori instanci uzivatele.
   * 
   * id uživatele se považuje za vìrohodné a nekontroluje se s databází
   *      
   * @param integer $userId ID uživatele
   * @param bool $nactiMistnosti maji se nacist mistnosti, jinak volejte {@link nactiMistnosti()}   
   */
  function ChattUzivatel ($userId, $nactiMistnosti = false) {
    $this -> userId = $userId;
    if ($nactiMistnosti) 
      $this -> nactiMistnosti();
  }
  
  /**
   * Nacte vsechny mistnosti
   */     
  function nactiMistnosti() {
    $this -> nactiVlastniMistnosti ();
    $this -> nactiSpravovaneMistnosti ();    
    $this -> nactiOblibeneMistnosti ();
  }
  
  /**
   * Nacte vlastni mistnosti
   * 
   * @access private   
   * @return integer pocet nactenych mistnosti      
   */      
  function nactiVlastniMistnosti () {
    $mistnosti = mysql_query("SELECT `chatt_mistnost`.*, COUNT(`chatt_prispevek`.`idPrispevek`) as `pocetNew`,
                                      `chatt_last_visit`.`since`
                              FROM `chatt_mistnost`
                              LEFT JOIN `chatt_last_visit`
                                ON `chatt_last_visit`.`idMistnost` = `chatt_mistnost`.`idMistnost` 
                                AND `chatt_last_visit`.`idUsers` = `chatt_mistnost`.`idVlastnik`
                              LEFT JOIN `chatt_prispevek`
                                ON `chatt_prispevek`.`idMistnost` = `chatt_mistnost`.`idMistnost`
                                AND `chatt_prispevek`.`time` > `chatt_last_visit`.`since`
                              WHERE `chatt_mistnost`.`idVlastnik` = '". $this -> getId () ."'
                              GROUP BY `chatt_mistnost`.`idMistnost`
                              ORDER BY `chatt_mistnost`.`jmeno`");
    
    $this -> vlastniMistnosti = null;
                      
    while ($m = mysql_fetch_array ($mistnosti)) {
      $tmp = new ChattMistnost ($m['idMistnost'], $m['pocetNew']);
      if ($tmp -> loaded ())
        $this -> vlastniMistnosti [$tmp -> getId()] = $tmp; 
    }
    
    if (is_null($this -> vlastniMistnosti)) {
      $this -> vlastniMistnosti = array ();
      return 0;
    }
    
    return count ($this -> vlastniMistnosti);
  }
  
  /**
   * Nacte oblibene mistnosti
   * 
   * @access private   
   * @return integer pocet nactenych mistnosti      
   */      
  function nactiOblibeneMistnosti () {
    $mistnosti = mysql_query("SELECT `chatt_mistnost`. * , COUNT(`chatt_prispevek`.`idPrispevek`) as `pocetNew`
                              FROM `chatt_mistnost` 
                              INNER JOIN `chatt_last_visit` 
                                ON  `chatt_last_visit`.`idMistnost` = `chatt_mistnost`.`idMistnost` 
                                AND `chatt_last_visit`.`idUsers` = '". $this -> getId () ."'
                              LEFT JOIN `chatt_prispevek`
                                ON  `chatt_prispevek`.`idMistnost` = `chatt_mistnost`.`idMistnost`
                                AND `chatt_prispevek`.`time` > `chatt_last_visit`.`since`
                              WHERE `chatt_last_visit`.`since` >= '".(time() - 60 * 60 * 24 * 7)."'
                              GROUP BY `chatt_mistnost`.`idMistnost`
                              ORDER BY `chatt_mistnost`.`jmeno`");
                              
    $this -> oblibeneMistnosti = null;
                      
    while ($m = mysql_fetch_array ($mistnosti)) {
      $tmp = new ChattMistnost ($m['idMistnost'], $m['pocetNew']);
      if ($tmp -> loaded ())
        $this -> oblibeneMistnosti [$tmp -> getId()] = $tmp; 
    }
    
    if (is_null($this -> oblibeneMistnosti)) {
      $this -> oblibeneMistnosti = array ();
      return 0;
    }

    return count ($this -> oblibeneMistnosti);
  }
  
  /**
   * Nacte spravovane mistnosti
   * 
   * @access private   
   * @return integer pocet nactenych mistnosti      
   */      
  function nactiSpravovaneMistnosti () {
    $mistnosti = mysql_query("SELECT `chatt_mistnost`.* , COUNT(`chatt_prispevek`.`idPrispevek`) as `pocetNew` ,`chatt_last_visit`.`since`  as `since`
                              FROM `chatt_mistnost` 
                              INNER JOIN `chatt_spravce` 
                                ON `chatt_spravce`.`idMistnost` = `chatt_mistnost`.`idMistnost` 
                              LEFT JOIN `chatt_last_visit` 
                                ON `chatt_last_visit`.`idMistnost` = `chatt_mistnost`.`idMistnost` 
                                AND `chatt_last_visit`.`idUsers` = `chatt_spravce`.`idUsers`
                              LEFT JOIN `chatt_prispevek` 
                                ON `chatt_prispevek`.`idMistnost` = `chatt_mistnost`.`idMistnost` 
                                AND `chatt_prispevek`.`time` > `chatt_last_visit`.`since` 
                              WHERE `chatt_spravce`.`idUsers` = '". $this -> getId () ."' 
                              GROUP BY `chatt_mistnost`.`idMistnost` 
                              ORDER BY `chatt_mistnost`.`jmeno`");

   
    $this -> spravovaneMistnosti = null;
                      
    while ($m = mysql_fetch_array ($mistnosti)) {
      $tmp = new ChattMistnost ($m['idMistnost'], $m['pocetNew']);
      if ($tmp -> loaded ())
        $this -> spravovaneMistnosti [$tmp -> getId()] = $tmp; 
    }
    
    if (is_null($this -> spravovaneMistnosti)) {
      $this -> spravovaneMistnosti = array ();
      return 0;
    }
    
    return count ($this -> spravovaneMistnosti);
  }
  
  /**
   * Nastavi uzivateli Last visit k dane mistnosti, ulozi do DB 
   *
   * @param integer $idMistnost ID mistnosti
   * @param string $heslo heslo, v md5
   * @return boolean last visit se povedl        
   */
  function updateLastVisit ($idMistnost, $heslo) {
    // ulozim si pripadny last visit
    if ($this ->  lastVisit == 0) {
      $q = mysql_query("SELECT * 
                        FROM `chatt_last_visit`
                        WHERE `idMistnost` = '".mysql_escape_string($idMistnost)."'
                        AND `idUsers` = '".mysql_escape_string($this -> getId())."'");
                        
      if ($r = mysql_fetch_array($q)) 
        $this -> lastVisit = $r['since'];
    }
    
    // smazu posledni navstevu, if any
    mysql_query("DELETE FROM `chatt_last_visit`
                 WHERE `idMistnost` = '".mysql_escape_string($idMistnost)."'
                 AND `idUsers` = '".mysql_escape_string($this -> getId())."'");
                 
    // nastavim novej last visit
    mysql_query("INSERT INTO `chatt_last_visit` 
                (`idMistnost`, `idUsers`, `since`, `heslo`) 
                VALUES('".mysql_escape_string($idMistnost)."'
                , '".mysql_escape_string($this -> getId ())."'
                , '".time()."'
                , '".mysql_escape_string($heslo)."');");
                
    return (bool)mysql_affected_rows();
  }
  
  /**
   * Vrati vlastni mistnosti jako pole objektu {@link ChattMistnost}
   *
   * return array vlastni mistnosti, array () pri chybe   
   */        
  function getVlastniMistnosti () {
    return $this -> vlastniMistnosti;
  }
  
  /**
   * Vrati spravovane mistnosti jako pole objektu {@link ChattMistnost}
   *
   * return array spravovane mistnosti, array () pri chybe   
   */        
  function getSpravovaneMistnosti () {
    return $this -> spravovaneMistnosti;
  }
  
  /**
   * Vrati oblibene mistnosti jako pole objektu {@link ChattMistnost}
   *
   * return array spravovane mistnosti, array () pri chybe   
   */        
  function getOblibeneMistnosti () {
    return $this -> oblibeneMistnosti;
  }
  
  /**
   * ID uzivatele v DB
   * 
   * @return integer ID      
   */     
  function getId () {
    return $this -> userId;
  }
  
  /**
   * cas posledni navstevy zde
   * 
   * cas se bere z volani {@link updateLastVisit()}
   * 
   * @return integer cas      
   */     
  function getLastVisit () {
    return $this -> lastVisit;
  }
  
}
?>