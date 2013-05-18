<?php
require 'koberec.class.php';
require 'test_text.php';
require '../dblogin.php';

/*$x = new Koberec (array ('hospodareni' => $text, 'ID' => 1, 'CSka' => "CSka \n CSAD", 'Poznamka' => "asd\n pozn", 'bounty1' => 10000,
								'bounty2' => 5000, 'bounty3' => 0, 'noob' => 1));*/
$x = new Koberec (30);

echo $x->toStr ();
?>