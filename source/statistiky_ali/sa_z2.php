<?php
function vstup_z2 () {
	echo '<form action="main.php?akce=statistiky_ali" method="post">
		<input type="hidden" name="typ" value="z2">
		<textarea name="z2_vstup_text"></textarea><br><br>
		<input type="submit" name="z2_akce" value="Uprav">
		</form>';
		
	if ($_REQUEST['z2_akce'] == "Uprav") {
		rozloz_text ($_POST['z2_vstup_text']);
	}
}
?>

