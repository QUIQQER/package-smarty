<?php

/**
 * Smarty plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {t}Text{/t} block plugin
 *
 * Type:     block function<br>
 * Name:     Translate<br>
 * Purpose:  Translation eines Textest im Template
 *
 * @author PCSG
 *
 * @param array $params
 * @param string $content - contents of the block
 * @param Smarty $Smarty
 *
 * @return string string $content cache
 */
function smarty_block_t($params, $content, $Smarty)
{
    if ($content === null) {
        return '';
    }

    if (!is_array($params)) {
        $params = array();
    }

    $_param = explode(' ', $content);

    if (strpos($_param[0], '/') !== false
        && strpos($_param[1], ' ') === false
    ) {
        $result = \QUI::getLocale()->get($_param[0], $_param[1], $params);

        if (empty($result)) {
            return $content;
        }

        return $result;
    }

    global $Site;

    if (!isset($Site)) {
        return $content;
    }

    $Project = \QUI\Projects\Manager::get();
    $Plugins = \QUI::getPlugins();

    $Plugin = $Plugins->getPluginByType(
        $Site->getAttribute('type')
    );

    $key    = 'plugin/' . $Plugin->getAttribute('name');
    $result = \QUI::getLocale()->get($key, $content, $params);

    if (empty($result)) {
        return $content;
    }

    return $result;
}
