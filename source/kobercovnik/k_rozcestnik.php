<?php
function Rozcestnik () {
	global $user_info;
	require_once "k_fce.php";
	require "koberec.class.php";

	echo '
	<div id="mini_menu">
		<div class="polozka"><a href="main.php?akce=koberce"><span'.($_REQUEST['typ'] == "" ? ' class="selected"' : "").'>Pøehled</span></a></div>
		<div class="polozka"><a href="main.php?akce=koberce&amp;typ=new"><span'.($_REQUEST['typ'] == "new" ? ' class="selected"' : "").'>Nový</span></a></div>
		<div class="polozka"><a href="main.php?akce=koberce&amp;typ=sprava"><span'.($_REQUEST['typ'] == "sprava" ? ' class="selected"' : "").'>Spravování</span></a></div>
	</div>
	<div class="clear">&nbsp;</div>
	';
	
	/* nacteni existujicich kobercu */
	$koberce_db = MySQL_Query ("SELECT `koberce`.`ID` as `ID` FROM `koberce`
	                   INNER JOIN `users` ON `users`.`ID` = `koberce`.`ID_vlastnik`
                  		WHERE (
                  				((`users`.`ID_ali_v` = '".$user_info['ID_ali_v']."') AND ('".$user_info['ID_ali_v']."' <> '0'))
                  				OR
                  				((`users`.`ID_ali_t` = '".$user_info['ID_ali_t']."') AND ('".$user_info['ID_ali_t']."' <> '0'))
                  				)
										 ORDER BY `expire` ASC");
	
	$koberce = null;
	while ($koberec = MySQL_Fetch_Array ($koberce_db)) {
		$koberce[$koberec['ID']] = new Koberec ($koberec['ID']);
	}
	/* --nacteni kobercu */
	
	/* novy tepich */
	if ($_POST['submit'] == "Zapsat") {
		$temp = new Koberec (array (
												'cil' => $_POST['k_cil'],
												'ID' => $user_info['ID'],
												'CSka' => $_POST['k_cska'],
												'poznamka' => $_POST['k_pozn'],
												'bounty1' => $_POST['k_bounty1'],
												'bounty2' => $_POST['k_bounty2'],
												'bounty3' => $_POST['k_bounty3'],
												'noob' => $_POST['k_noob'],
												'expire_h' => $_POST['k_expire_h'],
												'expire_m' => $_POST['k_expire_m']
												));
		if ($temp->id > 0)
			$koberce[$temp->id] = $temp;
	}
	
	/* zapsani si koberce */
	if ($_POST['k_akce'] == 'zapis') {
		if (isset ($koberce[$_POST['k_id']]))
			$koberce[$_POST['k_id']]->zapisStrelce ($user_info['ID'], $_POST['poznamka'], $_POST['poradi']);
		else echo "Takový koberec neexistuje!<br>";
	}
	
	/* odepsani si koberce */
	if ($_REQUEST['k_akce'] == 'odepsat') {
		if (isset ($koberce[$_REQUEST['k_id']])) {
			$koberce[$_REQUEST['k_id']]->odepisStrelce ($user_info['ID'], $_REQUEST['k_poradi']);
		}
		else echo "Takový koberec neexistuje!<br>";
	}
	
	if ($_POST['submit'] == 'Smazat') {
		if (isset ($koberce[$_REQUEST['s_id']])) {
		  if ($koberce[$_REQUEST['s_id']]->id_vlastnik == $user_info['ID']) {
  			$koberce[$_REQUEST['s_id']]->smaz();
  			$koberce[$_REQUEST['s_id']] = null;
  		} else {
        echo "Nemáte práva mazat tento koberec.";
      }
		}
		else echo "Takový koberec neexistuje!<br>";
	}

	switch ($_REQUEST['typ']) {
		case "new":
			/*require "k_novy.php";
			Novy_tabulka();*/
			$temp = new Koberec (-1);
			$temp->novyKoberecTabulka ();
		break;
		case "sprava":
			require "k_sprava.php";
			Seznam_vlastnich();
			if ($_POST['submit'] == 'Upravit') {
				if (isset ($koberce[$_REQUEST['s_id']])) {
				  if ($koberce[$_REQUEST['s_id']]->id_vlastnik == $user_info['ID'])
            $koberce[$_REQUEST['s_id']]->uprav(1);
          else echo "Nemáte práva upravovat tento koberec.";
				}
				else echo "Takový koberec neexistuje!<br>";
			} elseif (isset ($koberce[$_REQUEST['s_id']])) {
        if ($koberce[$_REQUEST['s_id']]->id_vlastnik == $user_info['ID'])
				  $koberce[$_REQUEST['s_id']]->uprav();
				else echo "Nemáte práva upravovat tento koberec.";
			} else 
        echo "Vyberte koberec na upraveni<br>";
    break;
    default:
      $cislo_koberce = 0;
      if (is_array ($koberce)) {
        echo '<script type="text/javascript">
              function toggleAll (num) {
                toggle (\'o_cili_\'+ num + \'_2\');
                toggle (\'strelci_\'+ num + \'_1\');
                toggle (\'strelci_\'+ num + \'_2\');
                toggle (\'strelci_\'+ num + \'_3\');
                toggle (\'strucne_detaily_\'+ num);
                toggle (\'casy_\'+ num);
                toggle (\'cska_\'+ num);
                toggle (\'pozn_\'+ num);
              }
              function toggle (id) {
                obj = document.getElementsByTagName("div");
                if (obj[id].style.display == \'none\'){
                obj[id].style.display = \'\';
                }
                else {
                obj[id].style.display = \'none\';
                }
              }
              </script>';

        $koberceLepsiVykresleni = array ();
        foreach ($koberce as $koberec) if ($koberec->maNarok ($user_info['ID'])) {
          $koberceLepsiVykresleni[] = $koberec->toStr()
              . '<script type="text/javascript">
                  toggleAll('.$koberec->id.');
                </script>';
        }
        
        if (count($koberceLepsiVykresleni) == 0)
          echo "Nejsou vypsány žádné koberce.<br />";
          
        for ($sloupec = 0; $sloupec < 3; $sloupec ++) {
          echo '<div class="keberec_sloupec">&nbsp;';
          for ($i = 0; $koberceLepsiVykresleni[$i * 3 + $sloupec]; $i ++)
            echo $koberceLepsiVykresleni[$i * 3 + $sloupec];
          echo '</div>';
        }
      } else {
        echo "Nejsou vypsány žádné koberce.<br />";
      }
    break;
  
  }
}
?>
