<?php

namespace Quiqqer\Engine\plugins_qui;

use QUI;

/**
 * Class QuiqqerTemplateFetch
 *
 * Responsible for fetching template files via Smarty.
 * This overwrites the default Smarty "fetch" method!
 */
class QuiqqerTemplateFetch
{
    public static function fetch($params, $Smarty)
    {
        if (empty($params['template'])) {
            return '';
        }

        $file = $params['template'];

        if (!empty($params['Template']) && $params['Template'] instanceof \QUI\Template) {
            $templatePath = $params['Template']->getTemplatePath();
        } else {
            $Project      = QUI::getRewrite()->getProject();
            $templatePath = OPT_DIR.$Project->getAttribute('template').'/';
        }

        if (!\file_exists($templatePath)) {
            QUI\System\Log::addError('Template path "'.$templatePath.'" not found.');
            return '';
        }

        $file = $templatePath.$file;

        if (!\file_exists($file)) {
            QUI\System\Log::addError('Template file "'.$file.'" not found.');
            return '';
        }

        $Engine = QUI::getTemplateManager()->getEngine();
        $Engine->assign($params);

        return $Engine->fetch($file);
    }
}
