<?php
function PridejDoLogu ($text, $ma_cas) {
	global $user_info;
	
	preg_match_all ('/\n[0-9]+\.\s+([SMBCZ]\s+)?([0-9]{4,})\s+(\*)*.*(Amazonka|Vìdma|Mág|Alchymista|Váleèník|Klerik|Hranièáø|Druid|Nekromant|Theurg|Iluzionista|Barbar)\s+([0-9]+)/', $text, $hraci);
	
	$text = '';
	
	foreach ($hraci[0] as $key => $value) {
    $text .= "{$hraci[2][$key]}|{$hraci[5][$key]}\n";
  }
	
	MySQL_Query ("INSERT INTO `hlidka_log` ( `ID` , `users_ID` , `cas` , `text`, `ma_cas` ) 
												VALUES ('', '".$user_info['ID']."', '".time()."', '".$text."', '$ma_cas');");
	/*											
	$max_logu = 3000;
												
	$count = MySQL_Fetch_Row (MySQL_Query ("SELECT COUNT(*) FROM `hlidka_log`"));
	if ($count[0] > $max_logu) {
		$posledni = MySQL_Query ("SELECT ID FROM `hlidka_log` ORDER BY `ID` LIMIT ".($count[0] - $max_logu));
		while ($last = MySQL_Fetch_Array ($posledni)) {
			MySQL_Query ("DELETE FROM `hlidka_log` WHERE `ID` = '".$last['ID']."'");
		}
		
	}*/
}
//------------
function PridejDoLoguHodin ($users_id) {
	if (!MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `hlidka_hodiny` WHERE `users_ID` = '$users_id'"))) 
		MySQL_Query ("INSERT INTO `hlidka_hodiny` (`users_ID`) VALUES ('$users_id')");
	
	return ((MySQL_Query ("UPDATE `hlidka_hodiny` SET `hod_".date('G')."` = `hod_".date('G')."` + 1 WHERE `users_ID` = '$users_id'"))
				&& (MySQL_Query ("UPDATE `users` SET `hlidka_last_update` = '".time()."' WHERE `ID` = '$users_id'")));
}
//------------
function aktualni_update ($ma_cas) { 
	/*	zjisti, zda je vlozena MA-stranka aktualni -> porovna
	*	cas dane stranky s tim, ktery byl naposled vlozen do DB.
	*	vraci 1 pokud cas je platny, 0 pokud neplatny
	*	new! - pouze v ramci jeho vlastni ali!	*/
	global $user_info;
	
	if (!$last = MySQL_Fetch_Array (MySQL_Query ("SELECT `hlidka_log`.`ma_cas` FROM `hlidka_log` 
															INNER JOIN `users` ON `users`.`ID` = `hlidka_log`.`users_ID`
															WHERE (`users`.`ID_ali_v` = '{$user_info['ID_ali_v']}' AND {$user_info['ID_ali_v']} > 0) OR (`users`.`ID_ali_t` = '{$user_info['ID_ali_t']}' AND {$user_info['ID_ali_t']} > 0)
															ORDER BY `hlidka_log`.`ID` DESC
															LIMIT 1")))
		///*return 0*/; //POZOR - to ze neprosla query muze znamenat, ze pouze jeste nidko nevlozil!!!
		return 1;
	
	
	
	if ($last['ma_cas'] == 0) return 1; //kvuli starsim verzim DB
	
	if ($last['ma_cas'] < $ma_cas) return 1;
	
	return 0;
}
?>
