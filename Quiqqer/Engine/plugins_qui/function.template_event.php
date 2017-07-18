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

    $Collector = new \QUI\Smarty\Collector();

    QUI::getEvents()->fireEvent($params['name'], array($Collector));

    if (!isset($params['assign'])) {
        return $Collector->getContent();
    }

    $Smarty->assign($params['assign'], $Collector->getContent());

    return '';
}
