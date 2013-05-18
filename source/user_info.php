<?php
/* overeni uzivatele */
require_once ("fce.php");
if (!CheckLogin ()) {
	LogOut();
}
/* ------ */
if ($_POST['typ'] == 'zmen_usera') {
  if ($_POST['otestuj_zpravu'] == 'on') {
    require_once 'hlidka/h_zmeny.php';
    require_once 'hlidka/h_fce.php';
    
    $mail = $_POST['ch_custom_hlidka_msg'] ? $_POST['ch_custom_hlidka_msg'] : $default_msg;
		
		$mail = preg_replace ('/%zmena_abs%/i', cislo(23456), $mail);
		$mail = preg_replace ('/%zmena_rel%/i', cislo(13), $mail);
		$mail = preg_replace ('/%sila_pred%/i', cislo(180430), $mail);
		$mail = preg_replace ('/%sila_po%/i', cislo(156974), $mail);
		$mail = preg_replace ('/%cas%/i', Date("H:i"), $mail);
		$mail = preg_replace ('/%zachrance%/i', 'test', $mail);
		
		@Mail ($_POST['ch_hlidka_mail'], "", $mail,"From: hlidka@snh.eu");
  }
  if (!get_magic_quotes_gpc ()) {
		$_POST['ch_popis'] = AddSlashes ($_POST['ch_popis']);
	}
	$err = 0;
	if ($_POST['ch_pwd'] != $_POST['ch_pwd2']) {
		echo "<script>alert (\"Hesla se neshodují. Zkontrolujte, pokud pouzivate FireFox, zda-li prohlizec automaticky nevyplnil jedno ze dvou poli HESLO. Pokud ano, obe polozky prosim vymazte.\");</script>";
		$err = 1;
	}
	if ($err == 0) {
	  $_POST['ch_popis'] = htmlspecialchars($_POST['ch_popis']);
	  
		$update = "UPDATE `users` SET 
												`popis` = '".$_POST['ch_popis']."',
												`icq` = '".$_POST['ch_icq']."' ";
		if ($_POST['ch_pwd'] != "") $update .= ", `heslo` = '".md5($_POST['ch_pwd'])."' ";
		if (MaPrava ("hlidka") ) {
			$update .= ", `hlidka_pwr_abs` = '".$_POST['ch_hlidka_abs']."'
							, `hlidka_pwr_rel` = '".$_POST['ch_hlidka_rel']."'
							, `hlidka_pwr_need_both` = '".($_POST['ch_hlidka_both'] == "on" ? "1" : "0")."'
							, `hlidka_mail` = '".$_POST['ch_hlidka_mail']."'
							, `hlidka_phone` = '".$_POST['ch_hlidka_phone']."'
							, `vzdy_prozvonit` = '".($_POST['ch_vzdy_prozvonit'] == "on" ? "1" : "0")."'
							, `custom_hlidka_msg` = '".$_POST['ch_custom_hlidka_msg']."'
              , `hlidka_od` = '".min(23,max(0,(int)$_POST['ch_hlidka_od']))."'
              , `hlidka_do` = '".min(23,max(0,(int)$_POST['ch_hlidka_do']))."'
              , `prozvani` = '".($_POST['ch_prozvani'] == "on" ? "1" : "0")."'";
		}
		$update .= "WHERE `ID` = '".$user_info['ID']."'";
		if (MySQL_Query ($update)) {
			echo "Údaje zmìnìny.";
			if ($_POST['ch_pwd'] != "") Prihlas ($user_info['login'], md5($_POST['ch_pwd']));
			NactiUdajeOUserovi();
		} else {
			echo "Nastala závažná chyba.<br>";
		}
	}
}
echo '
<form action="main.php" method="post">
<input type="hidden" name="typ" value="zmen_usera">
<table>
<tr>
  <td colspan="2" style="text-align: center; font-weight: bold;">General</td>
</tr>
<tr>
	<td>
		Login:
	</td>
	<td>
		'.htmlspecialchars($user_info['login']).'
	</td>
</tr>
<tr>
<td>
		Regent:
	</td>
	<td>
		'.htmlspecialchars($user_info['regent']).'
	</td>
</tr>
<tr>
	<td>
		Provincie:
	</td>
	<td>
		'.htmlspecialchars($user_info['provi']).'
	</td>
</tr>
<tr>
	<td>
		ICQ <i>(bez mezer)</i>:
	</td>
	<td>
		<input name="ch_icq" value="'.($user_info['icq'] > 0 ? $user_info['icq'] : '').'">
	</td>
