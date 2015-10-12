<?php

/**
 * Übersetzung in Smarty
 *
 * @param array $params
 * @param Smarty $Smarty
 * @return string
 */
function smarty_function_locale($params, $Smarty)
{
    if (!isset($params['group'])) {
        return '';
    }

    if (!isset($params['value']) && !isset($params['var'])) {
        return '';
    }

    $value = false;
    $group = $params['group'];

    if (isset($params['value'])) {
        $value = $params['value'];
    }

    if (isset($params['var'])) {
        $value = $params['var'];
    }

    unset($params['group']);
    unset($params['value']);
    unset($params['var']);

    $result = \QUI::getLocale()->get($group, $value, $params);

    if (!isset($params['assign'])) {
        return $result;
    }

    $Smarty->assign($params['assign'], $result);
    return '';
}
