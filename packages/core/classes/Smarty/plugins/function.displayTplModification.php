<?php
/**
 * Smarty plugin for Litotex 0.8
 * @author Jonas Schwabe
 * @package Smarty
 * @subpackage PluginsFunctionCompiler
 */


/**
 * Smarty {displayTplModification} function plugin
 *
 * Type:     function<br>
 * Name:     displayTplModification<br>
 * Purpose:  displayTplModification
 * @author   Jonas Schwabe
 * @param string
 * @param boolean include whitespace in the character count
 * @return string
 */
function smarty_function_displayTplModification($params, &$smarty)
{
    if(!isset($params['position']))
        trigger_error("displayTplModification: position parameter missing.");
	$position = str_replace("'", '', $params['position']);
    if (empty($position)) {
        trigger_error("displayTplModification: position parameter missing.");
        return;
    }
    global $out;
    if(!isset($out[$position]))
        return false;
    return $out[$position];
}

/* vim: set expandtab: */

?>
