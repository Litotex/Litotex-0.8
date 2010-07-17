<?php
/**
 * Smarty plugin for Litotex 0.8
 * @package Smarty
 * @subpackage plugins
 * @author Jonas Schwabe
 */


/**
 * Smarty count_characters modifier plugin
 *
 * Type:     modifier<br>
 * Name:     count_characteres<br>
 * Purpose:  count the number of characters in a text
 * @link http://smarty.php.net/manual/en/language.modifier.count.characters.php
 *          count_characters (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param boolean include whitespace in the character count
 * @return integer
 */
function smarty_function_make_link($params, &$smarty)
{
    if (empty($params['package'])) {
        $smarty->trigger_error("make_link: package parameter missing.");
        return;
    }
    $url = 'index.php';
    $url .= '?package='.urlencode($params['package']);
    unset($params['package']);
    foreach($params as $key => $value){
        $url .= '&' . $key . '='.urlencode($value);
    }
    return $url;
}

/* vim: set expandtab: */

?>
