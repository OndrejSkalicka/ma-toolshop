<?php
function Rozcestnik () {
	require "s_seznam.php";
	require "s_nova.php";
	require "s_detail.php";
	require "s_fce.php";
	require "s_prava.php";
	require "s_smaz.php";
	
	
	
	switch ($_REQUEST['uprav_akce']) {
		case "Uprav":
			if ($_REQUEST['skup_id'])
				uprav_skupinu ($_REQUEST['skup_id'], $_REQUEST['uprav_id_pravo']);
			if ($_REQUEST['ali_id'])
				uprav_ali ($_REQUEST['ali_id'], $_REQUEST['uprav_id_pravo']);
		break;
		case "Smazat":
			if ($_REQUEST['skup_id'])
				smaz_skupinu ($_REQUEST['skup_id']);
						if ($_REQUEST['ali_id'])
				smaz_ali ($_REQUEST['ali_id']);
		break;
		case "nova":
			if (!nova_skupina ($_REQUEST['nova_nazev'])) {
				echo '<div class="error">Chyba pøi vytváøení skupiny!</div>';
			}
		break;
	}
	
	echo '<div id="skupiny">
	<form action="main.php?akce=skupiny" method="post">
		<input type="hidden" name="uprav_akce" value="nova">
		<input name="nova_nazev">
		<input type="submit" value="Nová skupina">
	</form><br><br>
	<div id="skupiny_left">
		'.seznam_skupin().'
	</div><!-- left -->
	<div id="skupiny_right">
		';
	if ($_REQUEST['skup_id'])
		echo detail_skupiny($_REQUEST['skup_id']);
	if ($_REQUEST['ali_id'])
		echo detail_ali($_REQUEST['ali_id']);
	echo '
	</div><!-- right -->
	<div class="clear">&nbsp;</div>';
	
	
	
	echo "</div>";
}
?>