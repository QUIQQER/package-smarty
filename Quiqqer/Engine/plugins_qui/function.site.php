<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {site id=site_id var=MySite} function plugin
 *
 * Type:     function<br>
 * Name:     site<br>
 * @author Henning Leutz <henbug @ pcsg . de>
 * @param array params
 * @param Smarty
 * @return Site|false
 */
function smarty_function_site($params, $smarty)
{
    if (!isset($params['id']) || empty($params['id'])) {
        $smarty->assign($params['var'], false);
    }

    $Project = \QUI\Projects\Manager::get();

    try
    {
        $Site = $Project->get( (int)$params['id'] );
    } catch(QException $e)
    {
        $Site = false;
    }

    if (!isset($params['var']) && isset($params['attribute']))
    {
        if (!$Site) {
            return '';
        }

        return $Site->getAttribute($params['attribute']);
    }

    $smarty->assign($params['var'], $Site);
}

?>
