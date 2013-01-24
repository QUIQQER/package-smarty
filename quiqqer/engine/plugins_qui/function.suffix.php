<?php
/**
 * Smarty plugin
 * @package com.pcsg.pms.smarty
 * @subpackage plugins
 */


/**
 * Smarty {suffix} function plugin
 *
 * Type:     function<br>
 * Name:     url<br>
 * @author PCSG
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_suffix($params, &$smarty)
{
	global $Project;

	if (!isset($params['suffix'])) {
		return '';
	}

	$P = $Project;

	/* @var $Project Projects_Project */

	$url  = '';
    $site = false;

	if (isset($params['site']))
	{
		$site = $params['site'];
	} elseif (isset($params['id']))
	{
		if (isset($params['lang'])) {
			$P = new Project($Project->getAttribute('name'), $params['lang']);
		}

		$site = $P->get((int)$params['id']);
	}

	unset($params['site']);
	unset($params['id']);
	unset($params['lang']);

	$url = '';

	if ($site && $site->getId()) {
    	$url = $site->getUrl($params);
    }

    return $url;
}

?>