<?php

/**
 * Smarty {object} function plugin
 *
 * File:     function.object.php<br>
 * Type:     function<br>
 * Name:     object<br>
 * Purpose:  creates a object
 *
 * @param array $params parameters
 * @param Smarty $smarty Smarty object
 *
 * @return string
 *
 * @example {object class="\My\Namspace" assign=Obj}
 */
function smarty_function_object($params, $Smarty)
{
    if (!isset($params['class'])) {
        return '';
    }

    $assign = isset($params['assign']) ? $params['assign'] : false;
    $class  = $params['class'];

    unset($params['class']);
    unset($params['assign']);

    if (!\count($params)) {
        $Object = new $class;
    } else {
        try {
            $Reflection = new \ReflectionClass($class);
            $Object     = $Reflection->newInstanceArgs($params);
        } catch (\Exception $Exception) {
            \QUI\System\Log::writeException($Exception);

            return '';
        }
    }

    if (!$assign) {
        return $Object;
    }

    $Smarty->assign($assign, $Object);

    return '';
}
