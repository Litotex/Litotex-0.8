<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {generateAjaxLink} plugin
 *
 * Type:     function<br>
 * Name:     generateAjaxLink<br>
 * Purpose:  generates a link that shows a page without header and footer
 * @author Jonas Schwabe <jonas.schwabe@gmail.com>
 * @param array $params parameters
 * @param object $template template object
 * @return string url
 */
function smarty_function_generateAjaxLink($params, $template)
{
    if (empty($params['url'])) {
        trigger_error("[plugin] generateAjaxLink parameter 'url' cannot be empty",E_USER_NOTICE);
        return;
    }

    $lockID = sha1(microtime());
    if(!isset($_SESSION['ajaxLocks']))
        $_SESSION['ajaxLocks'] = array();
    $_SESSION['ajaxLocks'][$lockID] = true;
    $newUrl = '';
    if(preg_match('/\?/', $params['url']))
        return $params['url'] . '&ajaxLock=' . urldecode ($lockID);
    else
        return $params['url'] . '?ajaxLock=' . urldecode ($lockID);
}

?>