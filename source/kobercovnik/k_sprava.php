<?php
function Seznam_vlastnich () {
	global $user_info;

	switch ($_POST['submit']) {
		case "Upravit":
			UpravTepich ();
		break;
		/*case "Smazat":
			SmazTepich ($_REQUEST['s_id']);
		break;*/
	}
	
	echo '<div id="koberce">
	<div class="levy">';
	$koberce_db_active = MySQL_Query ("SELECT * FROM `koberce`
                  									WHERE `ID_vlastnik` = '".$user_info['ID']."'
                                    AND `expire` > '".time()."'");
	
	$koberce_db_old = MySQL_Query ("SELECT * FROM `koberce`
                  									WHERE `ID_vlastnik` = '".$user_info['ID']."'
                                    AND `expire` <= '".time()."'");
	
	if (MySQL_Num_Rows ($koberce_db_active) == 0 && MySQL_Num_Rows ($koberce_db_old) == 0) {
		echo 'Ještì jsi nevypsal žádné koberce.<br></div></div><div class="clear">&nbsp;</div>';
		return 0;
	}
	
	if (MySQL_Num_Rows ($koberce_db_active)) echo '<strong>Aktivní: </strong><br />';
	
	while ($koberce = MySQL_Fetch_Array ($koberce_db_active)) {
		echo '<a href="main.php?akce=koberce&amp;typ=sprava&amp;s_id='.$koberce['ID'].'">'.$koberce['cil_regent'].' ('.$koberce['cil'].') &plusmn; '.round ( ($koberce['expire'] - time()) / 3600 ).'h</a><br>';
	}
	
  if (MySQL_Num_Rows ($koberce_db_old)) echo '<br /><strong>Staré: </strong><br />';
	
	while ($koberce = MySQL_Fetch_Array ($koberce_db_old)) {
		echo '<a href="main.php?akce=koberce&amp;typ=sprava&amp;s_id='.$koberce['ID'].'">'.$koberce['cil_regent'].' ('.$koberce['cil'].') &plusmn; '.round ( ($koberce['expire'] - time()) / 3600 ).'h</a><br>';
	}
	
  echo '</div>';
	
		
	return 1;
}
function UpravTepich () {

}
function SmazTepich ($ID) {
	if ((MySQL_Query ("DELETE FROM `koberce` WHERE `ID` = '$ID'")) AND (MySQL_Query ("DELETE FROM `koberce_users` WHERE `ID_koberce` = '$ID'")))
	{
		echo "Smazáno.<br>";
	} else {
		echo "Chyba pøi mazání koberce!<br>";
	}
}
?>
