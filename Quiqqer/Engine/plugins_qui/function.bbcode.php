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
 * Smarty {url} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 */
function smarty_function_bbcode($params, $smarty)
{
    if ( !isset( $params['value'] ) ) {
        return;
    }

    $BBCode = new \QUI\Utils\Text\BBCode();
    $str    = $BBCode->parseToHTML( $params['value'] );

    if ( isset( $params['innerHTML'] ) )
    {
        $inner = preg_replace(
            '#<a([^>]*)>(.*?)<\/a>#is',
            "$2",
            $str
        );

        $inner_html = str_replace('%value', $inner, $params['innerHTML']);

        $str = preg_replace(
            '#<a([^>]*)>(.*?)<\/a>#is',
            "<a$1>$inner_html</a>",
            $str
        );
    }

    if ( isset( $params['assign'] ) ) {
        $smarty->assign( $params['assign'], $str );
    }

    return $str;
}
