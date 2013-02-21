<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {tite text=text} function plugin
 *
 * Type:     function<br>
 * Name:     title<br>
 * @author Henning Leutz <henbug @ pcsg . de>
 * @param array params
 * @param Smarty
 * @return User|false
 */
function smarty_function_title($params, $Smarty)
{
	$id = '';

	if ( isset( $params['id'] ) ) {
		$id .= ' id="'. $params['id'] .'"';
	}

	if ( isset( $params['style'] ) ) {
		$id .= ' style="'. $params['style'] .'"';
	}

	$str = '<div class="topic" '. $id .' >';

	if ( isset( $params['icon'] ) ) {
		$str .= '<img src="'. $params['icon'] .'" style="float: left; margin: 5px 0;" />';
	}

	if ( isset( $params['group'] ) && isset( $params['var'] ) )
	{
        $params['text'] = QUI::getLocale()->get(
            $params['group'],
            $params['var']
        );
	}

	if ( isset( $params['text'] ) )
	{
		$str .= '<span';

		if ( isset( $params['lineHeight'] ) ) {
            $str .= ' style="margin: 5px; float: left; line-height: '. (int)$params['lineHeight'] .'px" ';
		}

		$str .='>';
		$str .= $params['text'] .'</span>';
	}

	$str .= '</div>';

	return $str;
}

?>
