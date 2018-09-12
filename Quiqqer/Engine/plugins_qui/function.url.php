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
 * @param array $params -> GET params = _get__*
 * @param \Smarty $Smarty
 *
 * @return string
 */
function smarty_function_url($params, $Smarty)
{
    $url  = '';
    $Site = false;

    if (isset($params['url'])
        && !empty($params['url'])
        && QUI\Projects\Site\Utils::isSiteLink($params['url'])
    ) {
        try {
            $params['site'] = QUI\Projects\Site\Utils::getSiteByLink($params['url']);
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::addNotice($Exception->getMessage(), [
                'function' => 'smarty_function_url',
                'params'   => $params
            ]);
        }

        unset($params['url']);
    }

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
        QUI\System\Log::addInfo($Exception->getMessage(), [
            'function' => 'smarty_function_url',
            'params'   => $params
        ]);

        return '';
    }

    if (isset($params['site'])) {
        $Site = $params['site'];
    } elseif (isset($params['id'])) {
        try {
            $Site = $_Project->get((int)$params['id']);
        } catch (QUI\Exception $Exception) {
            QUI\System\Log::addInfo($Exception->getMessage(), [
                'function' => 'smarty_function_url',
                'params'   => $params
            ]);

            return '';
        }
    }

    unset($params['site']);
    unset($params['id']);
    unset($params['lang']);


    // get params
    $getParams = [];

    foreach ($params as $key => $value) {
        if (strpos($key, '_get__') === false) {
            continue;
        }

        unset($params[$key]);

        if (empty($value)) {
            continue;
        }

        $key             = str_replace('_get__', '', $key);
        $getParams[$key] = $value;
    }


    // path params
    if (isset($params['params'])) {
        $_params = $params['params'];
        unset($params['params']);

        if (!is_array($_params)) {
            $_params = [$_params];
        }

        $params = array_merge($params, $_params);
    }

    $assign = false;

    if ($Site && $Site->getId()) {
        if (isset($params['rewrited']) && $params['rewrited']) {
            $params['rewritten'] = $params['rewrited'];
            unset($params['rewrited']);
        }

        if (isset($params['rewritten']) && $params['rewritten']) {
            unset($params['rewritten']);

            if (empty($params['host'])) {
                $url = $Site->getUrlRewritten($params, $getParams);
            } else {
                unset($params['host']);
                $url = $Site->getUrlRewrittenWithHost($params, $getParams);
            }
        } else {
            $url = $Site->getUrl($params, $getParams);
        }
    }

    if (!$assign) {
        return $url;
    }

    $Smarty->assign($assign, $url);

    return '';
}
