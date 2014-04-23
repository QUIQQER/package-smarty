<?php
/**
 * Smarty {array} function plugin
 *
 * File:     function.array.php<br>
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  creates an array in a Smarty template
 *
 * @version 1.0<br>
 * @copyright PCSG - Henning
 * @author PCSG - Henning
 *
 * @param array $params parameters
 * @param object $smarty Smarty object
 *
 */
function smarty_function_array($params, $smarty)
{
    if ( !isset( $params['var'] ) ) {
        return;
    }

    $var = $params['var'];
    $arr = array();

    unset($params['var']);

    foreach ($params as $key => $value) {
        $arr[ $key ] = $value;
    }

    $smarty->assign($var, $arr);
}
