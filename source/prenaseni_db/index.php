<?php
	session_start ();
	
	error_reporting(E_ALL ^ E_NOTICE);
	
	/* handling sessions */
	if (!$_SESSION['charset'])
		$_SESSION['charset'] = 'windows-1250';
	if ($_POST['u_login']) {
		$_SESSION['u_name'] = strtolower($_POST['u_name']);
		$_SESSION['u_pwd'] = md5($_POST['u_pwd']);
	}
	if ($_POST['db_change']) {
		$_SESSION['db_host'] = $_POST['db_host'];
		$_SESSION['db_name'] = $_POST['db_name'];
		$_SESSION['db_user'] = $_POST['db_user'];
		if ($_POST['db_pwd'] || $_POST['db_clear_pwd'])
			$_SESSION['db_pwd'] = $_POST['db_pwd'];
	}
	if ($_POST['db_dump']) {
		$_SESSION['db_prefix'] = $_POST['db_prefix'];
		$_SESSION['db_data'] = $_POST['db_data'];
		$_SESSION['db_structure'] = $_POST['db_structure'];
	}
	
	$admins['savannah'] = 'fb536ee7d16ed931183eaca03a7a2f01';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>Database transferer</title>
	<meta http-equiv="Content-Type" content=<?php echo "text/html; charset={$_SESSION['charset']}";?>>
	<style>
		input, select {
			width: 100px;
			border: 1px solid black;
		 	padding-left: 3px;
		}
		#left {
			float: left;
			border: 1px solid black;
			
		}
		#right {
			float: left;
			width: 500px;
			border: solid black;
			border-width: 1px 1px 1px 0px;
		}
		#left, #right {
			height: 600px;
			float: left;
			padding: 3px;
		}
		#login {
			height: 50px;
		}
		#right .super {
			border: solid black;
			border-width: 0px 0px 1px 0px;
			margin: 0px 0px 3px 0px;
		}
		#right .top {
			padding: 3px;
		}
		#right .top .left {
			padding: 0px 3px 0px 0px;
			float: left;
		}
		#right .top .right {
			border: solid black;
			border-width: 0px 0px 0px 1px;
			padding: 0px 0px 0px 3px;
			float: left;
		}
		#right .top .right textarea {
			width: 360px;
			height: 300px;
		}
		.normal {
			width: 14px;
			height: 14px;
			padding: 0px;
			border: 1px solid;
			_border: none;
			_width: auto;
			_height: auto;
		}
		.clear {
			clear: both;
			width: 0px;
			height: 0px;
			font-size: 0px;
		}
		a, a:visited, a:active {
			color: Blue;
		}
	</style>
	<script type="text/javascript" language="javascript">
	function setSelectOptions(the_form, the_select, do_check)
		{
		    var selectObject = document.forms[the_form].elements[the_select];
		    var selectCount  = selectObject.length;
		
		    for (var i = 0; i < selectCount; i++) {
		        selectObject.options[i].selected = do_check;
		    } // end for
		
		    return true;
		}
	</script>
</head>

