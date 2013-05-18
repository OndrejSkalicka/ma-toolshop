<?php
function vypisZadavaciTabulku () {
	echo <<<END
	<a href="./hlidka/help.html" class="other" target="_blank">HELP</a>; 
	<a href="./hlidka/tmobile.html" class="other" target="_blank">T-Mobile (by Altex)</a>;
	<a href="./hlidka/orange.html" class="other" target="_blank">T-Mobile SK + Orange (by Azgaroth)</a>;
  <a href="http://www.bananas.cz/Hlidka.htm" class="other" target="_blank">Animovaný návod by Hexer</a>;
  <a href="./hlidka/navod_by_hulas.txt" class="other" target="_blank">Nastavení telefonu by Hulas</a><br>
	<form action="main.php?akce=hlidka" method="post">
	<textarea name="h_vstup"></textarea><br><br>
	<input type="submit" name="submit" value="Zadej">
	</form>
END;
}

?>