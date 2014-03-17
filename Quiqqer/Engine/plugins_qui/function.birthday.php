<?php
/**
 * Smarty Plugin
 *
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author PCSG - Henning
 */


/**
 * Smarty {birthday} function plugin
 *
 * Type:     function<br>
 * Name:     image<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 */
function smarty_function_birthday($params, &$smarty)
{
    $Locale = QUI::getLocale();

    // Tag
    $str = '<select ';

    if (isset($params['name'])) {
        $str .= ' name="'. $params['name'] .'"';
    }

    if (isset($params['style'])) {
        $str .= ' style="'. $params['style'] .'"';
    }

    $str .= '>';

    switch ($params['type'])
    {
        case 'day':
            $str .= '<option value="">'. $Locale->get('system', 'day') .'</option>';

            for ($i = 1; $i < 32; $i++)
            {
                $str .= '<option value="'. sprintf("%02d", $i) .'" ';

                if (isset($params['value']) && $params['value'] == $i) {
                    $str .= ' selected="selected"';
                }

                $str .= '>'. sprintf("%02d", $i) .'</option>';
            }
        break;

        case 'month':
            $mlist = array(
                ''   => $Locale->get('system', 'month'),
                '01' => $Locale->get('system', 'month.01'),
                '02' => $Locale->get('system', 'month.02'),
                '03' => $Locale->get('system', 'month.03'),
                '04' => $Locale->get('system', 'month.04'),
                '05' => $Locale->get('system', 'month.05'),
                '06' => $Locale->get('system', 'month.06'),
                '07' => $Locale->get('system', 'month.07'),
                '08' => $Locale->get('system', 'month.08'),
                '09' => $Locale->get('system', 'month.09'),
                '10' => $Locale->get('system', 'month.10'),
                '11' => $Locale->get('system', 'month.11'),
                '12' => $Locale->get('system', 'month.12')
            );

            foreach ($mlist as $value => $month)
            {
                $str .= '<option value="'. $value .'"';

                if (isset($params['value']) && $params['value'] == $value) {
                    $str .= ' selected="selected"';
                }

                $str .= '>'. $month .'</option>';
            }
        break;

        case 'year':
            $str .= '<option value="">'. $Locale->get('system', 'year') .'</option>';

            for ($i = 1920, $len = date('Y')-1; $len >= $i; $len--)
            {
                $str .= '<option value="'. $len .'"';

                if (isset($params['value']) && $params['value'] == $len) {
                    $str .= ' selected="selected"';
                }

                $str .= '>'. $len .'</option>';
            }
        break;
    }


    $str .= '</select>';

    return $str;
}
