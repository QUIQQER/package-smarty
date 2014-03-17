<?php
/**
 * Smarty plugin
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 */

/**
 * Smarty {ajax} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 * @return string|null
 * @deprecated
 */
function smarty_function_ajax($params, $Smarty)
{
    $Rewrite = QUI::getRewrite(); /* @var $Rewrite Rewrite */
    $Project = $Rewrite->getProject();
    $Site    = $Rewrite->getSite();

    $js = array(
        URL_BIN_DIR .'js/ptools/locale/locale.js',
        URL_BIN_DIR .'js/ptools/system/system.js',
        URL_BIN_DIR .'js/ptools/ajax/ajax.js',
        URL_BIN_DIR .'js/ptools/exception/exception.js',
        URL_BIN_DIR .'js/ptools/info/info.js',
        URL_BIN_DIR .'js/json2.js'
    );

    $css = array(
        URL_BIN_DIR .'js/ptools/info/style.css'
    );

    return '<script type="text/javascript">
    /* <![CDATA[ */
        if (typeof _pcsg == "undefined") {
            var _pcsg = {};
        };

        _pcsg.Project = '. json_encode(array(
            'name' => $Project->getAttribute('name'),
            'lang' => $Project->getAttribute('lang')
        )) .';

        _pcsg.Site = '. json_encode(array(
            'id'   => $Site->getId(),
            'type' => $Site->getAttribute('type')
        )) .';

        _pcsg.MVC.define("ptools/ajax", '.
            (!empty($js) ? json_encode($js) : 'false')
        .', '.
            (!empty($css) ? json_encode($css) : 'false')
        .', function() {
            // Init
            _ptools._System.setAttribute("ajax", "'. URL_DIR .'ajax.php");
            _ptools._System.setAttribute("path", "'. URL_DIR .'bin/js/ptools/");

            _Ajax = new ajax();
        });
    /* ]]> */
    </script>';



    $r  = '<script src="'. URL_BIN_DIR .'js/ptools/system/system.js" type="text/javascript"></script>'."\n";
    $r .= '<script src="'. URL_BIN_DIR .'js/ptools/ajax/ajax.js" type="text/javascript"></script>'."\n";
    $r .= '<script src="'. URL_BIN_DIR .'js/ptools/exception/exception.js" type="text/javascript"></script>'."\n";
    $r .= '<script src="'. URL_BIN_DIR .'js/ptools/info/info.js" type="text/javascript"></script>'."\n";
    $r .= '<script src="'. URL_BIN_DIR .'js/json2.js" type="text/javascript"></script>'."\n";
    $r .= '<link rel="StyleSheet"  type="text/css" href="'. URL_BIN_DIR .'js/ptools/info/style.css" />'."\n";
    $r .= '<script type="text/javascript">/* <![CDATA[ */'."\n";

    // Wichtige Objekte laden
    $r .= '_System = new _ptools.System({'."\n";
        $r .= '	host : "'. URL_DIR .'",'."\n";
        $r .= '	ajax : "'. URL_DIR .'ajax.php",'."\n";
        $r .= '	sTK  : "'. URL_DIR .'bin/js/ptools/"'."\n";
    $r .= '});'."\n";

    $r .= '_ptools._System.setAttribute("ajax", "'. URL_DIR .'ajax.php");';
    $r .= '_ptools._System.setAttribute("path", "'. URL_DIR .'bin/js/ptools/");';

    $r .= '_Ajax = new ajax();'."\n\n";
    $r .= "var _Project = {'name':'". $Project->getAttribute('name') ."','lang':'". $Project->getAttribute('lang') ."'}\n";

    $r .= "
    if (typeof _pcsg == 'undefined') {
        var _pcsg = {};
    };

    _pcsg.Project = {name:'". $Project->getAttribute('name') ."',lang:'". $Project->getAttribute('lang') ."'};
    _pcsg.Site    = {id:". $Site->getId() .", type: '". $Site->getAttribute('type') ."'};
    ";

    $r .= '/* ]]> */</script>'."\n";

    return $r;
}

?>
