<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {description text=text} function plugin
 *
 * Type:     function<br>
 * Name:     title<br>
 * @author Henning Leutz <henbug @ pcsg . de>
 * @param array $params
 * @param Smarty
 * @return String
 */
function smarty_function_description($params, &$smarty)
{
    $str = '<span class="description">';

    if (isset($params['text'])) {
        $str .= $params['text'];
    }

    $str .= '</span>';

    return $str;
}
