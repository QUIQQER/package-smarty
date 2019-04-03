<?php
/**
 * Smarty Plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author     PCSG - Henning
 */


/**
 * Smarty {json_decode} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 *
 * @param array $params
 * @param Smarty $Smarty
 *
 * @return Array
 * @author PCSG
 *
 */
function smarty_function_json_decode($params, $Smarty)
{
    if (!isset($params['var'])) {
        return [];
    }

    if (!isset($params['array'])) {
        $arr = \json_decode($params['var']);
    } else {
        $arr = \json_decode($params['var'], true);
    }

    if (isset($params['assign'])) {
        $Smarty->assign($params['assign'], $arr);
    } else {
        return $arr;
    }
}
