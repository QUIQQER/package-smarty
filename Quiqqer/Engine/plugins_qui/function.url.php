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
function smarty_function_url($params, $smarty)
{
    $url  = '';
    $Site = false;

    try
    {
        if ( isset( $params['project'] ) )
        {
            if ( isset( $params['lang'] ) )
            {
                $_Project = \QUI\Projects\Manager::getProject(
                    $params['project'],
                    $params['lang']
                );

            } else
            {
                $_Project = \QUI\Projects\Manager::getProject(
                    $params['project'],
                    \QUI::getLocale()->getCurrent()
                );
            }

        } else
        {
            $_Project = \QUI::getRewrite()->getProject();

            if ( isset( $params['lang'] ) )
            {
                $_Project = \QUI\Projects\Manager::getProject(
                    $_Project->getAttribute('name'),
                    $params['lang']
                );
            }
        }

    } catch ( \QUI\Exception $Exception )
    {
        \QUI\System\Log::writeException( $Exception );
        return '';
    }

    if ( isset( $params['site'] ) )
    {
        $Site = $params['site'];

    } elseif ( isset( $params['id'] ) )
    {
        try
        {
            $Site = $_Project->get((int)$params['id']);

        } catch ( \QUI\Exception $Exception )
        {
            \QUI\System\Log::writeException( $Exception );
            \QUI\System\Log::writeRecursive( $params );
            return '';
        }
    }

    unset( $params['site'] );
    unset( $params['id'] );
    unset( $params['lang'] );

    if ( isset( $params[ 'params' ] ) )
    {
        $_params = $params[ 'params' ];
        unset( $params[ 'params' ] );

        $params  = array_merge( $params, $_params );
    }

    $assign = false;
    $host   = false;

    if ( isset( $params['assign'] ) )
    {
        $assign = $params['assign'];
        unset( $params['assign'] );
    }

    if ( isset( $params['host'] ) )
    {
        $host = true;
        unset( $params['host'] );
    }

    if ( $Site && $Site->getId() )
    {
        if ( isset( $params['rewrited'] ) && $params['rewrited'] )
        {
            unset( $params['rewrited'] );

            $url = $Site->getUrl( $params, true );
        } else
        {
            $url = $Site->getUrl( $params );
        }
    }

    if ( $host ) {
        $url = HOST . $url;
    }

    if ( isset( $params['relative'] ) )
    {
        $url    = split( '/', $url );
        $folder = split( '/', $_SERVER['REQUEST_URI'] );
        $last   = end( $url );

        if ( strpos( $_SERVER['REQUEST_URI'], $last ) )
        {
            $url = end( $url );
        } else
        {
            $url = str_replace( '.html','', end( $folder ) ) .'/'. end( $url );
        }
    }

    if ( !$assign ) {
        return $url;
    }

    $smarty->assign( $assign, $url );
}
