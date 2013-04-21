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
/**
 * @package acp_navigation
 * @author Patrick K�nig <koenig@mail-buero.de>
 * @copyright: Copyright 2011 Litotex Team
 */
class package_acp_navigation extends acpPackage {
    /**
     * Package name
     * @var string
     */
    protected $_packageName = 'acp_navigation';
    /**
     * Avaibilbe actions in this package
     * @var array
     */
    protected $_availableActions = array();
    
    protected $_theme = 'empty.tpl';
    
    public function __action_main() {
        return true;
    }
    public static function registerTplModifications(){
        self::_registerTplModification(__CLASS__, 'displayAcpTopNavigation', 'acp_navigation');
        self::_registerTplModification(__CLASS__, 'displayAcpSubNavigation', 'acp_navigation');
        return true;
    }
    public static function __tpl_displayAcpTopNavigation(){
        Package::addJsFile('main.js','acp_navigation');
        //package::addJsFile('jquery.effects.core.min.js');
        $elements = array();
        $data = self::$pdb->query("SELECT `ID`, `title`, `description`, `icon`, `package`, `action` FROM `lttx1_acp_navigation` WHERE `parent` IS NULL ORDER BY `sort` ASC");
        foreach($data as $entry) {
            if(!isset($_GET['package'])) $_GET['package'] = 'main';
            $res = Package::$pdb->prepare("SELECT COUNT(*) FROM `lttx1_acp_navigation` WHERE `parent` = ? AND `package` = ?");
            $res->execute(array($entry['ID'], $_GET['package']));
            $res = $res->fetch();
            
            if($res[0] >= 1)
                $entry['active'] = true;
            else
                $entry['active'] = false;
            $elements[] = $entry;
        }
        self::$tpl->assign('navigationItems', $elements);
        self::$tpl->display(self::getTplDir('acp_navigation') . 'topNavigation.tpl');
    }
    public static function __tpl_displayAcpSubNavigation(){
        $counter=0;
        $data = self::$pdb->query("SELECT `ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `tab` FROM `lttx1_acp_navigation` WHERE `parent` IS NOT NULL ORDER BY `sort` ASC");
        $elements = array();
        foreach($data as $entry) {
            $counter++;
            $real = self::$pdb->prepare("SELECT COUNT(*) FROM `lttx1_acp_navigation` WHERE `parent` IS NULL AND `ID` = ?");
            $real->execute(array($entry['parent']));
            $real = $real->fetch();
            if($real[0] < 1) {
                continue;
            }
            $sub = self::$pdb->prepare("SELECT `ID`, `parent`, `title`, `description`, `icon`, `package`, `action`, `tab` FROM `lttx1_acp_navigation` WHERE `parent` = ? ORDER BY `sort` ASC");
            $sub->execute(array($entry['ID']));
            if(!isset($elements[$entry['parent']]))
                $elements[$entry['parent']] = array('active' => false);
                $subElements = array();
                $active = false;
                
            if(!isset($_GET['package']))
                $_GET['package'] = 'main';
            if($entry['package'] == $_GET['package']){
                $active = true;
            }
            foreach($sub as $subEntry) {
                $subElements[] = $subEntry;
                if(!isset($_GET['package']))
                    $_GET['package'] = 'main';
                if($subEntry['package'] == $_GET['package']) {
                    $active = true;
                }
            }
            $entry['sub'] = $subElements;
            $elements[$entry['parent']][] = $entry;
            if($active==true)
                $elements[$entry['parent']]['active'] = true;
        }
        self::$tpl->assign('navigationItems', $elements);
        self::$tpl->display(self::getTplDir('acp_navigation') . 'subNavigation.tpl');
    }
}
?>