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
 * Smarty {text_passage} function plugin
 *
 * Type:     function<br>
 * Name:     sentence<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 */
function smarty_function_text_passage($params, $smarty)
{
    if ( !isset( $params['text'] ) ) {
        return '';
    }

    if ( !isset( $params['start'] ) ) {
        return '';
    }

    if ( !isset( $params['end'] ) ) {
        return '';
    }

    $text  = $params['text'];
    $start = (int)$params['start'];
    $end   = (int)$params['end'];

    $text = htmlspecialchars_decode( $text );

    if ( isset( $params['striphtml'] ) ) {
        $text = strip_tags($text);
    }

    $last = mb_substr($text, $end-1, $end);

    if ( preg_match('/[^a-zA-Z0-9]/i', $last) )
    {
        // bei ganzen Wörtern abschneiden
        if ( isset( $params['wholewords'] ) )
        {
            $text = preg_replace('/\s+?(\S+)?$/', '', mb_substr( $text, $start, $end+3 ));
        } else
        {
            $text = mb_substr($text, $start, $end+3);
        }

    } else
    {
        // bei ganzen Wörtern abschneiden
        if ( isset( $params['wholewords'] ) )
        {
            $text = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $text, $start, $end ));
        } else
        {
            $text = mb_substr( $text, $start, $end );
        }
    }


    if ( class_exists('tidy') )
    {
        $tidy = new \tidy();

        $config = array(
            "char-encoding" => "utf8",
            'output-xhtml'   => true
        );

        $tidy->parseString( $text, $config, 'utf8' );
        $tidy->cleanRepair();

        $text = $tidy;
        $text = \Utils_Dom::getInnerBodyFromHTML( $text );
    }

    if ( isset( $params['append'] ) )
    {
        if ( mb_strlen( $params['text'] ) > mb_strlen( $text ) ) {
            $text .= $params['append'];
        }
    }

    return $text;
}