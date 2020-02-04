<?php

namespace Quiqqer\Engine\plugins_qui;

use QUI;

/**
 * Class QuiqqerTemplateFetch
 *
 * Responsible for fetching template files via Smarty.
 * This overwrites the default Smarty "fetch" method!
 *
 * @todo globale Smarty get Filename einführen
 * @todo getFilename dann nutzen
 */
class QuiqqerTemplateFetch
{
    /**
     * @param array $params
     * @param $Smarty
     *
     * @return string
     *
     * @throws QUI\Exception
     */
    public static function fetch($params, $Smarty)
    {
        if (empty($params['template'])) {
            return '';
        }

        $file    = $params['template'];
        $Project = QUI::getRewrite()->getProject();

        if (!empty($params['Template'])
            && $params['Template'] instanceof QUI\Template
            && \method_exists($params['Template'], 'getTemplatePath')
        ) {
            $templatePath = $params['Template']->getTemplatePath();
        } else {
            $templatePath = OPT_DIR.$Project->getAttribute('template').'/';
        }

        if (!\file_exists($templatePath)) {
            QUI\System\Log::addError('Template path "'.$templatePath.'" not found.');

            return '';
        }

        $file = $templatePath.$file;

        if (!\file_exists($file)) {
            try {
                // consider template parent if not exist
                $file = \str_replace(OPT_DIR, '', $file);
                $file = \explode(DIRECTORY_SEPARATOR, $file);

                $package = $file[0].DIRECTORY_SEPARATOR.$file[1];
                $Package = QUI::getPackage($package);

                unset($file[0]);
                unset($file[1]);

                if (\method_exists($Package, 'getTemplateParent')) {
                    $TemplateParent = $Package->getTemplateParent();

                    $file = OPT_DIR.
                            $TemplateParent->getName().
                            DIRECTORY_SEPARATOR.
                            \implode(DIRECTORY_SEPARATOR, $file);

                    if (!file_exists($file)) {
                        QUI\System\Log::addError('Template file "'.$file.'" not found.');

                        return '';
                    }
                }
            } catch (\Exception $Exception) {
                QUI\System\Log::addError('Template file "'.$file.'" not found.');
                QUI\System\Log::addError($Exception->getMessage());

                return '';
            }
        }


        $Engine = QUI::getTemplateManager()->getEngine();
        $Engine->assign($params);

        return $Engine->fetch($file);
    }
}
