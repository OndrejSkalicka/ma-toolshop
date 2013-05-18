<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */

class CJednotka
{
	var $jmeno, $dmg, $zvt, $brn, $brn_zbyva, $ini, $typ, $druh, $phb, $pwr, $pocet, $pocet_max, $pocetZranenych, 
		$cs, $xp, $hrac, $error, $ID, $turn_dam_mod, $hlas_krve, $overflow,
		$utok_na;   //hrac - 1 utoci, 2 brani

    function CJednotka ($jmeno, $pocet, $xp, $hrac, $vek = null)	//nacte info o jednotce z DB
    {
      $this->error = 0;
      
      if (is_null($vek)) {
        if ($hrac == 1)
          $vek = $_REQUEST['vek_hrac'];
        else 
          $vek = $_REQUEST['vek'];
      }
		  
		  $jmeno = trim($jmeno);
		  $xp = trim ($xp);
		  $pocet = trim ($pocet);
		  
		  /* kontrola jestli nema jit na pocet */
		  if (NA_POCET) {
  		  $new = preg_replace (NA_POCET, '', $jmeno);
  		  if ($new == $jmeno) {
  		  		$this->utok_na = "dmg";
  		  } else {
  		  		$jmeno = $new;
  		  		$this->utok_na = "pocet";
  		  }
		  } else {
        $this->utok_na = "dmg";
      }
		  
      $this->xp = max (min ($xp,100),1);
      $this->hrac = $hrac;
      $this->pocet_max = $this->pocet = max(1,$pocet);
      $this->pocetZranenych = 0;
		  $this->jmeno = $jmeno;
		  $this->hlas_krve = 1;
		  $this->turn_dam_mod = 1;
		  $this->overflow = 0;
		  
		  // osefovani toho, abych pro N branky (a branka 0)nemusel davat novy jednotky s `brankar` = 1 -> upraveno, protoze Nka uz maji taky svoje jednotky
		  global $branka;
		  $hrac_orig = $hrac;
		  if (/*($branka > 20)||(*/$branka == 0/*)*/)
		  {
				$hrac = 1;		  
		  }
		  //------------------------------------------------------------------------------
// 		  $add = "";
// 		  if ($hrac == 2) 
		  		$add = " AND `ID_veky` = '".$vek."'";
		  
      $unit = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '$jmeno' AND `brankar` = '".($hrac-1)."'$add");
		  
		  if (MySQL_Num_Rows ($unit) == 0) $unit = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '%$jmeno%' AND `brankar` = '".($hrac-1)."'$add");
		  
		  $hrac = $hrac_orig;
		  
		  if (MySQL_Num_Rows ($unit) == 0) $this->error = 1;
		  if (!Is_Numeric ($pocet)) $this->error = 2;
		  if (!Is_Numeric ($xp)) $this->error = 3;
		  if (!Is_Numeric ($hrac)) $this->error = 4;
		  
        if (!$unit_db = MySQL_Fetch_Array($unit))
		  {
//		  	$this->jmeno = "";
		  }
		  else
		  {
		  	$this->jmeno = $unit_db['jmeno'];
			$this->dmg = $unit_db['dmg'];
			$this->zvt = $unit_db['zvt'];
			$this->brn = $unit_db['brn'];
			$this->ini = $unit_db['ini'];
			$this->typ = $unit_db['typ'];
			$this->druh = $unit_db['druh'];
			$this->phb = $unit_db['phb'];
			$this->pwr = $unit_db['pwr'];
			$this->ID = $unit_db['ID'];
		  }
        $this->brn_zbyva = $this->brn;
        return 1;
    }
    function StitovaniNaDmg ($stituj_na = "dmg")
    {
//2do	 	if ($stituj_na == "pocet"
		return $this->pocet*$this->$stituj_na;
    }
    
    function zivotyZraneneJednotky () {
      return max(1, $this->zvt * ZRAN_ZIVOTY);
    }
    
    function pocetZranenychPercent ($zabito = 0, $dorazeno = 0) {
      return @round(($this->pocetZranenych - $dorazeno) / ($this->pocet - $zabito - $dorazeno), 2);
    }
    
    function getDamage () {
      global $SHOW_STATS, $DAM_MOD;
      
      $dmg = $this->dmg * $this->xp * $this->pocet / 100 * $this->hlas_krve;
      
      if ($SHOW_STATS == 1) echo '<table class="info">';
      if ($SHOW_STATS == 1) echo "<tr><td>Dmg zaklad: </td><td>dam: ".cislo($dmg)."</tr>\n";
      $dmg = $dmg * $DAM_MOD;
      if ($SHOW_STATS == 1) echo "<tr><td>Modifikator 'ATV' = ".$DAM_MOD."</td><td>dam: ".cislo($dmg)."<br>\n";
      $dmg = $dmg * $this->turn_dam_mod; // pisek, okouzleni ...
      if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kouzla = ".$this->turn_dam_mod."</td><td>dam: ".cislo($dmg)."<br>\n";
      $mod = BonusZaKolo();
      $dmg *= $mod;
      if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kolo = $mod</td><td>dam: ".cislo($dmg)."<br>\n";
      
      return max(0, $dmg);
    }
    
