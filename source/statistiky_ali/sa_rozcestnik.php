<?php
function Rozcestnik () {
	global $user_info;
	
	echo '<div id="stat_ali">';
	
	require "sa_zadavani.php";
	require "sa_z2.php";
	
	vstup_z2 ();											
	
	echo "</div>";
}
?>