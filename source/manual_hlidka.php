<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"><!--Transitional-->
<html>
<head>
  <title>Savannah toolshop</title>
  <LINK REL='STYLESHEET' TYPE='text/css' HREF='style.css'>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1250">
  <META http-equiv="cache-control" content="no-cache" />
  <!-- <link rel="shortcut icon" href="http://www.html-kit.com/dlc/cache_771d9346b14e9d078110bdab630e5246/favicon.ico" > -->
	<LINK rel="shortcut icon" href="favicon.ico">
  <script type="text/javascript" src="overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
  <script type="text/javascript" src="time.js"></script>
</head>

<body>
<?php
  /*ini_set('display_errors', 'on');
  
  error_reporting(E_ALL ^ E_NOTICE);*/
  
  require 'dblogin.php';
  
  //$_POST = $_GET;
  
  $user_info = mysql_query ("SELECT * 
                            FROM `users` 
                            WHERE `login` = " . (int)$_POST['login'] . " 
                              AND `heslo` = '" . md5($_POST['heslo']) . "'");
                            
  if (!($user_info = mysql_fetch_assoc($user_info))) {
    die ('Unauthorized');
  }
  
  require 'fce.php';
  if (!MaPrava('hlidka', $user_info['ID'])) {
  	die ('Permission denied');
  }
  
  require 'hlidka/h_log.php';
  require 'hlidka/h_zmeny.php';
  require 'hlidka/h_config.php';
  require 'hlidka/h_fce.php';
  
  if (!aktualni_update ($_POST['cas'])) {
    die ('Neaktualni hlidka');
  }
  
  $poklesu = 0;
  
  echo '<table>
	<tr>
	<td>ID</td>
	<td>Jmeno</td>
	<td>Provi</td>
	<td>ICQ</td>';
		
	if ($_POST['zlato'] > 0) {
    MySQL_Query ("UPDATE `users` SET `zlato` = '". (int)$_POST['zlato']."' WHERE `ID` = '".$user_info['ID']."'");
  }
	
  foreach ($_POST['hraci'] as $key => $value) if ($value > 0) {
		$upraveno = 1;
		$poklesu += updatuj_hrace($key, $value);
  }
  echo "</table>";
  if ($poklesu > 0) {
  	echo '<script>
			alert("Celkem '.$poklesu.' hráčů, kteří si vyžádali prozvonění (nebo jim nedošel mail), pokleslo, prozvoň je!");
			</script>';
  	}
  	
  // wrong time correction
  while ($_POST['cas'] > 1e10) $_POST['cas'] /= 10;
  while ($_POST['cas'] - time () > 1801) $_POST['cas'] -= 3600;
  while ($_POST['cas'] - time () < -1801) $_POST['cas'] += 3600;
  
  PridejDoLogu(print_r($_POST['hraci'], true), $_POST['cas']);
	PridejDoLoguHodin($user_info['ID']);
	IncDB ("hlidka_count");
?>
</body>