    function receiveDamage ($dmg) {
      global $SHOW_STATS, $ARMOR_ABSORB;    
    
      $dobrneni = min($dmg*$ARMOR_ABSORB,$this->brn_zbyva * $this->pocet * $this->xp / 100); //*$this->brn_zbyva/$this->brn
      $dmg -= $dobrneni;
      $this->brn_zbyva -= /*round */($dobrneni/$this->pocet/$this->xp*100);
      if ($SHOW_STATS == 1) echo "<tr><td>Brneni vzalo = ".cislo($dobrneni)."</td><td>dam: ".cislo($dmg)."<br>\n";
      if ($SHOW_STATS == 1) echo "<tr><td></td><td>Brn zbyva: ".cislo($this->brn_zbyva)."</td></tr>";
 
      if (ZRANOVANI) {
        $zraneni = new Zraneni();
        
        $wouldBeKilled = $dmg / ($this->zvt*$this->xp/100 * $this->hlas_krve);
        
        $atv = $zraneni->atv ($this->pocetZranenych, $wouldBeKilled);
        
        if ($SHOW_STATS == 1) echo "<tr><td>Normal</td><td>zabito: ".round($wouldBeKilled)."</td></tr>";
        
        $killed = min ($this->pocet, floor ($wouldBeKilled * $atv));
        
        if ($SHOW_STATS == 1) echo "<tr><td>ATV = ".round($atv * 100)."%</td><td>zabito: {$killed}</td></tr>";
        
        if ($this->pocetZranenychPercent() < 0.03) {
          $p = $killed/$this->pocet;
          $zraneno = $this->setPocetZranenychP ($zraneni->vyrobZranene1($p), $killed);
        } else {
          $m = $zraneni->vyrobZranene2($killed, $this->pocetZranenych);
          $zraneno = $this->setPocetZranenychPM ($m, $killed);
        }
      } else {
        $killed = min ($this->pocet, floor ($dmg / ($this->zvt*$this->xp/100 * $this->hlas_krve)));
      }
      
      return array ('killed' => max (0, $killed), 'zraneno' => max (0,$zraneno), 'dorazeno' => max(0, $dorazeno));
    }
    
    /**
     * Nastaveni poctu zranenych v procentech
     *      
     * @param double $p pocet procent (0..1) kolik ma byt nastaveno jako zranenych
     * @param int $killed pocet zabitych v ramci teto rany (zraneni v procentech se pocita az na nove zredukovany stack)     
     * @return int pocet (N) zranenych po nastaveni
     */     
    function setPocetZranenychP ($p, $killed = 0) {
      if ($p < 0) $p = 0;
      
      //echo 'SET '. round($p * 100) ." %<br />";
      
      $n = ($this->pocet - $killed) * $p;
      
      // aby mi to nepreteklo
      $this->pocetZranenych = round (min ($n, $this->pocet - $killed));
      
      global $SHOW_STATS;
      
      if ($SHOW_STATS == 1) echo "<tr><td>SPZ-P p = ".round($p * 100)."%</td><td>zranìno: {$this->pocetZranenych}</td></tr>";
      
      
      return $this->pocetZranenych;
    }
    
    /**
     * Multiplikativni nastaveni poctu zranenych v procentech
     * 
     * Vynasobi aktualni pocet zranenych konstantou.     
     * @param double $p multiplikativni konstanta
     * @param int $killed pocet zabitych v ramci teto rany (zraneni v procentech se pocita az na nove zredukovany stack)
     * @return int pocet (N) zranenych po nastaveni
     */    
    function setPocetZranenychPM ($m, $killed = 0) {
      
      
      $pPred = $this->pocetZranenychPercent();
      $pNew = min (max (0.60, $pPred), $pPred * $m); 
      
      $n = ($this->pocet - $killed) * $pNew;
      
      $this->pocetZranenych = round (min ($n, $this->pocet - $killed));
      
      global $SHOW_STATS;
      
      if ($SHOW_STATS == 1) echo "<tr><td>SPZ-M m = ".round($m * 100)."%</td><td>&nbsp;</td></tr>
                                  <tr><td>SPZ-M pPred = ".round($pPred * 100)."%</td><td>&nbsp;</td></tr>
                                  <tr><td>SPZ-M pNew = ".round($pNew * 100)."%</td><td>zranìno: {$this->pocetZranenych}</td></tr>";
      
      return $this->pocetZranenych;
    }
}

