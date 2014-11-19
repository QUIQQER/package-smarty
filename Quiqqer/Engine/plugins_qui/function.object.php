<?php

/**
 * Smarty {object} function plugin
 *
 * File:     function.object.php<br>
 * Type:     function<br>
 * Name:     object<br>
 * Purpose:  creates a object
 *
 * @param array $params parameters
 * @param object $smarty Smarty object
 *
 * @example {object class="\My\Namspace" assign=Obj}
 */

function smarty_function_object($params, $Smarty)
{
    if ( !isset( $params['class'] ) ) {
        return '';
    }

    try
    {
        $Object = new $params['class']();

    } catch ( \QUI\Exception $Exception )
    {
        \QUI\System\Log::writeException( $Exception );

        return '';
    }

    $assign = isset( $params['assign'] ) ? $params['assign'] : false;

    if ( !$assign ) {
        return $Object;
    }

    $Smarty->assign( $assign, $Object );
}
