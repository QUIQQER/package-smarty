<?php

/**
 * Smarty {template_event name=""} function plugin
 *
 * Type:     function<br>
 * Name:     templateEvent<br>
 *
 * @author PCSG
 *
 * @param array $params
 * @param Smarty $Smarty
 *
 * @return string|null
 */
function smarty_function_template_event($params, $Smarty)
{
    if (!isset($params['name'])) {
        return '';
    }

    $Collector = new Quiqqer\Engine\Collector();

    $name   = $params['name'];
    $assign = false;

    if (isset($params['assign'])) {
        $assign = $params['assign'];
    }

    unset($params['name']);
    unset($params['assign']);

    array_unshift($params, $Collector);

    try {
        QUI::getEvents()->fireEvent($name, $params);
    } catch (QUI\Exception $Exception) {
        QUI\System\Log::writeDebugException($Exception);
    }

    if (!$assign) {
        return $Collector->getContent();
    }

    $Smarty->assign($assign, $Collector->getContent());

    return '';
}
