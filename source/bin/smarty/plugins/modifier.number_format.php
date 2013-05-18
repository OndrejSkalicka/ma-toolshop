<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty number_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_format via {@link number_format()} <br>
 * Purpose:  format numbers
 * @author   Savannah
 * @param float
 * @param int 
 * @param string
 * @param string 
 * @return string
 */
function smarty_modifier_number_format($number, $decimals = null, $dec_point = null, $thousands_sep = null)
{
  if (is_null($decimals)) {
  	return number_format($number);
  }
  if (is_null($dec_point)) {
    return number_format ($number, $decimals);
  }
  if (is_null($thousands_sep)) {
    return number_format ($number, $decimals, $dec_point);
  }
  return number_format ($number, $decimals, $dec_point, $thousands_sep);
}

/* vim: set expandtab: */

?>
