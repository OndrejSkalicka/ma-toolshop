<?php
function detail_skupiny ($id) {
	if ($skupina = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `uziv_skupiny` WHERE `ID` = '$id'"))) {
		$ret_val = '
		<strong>Detail skupiny</strong>:<br>
		<form action="main.php?akce=skupiny" method="post">
			<input type="hidden" name="skup_id" value="'.$_REQUEST['skup_id'].'">
			<input name="uprav_nazev" value="'.$skupina['nazev'].'" readonly><br><br>
			<table>
			';
		
		$prava_db = MySQL_Query ("SELECT * FROM `pravo_text` ORDER BY `text`");
		while ($prava = MySQL_Fetch_Array ($prava_db)) {
			$status = ma_skupina_prava ($skupina['ID'],$prava['ID']);
			$ret_val .= '
				<tr>
				<td>'.$prava['text'].': 
				</td>
				<td>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_a['.$prava['ID'].']" type="radio" value="1"'.($status == 1 ? " checked" : "").'><label for="uprav_id_pravo_a['.$prava['ID'].']">Acess</label>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_n['.$prava['ID'].']" type="radio" value="-1"'.($status == -1 ? " checked" : "").'><label for="uprav_id_pravo_n['.$prava['ID'].']">Users defined</label>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_d['.$prava['ID'].']" type="radio" value="0"'.($status == 0 ? " checked" : "").'><label for="uprav_id_pravo_d['.$prava['ID'].']">Deny</label>
				</td>
				</tr>';
		}
		$ret_val .= '
			<tr>
			<td><input type="submit" name="uprav_akce" value="Uprav"></td>
			<td><input type="submit" name="uprav_akce" value="Smazat" onClick=\'return window.confirm("Jste si jist?")\'></td>
			</tr>
			</table>
			
		</form>';
	} else {
		$ret_val = "";
	}
	
	return $ret_val;
}
function detail_ali ($id) {
	if ($skupina = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `ali` WHERE `ID` = '$id'"))) {
		$ret_val = '
		<strong>Detail ali</strong>:<br>
		<form action="main.php?akce=skupiny" method="post">
			<input type="hidden" name="ali_id" value="'.$_REQUEST['ali_id'].'">
			<input name="uprav_nazev" value="'.$skupina['jmeno'].'" readonly><br><br>
			<table>
			';
		
		$prava_db = MySQL_Query ("SELECT * FROM `pravo_text` ORDER BY `text`");
		while ($prava = MySQL_Fetch_Array ($prava_db)) {
			$status = ma_ali_prava ($skupina['ID'],$prava['ID']);
			$ret_val .= '
				<tr>
				<td>'.$prava['text'].': 
				</td>
				<td>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_a['.$prava['ID'].']" type="radio" value="1"'.($status == 1 ? " checked" : "").'><label for="uprav_id_pravo_a['.$prava['ID'].']">Acess</label>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_n['.$prava['ID'].']" type="radio" value="-1"'.($status == -1 ? " checked" : "").'><label for="uprav_id_pravo_n['.$prava['ID'].']">Users defined</label>
					<input name="uprav_id_pravo['.$prava['ID'].']" id="uprav_id_pravo_d['.$prava['ID'].']" type="radio" value="0"'.($status == 0 ? " checked" : "").'><label for="uprav_id_pravo_d['.$prava['ID'].']">Deny</label>
				</td>
				</tr>';
		}
		$ret_val .= '
			<tr>
			<td><input type="submit" name="uprav_akce" value="Uprav"></td>
			<td><input type="submit" name="uprav_akce" value="Smazat" onClick=\'return window.confirm("Jste si jist?")\'></td>
			</tr>
			</table>
			
		</form>';
	} else {
		$ret_val = "";
	}
	
	return $ret_val;
}
?>