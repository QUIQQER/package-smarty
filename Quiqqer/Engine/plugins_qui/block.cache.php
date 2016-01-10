<?php
/**
 * Smarty plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {cache name="_name_"}{/cache} block plugin
 *
 * Type:     block function<br>
 * Name:     cache<br>
 * Purpose:  Cached den darin enthaltenen String in eine Datei<br>
 *            Falls es die Datei gibt wird der Inhalt der Datei verwendet anstatt DB Abfragen zu machen
 *
 * @param        array
 *                        <pre>
 *                        Params:   name: string (Name des Caches, Cachedatei in welcher der String gelagert wird)
 *                        </pre>
 *
 * @author PCSG - Henning
 *
 * @param array $params
 * @param string $content - of the block
 * @param Smarty $Smarty
 *
 * @return string string $content cache
 */
function smarty_block_cache($params, $content, $Smarty)
{
    $Project = \QUI\Projects\Manager::get();

    if ($content === null) {
        return '';
    }

    if (!isset($params['name'])) {
        return '';
    }

    $cache_dir = VAR_DIR . 'cache/templates/';

    // Falls es das Verzeichnis nicht gibt dann erstellen
    if (!is_dir($cache_dir)) {
        \QUI\Utils\System\File::mkdir($cache_dir);
    }

    $cache_file = $cache_dir . $params['name'] . '_' . $Project->getAttribute('name')
                  . '_' . $Project->getAttribute('lang');

    if (file_exists($cache_file)) {
        $_output = file_get_contents($cache_file);

    } else {
        file_put_contents($cache_file, $content);
        $_output = $content;
    }

    if (!isset($assign)) {
        return $_output;
    }

    $Smarty->assign($assign, $_output);

    return '';
}
