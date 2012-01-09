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
 * Every instance of this class contains one news element
 * All elements can be fetched by using static functions
 */
class news{
    /**
     * Is this instance initialized?
     * @var bool
     */
    private $_initialized = false;
    /**
     * Current ID
     * @var int
     */
    private $_ID = false;
    /**
     * Current title
     * @var string
     */
    private $_title = false;
    /**
     * Current newstext
     * @var string
     */
    private $_text = false;
    /**
     * ID of category this news belongs to
     * @var category
     */
    private $_category = false;
    /**
     * Date the news has been written
     * @var Date
     */
    private $_date = false;
    /**
     * Number of comments
     * @var int
     */
    private $_commentNum = false;
    /**
     * Writer
     * @var user
     */
    private $_writtenBy = false;
    /**
     * Active flag
     * @var bool
     */
    private $_active = false;
    /**
     * Contains preferences (auto inform etc)
     * @var option
     */
    private static $_options = false;
    /**
     * This will hold a cache for all news loaded
     * @var array
     */
    private static $_newsCache = array();
    /**
     * This will load news context and caches data
     * @param int $id
     * @return void
     */
    public function  __construct($ID) {
        //Save data to class
        if(!$this->_get($ID)){
            throw new Exception('News item ' . $ID . ' could not be found');
            return;
        }
        if(!self::$_options)
            self::$_options = new option('news');
        $this->_initialized = true;
        return;
    }
    /**
     * EMPTY
     * @return void
     */
    public function  __destruct() {
        return;
    }

