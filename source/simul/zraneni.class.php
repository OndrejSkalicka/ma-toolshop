<?php
  class Zraneni {
  
    function Zraneni () {
      ;
    }
    
    function zjistiZraneni ($zabitoCast) {
      /* parametr urcuje, v procentech, jaka cast jednotky by byla zabita 
        kdyby dostala urcitou ranu a v zavislosti na tom vrati procentualni 
        cast - kolik jednotek bude "pouze zraneno". Napr. pokud by rana mela 
        zabit 50% stacku ($zranenoZabito == 0.5), tak vysledek bude take cca
        50% (resp. 0.5) */
      
      $P2 = 2;
      $O2 = 0.57;
      
      
      return (1-pow($zabitoCast, $P2))*$O2;
      
      // REDUNDANT
        
//       $zabitoCast *= 1000;
//         
//       /* zranovaci tabulka, prvni sloupec udava (nasobeno 1000) kolik jednotek
//         melo zemrit z cele jednotky, tzn. $zabitoCast. Druha navratovou hodnotu 
//         (v nasobcich 1000) */
//       $dmgTabulka = array (963 => 91,
//                     918 => 114,
//                     875 => 171,
//                     717 => 298,
//                     712 => 306,
//                     602 => 372,
//                     516 => 421,
//                     467 => 443,
//                     452 => 449,
//                     452 => 449,
//                     463 => 451,
//                     418 => 464,
//                     413 => 474,
//                     368 => 485,
//                     336 => 493,
//                     335 => 494,
//                     331 => 506,
//                     267 => 525,
//                     236 => 528,
//                     136 => 553,
//                     135 => 558,
//                     131 => 575,
//                     127 => 643);
//                     
//       $lastValue = null;
//       //najdu v jakem intervalu lezim
//       foreach ($dmgTabulka as $key => $value) {
//         // jsem v nultem, tzn. vetsi nez prvni prvek pole
//         if ($lastValue == null && $zabitoCast > $key) {
//           $lastValue = $value;
//           break;
//         }
//         
//         //trefil jsem se presne do hranice intervalu
//         if ($zabitoCast == $key) {
//           break;
//         }
//         
//         if ($zabitoCast > $key) {
//           break;
//         }
//         
//         // jinak si ulozim posledni hodnout, at mam s cim prumerovat
//         $lastValue = $value;
//       }
// 
//       // udelam prumer z posledni a soucasne
//       return ($value + $lastValue )/2000;
     }
  }
?>
