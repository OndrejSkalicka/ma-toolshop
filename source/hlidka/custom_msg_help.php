<?php
	error_reporting (E_ALL ^ E_NOTICE);
?>
<html>
<head>
  <title>Hl�dka custom msg help</title>
  <LINK REL='STYLESHEET' TYPE='text/css' HREF='../style.css'>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
</head>
<body>
<center><H2>N�pov�da k vlastn�mu nastaven� zpr�vy hl�dky</H2></center><br><br>
<strong>V�znam:</strong>
<ul>
	<li>N�kte�� u�ivatel� maj� eurotel, kter� nepovoluje dlouh� zpr�vy a st�uj� si pak, �e jim v SMSce nep�ijde nejd�le�it�j�� ��st (nap�. na jakou s�lu padli</li>
	<li>Ka�d� u�ivatel si m��e nechat pos�alt informace tak uspo��dan�, jak chce</li>
</ul>
<strong>Nastaven�:</strong>
<ul>
	<li>Pokud chcete pou��vat v�choz� nastaven� (Pokles na XXX, o XXX (XXX %). Cas: XXX. Thx to: XXX), nechte pole pr�zdn�.</li>
	<li>Pole odpov�d� zpr�v�, kterou dostanete, s t�m, �e tyto v�razy:</li>
	<ul>
		<li>%zmena_abs% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pokles celkem)</li>
		<li>%zmena_rel% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(pokles v procentech)</li>
		<li>%sila_pred% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(s�la p�ed prvem)</li>
		<li>%sila_po% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(s�la po prvu)</li>
		<li>%cas% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(�as ve form�tu "hodiny:minuty")</li>
		<li>%zachrance% &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(kdo v�s zachr�nil, "thx to")</li>
	</ul>
	<br>budou nahrazeny p��slu�nou ��selnou hodnotou.
	<li>Nap�. v�choz� zpr�va pak vypad� takto: Pokles na %sila_po%, o %zmena_abs% (%zmena_rel% %). Cas: %cas%. Thx to: %zachrance%</li>
</ul>
<strong>Otestov�n�:</strong>
<ul>
	<li>Pokud chcete vid�t, jak v�sledn� mail/sms bude vypadat, m��ete si to vyzkou�et zde:<br>
	<form action="custom_msg_help.php" method="post">
	<input name="msg_test" <?php echo ' value="'.($_POST['msg_test'] ? $_POST['msg_test'] : "Pokles na %sila_po%, o %zmena_abs% (%zmena_rel% %). Cas: %cas%. Thx to: %zachrance%").'"';?> size="100">
	<input type="submit" value="Vyzkou�ej">
	</form>
	<i>(pro pokles z 150k na 130k (= cca 13%), v 12:23, diky "Mocn�mu lvovi" :-) )</i>
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
			$mail = preg_replace ('/%zachrance%/i', "Mocn� lev ;-)", $mail);
			
			echo 'V�sledn� zprava bude vypadat takhto: "'.$mail.'"';
		}
	?>
	</li>
</ul>
</body>
</html>