    public function  __toString() {
        return $this->getTitle();
    }
    /**
     * This will create a new newsentry
     * @param string $title Title of new news
     * @param string $text Text of new news
     * @param category $category Category instance to save in
     * @param bool $active Active by default?
     * @return bool
     */
    public static function publishNew($title, $text, $category, $active = true){
        if(!is_a($category, 'category'))
                return false;
        $title = htmlspecialchars($title);
        $insert = package::$pdb->prepare("INSERT INTO `lttx".package::$pdbn."_news` (`title`, `text`, `category`, `date`, `commentNum`, `writtenBy`, `active`) VALUES (?, ?, ?, ".package::$pdb->DBTimeStamp(date("Y-m-d H:m:s", time())).", ?, ?, ?)");
        $insert->execute(array($title, $text, $category->getID(), 0, (package::$user)?package::$user->getID():false, (bool)$active));
        $category->updateTimestamp();
        return ($insert->rowCount() < 1)?false:new news(package::$pdb->lastInsertId());
    }
    /**
     * Returns an array of all news based on category if set
     * @param category $category category to load
     * @param int $page to load (loads all if 0)
     * @param int $offset num of items to show (false = default)
     * @return array
     */
    public static function getAll($category = false, $page = 1, $offset = false){
        if($category && !is_a($category, 'category'))
                return false;
        if(!self::$_options)
            self::$_options = new option('news');
        $return = array();
        if($offset === false)
            $offset = self::$_options->get('newsPerSite');
        $offset = (int)$offset;
        $start = ($page-1) * $offset;
        if($page === 0){
            $start = 0;
            $offset = -1;
        }
        $return = array();
        if($category){
			$news = package::$pdb->prepare("SELECT `ID`, `title`, `text`, `category`, `date`, `commentNum`, `writtenBy`, `active` FROM `lttx".package::$pdbn."_news` WHERE `category` = ? AND `active` = ? ORDER BY `date` DESC");
			$news->bindParam(':offset', $start, PDO::PARAM_INT);
			$news->bindParam(':max', $offset, PDO::PARAM_INT);
			$news->execute(array($category->getID(), true));

			}
		else{
			$news = package::$pdb->prepare("SELECT `ID`, `title`, `text`, `category`, `date`, `commentNum`, `writtenBy`, `active` FROM `lttx".package::$pdbn."_news` WHERE `active` = ? ORDER BY `date` DESC");
			$news->bindParam(':offset', $start, PDO::PARAM_INT);
			$news->bindParam(':max', $offset, PDO::PARAM_INT);
			$news->execute(array(true));
	   }
	   
	   foreach($news as $rows){
            self::_writeCache($rows[0], $rows[1], $rows[2], $category, $rows[4], $rows[5], new user($rows[6]), $rows[7]);
			$return[] = new news($rows[0]);
        }
        return $return;
    }
    public static function getNumber($category = false){
        if($category && !is_a($category, 'category'))
                return false;
        if($category){
            $result = package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx".package::$pdbn."_news` WHERE `category` = ? AND `active` = ?");
            $result->execute(array($category->getID(), true));
        }else{
            $result = package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx".package::$pdbn."_news` WHERE `active` = ?");
            $result->execute(array(true));
        }
        if($result->rowCount() < 1)
            return false;
        
        $result = $result->fetch();
        return $result[0]*1;
    }
    /**
     * Returns current ID
     * @return int
     */
    public function getID(){
        if(!$this->_initialized)
                return false;
        return $this->_ID;
    }
    /**
     * This will return the current category object
     * @return category
     */
    public function getCategory(){
        if(!$this->_initialized)
                return false;
        return $this->_category;
    }
    /**
     * This will return the name of the category
     * @return string
     */
    public function getCategoryName(){
        if(!$this->_initialized)
                return false;
        return ($this->_category)?$this->_category->getTitle():false;
    }
    /**
     * This will return the current category ID
     * @return int
     */
    public function getCategoryID(){
        if(!$this->_initialized)
                return false;
        return ($this->_category)?$this->_category->getID():false;
    }
    /**
     * This will return the current number of comments
     * @return int
     */
    public function getcommentNum(){
        if(!$this->_initialized)
                return false;
        return $this->_commentNum;
    }
    /**
     * This will return the date this was written
     * @return Date
     */
    public function getDate(){
        if(!$this->_initialized)
                return false;
        return $this->_date;
    }
    /**
     * This will autoformate and return the date
     * @return string
     */
    public function getFormatedDate(){
        if(!$this->_initialized)
                return false;
        return $this->_date->formatDate();
	}
    /**
     * This will return the news title
     * @return string
     */
    public function getTitle(){
        if(!$this->_initialized)
                return false;
        return $this->_title;
    }
    /**
     * This will return the news text
     * @return string
     */
    public function getText(){
        if(!$this->_initialized)
                return false;
        return $this->_text;
    }
    /**
     * This will return the user object
     * @return user
     */
    public function getWriter(){
        if(!$this->_initialized)
                return false;
        return $this->_writtenBy;
    }
    /**
     * This will return the writer's ID
     * @return int
     */
    public function getWriterID(){
        if(!$this->_initialized)
                return false;
        return $this->_writtenBy->getUserID();
    }
    /**
     * This will return the writer's username
     * @return string
     */
    public function getWriterName(){
        if(!$this->_initialized)
                return false;
        return $this->_writtenBy->getUserName();
    }
    /**
     * This will set the active flag of this news
     * @param bool $value
     * @return bool
     */
    public function setActiveFlag($value){
        if(!$this->_initialized)
                return false;
        $value = (bool)$value;
        $result = package::$pdb->prepare("UPDATE `lttx".package::$pdbn."_news` SET `active` = ? WHERE `ID` = ?");
        $result->execute(array($value, $this->_ID));
        if($result->rowCount() < 1)
            return false;
        $this->_active = $value;
        return true;
    }
    /**
     * This will get the current active flag
     * @return bool
     */
    public function isActive(){
        if(!$this->_initialized)
                return false;
        return $this->_active;
    }
    /**
     * This will delete the current news
     * @return bool
     */
    public function delete(){
        if(!$this->_initialized)
                return false;
        $result = package::$pdb->prepare("DELETE FROM `lttx".package::$pdbn."_news` WHERE `ID` = ?");
        $result->execute(array($this->_ID));
        return ($result->rowCount() <= 0)?false:true;
    }
    /**
     * This sends an email when a new message was written
     * @return bool
     */
    public function informMail(){
        if(!$this->_initialized)
                return false;
        if(self::$_options->get('autoInformMail')){
            echo '<p>SEND MAIL!</p>';
        }
        return true;
    }
    /**
     * This sends a message when a new message was written
     * @return bool
     */
    public function informPM(){
        if(!$this->_initialized)
                return false;
        if(self::$_options->get('autoInformPM')){
            echo '<p>SEND PM!</p>';
        }
        return true;
    }
    /**
     * This will set new mail info settings
     * @param bool $value
     * @return bool
     */
    public static function setGlobalAutoInformMail($value){
        if(!self::$_options)
            self::$_options = new option('news');
        $result = self::$_options->set('autoInformMail', (bool)$value);
        if(!$result)
            $result = self::$_options->add('autoInformMail', (bool)$value, true);
        return $result;
    }
    /**
     * This will set new PM info settings
     * @param bool $value
     * @return bool
     */
    public static function setGlobalAutoInformPM($value){
        if(!self::$_options)
            self::$_options = new option('news');
        $result = self::$_options->set('autoInformPM', (bool)$value);
        if(!$result)
            $result = self::$_options->add('autoInformPM', (bool)$value, true);
        return $result;
    }
    /**
     * This will load a news element (it will also check the cache and write fetched elements to the cache to avoid double database connections)
     * @param int $ID
     * @return bool
     */
    private function _get($ID){
        if($this->_getCache($ID))
                return true;
        $news = package::$pdb->prepare("SELECT `title`, `text`, `category`, `date`, `commentNum`, `writtenBy`, `active` FROM `lttx".package::$pdbn."_news` WHERE `ID` = ?");
        $news->execute(array($ID));
        if($news->rowCount() < 1)
            return false;
        
        $news = $news->fetch();
        $this->_ID = $ID;
        $this->_title = $news[0];
        $this->_text = $news[1];
        $this->_category = new category($news[2]);
        $this->_date = new Date(package::$pdb->UnixTimeStamp($news[3]));
        $this->_commentNum = $news[4];
        $this->_writtenBy = new user($news[5]);
        $this->_writtenBy->setLocalBufferPolicy(false);
        $this->_active = $news[6];
        self::_writeCache($ID, $this->_title, $this->_text, $this->_category, $this->_date, $this->_commentNum, $this->_writtenBy, $this->_active);
        return true;
    }
    /**
     * This will check if there is a matching element saved in the cache and save it into the instance
     * @param int $ID
     * @return bool
     */
    private function _getCache($ID){
        if(!isset(self::$_newsCache[$ID]))
            return false;
        $this->_ID = $ID;
        $this->_title = self::$_newsCache[$ID]['title'];
        $this->_text = self::$_newsCache[$ID]['text'];
        $this->_category = self::$_newsCache[$ID]['category'];
        $this->_date = self::$_newsCache[$ID]['date'];
        $this->_commentNum = self::$_newsCache[$ID]['commentNum'];
        $this->_writtenBy = self::$_newsCache[$ID]['writtenBy'];
        $this->_active = self::$_newsCache[$ID]['active'];
        return true;
    }
    /**
     * This will write a news element to the cache
     * @param int $ID
     * @param string $title
     * @param string $text
     * @param category $category
     * @param Date $date
     * @param int $commentNum
     * @param user $writtenBy
     * @param bool $active
     * @return bool
     */
    private static function _writeCache($ID, $title, $text, $category, $date, $commentNum, $writtenBy, $active){
        $ID *= 1;
        if(!is_a($category, 'category'))
                return false;
        if(!is_a($date, 'Date'))
                return false;
        $commentNum *= 1;
        if(!is_a($writtenBy, 'user'))
                return false;
        $active = (bool)$active;
        $writtenBy->setLocalBufferPolicy(false);
        self::$_newsCache[$ID] = array(
            'title'     => $title,
            'text'      => $text,
            'category'  => $category,
            'date'      => $date,
            'commentNum'=> $commentNum,
            'writtenBy' => $writtenBy,
            'active'    => $active);
        return true;
    }
    /**
     * This Will generate the number of comments for an article and store it into the database
     * @return bool
     */
    public function regenerateCommentsNumCache(){
        $comments = comment::getAll($this);
        $n = count($comments);
        $result = package::$pdb->prepare("UPDATE `lttx".package::$pdbn."_news` SET `commentNum` = ? WHERE `ID` = ?");
        $result->execute(array($n, $this->_ID));
        if($result->rowCount() < 1)
            return false;
        return true;
    }
    /**
     * This increases the number of comments by one
     * @return bool
     */
    public function increaseComments(){
        $result = package::$pdb->prepare("UPDATE `lttx".package::$pdbn."_news` SET `commentNum` = `commentNum` + 1 WHERE `ID` = ?");
        $result->execute(array($n, $this->_ID));
        if($result->rowCount() < 1)
            return false;
        return true;
    }
    /**
     * This decreases the number of comments by one
     * @return bool
     */
    public function decreaseComments(){
        $result = package::$pdb->prepare("UPDATE `lttx".package::$pdbn."_news` SET `commentNum` = `commentNum` - 1 WHERE `ID` = ?");
        $result->execute(array($n, $this->_ID));
        if($result->rowCount() < 1)
            return false;
        return true;
    }
    /**
     * Returns options element of news
     * @return option
     */
    public static function getOptions(){
        if(!self::$_options)
            self::$_options = new option('news');
        return self::$_options;
    }
}
?>