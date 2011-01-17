<?php
/**
 * Smarty plugin for Litotex 0.8
 * @author Jonas Schwabe
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {make_link} plugin
 *
 * Type:     function<br>
 * Name:     make_link<br>
 * Purpose:  generate a link to a specified package
 * @author Jonas Schwabe
 * @param array $params parameters
 * @param object $template template object
 * @return string|null if the assign parameter is passed, Smarty assigns the
 *                     result to a template variable
 */
function smarty_function_make_link($params, $template)
{
    if (empty($params['package'])) {
        trigger_error("make_link: package parameter missing.");
        return;
    }
    $url = 'index.php';
    $url .= '?package=' . urlencode($params['package']);
    unset($params['package']);
    foreach($params as $key => $value) {
        $url .= '&amp;' . $key . '=' . urlencode($value);
    }
    return $url;
/* Entwurf:
    if (empty($params['package'])) {
        trigger_error("make_link: package parameter missing.");
        return;
    }
    return 'index.php?' . http_build_query($params);
*/
}
?>
