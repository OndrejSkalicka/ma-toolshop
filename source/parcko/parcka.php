<?php
function parcko_rozcestnik () {
	echo '
	<form action="main.php" method="post">
		<input type="hidden" name="akce" value="parcka">
		Maxim�ln� p��pustn� s�la do �toku: <input name="sila" value="'.($_POST['sila'] * 1).'" size="8">
		<input type="submit" value="Spo�ti">
	</form>';
	if (is_numeric($_POST['sila']))
		if ($_POST['sila'] > 25000)	
			echo "Celkov� s�la obr�nce: ".number_format(($_POST['sila'] - 25000) * 0.74, 0, '', ' ')."<br>
			�tok max. do  ".number_format(100/($_POST['sila']/ (($_POST['sila'] - 25000) * 0.74)), 0, '', ' ')." % s�ly obr�nce";
		else echo "min. 25k";
}
?>

