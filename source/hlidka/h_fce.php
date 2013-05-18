<?php
function cislo ($cislo, $des_mist = 0)
{
	return number_format($cislo, $des_mist, '', ' ');
}
function text_s_teckama ($text, $delka = 25) {
	if (strLen($text) <= $delka) {
		return $text;
	} else {
		return substr ($text, 0, $delka - 3)."...";
	}
}
?>