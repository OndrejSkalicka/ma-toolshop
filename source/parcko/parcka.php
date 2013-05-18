<?php
function parcko_rozcestnik () {
	echo '
	<form action="main.php" method="post">
		<input type="hidden" name="akce" value="parcka">
		Maximální pøípustná síla do útoku: <input name="sila" value="'.($_POST['sila'] * 1).'" size="8">
		<input type="submit" value="Spoèti">
	</form>';
	if (is_numeric($_POST['sila']))
		if ($_POST['sila'] > 25000)	
			echo "Celková síla obránce: ".number_format(($_POST['sila'] - 25000) * 0.74, 0, '', ' ')."<br>
			Útok max. do  ".number_format(100/($_POST['sila']/ (($_POST['sila'] - 25000) * 0.74)), 0, '', ' ')." % síly obránce";
		else echo "min. 25k";
}
?>

