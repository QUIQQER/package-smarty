<?php
/**
 * Smarty plugin
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 * @author PCSG - Henning
 */

/**
 * Smarty {minimize}{/minimize} block plugin
 *
 * Type:     block function<br>
 * Name:     minimize<br>
 * Purpose:  Verkleinert Request, fasst JavaScript und CSS zusammen
 *
 * @param string contents of the block
 * @param Smarty
 *
 * @return String $content
 */
function smarty_block_minimize($params, $content, $Smarty)
{
    if (isset($params['dev'])) {
        return $content;
    }

	if (is_null($content)) {
        return;
    }

    $Cache = new Cache(BIN_CACHE_DIR);
    return $Cache->packJS($content, URL_BIN_CACHE_DIR);
}

?>