<?php
/**
 * Smarty plugin
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {plugin_files} function plugin
 * Fügt Pluginfiles in den Header
 *
 *
 * Type:     function<br>
 * Name:     sibling<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_sibling($params, $Smarty)
{
    $type = 'next';

    if ( isset( $params['type'] ) )
    {
        switch ( $params['type'] )
        {
            default:
                $type = 'next';
            break;

            case 'prev':
            case 'previous':
                $type = 'previous';
            break;
        }
    }

    if ( !isset( $params['site'] ) ) {
        return '';
    }

    try
    {
        switch ( $type )
        {
            case 'next':
                $Sibling = $params['site']->nextSibling();
            break;

            case 'previous':
                $Sibling = $params['site']->previousSibling();
            break;
        }

        $Smarty->assign($params['assign'], $Sibling);

    } catch ( \QUI\Exception $Exception )
    {
        $Smarty->assign($params['assign'], false);
    }
}
