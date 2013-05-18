<?php
/**
 * @author Ondrej Skalicka <savannah@seznam.cz>
 * @version 1.0
 * @package MA_Toolshop
 */
/**
 * Trida prispevku v chattu  
 */ 
class ChattPrispevek {
  /**#@+
   * @ignore
   */  
  var $dbId, $text, $time, $author = array ();
  /**#@-*/
  
  /**
   * Vytvori instanci prispevku.
   * 
   * @param mixed $loadKey ID prispevku NEBO asoc pole (ekvivalent k vysledku z DB)
   */
  function ChattPrispevek ($loadKey) {
    $this -> dbId = 0;
    
    if (is_array ($loadKey)) {
      $this -> text = $loadKey['text'];
      $this -> time = $loadKey['time'];
      $this -> dbId = $loadKey['idPrispevek'];
      
      $this -> autor['regent'] = $loadKey['regent'];
      $this -> autor['provi']  = $loadKey['provi'];
      $this -> autor['login']  = $loadKey['login'];
      $this -> autor['pwr']    = $loadKey['last_pwr'];
      
      return 1;
    }
    
    $pdb = mysql_query("SELECT `chatt_prispevek`.*, 
                          `users`.`regent`, `users`.`provi`, `users`.`login`, `users`.`last_pwr`
                       FROM `chatt_prispevek`
                       LEFT JOIN `users`
                         ON `users`.`ID` = `chatt_prispevek`.`idUsers`
                       WHERE `idPrispevek` = '".mysql_escape_string($loadKey)."'");

    if (!$p = mysql_fetch_array($pdb))
      return false;
    
    $this -> text = $p['text'];
    $this -> time = $p['time'];
    $this -> dbId = $p['idPrispevek'];
    
    $this -> autor['regent'] = $loadKey['regent'];
    $this -> autor['provi']  = $p['provi'];
    $this -> autor['login']  = $p['login'];
    $this -> autor['pwr']    = $p['last_pwr'];
  }
  
  /**
   * text
   * 
   * @return string text pres {@link nl2br()}, ne {@link htmlspecialchars()} (+ mezery/taby na &nbsp;)
   */ 
  function getText () {
    $text = $this -> text;
    
     // pevne mezery/taby
    $text = str_replace('  ', '&nbsp;&nbsp;', $text);
    $text = str_replace('	', '&nbsp;&nbsp;&nbsp;&nbsp;', $text);
    
    // smajly
    $text = preg_replace('/<<(\d+)>>/', '<img src="img/smiles/$1.png" />', $text);
    
    
    // nl2br
    $text = nl2br ($text);
  
    return $text;
  }
  
  /**
   * cas
   * 
   * @param string $format pokud je nastaveny, tak vrati cas jako fce {@link date()}   
   * @return mixed text
   */ 
  function getTime ($format = '') {
    if ($format)
      return date($format, $this -> time);
    return $this -> time;
  }
  
  /**
   * autor prispevku
   * 
   * vysledek je asoc pole:
   * * 'regent' - jmeno regenta
   * * 'provi' - jmeno provi
   * * 'login' - id hrace 
   * * 'sila' - sila hrace z hlidky
   * 
   * nebo jedna polozka pokud je specifikovano $key (ktere ma hodnoty 'regent', 
   * 'provi' nebo 'login')                   
   * 
   * @param string $key klic ktery se ma poslat   
   * @return mixed autor
   */ 
  function getAutor ($key = '') {
    if (array_key_exists($key, $this -> autor))
      return $this -> autor [$key];
      
    return $this -> autor;
  }
  
  /**
   * id
   * 
   * @return integer id
   */ 
  function getDbId () {
    return $this -> dbId;
  }
}
?>