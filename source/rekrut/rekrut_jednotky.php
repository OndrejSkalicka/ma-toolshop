<?php
class CJednotka {
	var $jmeno, $pocet, $xp, $zl_tu_celkem, $mn_tu_celkem, $pp_tu_celkem,	//vstup
		 $zmeneno, //kontrola pridavani
		 $pwr, $phb, $typ, $druh //db
		 ;
	
	function CJednotka ($jmeno, $pocet, $xp, $zl_tu_celkem, $mn_tu_celkem, $pp_tu_celkem, $zmeneno) {
		$jednotka_db = MySQL_Query ("SELECT * FROM `MA_units` WHERE `jmeno` LIKE '$jmeno' AND `brankar` = '0' AND `ID_veky` = '{$_POST['rekrut_vek']}'");
		if (@$jednotka = MySQL_Fetch_Array ($jednotka_db)) 
		{
			$this->jmeno = $jmeno;
			$this->pocet = $pocet;
			$this->xp = $xp / 100;
			$this->zl_tu_celkem = -$zl_tu_celkem;
			$this->mn_tu_celkem = -$mn_tu_celkem;
			$this->pp_tu_celkem = -$pp_tu_celkem;
			
			if ($zmeneno > 0) {
				$this->zmeneno = $zmeneno;
			} else {
				$this->zmeneno = 0;
			}
			
			$this->pwr = $jednotka['pwr'];
			$this->phb = $jednotka['phb'];
			$this->typ = $jednotka['typ'];
			$this->druh = $jednotka['druh'];
		} else {
			echo "Chyba pri vytvareni jednotky jmenem: '$jmeno'.<br>";
			return 0;
		}
	}

	function VypisRadek () {
		if ($this->jmeno != "") {
			if ($this->zmeneno > 0) 
				$class = " class=\"zmeneno$this->zmeneno\"";
			$ret = "
			<tr$class>
				<td class=\"jmeno\">$this->jmeno</td>
				<td>".number_format($this->xp * 100, 2, ".", " ")."</td>
				<td>$this->phb</td>
				<td>$this->druh$this->typ</td>
				<td>".cislo($this->Sila())."</td>
				<td>".cislo($this->pocet)."</td>
				<td>".cislo(-$this->zl_tu_celkem)."</td>
				<td>".cislo(-$this->mn_tu_celkem)."</td>
				<td>".cislo(-$this->pp_tu_celkem)."</td>
			</tr>";
			
			return $ret;
		}
		
		return 0;
	}
	
	function Sila () {
		return $this->pocet * $this->xp * $this->pwr;
	}
	
	function PripojJednotku ($pocet, $xp, $zl_tu, $mn_tu, $pp_tu) {
		$this->xp = ($this->Sila() + $this->pwr * $pocet * $xp) / (($this->pocet + $pocet) * $this->pwr);
		$this->pocet += $pocet;
		$this->zl_tu_celkem += $zl_tu;
		$this->mn_tu_celkem += $mn_tu;
		$this->pp_tu_celkem += $pp_tu;
		
		if ($this->zmeneno == 0) $this->zmeneno = 1;
	}
}
?>
