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
        if(!self::exists($ID))
               return;
        
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
        $result = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_news_categories` WHERE `ID` = ?");
        $result->execute(array($ID));
        return ($result->rowCount() > 0)?true:false;
    }
    /**
     * This checks if there is a category named as the one to search for here
     * @param string $title Title of category to search for
     * @return bool
     */
    public static function titleExists($title){
        $result = Package::$pdb->prepare("SELECT `ID` FROM `lttx1_news_categories` WHERE `title` = ?");
        $result->execute(array($title));
        return($result->rowCount() > 0)?true:false;
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
        $result = Package::$pdb->prepare("INSERT INTO `lttx1_news_categories` (`title`, `description`, `newsNum`, `newsLastDate`) VALUES (?, ?, ?, ".Package::$pdb->DBTimeStamp(date("Y-m-d H:m:s", time())).")");
        $result->execute(array($title, $description, 0));
        return ($result->rowCount() <= 0)?false:new category(Package::$pdb->lastInsertId());
    }
    /**
     * This will delete the active category from database
     * @return bool
     */
    public function delete() {
        if(!$this->_initialized)
                return false;
        $result = Package::$pdb->prepare("DELETE FROM `lttx1_news_categories` WHERE `ID` = ?");
        $result->execute(array($this->_ID));
        $this->_initialized = false;
        return ($result->rowCount() <= 0)?false:true;
    }
    /**
     * This collects all categories and returns them in an array
     * @return array
     */
    public static function getAll() {
        $return = array();
        $categories = Package::$pdb->query("SELECT `ID` FROM `lttx1_news_categories`");
        foreach($categories as $item) {
            $return[] = new category($item[0]);
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
        $desc = Package::$pdb->prepare("SELECT `description` FROM `lttx1_news_categories` WHERE `ID` = ?");
        $desc->execute(array($this->_ID));
        if($desc->rowCount() < 1)
            return false;
        
        $desc = $desc->fetch();
        return $desc[0];
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
        $title = Package::$pdb->prepare("SELECT `title` FROM `lttx1_news_categories` WHERE `ID` = ?");
        $title->execute(array($this->_ID));

        if($title->rowCount() < 1)
            return false;
        
        $title = $title->fetch();
        return $title[0];
    }
    /**
     * Sets this time as last news date
     * @return bool
     */
    public function updateTimestamp(){
        $result = Package::$pdb->prepare("UPDATE `lttx1_news_categories` SET `newsLastDate` = " . Package::$pdb->DBTimeStamp(date("Y-m-d H:m:s", time())) . " WHERE `ID` = ?");
        $result->execute(array($this->_ID));
        return ($result->rowCount() <= 0)?false:true;
    }
}
?>
