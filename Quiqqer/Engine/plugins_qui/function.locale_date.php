<?php

/**
 * Global date format
 *
 * @param Array  $params
 * @param Smarty $Smarty
 *
 * @return String
 */
function smarty_function_locale_date($params, $Smarty)
{
    if (!isset($params['timestamp']) && !isset($params['date'])) {
        return '';
    }

    $timestamp = false;

    if (isset($params['date'])) {
        $timestamp = strtotime($params['date']);
    }

    if (isset($params['timestamp'])) {
        $timestamp = (int)$params['timestamp'];
    }

    if (!$timestamp) {
        return '';
    }


    $format = false;

    if (isset($params['format'])) {
        $format = $params['format'];
    }

    $result = QUI::getLocale()->formatDate($timestamp, $format);

    if (!isset($params['assign'])) {
        return $result;
    }

    $Smarty->assign($params['assign'], $result);
    return '';
}
