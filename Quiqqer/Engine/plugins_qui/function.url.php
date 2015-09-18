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
 * Smarty {url} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 *
 * @author PCSG
 *
 * @param array   $params -> GET params = _get__*
 * @param \Smarty $smarty
 *
 * @return string
 */
function smarty_function_url($params, $smarty)
{
    $url = '';
    $Site = false;

    try {
        if (isset($params['project'])) {

            if (isset($params['lang'])) {
                $_Project = QUI\Projects\Manager::getProject(
                    $params['project'],
                    $params['lang']
                );

            } else {
                $_Project = QUI\Projects\Manager::getProject(
                    $params['project'],
                    QUI::getLocale()->getCurrent()
                );
            }

        } else {
            $_Project = QUI::getRewrite()->getProject();

            if (isset($params['lang'])) {
                $_Project = QUI\Projects\Manager::getProject(
                    $_Project->getAttribute('name'),
                    $params['lang']
                );
            }
        }

    } catch (QUI\Exception $Exception) {

        QUI\System\Log::writeException($Exception);

        return '';
    }

    if (isset($params['site'])) {
        $Site = $params['site'];

    } elseif (isset($params['id'])) {

        try {
            $Site = $_Project->get((int)$params['id']);

        } catch (QUI\Exception $Exception) {

            QUI\System\Log::writeException($Exception);
            QUI\System\Log::writeRecursive($params);

            return '';
        }
    }

    unset($params['site']);
    unset($params['id']);
    unset($params['lang']);


    // get params
    $getParams = array();

    foreach ($params as $key => $value) {

        if (strpos($key, '_get__') === false) {
            continue;
        }

        unset($params[$key]);

        if (empty($value)) {
            continue;
        }

        $key = str_replace('_get__', '', $key);
        $getParams[$key] = $value;
    }


    // path params
    if (isset($params['params'])) {
        $_params = $params['params'];
        unset($params['params']);

        if (!is_array($_params)) {
            $_params = array($_params);
        }

        $params = array_merge($params, $_params);
    }

    $assign = false;
    $host = '';

    if (isset($params['assign'])) {
        $assign = $params['assign'];
        unset($params['assign']);
    }

    if (isset($params['host'])) {
        $host = $Site->getProject()->getVHost(true, true);
        unset($params['host']);
    }


    if ($Site && $Site->getId()) {
        if (isset($params['rewrited']) && $params['rewrited']) {
            unset($params['rewrited']);

            $_siteParams = $params;
            $_siteParams['site'] = $Site;

            $url = QUI::getRewrite()->getUrlFromSite($_siteParams);

            if (!empty($getParams)) {
                $url .= '?'. http_build_query($getParams);
            }

            // $url = URL_DIR . $Site->getUrlRewrited($params, $getParams);
        } else {
            $url = $Site->getUrl($params, $getParams);
        }
    }

    $url = $host.$url;

    if (isset($params['relative'])) {
        $url = explode('/', $url);
        $folder = explode('/', $_SERVER['REQUEST_URI']);
        $last = end($url);

        if (strpos($_SERVER['REQUEST_URI'], $last)) {
            $url = end($url);
        } else {
            $url = str_replace('.html', '', end($folder)).'/'.end($url);
        }
    }

    if (!$assign) {
        return $url;
    }

    $smarty->assign($assign, $url);

    return '';
}
