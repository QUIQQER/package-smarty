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
 * @author  www.pcsg.de (Henning Leutz)
 * @package com.pcsg.quiqqer.package.smarty3
 */
class Smarty3 implements QUI\Interfaces\Template\EngineInterface
{
    /**
     * @var array
     */
    public static $fileCache = null;

    /**
     * @var null|\Smarty
     */
    protected $Smarty = null;

    /**
     * construct
     *
     * @param boolean $admin
     */
    public function __construct($admin = false)
    {
        // Templates
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/templates');
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/compile');
        QUI\Utils\System\File::mkdir(VAR_DIR . 'cache/cache');

        if (!class_exists('\Smarty_Autoloader')) {
            require OPT_DIR . 'smarty/smarty/libs/bootstrap.php';
        }

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
     *
     * @see QUI\Interfaces\Template::getTemplateVariable()
     *
     * @param string $var
     * @return mixed
     */
    public function getTemplateVariable($var)
    {
        return $this->Smarty->getTemplateVars($var);
    }

    /**
     * (non-PHPdoc)
     *
     * @see QUI\Interfaces\Template::assign()
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
     * @param  String $resource_name
     * @return String
     */
    public function fetch($resource_name)
    {
        $error = QUI::getErrorHandler()->getAttribute('ERROR_2');

        // Error Behandlung bei Smarty ausschalten, zuviele fehler
        QUI::getErrorHandler()->setAttribute('ERROR_2', false);

        // exist a usr template?
        // template ist überschreibbar im usr template
        $Project           = QUI::getRewrite()->getProject();
        $projectName       = $Project->getName();
        $usr_resource_name = false;
        $tpl_resource_name = false;
        $template          = $Project->getAttribute('template');

        if (is_null(self::$fileCache)) {
            try {
                self::$fileCache = QUI\Cache\Manager::get('smarty/engine/fetch');
            } catch (QUI\Exception $Exception) {
                self::$fileCache = array();
            }
        }

        if (isset(self::$fileCache[$resource_name])) {
            $usr_resource_name = self::$fileCache[$resource_name];
        } elseif (strpos($resource_name, OPT_DIR) !== false) {
            $usr_resource_name = str_replace(
                OPT_DIR,
                USR_DIR . $projectName . '/lib/',
                $resource_name
            );

            $tpl_resource_name = str_replace(
                OPT_DIR,
                OPT_DIR . $template . '/',
                $resource_name
            );

            if (file_exists($usr_resource_name)) {
                self::$fileCache[$resource_name] = $usr_resource_name;
                $tpl_resource_name               = false;
            } elseif (file_exists($tpl_resource_name)) {
                self::$fileCache[$resource_name] = $tpl_resource_name;
                $usr_resource_name               = false;
            } else {
                self::$fileCache[$resource_name] = $resource_name;
                $usr_resource_name               = false;
                $tpl_resource_name               = false;
            }

            QUI\Cache\Manager::set('smarty/engine/fetch', self::$fileCache);
        } elseif (strpos($resource_name, LIB_DIR) !== false) {
            $usr_resource_name = str_replace(
                LIB_DIR,
                USR_DIR . $projectName . '/lib/',
                $resource_name
            );

            $tpl_resource_name = str_replace(
                LIB_DIR,
                OPT_DIR . $template . '/',
                $resource_name
            );

            if (file_exists($usr_resource_name)) {
                self::$fileCache[$resource_name] = $usr_resource_name;
                $tpl_resource_name               = false;
            } elseif (file_exists($tpl_resource_name)) {
                self::$fileCache[$resource_name] = $tpl_resource_name;
                $usr_resource_name               = false;
            } else {
                self::$fileCache[$resource_name] = $resource_name;
                $usr_resource_name               = false;
                $tpl_resource_name               = false;
            }

            QUI\Cache\Manager::set('smarty/engine/fetch', self::$fileCache);
        }

        if ($usr_resource_name) {
            $resource_name = $usr_resource_name;
        }

        if ($tpl_resource_name) {
            $resource_name = $tpl_resource_name;
        }

        QUI\System\Log::addDebug('Engine Template -> ' . $resource_name);
        $tpl = $this->Smarty->fetch($resource_name);

        // Errors wieder einschalten, falls es aus war
        QUI::getErrorHandler()->setAttribute('ERROR_2', $error);

        return $tpl;
    }
}
