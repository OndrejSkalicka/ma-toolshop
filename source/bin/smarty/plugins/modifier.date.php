<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty date_savannah modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_format via {@link date()} <br>
 * Purpose:  format numbers
 * @author   Savannah
 * @param string
 * @return string
 */
function smarty_modifier_date($timestamp, $format = 'H:i:s d-m-Y')
{
  return date ($format, $timestamp);
}

/* vim: set expandtab: */

?>