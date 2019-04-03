<?php
/**
 * Smarty {array} function plugin
 *
 * File:     function.array.php<br>
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  creates an array in a Smarty template
 *
 * @param array $params parameters
 * @param object $smarty Smarty object
 *
 * @author    PCSG - Henning
 *
 * @version   1.0<br>
 * @copyright PCSG - Henning
 */
function smarty_function_array($params, $smarty)
{
    if (!isset($params['var'])) {
        return;
    }

    $var = $params['var'];
    $arr = [];

    unset($params['var']);

    foreach ($params as $key => $value) {
        $arr[$key] = $value;
    }

    $smarty->assign($var, $arr);
}