</tr>
<tr>
	<td>
		Popis:
	</td>
	<td>
		<input name="ch_popis" value="'.htmlspecialchars($user_info['popis']).'">
	</td>
</tr>
<tr>
	<td>
		Zmìna hesla:
	</td>
	<td>
		<input name="ch_pwd" type="password">
	</td>
</tr>
<tr>
	<td>
		Heslo znovu:
	</td>
	<td>
		<input name="ch_pwd2" type="password">
	</td>
</tr>
<tr>
  <td colspan="2" style="text-align: center; font-weight: bold;">Stats</td>
</tr>
<tr>
	<td>
		Analýz aukce:
	</td>
	<td>
		'.$user_info['aukce_count'].'
	</td>
</tr>
<tr>
	<td>
		Simulací boje:
	</td>
	<td>
		'.$user_info['simul_count'].'
	</td>
</tr>
<tr>
	<td>
		Chat:
	</td>
	<td>
		'.$user_info['chat_count'].'
	</td>
</tr>';

if (MaPrava ("hlidka") ) {
	echo '
	<tr>
	 <td colspan="2" style="text-align: center; font-weight: bold;">Hlídka</td>
	</tr>
	<tr>
		<td>Pwr hranice abs <i>(cista sila)</i>:</td>
		<td><input name="ch_hlidka_abs" value="'.$user_info['hlidka_pwr_abs'].'" style="text-align: right;"></td>
	</tr>
	<tr>
		<td>Pwr hranice rel <i>(procent sily, cele cislo)</i>:</td>
		<td><input name="ch_hlidka_rel" value="'.$user_info['hlidka_pwr_rel'].'" style="text-align: right;"> %</td>
	</tr>
	<tr>
		<td>Musí platit oboje zároveò:</td>
		<td><input type="checkbox" name="ch_hlidka_both"'.($user_info['hlidka_pwr_need_both'] == 1 ? ' checked' : '').'></td>
	</tr>
	<tr>
		<td>Mail:</td>
		<td><input name="ch_hlidka_mail" value="'.htmlspecialchars($user_info['hlidka_mail']).'" style="text-align: right;"></td>
	</tr>
	<tr>
		<td>Telefon:</td>
		<td><input name="ch_hlidka_phone" value="'.htmlspecialchars($user_info['hlidka_phone']).'" style="text-align: right;"></td>
	</tr>
  <tr>
    <td>VŽDY prozvánìt:</td>
    <td><input type="checkbox" name="ch_vzdy_prozvonit"'.($user_info['vzdy_prozvonit'] == 1 ? ' checked' : '').'></td>
  </tr>
	<tr>
		<td>Sám prozváním:</td>
		<td><input type="checkbox" name="ch_prozvani"'.($user_info['prozvani'] == 1 ? ' checked' : '').'></td>
	</tr>
	<tr>
		<td>Vlastní zpráva (<a href="./hlidka/custom_msg_help.php" target="_blank" class="other">help</a>):</td>
		<td><input name="ch_custom_hlidka_msg" value="'.$user_info['custom_hlidka_msg'].'"></td>
		<td><input type="checkbox" id="otestuj_zpravu_id" name="otestuj_zpravu"> <label for="otestuj_zpravu_id">Otestuj zprávu</label></td>
	</tr>
	<tr>
	 <td>Hlídka "od do" <a href="javascript:void(0);" onclick="return overlib(\'Od kdy do kdy vám má chodit hlídka. Pokud chcete 24 hodin dennì, tak nechte nastaveno od 0 do 23. Pokud tøeba chcete jenom od 16:00 do 8:00 ráno, tak dejte od = 16, do = 7. Hodiny jsou vèetnì, tzn. pokud máte od 10 do 12 hod, tak je to fakticky od 10:00 do 12:59.\', STICKY, CAPTION,\'Hlídka &quot;Od Do&quot;\');"><img src="img/help.png" height="11" width="11" alt="[?]"></a>:</td>
	 <td>
	   Od: <input name="ch_hlidka_od" value="'.$user_info['hlidka_od'].'" style="text-align: right;" size="2">:00:00<br />
	   Do: <input name="ch_hlidka_do" value="'.$user_info['hlidka_do'].'" style="text-align: right;" size="2">:59:59
	 </td>
	</tr>
	
	';	
}

echo '<tr>
	<td>
		&nbsp;
	</td>
	<td>
		<input type="submit" value="Ulož zmìny" default="default">
	</td>
</tr>
</table>
</form>';
?>
