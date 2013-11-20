<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {sbutton} function plugin
 *
 * Type:     function<br>
 * Name:     sbutton<br>
 * @author Henning Leutz <henbug @ pcsg . de>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_sbutton($params, &$smarty)
{
    $Toolbar = new Controls_Toolbar_Bar(array(
        'name'   => 'tb',
        'parent' => 'site_type_edit'
       ));

    $Toolbar->appendChild(
           new Controls_Buttons_Button(array(
            'name'    => $params['name'],
            'onclick' => $params['onclick'],
            'text'	  => $params['text'],
            'icon'	  => $params['icon']
        ))
    );

    return '<script>'. $Toolbar->create() .'</script>';
}