<body>
<?php
	if ($admins[$_SESSION['u_name']] == '' || $admins[$_SESSION['u_name']] != $_SESSION['u_pwd']) {
		$_SESSION['u_name'] = '';
		$_SESSION['u_pwd'] = '';
		?>
		<form action="index.php" method="post">
			<table>
			<tr>
				<td>login: </td>
				<td><input name="u_name"></td>
			</tr>
			<tr>
				<td>pwd: </td>
				<td><input name="u_pwd" type="password"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="u_login" type="submit" value="login"></td>
			</tr>
			</table>
			
		
		</form>
		<?php
	} else {	/* logged in */
		@$spojeni = MySQL_Connect($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pwd']);
		@$select_db = MySQL_Select_DB($_SESSION['db_name'],$spojeni);
		echo '
		<div id="left">
			<strong>DATABASE:</strong>
			<form action="index.php" method="post">
				<table>
				<tr>
					<td>host: </td>
					<td><input name="db_host" value="'.$_SESSION['db_host'].'"></td>
				</tr>
				<tr>
					<td>user: </td>
					<td><input name="db_user" value="'.$_SESSION['db_user'].'"></td>
				</tr>
				<tr>
					<td>password: </td>
					<td><input name="db_pwd" type="password"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input name="db_clear_pwd" type="checkbox" class="normal"><label for="db_clear_pwd">Vymazat</label></td>
				</tr>
				<tr>
					<td>database: </td>
					<td><input name="db_name" value="'.$_SESSION['db_name'].'"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input name="db_change" type="submit" value="update"></td>
				</tr>
				</table>
				
			
			</form>
		</div>
		<div id="right">
		';
		
		if ($spojeni != '' && $select_db) { /* connected to db */
?>
<div class="top">
		<strong>EXPORT:</strong><br>
	<form action="index.php" method="post" name="dump">
	<div class="left">
		<select name="table_export[]" size="8" multiple="multiple">
		<?php
			$tables = MySQL_Query ("SHOW TABLES");
			while ($table = MySQL_Fetch_Array ($tables)) {
				echo "\t\t\t\t<option value=\"{$table[0]}\"".($x[$table[0]] ? ' selected' : '').">{$table[0]}</option>\n";
			}
		?></select><br>
		<a href="#" onclick="setSelectOptions('dump', 'table_export[]', true); return false;">Vše</a> |
		<a href="#" onclick="setSelectOptions('dump', 'table_export[]', false); return false;">Nic</a><br>
		<br>
		<input name="db_structure" class="normal" type="checkbox"<?php echo $_SESSION['db_structure'] == 'on' ? ' checked' : '';?>>
			<label for="db_structure">Struktura</label><br>
		<input name="db_data" class="normal" type="checkbox"<?php echo $_SESSION['db_data'] == 'on' ? ' checked' : '';?>>
			<label for="db_data">Obsah</label>
		<br>Prefix:<br>
		<input name="db_prefix" value=<?php echo "\"{$_SESSION['db_prefix']}\"";?>><br><br>
		<input type="submit" name="db_dump" value="Dump">
	</div>	<!-- top.left -->
	<div class="right">
		
		<?php
			$out = '';
			if ($_POST['db_dump'] && count($_POST['table_export'])) {
				foreach ($_POST['table_export'] as $table) {
					$table = $table;
					if ($_POST['db_structure'] == 'on') {
						$struct = MySQL_Fetch_Array (MySQL_Query ("SHOW CREATE TABLE `{$table}`"));
						$temp = preg_replace("/CREATE TABLE `{$table}`/", "CREATE TABLE `{$_SESSION['db_prefix']}{$table}`","\n-- Structure dump for `{$table}`\n\n{$struct['Create Table']};\n\n");
						/* strip vsech zbytecnosti, ktere to jenom komplikuji */
						$temp = preg_replace ('/(collate[ =]\w+ ?|default charset[ =]\w+ ?)/i', '', $temp);
						$out .= $temp;
						
					}
					if ($_POST['db_data'] == 'on') {
						$data = MySQL_Query ("SELECT * FROM `{$table}` WHERE 1 ORDER BY `ID`");
						$out .= "\n-- Data dump for `{$table}`\n\n";
						while ($radek = MySQL_Fetch_Array ($data)) {
							$keys = '';
							$values = '';
							$prvni = 1;
							foreach ($radek as $key => $value) if (!is_int($key)) {
								if (!$prvni) {
									$keys .= ', ';
									$values .= ' ,';
								} else $prvni = 0;
								$keys .= "`{$key}`";
								$values .= "'{$value}'";
							}
							$out .= "INSERT INTO `{$_SESSION['db_prefix']}{$table}` ({$keys}) VALUES ({$values});\n";
						}
					}
				}
			} 
			if ($out == '' && $_POST['zobraz_znova']) $out = stripslashes($_POST['sql_q']);
			echo "<textarea name=\"sql_q\" wrap=\"OFF\">$out</textarea>";
			?><br>
				<input type="checkbox" name="zobraz_znova" class="normal" id="zobraz_znova"<?php if ($_POST['zobraz_znova'] == 'on') echo ' checked';?>>
				<label for="zobraz_znova">Zobraz dotaz znovu</label><br><br>
				<input type="submit" name="sql_send" value="Odešli">
			<?php
			
			if ($_POST['sql_send']) {
				$qs = explode(';', $_POST['sql_q']);
				if (is_array($qs)) foreach ($qs as $q) {
					
					MySQL_Query ($q.";");
				}				
			}
		?>
	</div>	<!-- top.right -->
	</form>
	<div class="clear">&nbsp;</div>
</div>	<!-- top -->
<?php
		} else { /* not/connected to db */
			if ($spojeni == '') 
				echo "Pøihlašovací údaje k databázi nejsou správné.";
			else
				echo "Databáze nenalezena.";
		} /* connected to db */
		echo "</div>";
	} /* logged in && db selected*/
?>
</body>
</html>
