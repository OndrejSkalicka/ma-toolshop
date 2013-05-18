<?php 
/* overeni uzivatele */
require_once ("fce.php");
if ((!CheckLogin ()) || !MaPrava("nastaveni")) {
	LogOut();
}
/* ------ */
OverWritePOSTGET('typ');
?>
<div id="mini_menu">
	<div class="polozka"><a href="main.php?akce=settings&amp;typ=jednotky"><span<?php if ($_GET['typ'] == "jednotky") echo ' class="selected"';?>>Jednotky</span></a></div>
	<div class="polozka"><a href="main.php?akce=settings&amp;typ=brankari"><span<?php if ($_GET['typ'] == "brankari") echo ' class="selected"';?>>Brankáøi</span></a></div>
	<div class="polozka"><a href="main.php?akce=settings&amp;typ=brany"><span<?php if ($_GET['typ'] == "brany") echo ' class="selected"';?>>Brány</span></a></div>
	<div class="polozka"><a href="main.php?akce=settings&amp;typ=nova_jednotka"><span<?php if ($_GET['typ'] == "nova_jednotka") echo ' class="selected"';?>>Nové jednotky</span></a></div>
</div>
<div class="clear">&nbsp;</div>
<?php
switch ($_GET['typ']) {
	case "jednotky":
		include "uprav_jednotku.php";
		OverWritePOSTGET('ch_id');
		UpravJednotku($_GET['ch_id'],0,1,1);
	break;
	case "brankari":
		include "uprav_jednotku.php";
		OverWritePOSTGET('ch_id');
		UpravJednotku($_GET['ch_id'],1,1,1);
	break;
	case "brany":
		if ($_POST['nova_brana']) {
      include "nova_brana.php";
      novaBrana ();
    }    
    include "uprav_branu.php";
		UpravBranu($_REQUEST['ch_id'],1);
	break;
	case "nova_jednotka":
		include "nova_jednotka.php";
		Nova_jednotka();
	break;
	default:
		echo "vyberte shora";
	break;
}
?>
