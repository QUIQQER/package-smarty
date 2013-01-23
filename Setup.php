<?php

/**
 * This file contains QUI_Template
 */

namespace Quiqqer\Package\Smarty3;

class Install
{
    /**
     * Install the template Engine and register smarty3 to Quiqqer
     */
    static function install()
    {
        $path = pathinfo( __FILE__ );
        $dir  = $path[ 'dirname' ];

        \QUI_Template::register(
        	'smarty3',
            '\\Quiqqer\\Package\\Smarty3\\Engine'
        );
    }
}

?>