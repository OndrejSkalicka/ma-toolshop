<?php
function Novy_tabulka () {
	global $user_info;
	if ($_POST['submit'] == "Zapsat") {
		/*VytvorTepich();*/
		$koberec = new Koberec (array (
												'cil' => $_POST['k_cil'],
												'ID' => $user_info['ID'],
												'CSka' => $_POST['k_cska'],
												'Poznamka' => $_POST['k_pozn'],
												'bounty1' => $_POST['k_bounty1'],
												'bounty2' => $_POST['k_bounty2'],
												'bounty3' => $_POST['k_bounty3'],
												'noob' => $_POST['k_noob']
												));
	}
	echo '		
		<form action="main.php" class="novy_koberec" method="post">
		<input type="hidden" name="akce" value="koberce">
		<input type="hidden" name="typ" value="new">
		<table>
			<tr>
				<td>Text (špehové):</td>
				<td><textarea name="k_cil"></textarea></td>
			</tr>
			<tr>
				<td>CSka (servis):</td>
				<td><textarea name="k_cska"></textarea></td>
			</tr>
			<tr>
				<td>Poznamka (napø. èas sestøelu atp):</td>
				<td><textarea name="k_pozn"></textarea></td>
			</tr>
			<tr>
				<td>Bounty pro 1ku:</td>
				<td><input name="k_bounty1" value="0"></td>
			</tr>
			<tr>
				<td>Bounty pro 2ku:</td>
				<td><input name="k_bounty2" value="0"></td>
			</tr>
			<tr>
				<td>Bounty pro 3ku:</td>
				<td><input name="k_bounty3" value="0"></td>
			</tr>
			<tr>
				<td>Úroveò:</td>
				<td><select name="k_noob" size=2>
					<option value="1" selected>normální hráè</option>
					<option value="0">n00b</option>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Zapsat"></td>
			</tr>
		</table>
		
		</form>';
		
	return 1;
}
function VytvorTepich () {
	global $user_info;

	if (MySQL_Query ("INSERT INTO `koberce` ( `ID` , `ID_vlastnik` , `cil` , `cska`, `poznamka`, `cil_sila` , `bounty1` , `bounty2` , `bounty3` , `time` , `noob` ) 
												VALUES ('', '".$user_info['ID']."', '".$_POST['k_cil']."', '".$_POST['k_cska']."' , '".$_POST['k_pozn']."' , '".$_POST['k_cil_sila']."', '".$_POST['k_bounty1']."', '".$_POST['k_bounty2']."', '".$_POST['k_bounty3']."', '0', '".$_POST['k_noob']."')")) 
	{
		echo "Tepich pøidán!<br>";
	} else {
		echo "Nastala chyba!<br>";
	}
	
	return 1;
}
?>