function cislo ($cislo)
{
	return number_format($cislo, 0, '', ' ');
}
function SeradDleIni ($kolo)
{
	global $ini, $vojak;
	//unset ($ini);
	$ini = "";
 	foreach ($vojak as $key => $value) {
        if (
        	($value->typ == "S") // strelec
	        ||
	        (($value->typ == "B")&&($value->phb == 3)) //phb 3
	        ||
	        (($value->typ == "B")&&($value->phb == 2)&&($kolo>=2)) //phb2 ve druhym kole
	        ||
            ($kolo >= 3) //treti kolo utoci kazdej
        )
        {
		  	$ini[$key] = max (round($value->ini*($value->hrac==2?0.85:1)), 1)+($value->xp/201);
        	//$ini[$key] = max ($value->ini+($value->hrac==2?-4:0), 1)+($value->xp/201);
        }
	}
	if ($ini != "") arsort ($ini);
}
function SeradDlePwr ()
{
	global $power, $vojak;
	$power = "";
 	foreach ($vojak as $key => $value) {
        $power[$key] = $value->pwr*$value->xp*$value->pocet_max;        
	}
	arsort ($power);
}
function ZjistiVhodnyCil ($strana, $muze_na_letce, $utok_na = "dmg") //strana je strana utocnika, muze_na_letce - 1 ano, 0 ne
{
	global $vojak;
    $max = 0;
    $jednotka = -1;
    foreach ($vojak as $key => $value)
    {
    	if (($value->hrac != $strana)&&(($muze_na_letce==1)||($value->druh!="L")))
        {
        	if ($value->StitovaniNaDmg($utok_na)>$max)
            {
            	$max = $value->StitovaniNaDmg($utok_na);
                $jednotka = $key;
            }
        }
    }
    return $jednotka;
}
function ZjistiBonusZaTyp ($utok, $cil, $CSko = false)
{
  if ($CSko) {
    if ($utok->druh == "P") $mod = 1/3;
    if ($utok->druh == "L") $mod = 1/4;
    if ($cil->typ == "S") $mod /= 2;
  } else $mod = 1;
  
  
	if (VYPNUTE_BONUSY == 1) return 1 * $mod;
	if (($utok->typ == "S")&&($utok->druh == "P")&&($cil->typ == "B"))
    {
    	if ($cil->druh == "L") {return 1.25 * $mod;}
        if ($cil->druh == "P") {return 0.8 * $mod;}
    }
    if (($utok->typ == "B")&&($utok->druh == "L")&&($cil->druh == "P")&&($cil->typ == "S")) {return 0.5 * $mod;}
    if (($utok->phb == 1)&&($cil->phb > 1)&&($utok->typ == "B")) {return 0.8 * $mod;}
    return 1 * $mod;
}
function BonusZaKolo ()
{
	global $kolo;
    switch ($kolo)
    {
	    case 1:
	        return 0.6;
	    break;
	    case 2:
	        return 0.8;
	    break;
	    case 3:
	        return 1.0;
	    break;
	    case 4:
	        return 0.7;
	    break;
	}

}
function NastavStartKola() 
{
	global $vojak;
    foreach ($vojak as $key => $value)
	{
		$vojak[$key]->turn_dam_mod = 1;
		if ($vojak[$key]->xp < 70) $vojak[$key]->cs = 1;
		elseif ($vojak[$key]->xp < 95) $vojak[$key]->cs = 2;
		else $vojak[$key]->cs = 3;
    }
}
function GetPrefix ()
{
	global $branka, $zobraz_prefix;
	$prefix = MySQL_Query ("SELECT * FROM `prefixy` WHERE `branka` = $branka");
	if (!$prefix_db = MySQL_Fetch_Array ($prefix)) 
		return "";
	if ($zobraz_prefix)
		return $prefix_db['prefix']." ";
	return "";
}
function VojakSLinkem ($voj)
{
	global $branka;
	return '<a href="#" onClick="detail(\'simul/unit.php?id='.$voj->ID.'&branka='.($voj->hrac == 2?$branka:0).'\')">'.($voj->hrac==2?GetPrefix():"").$voj->jmeno."</a>";
}
function graf ($x, $last = 0, $width = 50, $height = 10) {
  if ($x > 1) $x = 1;
  if ($x < 0) $x = 0;
  $sirka = round(max(0,$x * $width));
  $sirka_pred = min ($width - $sirka, max (0, round(max(1,$last * $width)) - $sirka));

  $x = pow($x, 1.5);

  return '
  <div style="margin-left: 3px; float: left; background-color: white; width: '.$width.'px; height: '.$height.'px; font-size: 0px;">
    <div style="float: left; background-color: #'.substr("000000".dechex(65536*round (min (512 - $x * 512, 255)) + 256*round (min ($x * 512, 255))),-6).'; width: '.$sirka.'px; height: '.$height.'px; font-size: 0px;">&nbsp;</div>'.
    ($sirka_pred ? '<div style="float: left; background-color: #AEAEAE; width: '.$sirka_pred.'px; height: '.$height.'px; font-size: 0px;">&nbsp;</div>' : '').'
  </div>';
}
?>
