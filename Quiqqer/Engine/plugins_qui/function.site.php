<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty {site id=site_id var=MySite} function plugin
 *
 * Type:     function<br>
 * Name:     site<br>
 *
 * @author Henning Leutz <henbug @ pcsg . de>
 *
 * @param array $params
 * @param Smarty $smarty
 *
 * @return QUI\Projects\Site|false|void
 */
function smarty_function_site($params, $smarty)
{
    if (!isset($params['id']) || empty($params['id'])) {
        $smarty->assign($params['var'], false);
    }

    if (isset($params['project']) && isset($params['lang'])) {
        $Project = QUI::getProjectManager()->getProject(
            $params['project'],
            $params['lang']
        );

    } elseif (isset($params['project'])) {
        $Project = QUI::getProjectManager()->getProject(
            $params['project']
        );

    } else {
        $Project = \QUI::getRewrite()->getProject();
    }


    try {
        $Site = $Project->get((int)$params['id']);

    } catch (\QUI\Exception $Exception) {
        $Site = false;
    }

    if (isset($params['attribute'])) {
        if (!$Site) {
            $Site = '';

        } else {
            $Site = $Site->getAttribute($params['attribute']);
        }
    }

    if (!isset($params['var'])) {
        return $Site;
    }

    $smarty->assign($params['var'], $Site);
}
