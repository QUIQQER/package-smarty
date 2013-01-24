<?php

/**
 * Übersetzung in Smarty
 *
 * @param unknown_type $params
 * @param unknown_type $Smarty
 */

function smarty_function_locale($params, $Smarty)
{
    if (!isset($params['group'])) {
		return;
	}

    if (!isset($params['value'])) {
		return;
	}

	$group = $params['group'];
	$value = $params['value'];

	unset($params['group']);
	unset($params['value']);


	$result = QUI::getLocale()->get(
	    $group,
	    $value,
	    $params
	);;

	if (!isset($params['assign'])) {
		return $result;
	}

	$Smarty->assign($params['assign'], $result);
}

?>