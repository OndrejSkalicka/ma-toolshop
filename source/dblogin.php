<?php
//die ("Zavreno kvuli udrzbe");
$typ = 4;

error_reporting (E_ALL ^ E_NOTICE);

/*if ($typ != 2) {
	if ($_SESSION['database_type'] == "archiv") $typ = 3;
	if ($_SESSION['database_type'] == "savannah") $typ = 4;	
}*/

switch ($typ)
{
	case 1:	/* ma.wz.cz */
		$host = "mysql.webzdarma.cz";
		$user = "meliorannis";
		$password = "---";
		$database = "meliorannis";
	break;
	case 2:	/* local */
		$host = "localhost";
		$user = "savannah.stensoft.com";
		$password = "";
		$database = "melior";
	break;
	case 3:	/* archiv.wz.cz */
		$host = "mysql.webzdarma.cz";
		$user = "archivbranek";
		$password = "---";
		$database = "archivbranek";
	break;
	case 4:	/* InetPRO */
		$host = "localhost";
		$user = "savannah";
		$password = "---";
		$database = "savannah_meliorannis";
	break;
}
$spojeni = MySQL_Connect($host, $user, $password);
MySQL_Select_DB($database,$spojeni);
?>
