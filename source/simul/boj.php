<?php
/* overeni uzivatele */
require_once ("fce.php");

if (!CheckLogin () || !MaPrava("simul")) {
  LogOut();
}
/* ------ */

$newDesign = false;
$newDesII = false;


echo "<center><hr>";
for ($kolo=1;$kolo<=4;$kolo++)
{  
  echo "Round $kolo<br>\n<br>\n";
  
  if ($newDesign) echo '<table class="simulNewDes">';
  
  NastavStartKola();
  
  if ($ut[$kolo] != 0) kouzli (1,$ut[$kolo]);
  if ($ob[$kolo] != 0) kouzli (2,$ob[$kolo]);
  
  SeradDleIni($kolo);
  
  if ($ini != "")
  {
    foreach ($ini as $utok => $value)
    {
        if (($vojak[$utok]->pocet != 0)&&($vojak[$utok]->jmeno != ""))
          {
            if ($newDesign) echo "<tr><td>";
            echo '<div class="'.($vojak[$utok]->hrac == 1?"utok":"obrana").'">';
            echo $vojak[$utok]->pocet." x ".VojakSLinkem($vojak[$utok])." (ini ".round($value).") ".($vojak[$utok]->hrac == 1?"=":"-")."> ";
            $cil = ZjistiVhodnyCil($vojak[$utok]->hrac,(($vojak[$utok]->typ=="S")||($vojak[$utok]->druh=="L"))?1:0, $vojak[$utok]->utok_na);
            if ($cil > -1)
            {
              echo $vojak[$cil]->pocet."/".$vojak[$cil]->pocet_max." x ".VojakSLinkem($vojak[$cil])." (CS zbývá: ".max(0,$vojak[$cil]->cs-($vojak[$utok]->typ=="B"?1:0)).")<br>\n";
              $dmg = $vojak[$utok]->dmg*$vojak[$utok]->xp*$vojak[$utok]->pocet/100 * $vojak[$utok]->hlas_krve;
              if ($SHOW_STATS == 1) echo '<table class="info">';
              if ($SHOW_STATS == 1) echo "<tr><td>Dmg zaklad: </td><td>dam: ".cislo($dmg)."</tr>\n";
              $dmg = $dmg * $DAM_MOD; 
              if ($SHOW_STATS == 1) echo "<tr><td>Modifikator 'ATV' ".$DAM_MOD."</td><td>dam: ".cislo($dmg)."<br>\n";
              $dmg = $dmg * $vojak[$utok]->turn_dam_mod; // pisek, okouzleni ...
              if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kouzla ".$vojak[$utok]->turn_dam_mod."</td><td>dam: ".cislo($dmg)."<br>\n";
              $mod = BonusZaKolo();
              $dmg *= $mod;
              if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kolo $mod</td><td>dam: ".cislo($dmg)."<br>\n";
              $mod = ZjistiBonusZaTyp($vojak[$utok],$vojak[$cil]);
              $dmg *= $mod;
              if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za typ jednotek $mod</td><td>dam: ".cislo($dmg)."<br>\n";
              $dobrneni = min($dmg*$ARMOR_ABSORB,$vojak[$cil]->brn_zbyva*$vojak[$cil]->pocet*$vojak[$cil]->xp/100); //*$vojak[$cil]->brn_zbyva/$vojak[$cil]->brn
              $dmg -= $dobrneni;
              $vojak[$cil]->brn_zbyva -= /*round */($dobrneni/$vojak[$cil]->pocet/$vojak[$cil]->xp*100);
              if ($SHOW_STATS == 1) echo "<tr><td>Brneni vzalo ".cislo($dobrneni)."</td><td>dam: ".cislo($dmg)."<br>\n";
              if ($SHOW_STATS == 1) echo "<tr><td></td><td>Brn zbyva ".cislo($vojak[$cil]->brn_zbyva)."</td></tr>";
         
              $killed = floor ($dmg / ($vojak[$cil]->zvt*$vojak[$cil]->xp/100 * $vojak[$cil]->hlas_krve));
              $killed = min ($killed, $vojak[$cil]->pocet);
             
              /*$vojak[$cil]->overflow += $dmg - $killed * $vojak[$cil]->zvt;
              if ($SHOW_STATS == 1) echo "<tr><td>Damage overflow ".cislo($dmg - $killed * $vojak[$cil]->zvt)."</td><td>total overflow: ".cislo($vojak[$cil]->overflow)."</td><br>\n";
              $over_kill = floor ($vojak[$cil]->overflow / $vojak[$cil]->zvt);
              $vojak[$cil]->overflow -= $over_kill * $vojak[$cil]->zvt;
              if ((SHOW_STATS == 1) && ($over_kill > 0)) echo "<tr><td>Overflow killed $over_kill</td><td>overflow remaining: ".cislo($vojak[$cil]->overflow)."</td><br>\n";
              $killed += $over_kill;
              $killed = min ($killed, $vojak[$cil]->pocet);*/
            
              if ($SHOW_STATS == 1) echo "</table>";
             
              echo "Zabito: $killed, zbývá: ".($vojak[$cil]->pocet - $killed)."/".$vojak[$cil]->pocet_max."<br>\n";
              if ($vojak[$cil]->brn == 0) $zbyva = 0; else $zbyva = max($vojak[$cil]->brn_zbyva/$vojak[$cil]->brn*100, 0);
              
              echo "Brnìní zbývá: ".cislo($zbyva)."%".($newDesign ? '</td><td>'.graf(($vojak[$cil]->pocet - $killed)/$vojak[$cil]->pocet_max, $newDesII ? $vojak[$cil]->pocet/$vojak[$cil]->pocet_max : 0).'</td></tr><tr><td>' : '<br>')."\n";
                //------- CSko ---------
              if (($vojak[$utok]->typ == "B")&&($vojak[$cil]->cs > 0))
              {
                $vojak[$cil]->cs --;
                if ($newDesign) echo "</td></tr><tr><td>";
                echo "<div class=\"CS\"><br>Obrana:".($newDesign ? '</td></tr><tr><td>' : '<br>')."\n".$vojak[$cil]->pocet." x ".VojakSLinkem($vojak[$cil])." ".($vojak[$utok]->hrac == 1?"-":"=")."> ";
                echo $vojak[$utok]->pocet."/".$vojak[$utok]->pocet_max." x ".VojakSLinkem($vojak[$utok])." <br />\n";
                $dmg = $vojak[$cil]->dmg*$vojak[$cil]->xp*$vojak[$cil]->pocet/100 * $vojak[$cil]->hlas_krve;
                if ($SHOW_STATS == 1) echo '<table class="info">';
                if ($SHOW_STATS == 1) echo "<tr><td>Dmg zaklad: </td><td>dam: ".cislo($dmg)."</tr>\n";
                $dmg = $dmg * $DAM_MOD; 
                if ($SHOW_STATS == 1) echo "<tr><td>Modifikator 'ATV' ".$DAM_MOD."</td><td>dam: ".cislo($dmg)."<br>\n";
                $dmg = $dmg * $vojak[$cil]->turn_dam_mod; // pisek, okouzleni ...
                if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kouzla ".$vojak[$cil]->turn_dam_mod."</td><td>dam: ".cislo($dmg)."<br>\n";
                $mod = BonusZaKolo();
                $dmg *= $mod;
                if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za kolo $mod</td><td>dam: ".cislo($dmg)."<br>\n";
                $mod = ZjistiBonusZaTyp($vojak[$utok],$vojak[$cil]);
                $dmg *= $mod;
                if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za typ jednotek $mod</td><td>dam: ".cislo($dmg)."<br>\n";
                if ($vojak[$cil]->typ == "B")
                {
                  if ($vojak[$utok]->druh == "P") {$mod = 1/3;}
                  if ($vojak[$utok]->druh == "L") {$mod = 1/4;}
                }
                if ($vojak[$cil]->typ == "S")
                {
                  if ($vojak[$utok]->druh == "P") {$mod = 1/6;}
                  if ($vojak[$utok]->druh == "L") {$mod = 1/8;}
                }
                $dmg *= $mod;
                if ($SHOW_STATS == 1) echo "<tr><td>Modifikator za typ jednotek v CSku $mod</td><td>dam: ".cislo($dmg)."</td></tr>\n";
                $dobrneni = min($dmg*$ARMOR_ABSORB,$vojak[$utok]->brn_zbyva*$vojak[$utok]->pocet*$vojak[$utok]->xp/100); //*$vojak[$cil]->brn_zbyva/$vojak[$cil]->brn
                $dmg -= $dobrneni;
                $vojak[$utok]->brn_zbyva -= round ($dobrneni/$vojak[$utok]->pocet/$vojak[$utok]->xp*100);
                if ($SHOW_STATS == 1) echo "<tr><td>Brneni vzalo ".cislo($dobrneni)."</td><td>dam: ".cislo($dmg)."<br>\n";
                if ($SHOW_STATS == 1) echo "<tr><td></td><td>Brn zbyva ".cislo($vojak[$utok]->brn_zbyva)."</td></tr>";
           
                $killed_cs = floor ($dmg / ($vojak[$utok]->zvt*$vojak[$utok]->xp/100 * $vojak[$utok]->hlas_krve));
                $killed_cs = min ($killed_cs, $vojak[$utok]->pocet);
               
                /* $vojak[$utok]->overflow += $dmg - $killed_cs * $vojak[$utok]->zvt;
                if ($SHOW_STATS == 1) echo "<tr><td>Damage overflow ".cislo($dmg - $killed_cs * $vojak[$utok]->zvt)."</td><td>total overflow: ".cislo($vojak[$utok]->overflow)."</td><br>\n";
                $over_kill = floor ($vojak[$utok]->overflow / $vojak[$utok]->zvt);
                $vojak[$utok]->overflow -= $over_kill * $vojak[$utok]->zvt;
                if ((SHOW_STATS == 1) && ($over_kill > 0)) echo "<tr><td>Overflow killed $over_kill</td><td>overflow remaining: ".cislo($vojak[$utok]->overflow)."</td><br>\n";
                $killed_cs += $over_kill;
                $killed_cs = min ($killed_cs, $vojak[$utok]->pocet);*/
              
                if ($SHOW_STATS == 1) echo "</table>";
               
                echo "Zabito: $killed_cs, zbývá: ".($vojak[$utok]->pocet-$killed_cs)."/".$vojak[$utok]->pocet_max."<br>\n";
                if ($vojak[$utok]->brn == 0) $zbyva = 0; else $zbyva = max(0,$vojak[$utok]->brn_zbyva/$vojak[$utok]->brn*100);
                echo "Brnìní zbývá: ".cislo($zbyva)."%".($newDesign ? '</td><td>'.graf(($vojak[$utok]->pocet-$killed_cs)/$vojak[$utok]->pocet_max, $newDesII ? $vojak[$utok]->pocet/$vojak[$utok]->pocet_max : 0).'</td></tr><tr><td>' : '<br>')."\n";
                $vojak[$utok]->pocet -= $killed_cs;
                echo "</div>";
              }
              $vojak[$cil]->pocet -= $killed;
            }
            else
            {
                echo "Nema cil<br>\n";
            }
            echo "<br>\n";
            echo "</div>\n";
            if ($newDesign) echo "</td></tr>";
          }
    }
  }
  else
  {
    echo "Žádné bojové jednotky pro toto kolo!<br>";
  }
  if ($newDesign) echo "</table>";
  echo "<hr>";
   
}
?>
