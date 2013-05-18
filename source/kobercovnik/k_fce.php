<?php
function cislo ($cislo)
{
	return number_format($cislo, 0, '', ' ');
}
function SilaStrelce($pwr, $cislo_strelce, $noob)
{
	$pwr /= 1000;
	if ($noob) {
		switch ($cislo_strelce) {
			case 1:
				return $pwr * 1.25;
			break;
			case 2:
				return $pwr * 1.25 * 0.70;
			break;
			case 3:
				return $pwr * 1.25 * 0.75 * 0.55;
			break;
			default:
				return 0;
			break;
		}	
	} else {
		switch ($cislo_strelce) {
			case 1:
				return $pwr * 1.25;
			break;
			case 2:
				return $pwr * 1.25 * 0.75;
			break;
			case 3:
				return $pwr * 1.25 * 0.75 * 0.625;
			break;
			default:
				return 0;
			break;
		}
	}
	return 0;
}
?>