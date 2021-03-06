<?php

/**
 * Smarty {url_linked Site= lang=en} function plugin
 *
 * Type:     function<br>
 * Name:     url_linked<br>
 *
 * @param array $params -> GET params = _get__*, useOwnLangLink
 * @param \Smarty $smarty
 *
 * @return string
 * @author www.pcsg.de (Henning Leutz)
 *
 */
function smarty_function_url_linked($params, $smarty)
{
    if (!isset($params['Site'])) {
        return '';
    }

    if (!isset($params['lang'])) {
        return '';
    }

    $wantedLang       = $params['lang'];
    $useOwnLangLink   = false;
    $ownLangLinkParam = $wantedLang.'-link';

    if (isset($params['useOwnLangLink']) && $params['useOwnLangLink']) {
        $useOwnLangLink = true;
    }


    /* @var $Site QUI\Projects\Site */
    $Site    = $params['Site'];
    $Project = $Site->getProject();

    if ($Project->getLang() == $wantedLang) {
        if ($useOwnLangLink && $Site->getAttribute($ownLangLinkParam)) {
            return $Site->getAttribute($ownLangLinkParam);
        }

        try {
            return $Site->getUrlRewritten();
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::writeException($Exception);
        }
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
        if ($useOwnLangLink && $Site->getAttribute($ownLangLinkParam)) {
            return $Site->getAttribute($ownLangLinkParam);
        }

        $langId   = $Site->getId($params['lang']);
        $LangSide = $LangProject->get($langId);

        $Output = QUI::getRewrite()->getOutput();

        return $Output->getSiteUrl([
            'site' => $LangSide
        ]);
    } catch (QUI\Exception $Exception) {
    }

    try {
        return $LangProject->firstChild()->getUrlRewritten();
    } catch (QUI\Exception $Exception) {
    }

    return '';
}
