<?php
	error_reporting (E_ALL ^ E_NOTICE);
?>
<html>
<head>
  <title>Hlídka custom msg help</title>
  <LINK REL='STYLESHEET' TYPE='text/css' HREF='../style.css'>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
</head>
<body>
<center><H2>Nápověda k vlastnímu nastavení zprávy hlídky</H2></center><br><br>
<strong>Význam:</strong>
<ul>
	<li>Někteří uživatelé mají eurotel, který nepovoluje dlouhé zprávy a stěžují si pak, že jim v SMSce nepřijde nejdůležitější část (např. na jakou sílu padli</li>
	<li>Každý uživatel si může nechat posíalt informace tak uspořádané, jak chce</li>
</ul>
<strong>Nastavení:</strong>
<ul>
	<li>Pokud chcete používat výchozí nastavení (Pokles na XXX, o XXX (XXX %). Cas: XXX. Thx to: XXX), nechte pole prázdné.</li>
	<li>Pole odpovídá zprávě, kterou dostanete, s tím, že tyto výrazy:</li>
	<ul>
		<li>%zmena_abs% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pokles celkem)</li>
		<li>%zmena_rel% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pokles v procentech)</li>
		<li>%sila_pred% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(síla před prvem)</li>
		<li>%sila_po% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(síla po prvu)</li>
		<li>%cas% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(čas ve formátu "hodiny:minuty")</li>
		<li>%zachrance% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(kdo vás zachránil, "thx to")</li>
	</ul>
	<br>budou nahrazeny příslušnou číselnou hodnotou.
	<li>Např. výchozí zpráva pak vypadá takto: Pokles na %sila_po%, o %zmena_abs% (%zmena_rel% %). Cas: %cas%. Thx to: %zachrance%</li>
</ul>
<strong>Otestování:</strong>
<ul>
	<li>Pokud chcete vidět, jak výsledný mail/sms bude vypadat, můžete si to vyzkoušet zde:<br>
	<form action="custom_msg_help.php" method="post">
	<input name="msg_test" <?php echo ' value="'.($_POST['msg_test'] ? $_POST['msg_test'] : "Pokles na %sila_po%, o %zmena_abs% (%zmena_rel% %). Cas: %cas%. Thx to: %zachrance%").'"';?> size="100">
	<input type="submit" value="Vyzkoušej">
	</form>
	<i>(pro pokles z 150k na 130k (= cca 13%), v 12:23, diky "Mocnému lvovi" :-) )</i>
	<br><br>
	<?php
		if ($_POST['msg_test']) {
			require "h_fce.php";
			$mail = $_POST['msg_test'];
			
			$mail = preg_replace ('/%zmena_abs%/i', cislo(20000), $mail);
			$mail = preg_replace ('/%zmena_rel%/i', cislo(20/1.5), $mail);
			$mail = preg_replace ('/%sila_pred%/i', cislo(150000), $mail);
			$mail = preg_replace ('/%sila_po%/i', cislo(130000), $mail);
			$mail = preg_replace ('/%cas%/i', Date("H:i", 1140088986), $mail);
			$mail = preg_replace ('/%zachrance%/i', "Mocný lev ;-)", $mail);
			
			echo 'Výsledná zprava bude vypadat takhto: "'.$mail.'"';
		}
	?>
	</li>
</ul>
</body>
</html>