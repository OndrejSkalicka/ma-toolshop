<?php
$ARMOR_ABSORB = 0.35;
$DAM_MOD = (isset($_POST['DAM_MOD']) ? $_POST['DAM_MOD'] : 0.65);//ATV mod, puvodne 0.65
$SHOW_STATS = 1;
$HK_BOOST = 0.3;
define ("ZRAN_ZIVOTY", (isset($_POST['ZRAN_ZIVOTY']) ? $_POST['ZRAN_ZIVOTY'] : 0.2)); //kolik zivota ma zranena jednotka
define ("ZABLOKOVANE", 0);
define ("VYPNUTE_BONUSY", (isset($_POST['VYPNUTE_BONUSY']) ? 0 : 1));
define ("NA_POCET", '/^{poc}/');
?>
