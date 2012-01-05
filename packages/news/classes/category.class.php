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
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * This allows to access news categories
 */
class category {
    /**
     * Class initialized?
     * @var bool
     */
    private $_initialized = false;
    /**
     * ID of category
     * @var int
     */
    private $_ID = false;
    /**
     * This will hold a cache for every category loaded
     * @var array
     */
//    private static $_categoryCache = array(); TODO
    /**
     * This will check if the category exists and initializes the class
     * @param int $ID id of category
     * @return void
     */
    public function  __construct($ID) {
        if(!self::exists($ID)){
            throw new Exception('Category item ' . $ID . ' could not be found');
            return;
        }
        $this->_ID = $ID;
        $this->_initialized = true;
    }
    /**
     * EMPTY
     * @return void
     */
    public function  __destruct() {
        return true;
    }
    /**
     * This returns the title of the category
     * @return string
     */
    public function  __toString() {
        return $this->getTitle();
    }
    /**
     * This will check if a category ID is available
     * @param int $ID id to check for
     * @return bool
     */
    private function exists($ID) {
        $ID *= 1;
        $result = package::$db->Execute("SELECT `ID` FROM `lttx_news_categories` WHERE `ID` = ?", array($ID));
        if(!$result)
            return false;
        if(!isset($result->fields[0]) || !$result->fields[0])
            return false;
        return true;
    }
    /**
     * This checks if there is a category named as the one to search for here
     * @param string $title Title of category to search for
     * @return bool
     */
    public static function titleExists($title){
        $result = package::$db->Execute("SELECT `ID` FROM `lttx_news_categories` WHERE `title` = ?", array($title));
        return(!$result)?false:(bool)(1^$result->EOF);
    }
    /**
     * This creates a new category
     * @param string $title title of new category
     * @param string $description description for category
     * @return bool
     */
    public static function create($title, $description) {
        if(self::titleExists($title))
            return false;
        $result = package::$db->Execute("INSERT INTO `lttx_news_categories` (`title`, `description`, `newsNum`, `newsLastDate`) VALUES (?, ?, ?, ".package::$db->DBTimeStamp(date("Y-m-d H:m:s", time())).")", array($title, $description, 0));
        return (package::$db->Affected_Rows() <= 0)?false:new category($db->Insert_ID());
    }
    /**
     * This will delete the active category from database
     * @return bool
     */
    public function delete() {
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("DELETE FROM `lttx_news_categories` WHERE `ID` = ?", array($this->_ID));
        $this->_initialized = false;
        return (package::$db->Affected_Rows() <= 0)?false:true;
    }
    /**
     * This collects all categories and returns them in an array
     * @return array
     */
    public static function getAll() {
        $return = array();
        $categories = package::$db->Execute("SELECT `ID` FROM `lttx_news_categories`");
        while(!$categories->EOF) {
            $return[] = new category($categories->fields[0]);
            $categories->MoveNext();
        }
        return $return;
    }
    /**
     * This will return all news in this category
     * @return array
     */
    public function getNews() {
        return news::getAll($this);
    }
    /**
     * This will return the description of the current category
     * @return string
     */
    public function getDescription() {
        if(!$this->_initialized)
            return false;
        $desc = package::$db->Execute("SELECT `description` FROM `lttx_news_categories` WHERE `ID` = ?", array($this->_ID));
        if(!$desc)
            return false;
        if(!isset($desc->fields[0]) || !$desc->fields[0])
            return false;
        return $desc->fields[0];
    }
    /**
     * This will return the current ID
     * @return int
     */
    public function getID() {
        if(!$this->_initialized)
            return false;
        return $this->_ID;
    }
    /**
     * This will return the current title
     * @return string
     */
    public function getTitle() {
        if(!$this->_initialized)
            return false;
        $title = package::$db->Execute("SELECT `title` FROM `lttx_news_categories` WHERE `ID` = ?", array($this->_ID));
        if(!$title)
            return false;
        if(!isset($title->fields[0]) || !$title->fields[0])
            return false;
        return $title->fields[0];
    }
    /**
     * Sets this time as last news date
     * @return bool
     */
    public function updateTimestamp(){
        $result = package::$db->Execute("UPDATE `lttx_news_categories` SET `newsLastDate` = " . package::$db->DBTimeStamp(date("Y-m-d H:m:s", time())) . " WHERE `ID` = ?", array($this->_ID));
        return (package::$db->Affected_Rows() <= 0)?false:true;
    }
}
?>
