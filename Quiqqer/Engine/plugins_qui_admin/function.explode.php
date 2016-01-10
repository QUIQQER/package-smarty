<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {explode var= delimiter= assign=} function plugin
 *
 * Type:     function<br>
 * Name:     explode<br>
 * @author Henning Leutz <leutz@pcsg.de>
 * @param array $params
 * @param Smarty $Smarty
 * @return String|void
 */
function smarty_function_explode($params, $Smarty)
{
    if (!isset($params['var'])) {
        return;
    }

    if (!isset($params['delimiter'])) {
        return;
    }

    $result = explode($params['delimiter'], $params['var']);

    foreach ($result as $key => $value) {
        if (empty($result[$key])) {
            unset($result[$key]);
        }
    }

    if (!isset($params['assign'])) {
        return $result;
    }

    $Smarty->assign($params['assign'], $result);
}
