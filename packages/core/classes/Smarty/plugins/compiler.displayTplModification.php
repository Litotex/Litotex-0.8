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
function smarty_compiler_displayTplModification($params, &$smarty)
{
        $params = $smarty->_parse_attrs($params);
	$params['position'] = str_replace("'", '', $params['position']);
    if (empty($params['position'])) {
        $smarty->trigger_error("displayTplModification: position parameter missing.");
        return;
    }
    return 'global $out' . $params['position'] . '; echo $out' . $params['position'] . ';';
}

/* vim: set expandtab: */

?>
