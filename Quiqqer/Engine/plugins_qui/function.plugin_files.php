<?php
/**
 * Smarty plugin
 *
 * @package    com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {plugin_files} function plugin
 * Fügt Pluginfiles in den Header
 *
 *
 * Type:     function<br>
 * Name:     plugin_files<br>
 *
 * @author PCSG
 *
 * @param array parameters
 * @param Smarty
 *
 * @return string|null
 * @deprecated
 */
function smarty_function_plugin_files($params, $Smarty)
{
    $Rewrite = \QUI::getRewrite();
    $Project = $Rewrite->getProject();
    $Site    = $Rewrite->getSite();

    $Plugins = \QUI::getPlugins();
    $Plugin  = $Plugins->getPluginByType($Site->getAttribute('type'));

    $files = $Plugin->getHeaderFiles($Project);
    $str   = $Plugin->getTemplateHeader($Project);

    foreach ($files['css'] as $css) {
        $str .= "\n" . '<link rel="StyleSheet" type="text/css" href="' . $css
                . '" media="screen" />';
    }

    $str
        .= '<script type="text/javascript">
    /* <![CDATA[ */
        _pcsg.MVC.require("plugin/' . $Plugin->getAttribute('name') . '", function() {});
    /* ]]> */
    </script>'; // Plugin gleich ausführen


    return $str;


    // abwärtskompatibilität ?

    exit;

    // Project Files
    $css = 'bin/' . $Project->getAttribute('template') . '/'
           . $Site->getAttribute('type') . '/style.css';
    $js  = 'bin/' . $Project->getAttribute('template') . '/'
           . $Site->getAttribute('type') . '/style.css';

    if (file_exists(USR_DIR . $style)) {
        $css_files[] = URL_USR_DIR . $style;
    }


    $style_opt = OPT_DIR . $type_explode[0] . '/bin/style.css';

    $style     = \QUI\Utils\StringHelper::replaceDblSlashes($style);
    $style_opt = \QUI\Utils\StringHelper::replaceDblSlashes($style_opt);

    if (file_exists($style)) {
        $css = $url_dir_usr . 'bin/' . $Project->getAttribute('template') . '/'
               . $Site->getAttribute('type') . '/style.css';

        $include .= '<link rel="StyleSheet" type="text/css" href="' . $css
                    . '" media="screen" />' . "\n";
    } elseif (file_exists($style_opt)) {
        $css = URL_DIR . 'opt/' . $type_explode[0] . '/bin/style.css';
        $include .= '<link rel="StyleSheet" type="text/css" href="' . $css
                    . '" media="screen" />' . "\n";
    }

    // JS
    $script     = USR_DIR . 'bin/' . $Project->getAttribute('template') . '/'
                  . $Site->getAttribute('type') . '/script.js';
    $script_opt = OPT_DIR . $type_explode[0] . '/bin/script.js';

    $script     = \QUI\Utils\StringHelper::replaceDblSlashes($script);
    $script_opt = \QUI\Utils\StringHelper::replaceDblSlashes($script_opt);

    return $include;
}
