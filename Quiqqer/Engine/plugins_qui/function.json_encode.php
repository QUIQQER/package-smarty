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
 * @author PCSG
 *
 * @param array  $params
 * @param Smarty $Smarty
 *
 * @return Array
 */
function smarty_function_json_encode($params, $Smarty)
{
    if (!isset($params['var'])) {
        return '';
    }

    $arr = json_encode($params['var']);

    if (isset($params['assign'])) {
        $Smarty->assign($params['assign'], $arr);

    } else {
        return $arr;
    }
}
