<?php

/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty {user id=user_id} function plugin
 *
 * Type:     function<br>
 * Name:     user<br>
 *
 * @author Henning Leutz <henbug @ pcsg . de>
 *
 * @param array params
 * @param Smarty
 *
 * @return User|false
 */
function smarty_function_user($params, $Smarty)
{
    if (!isset($params['id']) || empty($params['id'])) {
        $Smarty->assign($params['var'], \QUI::getUsers()->getNobody());

        return;
    }

    try {
        $User = \QUI::getUsers()->get((int)$params['id']);

    } catch (\QUI\Exception $Exception) {
        $User = \QUI::getUsers()->getNobody();
    }

    $Smarty->assign($params['var'], $User);
}
