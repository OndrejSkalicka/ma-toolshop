<?php
function VypisKoberce () {
	global $user_info;
	
	switch ($_REQUEST['k_akce']) {
		case "zapsat":
			ZapisTepich($user_info['ID'], $_REQUEST['k_id'], $_REQUEST['k_poradi']);
		break;
		case "odepsat":
			OdepisTepich($user_info['ID'], $_REQUEST['k_id'], $_REQUEST['k_poradi']);
		break;
	
	}
	
	$koberce_db = MySQL_Query ("SELECT `koberce`.*, `users`.`regent`, `users`.`login` FROM `koberce` 
						INNER JOIN `users`
						ON `users`.`ID` = `koberce`.`ID_vlastnik`
						WHERE (
							((`users`.`ID_ali_v` = '".$user_info['ID_ali_v']."') AND ('".$user_info['ID_ali_v']."' <> '0'))
							OR
							((`users`.`ID_ali_t` = '".$user_info['ID_ali_t']."') AND ('".$user_info['ID_ali_t']."' <> '0'))
							)");
							
	if (MySQL_Num_Rows ($koberce_db) == 0) {
		echo "Žádný koberec.<br>";
		return 0;
	}
	
	while ($koberec = MySQL_Fetch_Array ($koberce_db)) {
		$pocet_tepu ++;
		echo '<div class="koberec">
		<div class="pole">Vypsal: '.$koberec['regent'].' ('.$koberec['login'].')</div> <!-- /pole^vypsal -->
		<div class="pole"><strong><u>Cíl:</u></strong><br>'.nl2br(htmlspecialchars($koberec['cil'])).'</div> <!-- /pole^cil -->';
		if ($koberec['cska']) echo '<div class="pole"><strong><u>CSka:</u></strong><br>'.nl2br(htmlspecialchars($koberec['cska'])).'</div> <!-- /pole^CSka -->';
		if ($koberec['poznamka'])echo '<div class="pole"><strong><u>Poznámka:</u></strong><br>'.nl2br(htmlspecialchars($koberec['poznamka'])).'</div> <!-- /pole^pozn -->';
		for ($poradi = 1; $poradi <= 3; $poradi ++) {
		
			$ma_zapsany = MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `koberce_users` WHERE `ID_koberce` = '".$koberec['ID']."' AND `ID_users` = '".$user_info['ID']."' AND `poradi` = '".$poradi."'"));
			
			echo '<div class="pole">
				Støelec '.$poradi.($koberec['bounty'.$poradi] > 0 ? " (<strong>".CisloSRadem($koberec['bounty'.$poradi])." zl</strong>)" : "").' pwr cca '.cislo(SilaStrelce($koberec['cil_sila'], $poradi, $koberec['noob'])).'k:<br>
				<div class="sub">';
			if ($ma_zapsany) {
				echo '<a href="main.php?akce=koberce&amp;k_akce=odepsat&amp;k_id='.$koberec['ID'].'&amp;k_poradi='.$poradi.'">Odepsat se (z '.$poradi.'. pozice)</a><br>
				';
			} else {
				echo '<a href="main.php?akce=koberce&amp;k_akce=zapsat&amp;k_id='.$koberec['ID'].'&amp;k_poradi='.$poradi.'">Zapsat se (jako '.$poradi.'ka)</a><br>
				';
			}
			$zapsani_db = MySQL_Query ("SELECT `users` . * 
												FROM `koberce_users` 
												INNER JOIN `users` ON `users`.`ID` = `koberce_users`.`ID_users` 
												WHERE `koberce_users`.`ID_koberce` = '".$koberec['ID']."' AND `koberce_users`.`poradi` = '$poradi'");
			while ($zapsani = MySQL_Fetch_Array ($zapsani_db)) {
				echo $zapsani['regent']." (".$zapsani['login'].")<br>";
			}
			
			echo "</div> <!-- /sub^strelci {$zapsani[\'regent\']} -->
			</div> <!-- /pole^strelci {$poradi} -->";
		}
		echo '</div> <!-- /koberec -->';
		if ($pocet_tepu % 3 == 0) {
			echo '<div class="clear">&nbsp;</div>';
		}
	}
}

function CisloSRadem ($cislo) {
	$mod = Array (1=> "k", "M", "G", "T", "P", "E");
	$rad = 0;
	
	while (($cislo / 1000 >= 1) && ($rad < 6)) {
		$cislo /= 1000;
		$rad ++;
	}
	
	return round ($cislo).$mod[$rad];
}
?>