<?php
/**
 * Smarty plugin
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {suffix} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_suffix($params, &$smarty)
{
    global $Project;

    if ( !isset( $params['suffix'] ) ) {
        return '';
    }

    $P = $Project;

    /* @var $Project \QUI\Projects\Project */

    $url  = '';
    $site = false;

    try
    {
        if ( isset( $params['site'] ) )
        {
            $site = $params['site'];

        } elseif ( isset( $params['id'] ) )
        {
            if ( isset( $params['lang'] ) )
            {
                $P = \QUI\Projects\Manager::getProject(
                    $Project->getAttribute('name'),
                    $params['lang']
                );
            }

            $site = $P->get( (int)$params['id'] );
        }

    } catch ( \QUI\Exception $Exception )
    {
        return '';
    }


    unset( $params['site'] );
    unset( $params['id'] );
    unset( $params['lang'] );

    $url = '';

    if ( $site && $site->getId() ) {
        $url = $site->getUrl( $params );
    }

    return $url;
}
