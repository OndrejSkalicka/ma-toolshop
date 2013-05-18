<?php
function rekrut () {
	require "analyse.php";
	require "rekrut_fce.php";
	require "rekrut_vystupy.php";
	
	echo '<div id="rekrut">';
	if (!$_POST['odeslano']) include "vstup.php";
	
	if ($_POST['odeslano']) {
		
		$test = $_POST['kouzla_in'];
		
		$test = preg_replace ('/(,)/','.',$test);
		$radky = preg_split ('/\n/', $test);
		
		foreach ($radky as $radek) {
			chop ($radek);
			$radek = preg_replace ('/^(.*?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)\s+([-+]?\d+(\.\d+)?)/', 
			'UPDATE `MA_units` SET `plat_zl` = \'$2\',
`plat_mn` = \'$4\',
`plat_lidi` = \'$6\' WHERE `jmeno` = \'$1\' AND `brankar` = \'0\' LIMIT 1 ;', 			
			$radek);
			
			
			echo "$radek... ";
			
			
			if (MySQL_Query($radek)) {
				echo "ok<br>";
			} else {
				echo "error<br>";
			}
		}

	
		/*$hospodareni = zanalizuj ($_POST['hospodareni_in']);
		$casti = RozdelVstupNaCasti ($_POST['rekrut_in']);
		if ($casti == '') return "Není co rekrutovat<br>";			
		
		foreach ($casti as $cast) {
			if (!RekrutujJednotku ($cast, $hospodareni))
				echo "chyba pøi nakrucování jednotky (chybné jméno, nulový poèet...) ".$cast['jmeno']."<br>";
		}
		
		vysledky ($hospodareni);*/
	}
	
	
	
	echo "</div>";
	return 1;
}
?>
