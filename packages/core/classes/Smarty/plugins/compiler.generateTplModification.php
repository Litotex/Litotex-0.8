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
function smarty_compiler_generateTplModification($params, &$smarty)
{
	$params = $smarty->_parse_attrs($params);
	$params['position'] = str_replace("'", '', $params['position']);
    if (empty($params['position'])) {
        $smarty->trigger_error("displayTplModification: position parameter missing.");
        return;
    }
    $code = '<?php ob_start(); package::$packages->displayTplModification(\'' . $params['position'] . '\'); global $out' . $params['position'] . '; $out' . $params['position'] . ' = ob_get_contents(); ob_end_clean(); ?>';
    package::$tpl->addAditionalSmartyHeader($code);
}

/* vim: set expandtab: */

?>
