<?php
function rekrut () {
	require "analyse.php";
	require "rekrut_fce.php";
	require "rekrut_vystupy.php";
	require "kouzla.php";
	require "rekrut_jednotky.php";
	
	echo '<div id="rekrut">';
	if (!$_POST['odeslano']) include "vstup.php";
	
	if ($_POST['odeslano']) {	
  	// nastaveni sessions
  	$_SESSION['toolshop_rekrut_in'] = $_POST['rekrut_in'];
    $_SESSION['toolshop_kouzla_in'] = $_POST['kouzla_in'];
    $_SESSION['toolshop_hospodareni_in'] = $_POST['hospodareni_in'];
    $_SESSION['toolshop_man_zl_tu'] = $_POST['man_zl_tu'];
    $_SESSION['toolshop_man_zl_akt'] = $_POST['man_zl_akt'];
    $_SESSION['toolshop_man_mn_tu'] = $_POST['man_mn_tu'];
    $_SESSION['toolshop_man_mn_akt'] = $_POST['man_mn_akt'];
    $_SESSION['toolshop_man_mn_max'] = $_POST['man_mn_max'];
    $_SESSION['toolshop_man_lidi_akt'] = $_POST['man_lidi_akt'];
    $_SESSION['toolshop_man_lidi_max'] = $_POST['man_lidi_max'];
    $_SESSION['toolshop_man_sk'] = $_POST['man_sk'];
    $_SESSION['toolshop_hospodareni_in_strip'] = $_POST['hospodareni_in_strip'];
    
	
	
		$hospodareni = zanalizuj_hosp_auto ($_POST['hospodareni_in'], $_POST['hospodareni_in_strip']);
		zanalizuj_hosp_manual ($hospodareni);
		$hospodareni['kouzla_base'] = Priprav_kouzla();
		$hospodareni['kouzla'] = RozdelKouzlaNaCasti ($_POST['kouzla_in'], $hospodareni['kouzla_base']);
		$casti = RozdelVstupNaCasti ($_POST['rekrut_in']);
		if ($casti == '') return "Není co rekrutovat<br>";
		
		foreach ($casti as $cast) {
			if (!RekrutujJednotku ($cast, $hospodareni))
				echo "chyba pøi nakrucování jednotky (chybné jméno, nulový poèet...) ".$cast['jmeno']."<br>";
		}
		
		vysledky ($hospodareni);
	}
	
	
	
	echo "</div>";
	return 1;
}
?>
