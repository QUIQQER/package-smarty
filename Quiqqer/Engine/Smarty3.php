<?php

/**
 * This file contains Package\Smarty3\Engine
 */

namespace Quiqqer\Engine;

use QUI;

/**
 * Smarty3 Interface for QUIQQER
 * provides the Smarty3 engine for QUIQQER
 *
 * @author www.pcsg.de (Henning Leutz)
 * @package com.pcsg.quiqqer.package.smarty3
 *
 * @todo translate comments
 */
class Smarty3 implements QUI\Interfaces\Template\Engine
{
    /**
     * @var null|\Smarty
     */
    protected $Smarty = null;

    /**
     * Konstruktor
     *
     * @param Boolean $admin
     */
    public function __construct($admin = false)
    {
        // Templates
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/templates');
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/compile');
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/cache');

        $Smarty = new \Smarty();
        $Smarty->setTemplateDir(VAR_DIR . 'cache/templates');
        $Smarty->setCompileDir(VAR_DIR . 'cache/compile');
        $Smarty->setCacheDir(VAR_DIR . 'cache/cache');

        $Smarty->compile_check = false;

        $Package = QUI::getPluginManager()->get('quiqqer/smarty');

        if ($Package->getSettings('settings', 'compilecheck')) {
            $Smarty->compile_check = true;
        }

        $DIR = dirname(__FILE__);

        $plugin_dir = $Smarty->getPluginsDir();

        if ($admin == true) {
            $plugin_dir[] = $DIR . '/plugins_qui/';
            $plugin_dir[] = $DIR . '/plugins_qui_admin/';

        } else {
            $plugin_dir[] = $DIR . '/plugins_qui/';
        }

        $Smarty->setPluginsDir($plugin_dir);

        try {
            QUI::getEvents()->fireEvent('smartyInit', array($Smarty));

        } catch (QUI\ExceptionStack $Exception) {
            $list = $Exception->getExceptionList();

            /* @var $Exc QUI\Exception */
            foreach ($list as $Exc) {
                QUI\System\Log::addDebug($Exc->getMessage());
            }

        } catch (QUI\Exception $Exception) {
            QUI\System\Log::addDebug($Exception->getMessage());
        }

        $this->Smarty = $Smarty;
    }

    /**
     * (non-PHPdoc)
     * @see Interface_Template_Engine::assign()
     *
     * @param String|array $var
     * @param Boolean $value
     */
    public function assign($var, $value = false)
    {
        if (is_string($var)) {
            $this->Smarty->assign($var, $value);
            return;
        }

        $this->Smarty->assign($var);
    }

    /**
     * executes & returns or displays the template results
     *
     * @param String $resource_name
     * @return String
     */
    public function fetch($resource_name)
    {
        $error = \QUI::getErrorHandler()->getAttribute('ERROR_2');

        // Error Behandlung bei Smarty ausschalten, zuviele fehler
        QUI::getErrorHandler()->setAttribute('ERROR_2', false);

        $tpl = $this->Smarty->fetch($resource_name);

        // Errors wieder einschalten, falls es aus war
        QUI::getErrorHandler()->setAttribute('ERROR_2', $error);

        return $tpl;
    }
}
