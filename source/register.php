<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
  <META http-equiv="cache-control" content="no-cache" />
  <meta name="Description" content="Utility pro Meliorannis - hl�dka, simul�tor branek a rekrutu a dal��" />
  <meta name="Keywords" content="Meliorannis Savannah Toolshop Str�ce Hl�dka Simul�tor Branek Rekrutu Anal�za Aukce" />
  <meta name="Title" content="Savannah's Toolshop pro Meliorannis" />
  <LINK rel="shortcut icon" href="favicon.ico">
  <script type="text/javascript" src="overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
  <script type="text/javascript" src="time.js"></script>
  <title>Savannah toolshop</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="screen, projection">
</head>
<body>
<?php
  /*require 'Explorer_Destroyer/ExplorerDestroyer.class.php';
  
  $ED = new ExplorerDestroyer (1);
  
  if ($ED->checkDelay() || ($_SERVER['REMOTE_ADDR'] == '85.71.123.250'))
      $ED->setLevel (0);
      
  $ED->setMaxDelay (12);  
  $ED->go();*/
?>
<div id="stranka">
<div id="hlavicka"><!--Toolshop--></div>
<div id="login">
<form action="main.php" method="post">
<input type="hidden" name="akce" value="login">
<table width="1000" align="center">
<tr>
  <td rowspan="3" width="500" align="right">&nbsp;</td>
	<td width="300"><img src="images/layout-login.png" width="68" height="19" align="right"></td>
	<td width="200"><input name="u_jmeno" style="background-color:#777777"></td>
</tr>
<tr>
	<td width="300"><img src="images/layout-password.png" width="120" height="17" align="right"></td>
	<td width="200"><input name="u_pwd" type="password" style="background-color:#777777";"border: white 1px solid"></td>
</tr>
<tr>
  <td width="300">&nbsp;</td>
  <td width="200"><input type="submit" value="login" style="background-color:#777777";"border: white 1px solid"; width="150px"></td>
