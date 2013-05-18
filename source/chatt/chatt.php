<?php
function chatt_main () {
	global $user_info;
	require_once "fce.php";
	
	if (isset ($_REQUEST['mistnost']))
	{
		
		
		echo "<center><h2><a href=\"main.php?akce=chatt\">Místnost: '".($_REQUEST['mistnost'] ? $_REQUEST['mistnost'] : "Základní místnost")."'</a></h2></center>";
		if ($_POST['zprav'] < 1) $_POST['zprav'] = 20;
		
		echo '
		<form action="main.php" method="post">
			<input type="hidden" name="akce" value="chatt">
			<input type="hidden" name="mistnost" value="'.$_REQUEST['mistnost'].'">
			<textarea name="chatt_box" id="chatt_box"></textarea>	
			<input name="zprav" value="'.$_POST['zprav'].'">
			<input type="submit" value="Odeslat/Aktualizovat">
		</form>';
		
		if ($_POST['chatt_box'] != "") {
			if (!get_magic_quotes_gpc()) {
				$_POST['chatt_box'] = addslashes ($_POST['chatt_box']);
				$_POST['mistnost'] = addslashes ($_REQUEST['mistnost']);
			}
			MySQL_Query	("INSERT INTO `chatt` (`ID`, `ID_users`, `text`, `datum`, `mistnost`)
												VALUES ('', '".$user_info['ID']."', '".$_POST['chatt_box']."', '".time()."', '".$_REQUEST['mistnost']."')");
			
			IncDB ("chat_count");
		}
		
		$msg_db = MySQL_Query ("SELECT `chatt`.`text` AS `text`, `chatt`.`datum` AS `datum`, `users`.`regent` AS `nick` FROM `chatt` 
										RIGHT JOIN `users` ON `users`.`ID` = `chatt`.`ID_users`
										WHERE `mistnost` LIKE '".$_REQUEST['mistnost']."'
										ORDER BY `chatt`.`ID` DESC LIMIT ".$_POST['zprav']);
		while ($msg = MySQL_Fetch_Array ($msg_db)) {
			$text = nl2br(htmlspecialchars($msg['text']));
			$text = preg_replace('#\[([biu])\](.*?)\[/(\1)\]#', '<$1>$2</$3>', $text);
			$text = preg_replace('#\[color=([A-z]*)\](.*?)\[/color\]#', '<span style="color:$1">$2</span>', $text);
			$text = preg_replace('#(http://[^ ]*)#', '<a href="$1" target="_blank">$1</a>', $text);
			
			echo '
			<div class="msg">
				'.$msg['nick'].' ('.date('d.m. v G:i', $msg['datum']).')<br>
					'.$text.'
			</div>';
		}
	} else { //neni vybrana mistnost
		echo '
		<form action="main.php" method="post">
			<input type="hidden" name="akce" value="chatt">
			<input name="mistnost">&nbsp;
			<input type="submit" value="Vsoupit/Vytvoøit místnost">
		</form>';		
		
		echo "<br>Oblíbené místnosti: <br><br>";
		OblibeneChaty ($user_info['ID']);
	}
	
	return 1;
}
function OblibeneChaty ($ID) {
	$q = MySQL_Query("SELECT `chatt`.`mistnost` FROM `chatt`
							INNER JOIN `users`
							  ON (`users`.`ID` = `chatt`.`ID_users`) AND (`users`.`ID` = '$ID')
							GROUP BY `chatt`.`mistnost`");
							
	while ($mistnost = MySQL_Fetch_Array ($q)) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"main.php?akce=chatt&amp;mistnost=$mistnost[0]\" class=\"other\">místnost: '".($mistnost[0] == "" ? "Základní místnost" : $mistnost[0])."'</a><br>";
	}
}
?>