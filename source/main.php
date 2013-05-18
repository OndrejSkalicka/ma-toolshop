<?php
	session_start();
	
	//setcookie('ads', 1, time () + 60*60*24*365);
	
	/* emulace GPC */
	if (get_magic_quotes_gpc() == 0) {
		function add_rec (&$value) {
			if (is_array ($value)) foreach ($value as $key => $v) add_rec ($value[$key]);
			else $value = addslashes ($value);
		}
		add_rec ($_POST);
		add_rec ($_GET);
	}
	
	require ("dblogin.php");
	require ("fce.php");
// 	if ($_POST['akce'] == 'login') {
// 		MySQL_Query ("INSERT INTO `iplog` (`ID`, `login`, `server`) VALUES ('', '{$_POST['u_jmeno']}', '".addslashes(var_export($_SERVER, true))."')");
// 		Prihlas ($_POST['u_jmeno'], md5($_POST['u_pwd']));
// 	}
  if ($_REQUEST['akce'] == 'login') {
		//MySQL_Query ("INSERT INTO `iplog` (`ID`, `login`, `server`) VALUES ('', '{$_REQUEST['u_jmeno']}', '".addslashes(var_export($_SERVER, true))."')");
		Prihlas ($_REQUEST['u_jmeno'], md5($_REQUEST['u_pwd']));
	}
	if ($_GET['akce'] == 'logout') {
		LogOut ();
	}
	if (!CheckLogin ()) {
		LogOut ();
	}
	
	NactiUdajeOUserovi();
	OverWritePOSTGET('akce');
	
	if ($_POST['database_type']) $_SESSION['database_type'] = $_POST['database_type'];
?>
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

<div id="stranka">

<div id="hlavicka"><!--Toolshop--></div>

<div id="menu" align="center" style="border-right: 1px #333333 solid;"><div class="polozka"><a href="main.php"><span<?php if (($_GET['akce'] == "") || ($_REQUEST['akce'] == 'login')) echo ' class="selected"';?>><?php echo $user_info['regent']?></span></a></div><!-- polozka -->
	<?php
		PolozkaMainMenu ("aukce", "Aukce");
		PolozkaMainMenu ("simul", "Simulátor branek");
		PolozkaMainMenu ("rekrut", "Simulátor Rekrutu");
		PolozkaMainMenu ("parcka", "CSko z maxu");
		PolozkaMainMenu ("aliance", "Aliance");
		PolozkaMainMenu ("hlidka", "Hlídka");
		PolozkaMainMenu ("statistiky_ali", "Ali reporty");
		PolozkaMainMenu ("koberce", "Tepichovník");	
		PolozkaMainMenu ("administrace", "Úèty", "ucty");
		PolozkaMainMenu ("settings", "Nastavení", "nastaveni");
		PolozkaMainMenu ("skupiny", "Uživatelské skupiny");
	?>
	  <div class="polozka"><a href="http://forum.savannahsoft.eu/index.php?c=11" target="_blank"><span>F&oacute;rum</span></a></div>
	  <div class="polozka"><a href="main.php?akce=kontakty"><span>Kontakty</span></a></div>
		<div class="polozka"><a href="main.php?akce=maplus"><span><span style="color:yellow; display: inline; font-weight: bold;">NEW</span> MA Plus</span></a></div>
	  <div class="polozka"><a href="main.php?akce=logout"><span>LOGOUT</span></a></div><!-- /polozka --></div>

<div id="obsah"><div class=" obsah">

<?php 
	switch ($_REQUEST['akce']) {
		case "administrace":
			if (MaPrava ("ucty"))
				include "administrace.php";
		break;
		case "aukce":
			if (MaPrava ("aukce"))
				include "./aukce/aukce.php";
		break;
		case "simul":
			if (MaPrava ("simul"))
				include "./simul/index.php";
		break;
		case "settings":
			include "./settings/settings.php";
		break;
		case "rekrut":
			if (MaPrava ("rekrut")) {
				include "./rekrut/rekrut.php";
				$ret = rekrut();
				if ($ret !== 1) echo "$ret";
			}
		break;
		case "parcka":
		if (MaPrava ("parcka")) {
				include "./parcko/parcka.php";
				parcko_rozcestnik();
			}
		break;
		case "aliance":
			if (MaPrava ("aliance")) {
				require "./aliance/a_rozcestnik.php";
				Rozcestnik();
			}
		break;
		case "koberce":
			if (MaPrava ("")) {
				require "./kobercovnik/k_rozcestnik.php";
				Rozcestnik();
			}
		break;
		case "hlidka":
			if (MaPrava ("hlidka")) {
				require "./hlidka/h_rozcestnik.php";
				Rozcestnik();
			}
		break;
		case "skupiny":
			if (MaPrava ("skupiny")) {
				require "./skupiny/s_rozcestnik.php";
				Rozcestnik();
			}
		break;
		case "statistiky_ali":
			if (MaPrava ("statistiky_ali")) {
				require "./statistiky_ali/sa_rozcestnik.php";
				Rozcestnik();
			}
		break;
		case "kontakty":
		  include 'kontakty.html';
		break;
		case 'maplus':
		  include 'maplus.html';
		break;
		default:
			if (MaPrava ()) {
				include "user_info.php";
			} else {
				echo "Nemáte práva!<br>";
			}
		break;
	}
	?>
</div>
</div>
<!-- <p id="paticka">Copyright &copy; Savannah MeliorAnnis Toolshop 2010</div> -->
</div>
</body>
</html>