</tr>
</table>
</form>
</div>
<div id="menu" align="center" style="border-right: 1px #333333 solid;">
<div class="polozka"><a href="register.php" class="other" target="_blank">Registrace</a></div>
<br>
<div class="polozka"><a href="http://www.meliorannis.com" target="_blank">Melior Annis</a></div>
<div class="polozka"><a href="http://cs.wiki.ma.savannahsoft.eu" target="_blank">Melior Annis Wiki CZ</a></div>
<div class="polozka"><a href="http://en.wiki.ma.savannahsoft.eu" target="_blank">Melior Annis Wiki EN</a></div>
<br>
<div class="polozka"><a href="http://forum.meliorannis.com" target="_blank">Melior Annis f�rum</a></div>
<div class="polozka"><a href="http://archiv.ma.savannahsoft.eu/" target="_blank">Archiv branek</a></div>
<div class="polozka"><a href="http://forum.savannahsoft.eu">Savannah f�rum</a></div>
<br>
<div class="polozka"><a href="http://www.hulas.cz" target="_blank">Hulas CS servis</a></div>
<br>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="88" height="31" id="FlashID" title="Button_banner_MA">
      <param name="movie" value="images/banner88x31.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="9.0.45.0">
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don�t want users to see the prompt. -->
      <param name="expressinstall" value="Scripts/expressInstall.swf">
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="images/banner88x31.swf" width="88" height="31">
        <!--<![endif]-->
        <param name="quality" value="high">
        <param name="wmode" value="opaque">
        <param name="swfversion" value="9.0.45.0">
        <param name="expressinstall" value="Scripts/expressInstall.swf">
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
<br /><br /><br /><br />
</div>
</div>
<div id="obsah"><div class="obsah">
<?php
  //die ("Strpen� pros�m");
	require "dblogin.php";
	if ($_POST['akce'] == "register") {
		if (!get_magic_quotes_gpc ()) {
			$_POST['r_popisek'] = AddSlashes ($_POST['r_popisek']);
			$_POST['r_regent'] = AddSlashes ($_POST['r_regent']);
			$_POST['r_provi'] = AddSlashes ($_POST['r_provi']);
		}
		
		$_POST['r_provi'] = htmlspecialchars($_POST['r_provi']);
		$_POST['r_popisek'] = htmlspecialchars($_POST['r_popisek']);
		$_POST['r_regent'] = htmlspecialchars($_POST['r_regent']);
		

		if ($_POST['r_pwd'] != $_POST['r_pwd2']) {
			echo "Hesla se neshoduj�.<br>";
			$err = 1;
		}
		if (!Is_Numeric($_POST['r_jmeno'])) {
			echo "Login nen� ��slo.";
			$err = 1;
		}
		if (($_POST['r_pwd'] == "") || ($_POST['r_jmeno'] == "") || ($_POST['r_provi'] == "") || ($_POST['r_regent'] == "") || ($_POST['r_pwd2'] == "")) {
			echo "Nevyplnili jste v�echna povinn� pole.<br>";
			$err = 1;
		}
		if ($_POST['r_souhlas'] != 'on') {
			echo "Nesouhlas�te s podm�nkami.<br>";
			$err = 1;
		}
		if ($err != 1) {
			$insert = "INSERT INTO `users` ( `login` , `heslo` , `popis` , `regent`, `provi`, `overen` ) 
											VALUES ('".$_POST['r_jmeno']."', '".md5($_POST['r_pwd'])."', '".$_POST['r_popisek']."', '".$_POST['r_regent']."', '".$_POST['r_provi']."', 1);";
			
			if (!MySQL_Query ($insert)) {
				echo "Takov�to u�ivatel ji� existuje.<br>";
			} else {
			  // prava
			  
			  $id = mysql_insert_id();
			  
			  $prava = mysql_query("SELECT * FROM `pravo_text` WHERE `pro_usery` = '1'");
			  while ($pravo = mysql_fetch_array($prava)) {
          if (!mysql_query("INSERT INTO `prava` (`ID`, `ID_users`, `ID_pravo_text`) 
                                        VALUES ('', '$id', '{$pravo['ID']}')"))
            $err = 1;
        }
        
        if ($err) echo "Chyba p�i nastavov�n� pr�v, kontaktuje administr�tory o manu�ln� nastaven�.<br />";
			
				echo "Registrace prob�hla �sp�n�. Toto okno m��ete zav��t.<br>";
			}
		}
	} else {
?>
<form action="register.php" method="post">
<table align="center">
<tr>
	<td>Login (ID) *:</td>
	<td><input name="r_jmeno"></td>
</tr>
<tr>
	<td>Heslo *:</td>
	<td><input name="r_pwd" type="password"></td>
</tr>
<tr>
	<td>Heslo znovu *:</td>
	<td><input name="r_pwd2" type="password"></td>
</tr>
<tr>
	<td>Regent *:</td>
	<td><input name="r_regent"></td>
</tr>
<tr>
	<td>Provincie *:</td>
	<td><input name="r_provi"></td>
</tr>
<tr>
	<td>Popisek:</td>
	<td><input name="r_popisek"></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input name="r_souhlas" type="checkbox"> �ETL JSEM NOVINKY NA <a href="http://forum.savannahsoft.eu/viewtopic.php?t=163" target="_blank" class="other" style="text-decoration: underline;">F�RU</a> A NEBUDU TUD͎ OTRAVOVAT SAVANNAHA SE ZBYTE�N�MA DOTAZAMA! </td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><br />Vypl�te pros�m <a href="https://spreadsheets.google.com/viewform?hl=en&pli=1&formkey=dExQM3BZV2J3WlhFeHNLOXlfdDdrS1E6MQ" target="_blank" class="other" style="text-decoration: underline;">ANKETU</a> a pomo�te tak zlep�it toolshop.</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="register" name="akce"></td>
</tr>
</table>
</form>
<script>
alert("Vypl�te pros�m anketu! Odkaz je pod registra�n�m formul��em. D�kujeme");
</script>

<?php 
}
?>
</div></div>
</body>
</html>
