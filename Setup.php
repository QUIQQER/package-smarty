<?php

/**
 * This file contains QUI_Template
 */

namespace \Quiqqer\Package\Smarty3;

use Composer\Script\Event;


class Setup
{
    /**
     * Install the template Engine and register smarty3 to Quiqqer
     */
    static function install(Event $event)
    {
        $path = pathinfo( __FILE__ );
        $dir  = $path[ 'dirname' ];

        $IO = $event->getIO();
        $IO->write( 'Add smarty to template engines' );

        echo 11111111111111111111;

        /*
        \QUI_Template::register(
        	'smarty3',
            '\\Quiqqer\\Package\\Smarty3\\Engine'
        );
        */
    }
}

?>