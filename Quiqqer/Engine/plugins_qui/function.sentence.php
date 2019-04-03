<?php
/**
 * Smarty Plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author     PCSG - Henning
 */


/**
 * Smarty {sentence} function plugin
 *
 * Type:     function<br>
 * Name:     sentence<br>
 *
 * @param array parameters
 * @param Smarty
 *
 * @return string
 * @author PCSG
 *
 */
function smarty_function_sentence($params, $smarty)
{
    if (!isset($params['text'])) {
        return '';
    }

    $text = $params['text'];

    if (\strpos($text, '.') === false
        && \strpos($text, '!') === false
        && \strpos($text, '?') === false
    ) {
        return '';
    }

    $text = \preg_replace('/(.*?[^\.|\!|\?][\.|\!|\?])(.*?)$/', '$1', $text);

    if (!isset($params['assign'])) {
        return $text;
    }

    $smarty->assign($params['assign'], $text);
}
