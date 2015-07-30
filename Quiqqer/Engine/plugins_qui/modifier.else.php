<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty else modifier plugin
 *
 * Type:     modifier<br>
 * Name:     else<br>
 * Date:     Jun 9, 2011
 * Purpose:  setzt alternative falls variable false oder leer ist
 * Input:    string
 * Example:  {$var|else:"..."}
 *
 * @author   PCSG Henning - www.pcsg.de
 * @version  1.0
 *
 * @param string
 * @param string
 *
 * @return string
 */
function smarty_modifier_else($string, $else)
{
    if (empty($string) || !$string) {
        return $else;
    }

    return $string;
}
