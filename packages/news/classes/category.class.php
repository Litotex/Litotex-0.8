<?php
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
        $result = package::$db->Execute("SELECT `ID` FROM `lttx_newsCategories` WHERE `ID` = ?", array($ID));
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
        $result = package::$db->Execute("SELECT `ID` FROM `lttx_newsCategories` WHERE `title` = ?", array($title));
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
        $result = package::$db->Execute("INSERT INTO `lttx_newsCategories` (`title`, `description`, `newsNum`, `newsLastDate`) VALUES (?, ?, ?, ".package::$db->DBTimeStamp(date("Y-m-d H:m:s", time())).")", array($title, $description, 0));
        return (package::$db->Affected_Rows() <= 0)?false:new category($db->Insert_ID());
    }
    /**
     * This will delete the active category from database
     * @return bool
     */
    public function delete() {
        if(!$this->_initialized)
                return false;
        $result = package::$db->Execute("DELETE FROM `lttx_newsCategories` WHERE `ID` = ?", array($this->_ID));
        $this->_initialized = false;
        return (package::$db->Affected_Rows() <= 0)?false:true;
    }
    /**
     * This collects all categories and returns them in an array
     * @return array
     */
    public static function getAll() {
        $return = array();
        $categories = package::$db->Execute("SELECT `ID` FROM `lttx_newsCategories`");
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
        $desc = package::$db->Execute("SELECT `description` FROM `lttx_newsCategories` WHERE `ID` = ?", array($this->_ID));
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
        $title = package::$db->Execute("SELECT `title` FROM `lttx_newsCategories` WHERE `ID` = ?", array($this->_ID));
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
        $result = package::$db->Execute("UPDATE `lttx_newsCategories` SET `newsLastDate` = " . package::$db->DBTimeStamp(date("Y-m-d H:m:s", time())) . " WHERE `ID` = ?", array($this->_ID));
        return (package::$db->Affected_Rows() <= 0)?false:true;
    }
}
?>
