<?php
/**
 * Smarty Plugin
 *
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author PCSG - Henning
 */


/**
 * Smarty {PCSG} function plugin
 *
 * Type:     function<br>
 * Name:     PCSG<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 *
 * @example {PCSG method=getPlugins assign=Plugins}
 */
function smarty_function_PCSG($params, $smarty)
{
    if (!isset($params['method'])) {
        return;
    }

    $class = 'PCSG';

    if (!method_exists($class, $params['method'])) {
        return;
    }

    $result = call_user_func(array($class, $params['method']));

    if (!isset($params['assign'])) {
		return $result;
	}

	$smarty->assign($params['assign'], $result);
}

?>
