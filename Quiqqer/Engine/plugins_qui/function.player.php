<?php
/**
 * Smarty Plugin
 *
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 *
 * @author PCSG - Henning
 */

/**
 * Smarty {player} function plugin
 *
 * Type:     function<br>
 * Name:     image<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 *
 * @return string
 * @deprecated
 */
function smarty_function_player($params, &$smarty)
{
    if (!isset($params['file'])) {
        return '';
    }

    $File = $params['file']; /* @var $File MF_File */

    $playerparams = array(
        'height' => 300,
        'width'  => 500,
        'name'   => 'pcsg-player',

        'quality'  => "high",
        'bgcolor'  => "#ffffff",
        'wmode'    => "opaque",
        'menu'     => "false",
        'loop'     => "false",
        'scale'    => "noscale",
        'salign'   => "tl",
        'autoplay' => 'true',
        'volume'   => '0.8',

        'allowfullscreen' => 'false',
        'autoscale'       => 'false',
        'smoothing'       => 'true',
        'skincolor'       => '0xffffff'
    );

    foreach ($params as $key => $value) {
        $playerparams[$key] = $value;
    }

    $flashvars = '
        video='. $File->getUrl(true) .'&
        preview='. $File->getUrl(true) .'&
        autoplay='. $playerparams['autoplay'] .'&
        loop='. $playerparams['loop'] .'&
        volume='. $playerparams['volume'] .'&
        autoscale='. $playerparams['autoscale'] .'&
        videowidth='. $playerparams['width'] .'&
        videoheight='. $playerparams['height'] .'&
        smoothing='. $playerparams['smoothing'] .'&
        skincolor='. $playerparams['skincolor'] .'&
        skin=skin-play-seek-fullscreen-vol.swf&
        buttonoverlay=defaultbuttonoverlay.swf&
        ending=defaultending.swf&
        preloader=defaultpreloader.swf&
        debug=false&
        contentpath='. HOST .'&
        playerpath='. URL_BIN_DIR .'js/ptools/editor/plugins/mediaplayer/player';

    $flashvars = str_replace(array("\n", "\t", " "), '', $flashvars);
    //$flashvars = urldecode($flashvars);

    $str = '
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
        width="'. $playerparams['width'] .'"
        height="'. $playerparams['height'] .'">

        <param name="movie" value="'. URL_BIN_DIR .'js/ptools/editor/plugins/mediaplayer/player/flvplayer.swf" />
        <param name="quality" value="'. $playerparams['quality'] .'" />
        <param name="scale" value="'. $playerparams['scale'] .'" />
        <param name="bgcolor" value="'. $playerparams['bgcolor'] .'" />
        <param name="wmode" value="'. $playerparams['wmode'] .'" />

        <param name="flashvars" value="'. $flashvars .'" />
        <param name="menu" value="'. $playerparams['menu'] .'" />
        <param name="salign" value="'. $playerparams['salign'] .'" />
        <param name="allowfullscreen" value="'. $playerparams['allowfullscreen'] .'" />

        <embed id="KFO Praxis Film" quality="high" width="'. $playerparams['width'] .'" height="'. $playerparams['height'] .'"
            type="application/x-shockwave-flash"
            src="'. URL_BIN_DIR .'js/ptools/editor/plugins/mediaplayer/player/flvplayer.swf"
            bgcolor="'. $playerparams['bgcolor'] .'"
            name="'. $File->getAttribute('name') .'"
            flashvars="'. $flashvars .'"
            wmode="'. $playerparams['wmode'] .'"
            scale="'. $playerparams['scale'] .'"
            salign="'. $playerparams['salign'] .'"
            menu="'. $playerparams['menu'] .'"
            allowfullscreen="'. $playerparams['allowfullscreen'] .'" />
        </object>';


/*
    $str = '
        <object
              data="/bin/js/ptools/editor/plugins/mediaplayer/player/flvplayer.swf"
            type="application/x-shockwave-flash"
            height="'. $playerparams['height'] .'"
            width="'. $playerparams['width'] .'"
            name="'. $playerparams['name'] .'"
            class="none-ie-flash-player">
            <param name="autoplay" value="true"></param>
            <param name="flashvars" value="'. $flashvars .'"></param>
        </object>

        <!--[if IE]>
        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'. $playerparams['width'] .'" height="'. $playerparams['height'] .'">
              <param name="movie" value="/bin/js/ptools/editor/plugins/mediaplayer/player/flvplayer.swf" />
            <param name="quality" value="'. $playerparams['quality'] .'" />
            <param name="bgcolor" value="'. $playerparams['bgcolor'] .'" />
            <param name="wmode" value="'. $playerparams['wmode'] .'" />
            <param name="menu" value="'. $playerparams['menu'] .'" />
            <param name="allowfullscreen" value="'. $playerparams['allowfullscreen'] .'" />
            <param name="loop" value="'. $playerparams['value'] .'" />
            <param name="flashvars" value="'. $flashvars .'" />
        </object>
        <style type="text/css">
            .none-ie-flash-player { display: none; }
        <style>
        <![endif]-->';
*/
    if (!isset($params['assign'])) {
        return $str;
    }

    $smarty->assign($params['assign'], $str);
}
