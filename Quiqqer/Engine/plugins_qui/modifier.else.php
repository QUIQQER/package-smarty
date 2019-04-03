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
 * @param string
 * @param string
 *
 * @return string
 * @version  1.0
 *
 * @author   PCSG Henning - www.pcsg.de
 */
function smarty_modifier_else($string, $else)
{
    if (empty($string) || !$string) {
        return $else;
    }

    return $string;
}
