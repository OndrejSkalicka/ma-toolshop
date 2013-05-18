<?php
echo '
<script language="JavaScript">
	<!--
	function otevri_Okno(s){
	var newOkno=window.open(s,"Okno","toolbar=no,directories=no,copyhistory=no, menubar=no,scrollbars=yes,resizable=no,width=515,height=550")
	}
	//-->
</script>

<center>
<a href="#0" onClick="otevri_Okno(\'rekrut/help.html\')" class="other">Help</a><br />
<a href="http://www.bananas.cz/rekrut.htm" target="_blank" class="other">new GREAT Help by Hexer!!</a>
</center><br>
  <form action="main.php" method="post">
	<input type="hidden" name="akce" value="rekrut">
	<input type="checkbox" name="stats" id="stats"><label for="stats"> Zobrazovat postup</label><br><br />
  <div class="left_input">
		
  	<input name="manveze_in" value="0"> stavìt manavìže (poèet TU)
	</div>
	<div class="right_input">
	   Vìk: <select name="rekrut_vek">';
$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
while ($vek = MySQL_Fetch_Array ($veky)) {
	echo '<option value="'.$vek['ID'].'">'.$vek['jmeno']." ({$vek['title']})</option>\n";
}
echo '
    </select>
	</div>
	



	<strong>REKRUT</strong><br>
		<div class="left_input">
			<span style="text-decoration: line-through;">auto</span>:
			<textarea disabled></textarea>
		</div>
		<div class="right_input">
			manual:<br>
			<textarea name="rekrut_in">'.$_SESSION['toolshop_rekrut_in'].'</textarea>
		</div>
	<div class="clear">&nbsp;</div>
	<strong>KOUZLA</strong><br>
		<div class="left_input">
			<span style="text-decoration: line-through;">auto</span>:
			<textarea disabled></textarea>
		</div>
		<div class="right_input">
			manual:<br>
			<textarea name="kouzla_in">'.$_SESSION['toolshop_kouzla_in'].'</textarea>
		</div>
	<div class="clear">&nbsp;</div>
	<strong>HOSPODAØENÍ</strong><br>
		<div class="left_input">
			auto:<br>
			<textarea name="hospodareni_in">'.$_SESSION['toolshop_hospodareni_in'].'</textarea><br />
			<input type="checkbox" name="hospodareni_in_strip" id="hospodareni_in_strip"'.($_SESSION['toolshop_hospodareni_in_strip'] ? ' checked' : '').'> <label for="hospodareni_in_strip">Hospodaøení bez jednotek</label> <a href="javascript:void(0);" onclick="return overlib(\'Tuto možnost zaškrtnìne pokud chcete aby systém ignoroval vaší stávající armádu, tzn. aby eko šlo jako by jste všechno co máte propustili (užiteèné když máte nìjakou armádu a plánujete že pùjdete na bránu po full eku)\', STICKY, CAPTION,\'Hospodaøení bez jednotek\');"><img src="img/help.png" height="11" width="11" alt="[?]"></a>
		</div>		
		<div class="right_input">
			manual:<br>
			<table width="100%">
			<tr>
				<td>Hodnota</td>
				<td>za TU</td>
				<td>aktulání</td>
				<td>maximum</td>
			</tr>
			<tr>
				<td>Zlato</td>
				<td><input name="man_zl_tu" value="'.$_SESSION['toolshop_man_zl_tu'].'"></td>
				<td><input name="man_zl_akt" value="'.$_SESSION['toolshop_man_zl_akt'].'"></td>
				<td><input disabled value="- - -"></td>
			</tr>
			<tr>
				<td>Mana</td>
				<td><input name="man_mn_tu" value="'.$_SESSION['toolshop_man_mn_tu'].'"></td>
				<td><input name="man_mn_akt" value="'.$_SESSION['toolshop_man_mn_akt'].'"></td>
				<td><input name="man_mn_max" value="'.$_SESSION['toolshop_man_mn_max'].'"></td>
			</tr>
			<tr>
				<td>Lidi</td>
				<td><input disabled value="- - -"></td>
				<td><input name="man_lidi_akt" value="'.$_SESSION['toolshop_man_lidi_akt'].'"></td>
				<td><input name="man_lidi_max" value="'.$_SESSION['toolshop_man_lidi_max'].'"></td>
			</tr>
			<tr>
				<td>SK</td>
				<td><input name="man_sk" value="'.$_SESSION['toolshop_man_sk'].'"></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			</table>
			
		</div>
	<div class="clear">&nbsp;</div>
	<input type="submit" name="odeslano" value="Odešli">
	</form>';
?>
