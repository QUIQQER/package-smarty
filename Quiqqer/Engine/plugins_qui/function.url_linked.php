<?php

/**
 * Smarty {url_linked Site= lang=en} function plugin
 *
 * Type:     function<br>
 * Name:     url_linked<br>
 *
 * @author www.pcsg.de (Henning Leutz)
 *
 * @param array $params -> GET params = _get__*
 * @param \Smarty $smarty
 *
 * @return string
 */
function smarty_function_url_linked($params, $smarty)
{
    if (!isset($params['Site'])) {
        return '';
    }

    if (!isset($params['lang'])) {
        return '';
    }

    $wantedLang = $params['lang'];

    /* @var $Site QUI\Projects\Site */
    $Site    = $params['Site'];
    $Project = $Site->getProject();

    if ($Project->getLang() == $wantedLang) {
        return $Site->getUrlRewritten();
    }

    try {
        $LangProject = QUI::getProjectManager()->getProject(
            $Project->getName(),
            $wantedLang
        );

    } catch (QUI\Exception $Exception) {
        return '';
    }


    try {

        $langId   = $Site->getId($params['lang']);
        $LangSide = $LangProject->get($langId);

        return $LangSide->getUrlRewritten();

    } catch (QUI\Exception $Exception) {

    }

    try {
        return $LangProject->firstChild()->getUrlRewritten();

    } catch (QUI\Exception $Exception) {

    }

    return '';
}
