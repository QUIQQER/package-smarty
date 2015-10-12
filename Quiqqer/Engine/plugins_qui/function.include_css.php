<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty {include_css file.0="" file.1="" cache="" urlcache=""} function plugin
 *
 * Type:     function<br>
 * Name:     include_css<br>
 *
 * @author Henning Leutz <leutz @ pcsg . de>
 *
 * @param array params
 * @param Smarty
 *
 * @return User|false
 */
function smarty_function_include_css($params, &$smarty)
{
    if (!isset($params['cache']) || !isset($params['urlcache'])) {
        return '';
    }

    if (file_exists($params['cache'])) {
        return '<link rel="StyleSheet"  type="text/css" href="'
        .$params['urlcache'].'" media="screen" />';
    }

    $css = '';
    $i = 0;

    while ($params['file'.$i]) {
        if (file_exists($params['file'.$i])) {
            $c = file_get_contents($params['file'.$i]);
            $css .= str_replace(array("\r\n", "\n", "\r"), ' ', $c);
        }

        $i++;
    }

    file_put_contents($params['cache'], $css);

    return '<link rel="StyleSheet"  type="text/css" href="'.$params['urlcache']
    .'" media="screen" />';
}
