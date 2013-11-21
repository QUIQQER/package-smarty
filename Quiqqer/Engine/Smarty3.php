<?php

/**
 * This file contains Package\Smarty3\Engine
 */

namespace Quiqqer\Engine;

/**
 * Smarty3 Interface for QUIQQER
 * provides the Smarty3 engine for QUIQQER
 *
 * @author www.pcsg.de (Henning Leutz)
 * @package com.pcsg.quiqqer.package.smarty3
 *
 * @todo translate comments
 */

class Smarty3 implements \Interface_Template_Engine
{
    protected $_Smarty = null;

    /**
     * Konstruktor
     *
     * @param unknown_type $admin
     */
    public function __construct($admin=false)
    {
        // Templates
        \QUI\Utils\System\File::mkdir( VAR_DIR .'cache/templates' );
        \QUI\Utils\System\File::mkdir( VAR_DIR .'cache/compile' );
        \QUI\Utils\System\File::mkdir( VAR_DIR .'cache/cache' );

        $Smarty = new \Smarty();
        $Smarty->setTemplateDir( VAR_DIR .'cache/templates' );
        $Smarty->setCompileDir( VAR_DIR .'cache/compile' );
        $Smarty->setCacheDir( VAR_DIR .'cache/cache' );

        $DIR = dirname( __FILE__ );

        $plugin_dir = $Smarty->getPluginsDir();

        if ( $admin == true )
        {
            $plugin_dir[] = $DIR .'/plugins_qui/';
            $plugin_dir[] = $DIR .'/plugins_qui_admin/';

        } else
        {
            $plugin_dir[] = $DIR .'/plugins_qui/';
        }

        $Smarty->setPluginsDir( $plugin_dir );

        $this->_Smarty = $Smarty;
    }

    /**
     * (non-PHPdoc)
     * @see Interface_Template_Engine::assign()
     *
     * @param String|array $var
     * @param unknown_type $value
     */
    public function assign($var, $value=false)
    {
        if ( is_string( $var ) )
        {
            $this->_Smarty->assign( $var, $value );
            return;
        }

        $this->_Smarty->assign( $var );
    }



    /**
     * Schaltet das Logging der Smartyfehler an
     */
    public function showErrors()
    {
        //$this->_PCSG_Errors = true;
    }

    /**
     * Schaltet das Logging der Smartyfehler aus
     */
    public function hideErrors()
    {
        //$this->_PCSG_Errors = false;
    }

    /**
     * executes & returns or displays the template results
     *
     * @param unknown_type $resource_name
     * @return unknown
     */
    public function fetch($resource_name)
    {
        $error = \QUI::getErrorHandler()->getAttribute( 'ERROR_2' );

        // Error Behandlung bei Smarty ausschalten, zuviele fehler
        \QUI::getErrorHandler()->setAttribute( 'ERROR_2', false );

        $tpl = $this->_Smarty->fetch( $resource_name );

        // Errors wieder einschalten, falls es aus war
        \QUI::getErrorHandler()->setAttribute( 'ERROR_2', $error );

        return $tpl;
    }

    /**
     * HEADER Erweiterung
     */

    /**
     * Header Erweitern
     *
     * @param String $str
     * @param Priorität $prio
     */
    public function extendHeader($str, $prio=3)
    {
        $prio = (int)$prio;

        if ( !isset( $this->_headers[ $prio ] ) ) {
            $this->_headers[ $prio ] = '';
        }

        $_str  = $this->_headers[ $prio ];
        $_str .= $str;

        $this->_headers[ $prio ] = $_str;
    }

