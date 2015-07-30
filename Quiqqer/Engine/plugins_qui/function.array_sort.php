<?php
/**
 * Smarty {array_sort var=$array assign= sort_flags=} function plugin
 *
 * File:     function.array_unset.php<br>
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  unset an array key
 *
 * @author    www.pcsg.de (Henning Leutz)
 *
 * @param array  $params - parameters
 * @param Smarty $Smarty - Smarty object
 *
 * sort_flags are optional
 */
function smarty_function_array_sort($params, $Smarty)
{
    if (!isset($params['var'])) {
        return;
    }

    if (!isset($params['assign'])) {
        return;
    }

    $sort = SORT_REGULAR;

    if (isset($params['sort_flags'])) {
        switch ($params['sort_flags']) {
            case 'SORT_NUMERIC':
                $sort = SORT_NUMERIC;
                break;

            case 'SORT_STRING':
                $sort = SORT_STRING;
                break;

            case 'SORT_LOCALE_STRING':
                $sort = SORT_LOCALE_STRING;
                break;

            case 'SORT_NATURAL':
                $sort = SORT_NATURAL;
                break;

            case 'SORT_FLAG_CASE':
                $sort = SORT_FLAG_CASE;
                break;
        }
    }

    $array = $params['var'];
    sort($array, $sort);

    $Smarty->assign($params['assign'], $array);
}
