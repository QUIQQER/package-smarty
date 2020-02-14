<?php
/**
 * Smarty {image} function plugin
 *
 * Type:     function<br>
 * Name:     image<br>
 *
 * @param array $params
 * @param Smarty $smarty
 *
 * @return string
 *
 * @event onSmartyImageBegin [array &$params]
 * @event onSmartyImageEnd [string &$imageString]
 * @event onSmartyImageBeforeSource [string &$src]
 * @author www.pcsg.de (Henning Leutz)
 *
 */
function smarty_function_image($params, $smarty)
{
    // defaults
    if (!isset($params['type'])) {
        $params['type'] = 'resize';
    }

    try {
        QUI::getEvents()->fireEvent('smartyImageBegin', [$smarty, &$params]);
    } catch (QUI\Exception $Exception) {
        QUI\System\Log::writeDebugException($Exception);
    }

    if (!isset($params['image'])) {
        if (!isset($params['src']) || empty($params['src'])) {
            $src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABc0lEQVR4XqWTMUsDQ'.
                   'RCFJyjYSFKkSS9XCGKTCEFQNglip1VSpFG0sLGxSSeeaCEp7K2sbOJPkER7EwvBKmid5porBCMyzpvlhjNoYwJf'.
                   '3sybmb3NhMswM03zmVU52ICEwolQER7+6HfCvXCq/Vd3RLjB134lFJg7FywKHPw08CZ6Qvha/NhZ0wLfnKlKDuw'.
                   'QxL/12AHvzbITmK+PgTYgV98DL6lbDbO6xLhetN+XrdVIP/k8xZ2OhtlGgyiKNI67XUr2lL0d+BtEW0uJOoH58h'.
                   'DgqcByqQGXntGv0WYATWInMJ/v/UA8gOH0jP8bP8djiGJxLkc0Gvm4UDB/sk9PeVstJOoEf+2j7TTqSQ24ZMaWO'.
                   'FzJ2xKDVouo10NOw35fNSiVVKlapWG7bUsMHiN/g5fleSf4J++uK8jV95gvPVazJT4vzvnhZlmRHNjCEAtWRy9y'.
                   'O+ApmAkF5nqRRYGTmNLAm+gJbQeDhcy/XqbiK9PUr/M3T3GnVEK0IY4AAAAASUVORK5CYII=';

            // Get the placeholder if available
            try {
                $CurrentProject = QUI::getRewrite()->getProject();
            } catch (QUI\Exception $Exception) {
                $src = '<img src="'.$src.'" class="quiqqer-empty-image" />';

                return \smarty_plugin_image_assign($params, $src, $smarty);
            }

            $PlaceHolder = $CurrentProject->getMedia()->getPlaceholderImage();

            if ($PlaceHolder) {
                $src = $PlaceHolder->getSizeCacheUrl();
            }

            if (isset($params['onlyicon'])) {
                return \smarty_plugin_image_assign($params, '', $smarty);
            }

            if (isset($params['onlysrc'])) {
                return \smarty_plugin_image_assign($params, $src, $smarty);
            }

            $src = '<img src="'.$src.'" class="quiqqer-empty-image" />';

            return \smarty_plugin_image_assign($params, $src, $smarty);
        }

        // Image Params
        if (\strpos($params['src'], 'image.php') === false) {
            if ((isset($params['onlyicon']) ||
                 !isset($params['onlyicon']) && !isset($params['onlysrc']) ||
                 isset($params['onlysrc']) && !$params['onlysrc'])
                && QUI\Icons\Handler::getInstance()->isIcon($params['src'])
            ) {
                return \smarty_plugin_image_assign(
                    $params,
                    '<span class="quiqqer-icon '.$params['src'].'"></span>',
                    $smarty
                );
            }

            if (\strpos($params['src'], 'fa fa-') !== false) {
                return \smarty_plugin_image_assign($params, '', $smarty);
            }

            QUI\System\Log::writeRecursive(
                'unknown image',
                QUI\System\Log::LEVEL_WARNING,
                [
                    'smarty' => '{image}',
                    'image'  => $params['src']
                ]
            );

            return \smarty_plugin_image_assign($params, '', $smarty);
        }

        if (isset($params['onlyicon'])) {
            return \smarty_plugin_image_assign($params, '', $smarty);
        }

        $src        = \str_replace('&amp;', '&', $params['src']);
        $attributes = QUI\Utils\StringHelper::getUrlAttributes($src);

        if (!isset($attributes['id']) || !isset($attributes['project'])) {
            if (isset($params['onlysrc'])) {
                return \smarty_plugin_image_assign(
                    $params,
                    $params['src'],
                    $smarty
                );
            }

            return \smarty_plugin_image_assign($params, '', $smarty);
        }

        try {
            $Project = QUI::getProject($attributes['project']);
            $Media   = $Project->getMedia();

            /* @param $Image \QUI\Projects\Media\Image */
            $Image = $Media->get((int)$attributes['id']);
        } catch (\Exception $Exception) {
            QUI\System\Log::addNotice(
                'Smarty Image Plugin {image} : '.$Exception->getMessage()
            );

            if (isset($params['onlysrc'])) {
                return \smarty_plugin_image_assign(
                    $params,
                    $params['src'],
                    $smarty
                );
            }

            return \smarty_plugin_image_assign($params, '', $smarty);
        }
    } else {
        $Image = $params['image'];

        // Falls $Image ein Folder ist, dann das erste Bild nehmen
        if (QUI\Projects\Media\Utils::isFolder($Image)) {
            /* @var $Image \QUI\Projects\Media\Folder */
            try {
                $Image = $Image->firstImage();
            } catch (\Exception $Exception) {
                $Image = false;
            }
        }
    }

    if (!$Image) {
        return \smarty_plugin_image_assign($params, '', $smarty);
    }

    /* @var $Image \QUI\Projects\Media\Image */

    // Falls das Objekt gewünscht ist
    if (isset($params['assign']) && isset($params['object'])) {
        $smarty->assign($params['assign'], $Image);

        return '';
    }

    if ($Image->getType() != 'QUI\Projects\Media\Image') {
        return \smarty_plugin_image_assign($params, '', $smarty);
    }

    if (!isset($params['height'])) {
        $params['height'] = false;
    }

    if (!isset($params['width'])) {
        $params['width'] = false;
    }

    $maxWidth = '';

    try {
        $maxWidth = $Image->getWidth();
    } catch (\Exception $Exception) {
        QUI\System\Log::addDebug($Exception->getMessage());
    }

    if (isset($params['width']) && $params['width'] > $maxWidth) {
        $params['width'] = $maxWidth;
    }

    if (isset($params['reflection'])) {
        $Image->setAttribute('reflection', true);
    }

    switch ($params['type']) {
        default:
        case 'resize':
            try {
                $src        = $Image->createResizeCache($params['width'], $params['height']);
                $resizeData = $Image->getResizeSize($params['width'], $params['height']);

                if ($resizeData['width'] < $params['width']) {
                    $params['width'] = $resizeData['width'];
                }
            } catch (\Exception $Exception) {
                if (isset($params['onlysrc']) && isset($params['src'])) {
                    return \smarty_plugin_image_assign(
                        $params,
                        $params['src'],
                        $smarty
                    );
                }

                return \smarty_plugin_image_assign(
                    $params,
                    '',
                    $smarty
                );
            }
            break;
    }

    $src = \str_replace(CMS_DIR, URL_DIR, $src);

    if (isset($params['host']) && $params['host'] == 1) {
        $host = $Image->getMedia()->getProject()->getVHost(true, true);
        $src  = $host.$src;
    }

    if (isset($params['onlysrc'])) {
        return \smarty_plugin_image_assign($params, $src, $smarty);
    }


    // create picture / image tag

    try {
        QUI::getEvents()->fireEvent('smartyImageBeforeSource', [$smarty, &$src, &$params]);
    } catch (QUI\Exception $Exception) {
        QUI\System\Log::writeDebugException($Exception);
    }

    $attributes = [];
    $filter     = \array_flip([
        'src',
        'type',
        'reflection',
        'image',
        'assign',
        'host',
        'nosrcset'
    ]);

    foreach ($params as $key => $value) {
        if (!isset($filter[$key])) {
            $attributes[$key] = $value;
        }
    }


    // missing alt / title
    if (!isset($attributes['alt'])) {
        $attributes['alt'] = \htmlentities($Image->getAlt(), ENT_COMPAT, 'UTF-8');
    }

    if (!isset($attributes['title'])) {
        $attributes['title'] = \htmlentities($Image->getTitle(), ENT_COMPAT, 'UTF-8');
    }

    $result = QUI\Projects\Media\Utils::getImageHTML($Image->getUrl(), $attributes);


    try {
        QUI::getEvents()->fireEvent('smartyImageEnd', [$smarty, &$result]);
    } catch (QUI\Exception $Exception) {
        QUI\System\Log::writeDebugException($Exception);
    }

    return \smarty_plugin_image_assign($params, $result, $smarty);
}

/**
 * To assign the result to a variable
 *
 * @param array $params
 * @param string $str
 * @param Smarty $smarty
 *
 * @return string
 */
function smarty_plugin_image_assign($params, $str, $smarty)
{
    if (!isset($params['assign'])) {
        return $str;
    }

    $smarty->assign($params['assign'], $str);

    return '';
}