    /**
     * Enter description here...
     *
     * @param \QUI\Projects\Project $Project
     * @param \QUI\Projects\Site $Site
     *
     * @return String
     */
    public function getHeader($Project=false, $Site=false)
    {
        if ( $Project == false ) {
            $Project = $this->Template->getAttribute('Project');
        }

        if ( $Site == false ) {
            $Site = $this->Template->getAttribute('Site');
        }


        $file_header_cache = VAR_DIR .'cache/sites/'.
            $Project->getAttribute('name') .'_'.
            $Project->getAttribute('template') .'_'.
            $Project->getAttribute('lang') .'-'.
            $Site->getId();

        //if (file_exists($file_header_cache) && false) {
        //		return file_get_contents($file_header_cache);
        //}

        $header_extend  = '';
        $header_extend .= '<script type="text/javascript" src="'. URL_BIN_DIR .'js/templates/templates.js"></script>';
        $header_extend .= '<script type="text/javascript">';
        $header_extend .= '/* <![CDATA[ */';
            $header_extend .= 'var URL_BIN_DIR = \''. URL_BIN_DIR .'\';';
            $header_extend .= 'var BIN_URL     = \''. URL_DIR .'usr/bin/'. $Project->getAttribute('template') .'/\';';
        $header_extend .= '/* ]]> */';
        $header_extend .= '</script>';

        /**
         * CMS Header Attribute
         */
        foreach ( $this->_header_attributes as $key => $value )
        {
            if ( $value == false ) {
                continue;
            }

            switch ($key)
            {
                case 'zoom':
                    $header_extend .= '<!-- [begin] ZOOM -->';
                    $header_extend .= '<script type="text/javascript" src="'. URL_BIN_DIR .'js/extern/highslide/highslide.js"></script>';
                    $header_extend .= '<script type="text/javascript" src="'. URL_BIN_DIR .'js/extern/highslide/zoom.js"></script>';
                    $header_extend .= '<link rel="StyleSheet" type="text/css" href="'. URL_BIN_DIR .'js/extern/highslide/style.css" media="screen" />';

                    $header_extend .= '<script type="text/javascript">';
                    $header_extend .= '/* <![CDATA[ */';
                        $header_extend .= 'window.addOnLoad(function() {';
                            $header_extend .= 'zoom_image_loader();';
                        $header_extend .= '});';
                    $header_extend .= '/* ]]> */';
                    $header_extend .= '</script>';

                    $header_extend .= '<!-- [end] ZOOM -->';
                break;

                case 'ptools:button2':
                    $header_extend .= '<script type="text/javascript" src="'. URL_BIN_DIR . 'js/ptools/button/button2.js?hash='. md5_file(BIN_DIR .'js/ptools/button/button2.js') .'"></script>'."\n";
                    $header_extend .= '<link rel="StyleSheet" type="text/css" href="'. URL_BIN_DIR . 'js/ptools/button/style2.css?hash='. md5_file(BIN_DIR .'js/ptools/button/style2.css') .'" />'."\n";
                break;

                case 'ptools:helper':
                    $header_extend .= '<script type="text/javascript" src="'. URL_BIN_DIR . 'js/ptools/helper/helper.js?hash='. md5_file(BIN_DIR .'js/ptools/helper/helper.js') .'"></script>'."\n";
                    $header_extend .= '<link rel="StyleSheet" type="text/css" href="'. URL_BIN_DIR . 'js/ptools/helper/style.css?hash='. md5_file(BIN_DIR .'js/ptools/helper/style.css') .'" />'."\n";
                break;
            }
        }

        foreach ( $this->_headers as $_str ) {
            $header_extend .= $_str;
        }


        $Smarty = \QUI_Template::getEngine( true );

        $Smarty->assign(array(
            'header_extend' => $header_extend,
            'Project'       => $Project,
            'Site'          => $Site,
            'Smarty'        => $this
        ));

        return $Smarty->fetch( LIB_DIR .'templates/header.html' );
        //file_put_contents($file_header_cache, $content);
        //return $content;
    }

    /**
     * Footer für Templates
     *
     * @return String
     */
    public function getFooter()
    {
        return '
        <script type="text/javascript">
        /* <![CDATA[ */
            if (typeof window.addEvent == \'function\')
            {
                window.addEvent(\'domready\', function() {
                    window.execLoad();
                });
            } else if (typeof $ != \'undefined\' && typeof $(document) != \'undefined\')
            {
                $(document).ready(function() {
                    window.execLoad();
                });
            } else
            {
                if (window.addEventListener)
                {
                    document.addEventListener("DOMContentLoaded", window.execLoad, false);
                    window.addEventListener("load", window.execLoad, false);
                } else if (window.attachEvent)
                {
                    window.attachEvent(\'onload\', window.execLoad);
                } else
                {
                    window.onload = function() {
                        window.execLoad();
                    };
                };

                //window.document.body.onload = function() {
                //	window.execLoad();
                //};
            };
        /* ]]> */
        </script>';
    }

    /**
     * Header Attribute setzen
     *
     * @param String $attribute
     * @param Bool $value
     */
    public function setHeaderAttribute($attribute, $value)
    {
        $this->_header_attributes[ $attribute ] = $value;
    }

    /**
     * Gibt ein Header Attribute zurück
     *
     * @param unknown_type $attribute
     *
     * @return Bool
     */
    public function getHeaderAttribute($attribute)
    {
        if ( isset( $this->_header_attributes[ $attribute ] ) ) {
            return $this->_header_attributes[ $attribute ];
        }

        return false;
    }
}
