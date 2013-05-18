<?php
require ("prava_fce.php");

function Prihlas ($jmeno, $heslo) {
  session_regenerate_id();
	$_SESSION["u_name"] = $jmeno;
	$_SESSION["u_pwd"] = $heslo;
}
function CheckLogin () {
	/* verze s pravama */
	$uziv_db = MySQL_Query ("SELECT `login`,`heslo`, `ID` FROM `users` WHERE `login` = '".$_SESSION["u_name"]."' AND `heslo` = '".$_SESSION["u_pwd"]."'");

	if (@MySQL_Num_Rows ($uziv_db) == 1) {
		$uziv = MySQL_Fetch_Array ($uziv_db);
		if (MaPrava('LOGIN', $uziv['ID']))
			return 1;
	} else {
		return 0;
	}
	
	
	return 0;
}
function LogOut () {
	// Unset all of the session variables.	
// 	$_SESSION = array();
	
	// Finally, destroy the session.
	@session_destroy();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
// 	if (isset($_COOKIE[session_name()])) {
// 	   //setcookie(session_name(), '', time()-42000, '/');
// 	   setcookie(session_name(), session_id(), 1, '/');
// 	}	
	
	echo "Byl jste úspì‘nì odhlá‘en<br>";
	
	exit ();
}
function OverWritePOSTGET ($var) {
	global $_POST, $_GET;
	if (($_GET[$var] == "") && ($_POST[$var] != "")) {
		$_GET[$var] = $_POST[$var];
	}
}
/* user fce */
function NactiUdajeOUserovi () {
	global $user_info;
	$user_info = "";
	$user_info_db = MySQL_Query ("SELECT * FROM `users` WHERE `login` LIKE '".$_SESSION["u_name"]."'");
	if (!$user_info = MySQL_Fetch_Array ($user_info_db)) {
		LogOut();
	}
}
function IncDB ($col) {
	global $user_info;
	$user_inc_db = MySQL_Query ("SELECT `$col` FROM `users` WHERE `ID` = '".$user_info['ID']."'");
	if ($user_inc = MySQL_Fetch_Row ($user_inc_db)) {
		$update = "UPDATE `users` SET `$col` = '".($user_inc[0]+1)."' WHERE `ID` = '".$user_info['ID']."'";
		if (MySQL_Query ($update)) {
			return 1;
		}
	}
	return 0;
}
function PolozkaMainMenu ($akce, $caption, $prava = -1) {
	if ($prava == -1) {
		$prava = $akce;
	}
	
	if (MaPrava ($prava))
		echo '<div class="polozka"><a href="main.php?akce='.$akce.'"><span'.($_REQUEST['akce'] == "$akce" ? ' class="selected"' : "").'>'.$caption."</span></a></div>\n";
}

function icq ($icq_num) {
	//if ($icq_num > 0) return '<a href="http://www.icq.com/people/cmd.php?uin='.$icq_num.'&action=message" class="icq"><img src="http://status.icq.com/online.gif?icq='.$icq_num.'&img=5" title="'.$icq_num.'" alt="['.$icq_num.']"></a>';
	if ($icq_num > 0) return '<a href="http://www.icq.com/people/cmd.php?uin='.$icq_num.'&action=message" class="icq"><img src="img/icq.gif" title="'.$icq_num.'" alt="['.$icq_num.']"></a>';
}
?>