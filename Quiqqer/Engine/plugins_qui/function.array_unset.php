<?php
/**
 * Smarty {array_unset var=$array key=1 assign=} function plugin
 *
 * File:     function.array_unset.php<br>
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  unset an array key
 *
 * @author    www.pcsg.de (Henning Leutz)
 *
 * @param array  $params - parameters
 * @param Smarty $Smarty - Smarty object
 */
function smarty_function_array_unset($params, $Smarty)
{
    if (!isset($params['var'])) {
        return;
    }

    if (!isset($params['assign'])) {
        return;
    }

    $array = $params['var'];
    $key = $params['key'];

    if (isset($array[$key])) {
        unset($array[$key]);
    }

    $Smarty->assign($params['assign'], $array);
}
