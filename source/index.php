<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
  <META http-equiv="cache-control" content="no-cache" />
  <meta name="Description" content="Utility pro Meliorannis - hlídka, simulátor branek a rekrutu a další" />
  <meta name="Keywords" content="Meliorannis Savannah Toolshop Strážce Hlídka Simulátor Branek Rekrutu Analýza Aukce" />
  <meta name="Title" content="Savannah's Toolshop pro Meliorannis" />
  <LINK rel="shortcut icon" href="favicon.ico">
  <script type="text/javascript" src="overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
  <script type="text/javascript" src="time.js"></script>
  <title>Savannah toolshop</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="screen, projection">
  <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-13306901-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
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
	<td width="200"><input name="u_jmeno" type="text"></td>
</tr>
<tr>
	<td width="300"><img src="images/layout-password.png" width="120" height="17" align="right"></td>
	<td width="200"><input name="u_pwd" type="password"></td>
</tr>
<tr>
  <td width="300">&nbsp;</td>
  <td width="200"><input name="Login" type="submit" id="Login" title="Login" value="Login"></td>
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
<div class="polozka"><a href="http://forum.meliorannis.com" target="_blank">Melior Annis fórum</a></div>
<div class="polozka"><a href="http://archiv.ma.savannahsoft.eu/" target="_blank">Archiv branek</a></div>
<div class="polozka"><a href="http://forum.savannahsoft.eu">Savannah fórum</a></div>
<br>
<div class="polozka"><a href="http://www.hulas.cz" target="_blank">Hulas CS servis</a></div>
<br>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="88" height="31" id="FlashID" title="Button_banner_MA">
      <param name="movie" value="images/banner88x31.swf">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="swfversion" value="9.0.45.0">
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
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
</br></br></br></br>
</div>
</div>
<div id="obsah"><center>
  <h2><strong>Aliance zakládá a změny správy provádí</strong></h2>
  <div style="color:gold;">
  <p><strong>Hanka - ID 1023</strong></p>
  <p><strong>Honza - ID 1170</strong></p>
  </div>
<br>
  <h3>do mailu dávejte vždy tyto položky: Jméno regenta + ID, Typ aliance (V/T) a přesný název aliance</h3>
  <br>
<br>
  <h4>Pokud si zachováte stávající ID do příštích věků tak není nutná nová registrace. Účty byly zachovány.</h4>
  <br>
  <br><h3><div style="text-decoration:blink; color: red;"> Pozor! U T-mobile mohou chodit SMS se zpožděním i v řádech několika desítek minut!!!</div></h3>
  <br><h3><div style="color: red;"> Okamžité odesílání notifikace je nyní u T-Mobile zpoplatněno, bezplatné upozornění nechodí okamžitě.</div></h3>
  <center>
  <br>
  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><h3>&nbsp;</h3></td>
  </tr>
</table> 
  (Upraveno 07.08.2011 19:00)
<br /><br />
  <a href="http://www.inetpro.sk" target="_blank"><img src="img/logoinetproblack.jpg" alt="Hosted by INETPRO.SK" title="Hosted by INETPRO.SK" height="123" width="163"></a></p>
</center>
</center>
</div>
</div>
</body>
</html>