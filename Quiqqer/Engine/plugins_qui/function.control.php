<?php

/**
 * Smarty {control} function plugin
 *
 * File:     function.control.php<br>
 * Type:     function<br>
 * Name:     array<br>
 * Purpose:  creates a control
 *
 * @param array  $params parameters
 * @param Smarty $Smarty Smarty object
 *
 * @return string
 */

function smarty_function_control($params, $Smarty)
{
    if (!isset($params['control'])) {
        return '';
    }

    try {
        $Control = new $params['control']();

    } catch (\QUI\Exception $Exception) {
        \QUI\System\Log::writeException($Exception);

        return '';
    }

    $assign = isset($params['assign']) ? $params['assign'] : false;

    unset($params['control']);
    unset($params['assign']);

    /* @var $Control QUI\Control */
    $Control->setAttributes($params);

    if (!$Control->getAttribute('Site')) {
        $Control->setAttribute('Site', $Smarty->getTemplateVars('Site'));
        $Control->setAttribute('Project', $Smarty->getTemplateVars('Project'));
    }


    if (!$assign) {
        if (method_exists($Control, 'create')) {
            try {
                return $Control->create();

            } catch (\QUI\Exception $Exception) {
                \QUI\System\Log::writeException($Exception);
            }
        }

        return '';
    }

    $Smarty->assign($assign, $Control);
    return '';
}
