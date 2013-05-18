<?php
/* REQUIREMENTS: $user_info */

class Koberec {
	var $id, $cil, $id_vlastnik, $expire, $vlozeno;
	
	function Koberec ($vstup) {
		global $user_info;
		
		if ($vstup == -1) return 0;

		/* pokud neni vstup ID v databazi, tak rozkladam vstup z hospodareni */
		if (!is_numeric ($vstup)) {
			$vstup = $this->novyKoberec ($vstup);
		}
		
		$this->nactiDB ($vstup);
	}
	
	/* nacte udaje o koberci z databaze (podle $id) */
	function nactiDB ($id) {
		
		
		if (!$data = MySQL_Fetch_Array (MySQL_Query ("SELECT `koberce`.*, `users`.`regent`, `users`.`login` FROM `koberce` 
						INNER JOIN `users`
						ON `users`.`ID` = `koberce`.`ID_vlastnik`
						WHERE `koberce`.`ID` = '{$id}'"))) return 0;
		
		$this->id = $data['ID'];
		$this->expire = $data['expire'];
		$this->vlozeno = $data['time'];
    /* info o cili */
		$this->cil['sila'] = $data['cil_sila'];
		$this->cil['ID'] = $data['cil'];
		$this->cil['regent'] = $data['cil_regent'];
		$this->cil['provincie'] = $data['cil_provincie'];
		$this->cil['povolani'] = $data['cil_povolani'];
		$this->cil['rasa'] = $data['cil_rasa'];
		$this->cil['pohlavi'] = $data['cil_pohlavi'];
		$this->cil['slava'] = $data['cil_slava'];
		$this->cil['lidi'] = $data['cil_lidi'];
		$this->cil['hrady'] = $data['cil_hrady'];
		$this->cil['zlato'] = $data['cil_zlato'];
		$this->cil['mana'] = $data['cil_mana'];
		$this->cil['rozloha'] = $data['cil_rozloha'];
		$this->cil['presvedceni'] = $data['cil_presvedceni'];
		$this->cil['aliance'] = $data['cil_aliance'];
		$this->cil['bounty1'] = $data['bounty1'];
		$this->cil['bounty2'] = $data['bounty2'];
		$this->cil['bounty3'] = $data['bounty3'];
		$this->cil['noob'] = $data['noob'];
		$this->cil['cska'] = $data['cska'];
		$this->cil['poznamka'] = $data['poznamka'];
		$this->cil['verejny'] = $data['verejny'];
		
		$this->id_vlastnik = $data['ID_vlastnik'];
	}
	
	function cislo ($n) {
    return number_format($n, 0, '.', ',');
  }
	
	/* vrati string - tabulku informaci o cili, pokud je 
	*	$uprav == 1, tak bude editovatelny (tzn. misto textu
	*	1ka zobrazuje dulezite veci, 2ka podruzne	
	*	budou inputy (<- 2do) */
	function infoOCili1 ($uprav = 0) {
		$retval = '';
		$retval .= "
		<table class=\"o_cili\">
		<tr>
		  <td>ID</td><td>{$this->cil['ID']}</td>
		</tr>
		<tr>
			<td>Regent</td><td>{$this->cil['regent']}</td>
		</tr>
		<tr>
			<td>Síla P.</td><td>".$this->cislo($this->cil['sila'])."</td>
		</tr>
		<tr>
			<td>Zlato</td><td>".$this->cislo($this->cil['zlato'])."</td>
		</tr>
		<tr>
			<td>Mana</td><td>".$this->cislo($this->cil['mana'])."</td>
		</tr>
		<tr>
			<td>Rozloha</td><td>".$this->cislo($this->cil['rozloha'])."</td>
		</tr>
		<tr>
		  <td colspan=\"2\" style=\"text-align: center; cursor: hand;\"><span onClick=\"toggleAll('{$this->id}');\" style=\"text-decoration: underline;\">Detaily</span></td>
    </tr>
		</table>
    ";
		
		return $retval;
	}
	
	/* vrati string - tabulku informaci o cili, pokud je 
	*	$uprav == 1, tak bude editovatelny (tzn. misto textu
	*	1ka zobrazuje dulezite veci, 2ka podruzne	
	*	budou inputy (<- 2do) */
	function infoOCili2 ($uprav = 0) {
		$retval = "
		<table class=\"o_cili\">
		<tr>
			<td>Povolání</td><td>{$this->cil['povolani']}</td>
		</tr>
		<tr>
			<td>Rasa</td><td>{$this->cil['rasa']}</td>
		</tr>
		<tr>
			<td>Pohlaví</td><td>{$this->cil['pohlavi']}</td>
		</tr>
		<tr>
			<td>Sláva</td><td>".$this->cislo($this->cil['slava'])."</td>
		</tr>
		<tr>
			<td>Poèet lidí</td><td>".$this->cislo($this->cil['lidi'])."</td>
		</tr>
		<tr>
			<td>Poèet hradù</td><td>".$this->cislo($this->cil['hrady'])."</td>
		</tr>
		<tr>
			<td>Pøesvìdèení</td><td>{$this->cil['presvedceni']}</td>
		</tr>
		<tr>
			<td>Aliance</td><td>{$this->cil['aliance']}</td>
		</tr>
		</table>
    ";
		
		return $retval;
	}

  /**
   * Rozlozi Servis-style CSka na jejich slozky
   * 
   * Vrati 2d pole. Prvni index udava cislo polozky (CSka). Vyjimka je index 0 (pro PRVNI index), kde je vracen nerozlozeny zbytek!<br /><br />
   * Druhy index udava   
   * 1. ID
   * 2. regent
   * 3. cas v h
   * 4. ztraty U
   * 5. ztraty O.   
   * v danem poradi. 
   * @return mixed        
   */     
  function rozlozCSka ($text = null) {
    if (is_null($text)) $text = $this->cil['cska'];
                                       
    $pattern = '#CSko \! ARMY	(\d+) ([?áäéìëíóöôúùüýyÁÄÉÌËÍÓÖÔÚÙÜÝYèïòøšž¾àÈÏÒØŠŽ¼ÀabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ¹»©«®_\s]*) : (\d+)h / (?:\d+)k	(\d+\.?\d*) : (\d+\.?\d*)#';
    $retval = array (0 => 'reserved');
    
    //strip first line
    $text = preg_replace('/'.$this->cil['ID'].'\s+'.$this->cil['regent'].'\s+'.$this->cil['povolani'].'/','', $text);
    
    while (preg_match($pattern, $text, $matches)) {
      $text = preg_replace($pattern, '', $text, 1);
      unset ($matches[0]);
      $retval[] = $matches;
    }    
    $retval[0] = trim($text);    
    return $retval;
  }

	function toStr () {
		global $user_info;
		
		if (!$info = MySQL_Fetch_Array (MySQL_Query ("SELECT * FROM `users` WHERE `ID` = '{$this->id_vlastnik}'"))) return null;
		$retval = '<div class="koberec">
		<div class="pole">Vypsal: '.$info['regent'].' ('.$info['login'].') '.icq($info['icq']).'</div> <!-- /pole^vypsal -->
		<div class="pole"><strong><u>Cíl:</u></strong><br>';

    $retval .= $this->infoOCili1 ()
      . '</div>
        <div class="pole" id="casy_'.$this->id.'">
          <strong><u>Èasy:</u></strong><br />
          Vloženo: '.date('d.m. v G:i', $this->vlozeno).'<br />
          Vyprší: '.date('d.m. v G:i', $this->expire).' (za &plusmn; '.round(($this->expire - time()) / 3600).' h)<br />
        </div>
        <div class="pole" id="o_cili_'.$this->id.'_2"><strong><u>Cíl detaily:</u></strong><br>'	
  		. $this->infoOCili2 ()
      . '</div> <!-- /pole^cil -->';
      
    // strucne detaily
    $retval .= '<div class="pole" id="strucne_detaily_'.$this->id.'" style="display: none;"><strong><u>Struènì:</u></strong><br />'
      . 'CSek: '.(count($this->rozlozCSka())-1).'<br />'
      . 'Støelci: ';
    
    $s1 = mysql_num_rows(mysql_query("SELECT `ID` FROM `koberce_users` WHERE `ID_koberce` = '{$this->id}' AND `poradi` = '1'")); 
    $s2 = mysql_num_rows(mysql_query("SELECT `ID` FROM `koberce_users` WHERE `ID_koberce` = '{$this->id}' AND `poradi` = '2'"));
    $s3 = mysql_num_rows(mysql_query("SELECT `ID` FROM `koberce_users` WHERE `ID_koberce` = '{$this->id}' AND `poradi` = '3'"));
    $max_color_strelci = 3;
    $retval .= $this->colorSpan($s1/$max_color_strelci, $s1.' x ').$this->colorSpan($s2/$max_color_strelci, $s2.' x ').$this->colorSpan($s3/$max_color_strelci, $s3.' x ').'<br />'
      . 'Vyprší: '.date('d.m. v G:i', $this->expire).' (za &plusmn; '.round(($this->expire - time()) / 3600).' h)';
    
    $retval .= '</div>';
    
    
		
		if ($this->cil['cska']) {
		  $cska = $this->rozlozCSka();
		  $retval .= '<div class="pole" id="cska_'.$this->id.'"><strong><u>CSka:</u></strong><br>';
		  if (count($cska) > 1) {
        $retval .= '<table><tr><td>ID</td><td>regent</td><td>zbývá</td></tr>';
        foreach ($cska as $key => $csko)
  		    if ($key) $retval .= '<tr><td>'.$csko[1].'</td><td>'.$csko[2].'</td><td>&plusmn; '.max(-1,round($csko[3] - (time() - $this->vlozeno) / 3600)).' h</td></tr>';
  		  $retval .= '</table>';
      }
      
		  $retval .= '<i>'.$cska[0].'</i>'
		    . '</div> <!-- /pole^CSka -->';
    }
		if ($this->cil['poznamka'])$retval .= '<div class="pole" id="pozn_'.$this->id.'"><strong><u>Poznámka:</u></strong><br>'.nl2br(htmlspecialchars($this->cil['poznamka'])).'</div> <!-- /pole^pozn -->';
		for ($poradi = 1; $poradi <= 3; $poradi ++) {
		
			$ma_zapsany = MySQL_Num_Rows (MySQL_Query ("SELECT * FROM `koberce_users` WHERE `ID_koberce` = '".$this->id."' AND `ID_users` = '".$user_info['ID']."' AND `poradi` = '".$poradi."'"));
			
			$retval .= '<div class="pole" id="strelci_'.$this->id.'_'.$poradi.'">
				Støelec '.$poradi.($this->cil['bounty'.$poradi] > 0 ? " (<strong>".$this->cisloSRadem($this->cil['bounty'.$poradi])." zl</strong>)" : "").' pwr cca '.($this->silaStrelce($this->cil['sila'], $poradi, $this->cil['noob'])).':<br>
				<div class="sub">';
			if ($ma_zapsany) {
				$retval .= '<a href="main.php?akce=koberce&amp;k_akce=odepsat&amp;k_id='.$this->id.'&amp;k_poradi='.$poradi.'">Odepsat se (z '.$poradi.'. pozice)</a><br>
				';
			} else {
				$retval .= '
				<form action="main.php" method="post" class="zapis_tepichu">
					<input type="hidden" name="akce" value="koberce">
					<input type="hidden" name="k_id" value="'.$this->id.'">
					<input type="hidden" name="poradi" value="'.$poradi.'">
					<input type="submit" name="k_akce" value="zapis" class="submit"> <input name="poznamka" value="">					
				</form>';
				/*$retval .= '<a href="main.php?akce=koberce&amp;k_akce=zapsat&amp;k_id='.$this->id.'&amp;k_poradi='.$poradi.'">Zapsat se (jako '.$poradi.'ka)</a><br>
				';*/
			}
			$zapsani_db = MySQL_Query ("SELECT `users`.* , `koberce_users`.`poznamka`
												FROM `koberce_users` 
												INNER JOIN `users` ON `users`.`ID` = `koberce_users`.`ID_users` 
												WHERE `koberce_users`.`ID_koberce` = '".$this->id."' AND `koberce_users`.`poradi` = '$poradi'");
			while ($zapsani = MySQL_Fetch_Array ($zapsani_db)) {
				$retval .= '<div class="strelec">
				'.$zapsani['regent']." (ID ".$zapsani['login'].") ".icq($zapsani['icq']).'
				</div> <!-- //strelec -->';
				if ($zapsani['poznamka'])
					$retval .= '<div class="pozn">
						"'.htmlspecialchars($zapsani['poznamka']).'"
					</div> <!-- // -->';
			}
			
			$retval .= "</div> <!-- /sub^strelci {$zapsani['regent']} -->
			</div> <!-- /pole^strelci {$poradi} -->";
		}
		$retval .= '</div> <!-- /koberec -->';
		
		return $retval;
	}
	
	/**
	 * Vykresli span ktery bude mit pozadi a text barevne oznacen
	 * @param double $p Pocet procent, 0..1
	 * @param string $text Text ktery se ve spanu zobrazi
	 */   	
	function colorSpan ($p, $text) {
    if ($p > 1) $p = 1;
    if ($p < 0) $p = 0; 
    $back = "#".substr("000000".dechex(65536*round (min (512 - $p * 512, 255)) + 256*round (min ($p * 512, 255))),-6);
    $front = '#000000';
    return '<span style="background-color: '.$back.'; color: '.$front.'; margin-right: 1px;">'.$text.'</span>';
  }
	
	/* zapise uzivateli s ID = $u_id koberec + nastavi poznamku */
	function zapisStrelce ($u_id, $pozn, $poradi) {
		/* 1. kontrola jestli mam na koberec narok */
		if (!$this->maNarok ($u_id)) {
			echo "Nemáte právo zapsat si tento koberec.<br>";
			return 0;
		}
		/* 2. kontrola jestli jsem se uz nezapsal */
		$test = MySQL_Num_Rows(MySQL_Query ("SELECT * FROM `koberce_users` WHERE `ID_users` = '$u_id' AND `ID_koberce` = '{$this->id}' AND `poradi` = '{$poradi}'"));
		if ($test > 0) {
			echo "Už tento koberec máte zapsán.<br>";
			return 0;
		}
		MySQL_Query ("INSERT INTO `koberce_users` ( `ID` , `ID_users` , `ID_koberce` , `poradi` , `poznamka` ) 
													VALUES ('' , '{$u_id}', '{$this->id}', '{$poradi}', '{$pozn}');");
	}
	
	function odepisStrelce ($u_id, $poradi) {
		if (MySQL_Query ("DELETE FROM `koberce_users` WHERE `ID_users` = '$u_id' AND `ID_koberce` = '{$this->id}' AND `poradi` = '$poradi'"))
			return 1;
		return 0;
	}
	
	
	/* odpovi, jestli ma dany uzivatel narok na tento tepich */
	function maNarok ($u_id) {
    if ($this->expire < time ()) return 0;
		// 2do
		return 1;
	}
	
	/* jakou ma silu strelec na urcity cil (pokud je cil n00b/profi a kolikaty strelec jde */
	function silaStrelce($pwr, $poradi, $noob)
	{
		if ($noob) 
			$sily = array (1 => 1.25, 1.25 * 0.7, 1.25 * 0.75 * 0.55);
		else
			$sily = array (1 => 1.25, 1.25 * 0.75, 1.25 * 0.75 * 0.625);
		if (!$sily[$poradi])
			return 0;
			
		return $this->cisloSRadem($pwr * $sily[$poradi]);
	}
	
	
	/* vypise upravovaci tabulku. Pokud $uprav == 1, tak i upravi v databazi */
	function uprav ($uprav = 0) {
		/* 1. pokud ho mam upravit, tak to musim udelat jako prvni krok (abych ho vypsal uz upraveny) */
		if ($uprav) {
			$query = "UPDATE `koberce` SET ";
			if ($match = $this->rozklad($_POST['k_cil'])) {
				$query .= "`cil` = '{$match[3]}',
							  `cil_povolani` = '{$match[4]}',
							  `cil_rasa` = '{$match[5]}',
							  `cil_pohlavi` = '{$match[6]}',
							  `cil_slava` = '{$match[7]}',
							  `cil_lidi` = '{$match[8]}',
							  `cil_hrady` = '{$match[9]}',
							  `cil_zlato` = '{$match[11]}',
							  `cil_mana` = '{$match[12]}',
							  `cil_rozloha` = '{$match[13]}',
							  `cil_aliance` = '{$match[14]}',
							  `cil_sila` = '{$match[10]}', ";
			}
			
			$expire = time () + $_POST['k_expire_h'] * 60 * 60 + $_POST['k_expire_m'] * 60;
			
			$query .= "`bounty1` = '{$_POST['k_bounty1']}',
						  `bounty2` = '{$_POST['k_bounty2']}',
						  `bounty3` = '{$_POST['k_bounty3']}',
						  `noob` = '{$_POST['k_noob']}',
						  `poznamka` = '{$_POST['k_pozn']}',
						  `cska` = '{$_POST['k_cska']}',
						  `expire` = '{$expire}',
						  `verejny` = '".($_POST['k_verejny'] == 'on' ? '1' : '0')."'";

			$query .= " WHERE `ID` = '{$this->id}'";
			
			if (MySQL_Query ($query)) {
				$this->nactiDB($this->id);
			} else {
				echo '<div class="error">
					Chyba pøi upravování koberce!
				</div> <!-- //error -->';
			}
		}
		echo '<div class="pravy">
				<form action="main.php" class="novy_koberec" method="post">
					<input type="hidden" name="akce" value="koberce">
					<input type="hidden" name="typ" value="sprava">
					<input type="hidden" name="s_id" value="'.$this->id.'">
					<table>
						<tr>
							<td>Cíl:</td>
							<td>'."{$this->cil['regent']}, {$this->cil['provincie']} ({$this->cil['ID']})".'</td>
						</tr>
						<tr>
							<td>
							Text: <br>(špehové) <a href="javascript:void(0);" onclick="return overlib(\'CTRL+A, CTRL+C, CTRL+V ze stránky špehù o vašem cíli. <strong>Zadávejte pouze pokud chcete aktualizovat, jinak nevyplòujte.</strong>\', STICKY, CAPTION,\'Špehové\');"><img src="img/help.png" height="11" width="11" alt="[?]">
              </td>
							<td><textarea name="k_cil"></textarea></td>
						</tr>
						<tr>
							<td>
                CSka: <br>(servis) <a href="javascript:void(0);" onclick="return overlib(\'Oznaète pouze CSka vašeho cíle (ze servisu) a ty sem vložte! <strong>NEDÁVEJTE CTRL+A, CTRL+C, CTRL+V</strong>\', STICKY, CAPTION,\'CSka\');"><img src="img/help.png" height="11" width="11" alt="[?]">
              </td>
							<td><textarea name="k_cska">'.htmlspecialchars($this->cil['cska']).'</textarea></td>
						</tr>
						<tr>
							<td>
                Poznámka: <a href="javascript:void(0);" onclick="return overlib(\'Cokoli dùležitého co chcete pøipsat\', STICKY, CAPTION,\'Poznámky\');"><img src="img/help.png" height="11" width="11" alt="[?]"> 
              </td>
							<td><textarea name="k_pozn">'.htmlspecialchars($this->cil['poznamka']).'</textarea></td>
						</tr>
						<tr>
    				 <td>
                Vyprší za: <a href="javascript:void(0);" onclick="return overlib(\'Za jak dlouho koberec (potažmo vaše CSko) konèí, starší koberce se nezobrazují, takže pokud necháte 0, tak se koberec vùbec nezobrazí\', STICKY, CAPTION,\'Timeout\');"><img src="img/help.png" height="11" width="11" alt="[?]">          
              </td>
    				  <td><input name="k_expire_h" value="'.max(0,  floor(($this->expire - time())/3600)).'" style="width: 20px;" size="2"> h, <input name="k_expire_m" value="'.max(0, floor((($this->expire - time()) % 3600) / 60)).'" style="width: 20px;" size="2"> m</td>
    				</tr>
    				<tr>
							<td>
                Bounty pro 1ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 1ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 1ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
              </td>
							<td><input name="k_bounty1" value="'.$this->cil['bounty1'].'"></td>
						</tr>
						<tr>
							<td>
                Bounty pro 2ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 2ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 2ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
              </td>
							<td><input name="k_bounty2" value="'.$this->cil['bounty2'].'"></td>
						</tr>
						<tr>
							<td>
                Bounty pro 3ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 3ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 3ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
              </td>
							<td><input name="k_bounty3" value="'.$this->cil['bounty3'].'"></td>
						</tr>
						<tr>
							<td>Úroveò:</td>
							<td><select name="k_noob" size=2>
								<option value="1"'.($this->cil['noob'] == 1 ? " selected" : "").'>normální hráè</option>
								<option value="0"'.($this->cil['noob'] == 0 ? " selected" : "").'>n00b</option>
							</select></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><input type="submit" name="submit" value="Upravit"><br>
							<br>
							<input type="submit" name="submit" value="Smazat" onClick=\'return window.confirm("Opravdu chcete smazat?")\'></td>
						</tr>
					</table>
					
					</form>
				</div> <!-- //pravy -->';
	}
	
	/* $vstup je pole, musi obsahovat:
	*		'cil' - spehove na cil
	*		'ID' - ID vlastnika
	*		'CSka' - CSka
	*		'Poznamka' - poznamka
	*		'bounty1'...'bounty3' - odmeny
	*		'noob' - 1 = noob, 0 = hrac 
	*	navratova hodnota je ID vlozeneho koberce */
	function novyKoberec ($vstup) {
		if ($match = $this->rozklad($vstup['cil'])) {				
		  $expire = time () + $vstup['expire_h'] * 60 * 60 + $vstup['expire_m'] * 60;
			if (MySQL_Query ("INSERT INTO `koberce` ( `ID` , `ID_vlastnik` , 	`cil` , 			`cska` , 					`poznamka` , 				`cil_regent` , `cil_provincie` , `cil_povolani` , `cil_rasa` , `cil_pohlavi` , `cil_slava` , `cil_lidi` , `cil_hrady` , `cil_zlato` , `cil_mana` , `cil_rozloha` , `cil_presvedceni` , `cil_aliance` , `cil_sila` , `bounty1` , `bounty2` , `bounty3` , `time` , `noob`, `verejny`, `expire` ) 
															         VALUES ('', '{$vstup['ID']}', '{$match[3]}', '{$vstup['CSka']}', '{$vstup['poznamka']}', '{$match[1]}', '{$match[2]}', '{$match[4]}', '{$match[5]}', '{$match[6]}', '{$match[7]}', '{$match[8]}', '{$match[9]}', '{$match[11]}', '{$match[12]}', '{$match[13]}', '{$match[14]}', '{$match[15]}', '{$match[10]}', '{$vstup['bounty1']}', '{$vstup['bounty2']}', '{$vstup['bounty3']}', '".time()."', '{$vstup['noob']}', '".($_POST['k_verejny'] == 'on' ? '1' : '0')."', '{$expire}');") 
				&&	$id = MySQL_Fetch_Array (MySQL_Query ("SELECT `ID` FROM `koberce` ORDER BY `ID` DESC"))) {			
					echo "Koberec úspìšnì pøidán.<br>";
					return $id[0];
			} else {
				echo "Chyba pøi vkládíní koberce do databáze.<br>";
				return 0;
			}
		}	
		echo '<div class="error">
			Chyba pøi rozkladu dat!
		</div> <!-- //error -->';	
		return 0;
	}
	
	/* vrati rozlozene hospodareni jako pole, pokud 
	*	neprojde, vraci 0 */
	function rozklad ($vstup) {
		if (preg_match ('/
				regent\s+(.+?)\s*\r\n						#1 regent
				provincie\s+(.+?)\s+\((\d+)\)\s*\r\n	#2 provi, 3 id
				povolání\s+(.+?)\s*\r\n						#4 povolani
				Rasa\s+(.+?)\s*\r\n							#5 rasa
				Pohlaví\s+(.+?)\s*\r\n						#6 pohlavi
				Sláva\s+(\d+).*\r\n							#7 slava
				Poèet\ lidí\s+(\d+)\s*\r\n					#8 lidi
				Poèet\ hradù\s+(\d+)\s*\r\n				#9 hrady
				Síla\ P\.\s+(\d+)\s*\r\n					#10 sila
				Zlato\s+(\d+)\s*\r\n							#11 zlato
				Mana\s+(\d+)\s*\r\n							#12 mana
				Rozloha\s+(\d+)\s*\r\n						#13 rozloha
				Pøesvìdèení\s+(.+?)\s*\r\n					#14 presvedceni
				(?:Aliance\s+([^\r\n]+))?							#15 ali
				/xi', $vstup, $match))
			return $match;
		return 0;
	}
	
	function smaz () {
		MySQL_Query ("DELETE FROM `koberce_users` WHERE `ID_koberce` = '{$this->id}'");
		MySQL_Query ("DELETE FROM `koberce` WHERE `ID` = '{$this->id}'");
	}
	
	function cisloSRadem ($cislo) {
		$mod = Array (1=> "k", "M", "G", "T", "P", "E");
		$rad = 0;
		
		while (($cislo / 1000 >= 1) && ($rad < 6)) {
			$cislo /= 1000;
			$rad ++;
		}
		
		return round ($cislo).$mod[$rad];
	}
	
	function novyKoberecTabulka () {
		global $user_info;
		
		echo '		
			<form action="main.php" class="novy_koberec" method="post">
			<input type="hidden" name="akce" value="koberce">
			<input type="hidden" name="typ" value="new">
			<table>
				<tr>
					<td>
            Text: <br>(špehové) <a href="javascript:void(0);" onclick="return overlib(\'CTRL+A, CTRL+C, CTRL+V ze stránky špehù o vašem cíli.\', STICKY, CAPTION,\'Špehové\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
					<td><textarea name="k_cil"></textarea></td>
				</tr>
				<tr>
					<td>
            CSka: <br>(servis) <a href="javascript:void(0);" onclick="return overlib(\'Oznaète pouze CSka vašeho cíle (ze servisu) a ty sem vložte! <strong>NEDÁVEJTE CTRL+A, CTRL+C, CTRL+V</strong>\', STICKY, CAPTION,\'CSka\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
					<td><textarea name="k_cska"></textarea></td>
				</tr>
				<tr>
					<td>
            Poznámka: <a href="javascript:void(0);" onclick="return overlib(\'Cokoli dùležitého co chcete pøipsat\', STICKY, CAPTION,\'Poznámky\');"><img src="img/help.png" height="11" width="11" alt="[?]"> 
          </td>
					<td><textarea name="k_pozn"></textarea></td>
				</tr>
				<tr>
				  <td>
            Vyprší za: <a href="javascript:void(0);" onclick="return overlib(\'Za jak dlouho koberec (potažmo vaše CSko) konèí, starší koberce se nezobrazují, takže pokud necháte 0, tak se koberec vùbec nezobrazí\', STICKY, CAPTION,\'Timeout\');"><img src="img/help.png" height="11" width="11" alt="[?]">          
          </td>
				  <td><input name="k_expire_h" value="00" style="width: 20px;" size="2"> h, <input name="k_expire_m" value="00" style="width: 20px;" size="2"> m</td> 
				</tr>
				<tr>
					<td>
            Bounty pro 1ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 1ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 1ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
					<td><input name="k_bounty1" value="0"></td>
				</tr>
				<tr>
					<td>
            Bounty pro 2ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 2ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 2ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
					<td><input name="k_bounty2" value="0"></td>
				</tr>
				<tr>
					<td>
            Bounty pro 3ku: <a href="javascript:void(0);" onclick="return overlib(\'Orientaèní odmìna kterou jste ochotni nabídnou 3ce (zadávejte celé èíslo jakožto poèet zlata)\', STICKY, CAPTION,\'Bounty pro 3ku\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
					<td><input name="k_bounty3" value="0"></td>
				</tr>
				<tr>
					<td>
            Úroveò: <a href="javascript:void(0);" onclick="return overlib(\'Jestli je cíl lama :-) Podle toho se poèítá pøibližná síla 2ky a 3ky.\', STICKY, CAPTION,\'Lamovost cíle\');"><img src="img/help.png" height="11" width="11" alt="[?]">
          </td>
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
}
?>
