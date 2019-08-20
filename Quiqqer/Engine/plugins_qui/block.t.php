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
 * @param array $params
 * @param string $content - contents of the block
 * @param Smarty $Smarty
 *
 * @return string string $content cache
 * @author PCSG
 *
 */
function smarty_block_t($params, $content, $Smarty)
{
    if ($content === null) {
        return '';
    }

    if (!\is_array($params)) {
        $params = [];
    }

    $_param = \explode(' ', $content);

    if (\strpos($_param[0], '/') !== false && \strpos($_param[1], ' ') === false) {
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

    return '';
}
