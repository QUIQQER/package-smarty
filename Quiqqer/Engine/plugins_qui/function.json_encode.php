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
 * Smarty {json_decode} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return Array
 */
function smarty_function_json_encode($params, &$smarty)
{
    $type = false;

    if ( !isset( $params['var'] ) ) {
        return;
    }

    $arr = json_encode( $params['var'] );

    if ( isset( $params['assign'] ) )
    {
        $smarty->assign( $params['assign'], $arr );

    } else
    {
        return $arr;
    }
}
