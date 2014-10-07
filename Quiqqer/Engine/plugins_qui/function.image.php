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
 * Smarty {image} function plugin
 *
 * Type:     function<br>
 * Name:     image<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 */
function smarty_function_image($params, &$smarty)
{
    // defaults
    if ( !isset( $params['type'] ) ) {
        $params['type'] = 'resize';
    }


    if ( !isset( $params['image'] ) )
    {
        if ( !isset( $params['src'] ) ) {
            return '';
        }

        // Image Params
        if ( strpos($params['src'], 'image.php') === false ) {
            return smarty_plugin_image_assign($params, '', $smarty);
        }

        $src        = str_replace('&amp;', '&', $params['src']);
        $attributes = \QUI\Utils\String::getUrlAttributes($src);

        if ( !isset( $attributes['id'] ) || !isset( $attributes['project'] ) )
        {
            if ( isset( $params['onlysrc'] ) ) {
                return smarty_plugin_image_assign($params, $params['src'], $smarty);
            }

            return smarty_plugin_image_assign($params, '', $smarty);
        }

        try
        {
            $Project = \QUI::getProject($attributes['project']);
            $Media   = $Project->getMedia();
            $Image   = $Media->get((int)$attributes['id']); /* @param $Image MF_Image */

        } catch ( \QUI\Exception $Exception )
        {
            \QUI\System\Log::addNotice(
                'Smarty Image Plugin'. $Exception->getMessage()
            );

            if ( isset( $params['onlysrc'] ) ) {
                return smarty_plugin_image_assign($params, $params['src'], $smarty);
            }

            return smarty_plugin_image_assign($params, '', $smarty);
        }

    } else
    {
        $Image = $params['image'];

        // Falls $Image ein Folder ist, dann das erste Bild nehmen
        if ( $Image->getType() == 'FOLDER' )
        {
            try
            {
                $Image = $Image->firstChild('IMAGE');

            } catch ( \QUI\Exception $Exception )
            {
                $Image = false;
            }
        }
    }

    if ( !$Image ) {
        return smarty_plugin_image_assign($params, '', $smarty);
    }

    // Falls das Objekt gewünscht ist
    if ( isset($params['assign'] ) && isset( $params['object'] ) )
    {
        $smarty->assign( $params['assign'], $Image );
        return;
    }

    if ( $Image->getType() != 'QUI\Projects\Media\Image' ) {
        return smarty_plugin_image_assign($params, '', $smarty);
    }

    if ( !isset( $params['height'] ) ) {
        $params['height'] = false;
    }

    if ( !isset( $params['width'] ) ) {
        $params['width'] = false;
    }

    if ( isset( $params['reflection'] ) ) {
        $Image->setAttribute('reflection', true);
    }

    switch ( $params['type'] )
    {
        default:
        case 'resize':
            $src = $Image->createResizeCache( $params['width'], $params['height'] );
        break;
    }

    $src = str_replace( CMS_DIR, URL_DIR, $src );

    if ( isset( $params['onlysrc'] ) ) {
        return smarty_plugin_image_assign( $params, $src, $smarty );
    }

    // create image tag
    // @todo \QUI\Projects\Media\Utils::getImageHTML

    $str = '<img src="'. $src .'"';

    foreach ( $params as $key => $value )
    {
        if ( !$value ) {
            continue;
        }

        if ( $key == 'src' ||
             $key == 'type' ||
             $key == 'height' ||
             $key == 'width' ||
             $key == 'reflection' ||
             $key == 'image' )
        {
            continue;
        }

        $str .= ' '. $key .'="'. htmlentities($value, ENT_COMPAT, 'UTF-8') .'"';
    }

    // alt und title setzen
    if ( !isset( $params['alt'] ) ) {
        $str .= ' alt="'. htmlentities( $Image->getAttribute('alt'), ENT_COMPAT, 'UTF-8' ) .'"';
    }

    if ( !isset( $params['title'] ) ) {
        $str .= ' title="'. htmlentities( $Image->getAttribute('title'), ENT_COMPAT, 'UTF-8' ) .'"';
    }

    $str .= ' />';

    return smarty_plugin_image_assign( $params, $str, $smarty );
}

/**
 * Um das Ergebniss in eine Variable zuzuweisen
 *
 * @param unknown_type $params
 * @param unknown_type $str
 * @param unknown_type $smarty
 * @return unknown
 */
function smarty_plugin_image_assign($params, $str, $smarty)
{
    if ( !isset( $params['assign'] ) ) {
        return $str;
    }

    $smarty->assign( $params['assign'], $str );
}
