<?php 
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
	LogOut();
}
/* ------ */
require ("./simul/config.php");
?>
 <script language="JavaScript">
	<!--
	function otevri_Okno(s){
	var newOkno=window.open(s,"Okno","toolbar=no,directories=no,copyhistory=no, menubar=no,scrollbars=no,resizable=no,width=515,height=550")
	}
	function detail(s){
	var newOkno=window.open(s,"Okno","toolbar=no,directories=no,copyhistory=no, menubar=no,scrollbars=no,resizable=no,width=310,height=200")
	}
	//-->
</script>

<center>
<a href="#0" onClick="otevri_Okno('simul/help.html')" class="other">Help</a>
</center><br>

<?php
	if ($_POST['fight'] == 0)
		{?>
		<form action="main.php" method="post">
		<input type="hidden" name="akce" value="simul">
		<table id="uvodni_tabulka">
		<tr>
			<td>
				Útoèník
			</td>
			<td>
				Obránce
			</td>
		</tr>
		<tr>
			<td>
				<textarea name="utok" rows="12" id="vyber_utok"><?php
					if (isset($_POST['z_rekrutu'])) {
						echo $_POST['z_rekrutu'];
					} elseif (isset($_SESSION['last_utok'])) echo $_SESSION['last_utok'];					
					?></textarea>
			</td>
			<td>
				<textarea name="obrana" rows="12" id="vyber_obrana"><?php if (isset($_SESSION['last_obrana'])) echo $_SESSION['last_obrana'];?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				include ("sesilatelna_kouzla.php");
				?>
			</td>
			<td>
			<?php
				include ("seznam_branek.php");
			?>
			</td>
		</tr>
		<tr>
			<td>Vìk hráèe:
			<select name="vek_hrac">
				<?php
					$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['ID'].'">'.$vek['jmeno']." ({$vek['title']})</option>\n";
					}
				?>
				
			</select>
			</td>
			<td>Vìk branky:
			<select name="vek">
				<?php
					$veky = MySQL_Query ("SELECT * FROM `veky` ORDER BY `priorita`");
					while ($vek = MySQL_Fetch_Array ($veky)) {
						echo '<option value="'.$vek['ID'].'">'.$vek['jmeno']." ({$vek['title']})</option>\n";
					}
				?>
				
			</select>
			</td>
		</tr>
		</table>
		<input type="hidden" name="fight" value="1">
		<?php
		if (MaPrava('simul_super')) echo '<br><label for="showstats">Zobrazovat statistiky</label> <input type="checkbox" name="stats" id="showstats"><br>
                                        <label for="VYPNUTE_BONUSY">pouzit bonusy (PS vs LB atp) </label><input type="checkbox" name="VYPNUTE_BONUSY" id="VYPNUTE_BONUSY"><br />
                                        <br />
                                        ATV <input name="DAM_MOD" value="'.$DAM_MOD.'"><br />';
		?>
		<br>
		<br>
		<center>
		
		
		
		
		<input type="submit" value="FAJTIT"></center>
		</form>
		
<?php
	}
	else // fight == 1
	{
		IncDB ("simul_count");
		$_SESSION['last_utok'] = $_POST['utok'];
		if ($branka == 0) 
      $_SESSION['last_obrana'] = $_POST['obrana'];
		$_SESSION['last_brana'] = $_POST['branka'];

		
		require ("./simul/fce.php");
		require ("./simul/init.php");
		require ("./simul/kouzla.php");
		require ("./simul/zraneni.class.php");
		
		if ($_POST['stats'] == "on") $SHOW_STATS = 1;
		if ($_POST['stats'] == "") $SHOW_STATS = 0;
		
		require ("./simul/boj.php");
		require ("./simul/vysledky.php");
		
		echo '<br>
		<br>
		<a href="main.php?akce=simul" class="other">Back</a><br>';
		
	}
?>


</center>
