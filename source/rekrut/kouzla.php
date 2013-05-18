<?php
class CKouzlo
{
	var $jmeno, $cena, $default_TU, $zlato_SK, $mana_akt_proc;
	
	function CKouzlo ($jmeno, $cena, $default_TU, $zlato_SK, $mana_akt_proc, $lidi_chybi_proc)	//nacte info o jednotce z DB
	{
		$this->jmeno = $jmeno;
		$this->cena  = $cena;
		$this->zlato_SK = $zlato_SK;
		$this->default_TU = $default_TU;
		$this->mana_akt_proc = $mana_akt_proc;
		$this->lidi_chybi_proc = $lidi_chybi_proc;
	}
	
	function sedi_nazev ($text) {
		if (preg_match ("#$text#i", $this->jmeno)) return 1;
		return 0;
	}
	
	function sesli (&$hospodareni, $xp) {
		$ret = "";
		$ret['text'] .= "Sesláno ".$this->jmeno."<br>";
		if ($this->zlato_SK) {
			$ret['zl'] = max (0, $ret['zl'] + $hospodareni['SK'] * $this->zlato_SK * $xp);
		}
		if ($this->mana_akt_proc) {
			$ret['mn'] = max (0, $ret['mn'] + $hospodareni['mn_akt'] * $this->mana_akt_proc * $xp);
		}
		if ($this->lidi_chybi_proc) {
			$chybi = $hospodareni['lidi_max'] - ($hospodareni ['lidi_akt'] * $this->lidi_chybi_proc * $xp);
			$ret['lidi'] = max (0, $ret['lidi'] + $chybi);
		}
		return $ret;
	}
}
function priprav_kouzla () {
	$kouzla = "";
	/* 1. jmeno; 2. cena mn; 3. sesilani TU; 4. zlato na 1 SK; 5. many z aktualni zasoby, 6. lidi do maxima*/
	$kouzla [] = new CKouzlo ("Koncentrace", 200000, 8, 0, 1.00, 0);
	$kouzla [] = new CKouzlo ("Láska", 600000, 15, 4000, 0, 0);
	$kouzla [] = new CKouzlo ("Poklad", 800000, 30, 15000, 0, 0);
	$kouzla [] = new CKouzlo ("Elixír", 800000, 15, 0, 0, 1.00);
	$kouzla [] = new CKouzlo ("Štìstí", 450000, 15, 0, 0.30, 0);
	$kouzla [] = new CKouzlo ("Populaèní exploze", 0, 15, 4000, 0, 1.00);
	$kouzla [] = new CKouzlo ("Odinùv zpìv", 600000, 30, 12000, 0, 1.00);
	
	return $kouzla;
}
?>
