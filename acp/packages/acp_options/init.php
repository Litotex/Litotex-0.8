<?php
/*
 * Copyright (c) 2010 Litotex
 * 
 * Permission is hereby granted, free of charge,
 * to any person obtaining a copy of this software and
 * associated documentation files (the "Software"),
 * to deal in the Software without restriction,
 * including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions
 * of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

class package_acp_options extends acpPackage {

    protected $_availableActions = array('main', 'edit', 'editSubmit');
    public static $dependency = array('acp_config');
    protected $_packageName = 'acp_options';
    protected $_theme = 'main.tpl';
    private static $_options = false;

    public function __action_main() {
        self::addCssFile('options.css', 'acp_options');
        self::addJsFile('options.js', 'acp_options');

        // get all options from database
        $state = package::$pdb->prepare('
            SELECT `ID`, `package`, `key`, `value`, `default` 
            FROM `lttx' . package::$pdbn . '_options`
        ');
        $state->execute();
        
        $options = array();
        while($option = $state->fetch()) {
            $options[] = array(
                'optionID'  => $option['ID'],
                'package'   => $option['package'],
                'key'       => $option['key'],
                'value'     => $option['value'],
                'default'   => $option['default']
            );
        }
        package::$tpl->assign('options', $options);
        
        return true;
    }
    
    public function __action_edit() {
        $this->_theme = 'edit.tpl';
        self::addCssFile('options.css', 'acp_options');
        
        $optionID = (int) $_GET['optionID'];
        $state = self::$pdb->prepare('
            SELECT `ID`, `package`, `key`, `value`, `default` 
            FROM `lttx'.package::$pdbn.'_options`
            WHERE
                ID = :optionID
        ');
        $state->execute(array(
           ':optionID' => $optionID
        ));
        
        // so, let us look if there is a result
        if($state->rowCount() < 1) {
            throw new LitotexError('LN_OPTION_OPTION_NOT_EXISTS');
            return true;
        }
        
        $result = $state->fetch();
        $option = array(
            'optionID'  => $result['ID'],
            'package'   => $result['package'],
            'key'       => $result['key'],
            'value'     => $result['value'],
            'default'   => $result['default']
        );
        self::$tpl->assign('option', $option);
        return true;
    }
    
    public function __action_editSubmit() {
        // get form data
        $optionID = $_POST['optionID'];
        $value = $_POST['value'];
        
        // get current option data
        $state = package::$pdb->prepare('
            SELECT `ID`, `package`, `key`, `value`, `default`
            FROM `lttx'.package::$pdbn.'_options`
            WHERE
                ID = :optionID
        ');
        $state->execute(array(
            ':optionID' => $optionID
        ));
                
        // so, let us look if there is a result
        if($state->rowCount() < 1) {
            throw new lttxError('LN_OPTION_OPTION_NOT_EXISTS');
            return false;
        }
        
        // save
        $result = $state->fetch();
        $option = new Option($result['package'], true);
        if($value == '') {
            $option->reset($result['key']);
        } else {
            $option->set($result['key'], $value);
        }
        if(!isset($_GET['ajax']))
            Header('Location: ?package=acp_options&action=main');
        return true;
    }
}