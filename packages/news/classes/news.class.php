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
class news {

    /**
     * Is this instance initialized?
     * @var bool
     */
    private $_initialized = false;

    /**
     * Current News ID
     * @var int
     */
    private $_news_ID = 0;

    /**
     * Current News title
     * @var string
     */
    private $_news_title = false;

    /**
     * Current news text
     * @var string
     */
    private $_news_text = false;

    /**
     * ID of category this news belongs to
     * @var int
     */
    private $_news_category_id = false;

    /**
     * Date the news has been written
     * @var Date
     */
    private $_news_date;

    /**
     * allow comments
     * @var int
     */
    private $_news_allow_comments = 0;

    /**
     * Number of comments
     * @var int
     */
    private $_news_commentNum = 0;

    /**
     * Newsauthor
     * @var User
     */
    private $_news_writtenBy = false;

    /**
     * Active flag
     * @var bool
     */
    private $_news_active = false;

    /**
     * Category title
     * @var string
     */
    private $_category_tiltle = false;

    /**
     * Category Description
     * @var string
     */
    private $_category_description = false;

    /**
     * Category Date
     * @var string
     */
    private $_category_date = false;

    /**
     * Contains preferences (auto inform etc)
     * @var Option
     */
    private static $_options = false;

    /**
     * This will hold a cache for all news loaded
     * @var array
     */
    private static $_newsCache = array();
    
    /**
     * @var User
     */
    private $_writtenBy = null;

    /**
     * This will load news context and caches data
     * @param int $id
     * @return void
     */
    public function __construct($ID) {
        //Save data to class
        if (!$this->_get($ID)) {
            throw new Exception('News item ' . $ID . ' could not be found');
            return;
        }
        if (!self::$_options)
            self::$_options = new Option('news');
        $this->_initialized = true;
        return;
    }

    /**
     * EMPTY
     * @return void
     */
    public function __destruct() {
        return;
    }

    public function __toString() {
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
    public static function publishNew($title, $text, $category, $active = true) {
        if (!is_a($category, 'category'))
            return false;
        $title = htmlspecialchars($title);
        $insert = Package::$pdb->prepare("INSERT INTO `lttx1_news` (`title`, `text`, `category`, `date`, `writtenBy`, `active`) VALUES (?, ?, ?, " . Package::$pdb->DBTimeStamp(date("Y-m-d H:m:s", time())) . ", ?, ?)");
        $insert->execute(array($title, $text, $category->getID(), (Package::$user) ? Package::$user->getID() : false, (bool) $active));
        $category->updateTimestamp();
        return ($insert->rowCount() < 1) ? false : new news(Package::$pdb->lastInsertId());
    }

    /**
     * Returns an array of all news based on category if set
     * @param category $category category to load
     * @param int $page to load (loads all if 0)
     * @param int $offset num of items to show (false = default)
     * @return array
     */
    public static function getAll($category = false, $page = 1, $offset = false) {
        //if ($category && !is_a($category, 'category'))
        //    return false;
        if (!self::$_options)
            self::$_options = new Option('news');
        $return = array();
        if ($offset === false)
            $offset = self::$_options->get('newsPerSite');
        $offset = (int) $offset;
        $start = ($page - 1) * $offset;
        if ($page === 0) {
            $start = 0;
            $offset = -1;
        }
           
        $return = array();
        if ($category) {
            $news = Package::$pdb->prepare("SELECT n.ID, n.title, n.text, n.category, date_format(n.date, '%d.%m.%Y %H:%i') as new_date , n.writtenBy, n.active,n.allow_comments, l1.title, l1.description, l1.newsLastDate FROM lttx1_news n LEFT OUTER JOIN lttx" . Package::$pdbn . "_news_categories l1 ON (n.category=l1.ID) WHERE  n.category = '".$category."' AND n.active = ? ORDER BY n.date DESC");
            $news->bindParam(':offset', $start, PDO::PARAM_INT);
            $news->bindParam(':max', $offset, PDO::PARAM_INT);
            $news->execute(array(true));
        } else {
            $news = Package::$pdb->prepare("SELECT n.ID, n.title, n.text, n.category, date_format(n.date, '%d.%m.%Y %H:%i') as new_date , n.writtenBy, n.active,n.allow_comments, l1.title, l1.description, l1.newsLastDate FROM lttx1_news n LEFT OUTER JOIN lttx" . Package::$pdbn . "_news_categories l1 ON (n.category=l1.ID) WHERE `active` = ? ORDER BY n.date DESC");
            $news->bindParam(':offset', $start, PDO::PARAM_INT);
            $news->bindParam(':max', $offset, PDO::PARAM_INT);
            $news->execute(array(true));
        }

        foreach ($news as $rows) {
            self::_writeCache($rows[0], $rows[1], $rows[2], $rows[3], $rows[4], new User($rows[5]), $rows[6], $rows[7], $rows[8], $rows[9], $rows[10]);
            $return[] = new news($rows[0]);
        }
        return $return;
    }

    /**
     * Returns current ID
     * @return int
     */
    public function getID() {
        if (!$this->_initialized)
            return false;
        return $this->_news_ID;
    }

    /**
     * This will return allow comments
     * @return category
     */
    public function getAllowComments() {
        if (!$this->_initialized)
            return false;
        return $this->_news_allow_comments;
    }

    /**
     * This will return the name of the category
     * @return string
     */
    public function getCategoryName() {
        if (!$this->_initialized)
            return false;
        return $this->_category_tiltle;
    }

    /**
     * This will return the current category ID
     * @return int
     */
    public function getCategoryID() {
        if (!$this->_initialized)
            return false;
        return $this->_news_category_id;
    }

    /**
     * This will return the current number of comments
     * @return int
     */
    public function getcommentNum() {
        if (!$this->_initialized)
            return false;
        return $this->_news_commentNum;
    }

    /**
     * This will return the date
     * @return string
     */
    public function getFormatedDate() {
        if (!$this->_initialized)
            return false;

        return $this->_news_date;
    }

    /**
     * This will return the news title
     * @return string
     */
    public function getTitle() {
        if (!$this->_initialized)
            return false;
        return $this->_news_title;
    }

    /**
     * This will return the news text
     * @return string
     */
    public function getText() {
        if (!$this->_initialized)
            return false;
        return $this->_news_text;
    }

    /**
     * This will return the user object
     * @return User
     */
    public function getAuthor() {
        if (!$this->_initialized)
            return false;
        return $this->_writtenBy;
    }

    /**
     * This will return the Authors's ID
     * @return int
     */
    public function getAuthorID() {
        if (!$this->_initialized)
            return false;
        return $this->_writtenBy->getUserID();
    }

    /**
     * This will return the Authors's username
     * @return string
     */
    public function getAuthorName() {
        if (!$this->_initialized)
            return false;
        return $this->_writtenBy->getUserName();
    }

    /**
     * This will set the active flag of this news
     * @param bool $value
     * @return bool
     */
    public function setActiveFlag($value) {
        if (!$this->_initialized)
            return false;
        $value = (bool) $value;
        $result = Package::$pdb->prepare("UPDATE `lttx1_news` SET `active` = ? WHERE `ID` = ?");
        $result->execute(array($value, $this->_news_ID));
        if ($result->rowCount() < 1)
            return false;
        $this->_news_active = $value;
        return true;
    }

    /**
     * This will get the current active flag
     * @return bool
     */
    public function isActive() {
        if (!$this->_initialized)
            return false;
        return $this->_news_active;
    }

    /**
     * This will delete the current news
     * @return bool
     */
    public function delete() {
        if (!$this->_initialized)
            return false;
        $result = Package::$pdb->prepare("DELETE FROM `lttx1_news` WHERE `ID` = ?");
        $result->execute(array($this->_news_ID));
        return ($result->rowCount() <= 0) ? false : true;
    }

    /**
     * This sends an email when a new message was written
     * @return bool
     */
    public function informMail() {
        if (!$this->_initialized)
            return false;
        if (self::$_options->get('autoInformMail')) {
            echo '<p>SEND MAIL!</p>';
        }
        return true;
    }

    /**
     * This sends a message when a new message was written
     * @return bool
     */
    public function informPM() {
        if (!$this->_initialized)
            return false;
        if (self::$_options->get('autoInformPM')) {
            echo '<p>SEND PM!</p>';
        }
        return true;
    }

    /**
     * This will set new mail info settings
     * @param bool $value
     * @return bool
     */
    public static function setGlobalAutoInformMail($value) {
        if (!self::$_options)
            self::$_options = new Option('news');
        $result = self::$_options->set('autoInformMail', (bool) $value);
        if (!$result)
            $result = self::$_options->add('autoInformMail', (bool) $value, true);
        return $result;
    }

    /**
     * This will set new PM info settings
     * @param bool $value
     * @return bool
     */
    public static function setGlobalAutoInformPM($value) {
        if (!self::$_options)
            self::$_options = new Option('news');
        $result = self::$_options->set('autoInformPM', (bool) $value);
        if (!$result)
            $result = self::$_options->add('autoInformPM', (bool) $value, true);
        return $result;
    }

    /**
     * This will load a news element (it will also check the cache and write fetched elements to the cache to avoid double database connections)
     * @param int $ID
     * @return bool
     */
    private function _get($ID) {
        if ($this->_getCache($ID))
            return true;
        $news = Package::$pdb->prepare("SELECT n.ID, n.title, n.text, n.category, date_format(n.date, '%d.%m.%Y %H:%i') as new_date , n.writtenBy, n.active,n.allow_comments, l1.title, l1.description, l1.newsLastDate FROM lttx1_news n LEFT OUTER JOIN lttx" . Package::$pdbn . "_news_categories l1 ON (n.category=l1.ID) WHERE n.ID = ?");
        $news->execute(array($ID));
        if ($news->rowCount() < 1)
            return false;

        $news = $news->fetch();
        $this->_news_ID = $news[0];
        $this->_news_title = $news[1];
        $this->_news_text = $news[2];
        $this->_news_category_id = $news[3];
        $this->_news_date = $news[4];
        $this->_news_commentNum = self::_getCommentCount($ID);
        $this->_writtenBy = new User($news[5]);
        $this->_writtenBy->setLocalBufferPolicy(false);
        $this->_news_active = $news[6];
        $this->_news_allow_comments = $news[7];
        $this->_category_tiltle = $news[8];
        $this->_category_description = $news[9];
        $this->_category_date = $news[10];

        self::_writeCache($ID, $this->_news_title, $this->_news_text, $this->_news_category_id, $this->_news_date, $this->_news_writtenBy, $this->_news_active, $this->_news_allow_comments, $this->_category_tiltle, $this->_category_description, $this->_category_date);
        return true;
    }

    /**
     * This will check if there is a matching element saved in the cache and save it into the instance
     * @param int $ID
     * @return bool
     */
    private function _getCache($ID) {
        if (!isset(self::$_newsCache[$ID]))
            return false;
        $this->_news_ID = $ID;
        $this->_news_title = self::$_newsCache[$ID]['news_title'];
        $this->_news_text = self::$_newsCache[$ID]['news_text'];
        $this->_news_category_id = self::$_newsCache[$ID]['news_category_id'];
        $this->_news_date = self::$_newsCache[$ID]['news_date'];
        $this->_news_commentNum = self::$_newsCache[$ID]['news_commentNum'];
        $this->_writtenBy = self::$_newsCache[$ID]['news_writtenBy'];
        $this->_news_active = self::$_newsCache[$ID]['news_active'];
        $this->_news_allow_comments = self::$_newsCache[$ID]['news_allow_comments'];
        $this->_category_tiltle = self::$_newsCache[$ID]['categroy_title'];
        $this->_category_description = self::$_newsCache[$ID]['categroy_description'];
        $this->_category_date = self::$_newsCache[$ID]['categroy_date'];
        return true;
    }

    /**
     * This will write a news element to the cache
     * @param type $news_id
     * @param type $news_title
     * @param type $_news_text
     * @param type $news_cat_id
     * @param type $date
     * @param type $writtenBy
     * @param type $active
     * @param type $allow_comments
     * @param type $cat_title
     * @param type $cat_description
     * @param type $cat_last_date
     * @return boolean 
     */
    private static function _writeCache($news_id, $news_title, $_news_text, $news_cat_id, $date, $writtenBy, $active, $allow_comments, $cat_title, $cat_description, $cat_last_date) {
        $news_id *= 1;
        //$commentNum *= 1;
        if (!is_a($writtenBy, 'user'))
            return false;
        $active = (bool) $active;
        $writtenBy->setLocalBufferPolicy(false);
        self::$_newsCache[$news_id] = array(
            'news_id' => $news_id,
            'news_title' => $news_title,
            'news_text' => $_news_text,
            'news_category_id' => $news_cat_id,
            'news_date' => $date,
            'news_commentNum' => self::_getCommentCount($news_id),
            'news_writtenBy' => $writtenBy,
            'news_active' => $active,
            'news_allow_comments' => $allow_comments,
            'categroy_title' => $cat_title,
            'categroy_description' => $cat_description,
            'categroy_date' => $cat_last_date);



        return true;
    }

    /**
     * Returns numbers of comments
     * @return int
     */
    private static function _getCommentCount($ID) {
        $result = Package::$pdb->prepare("SELECT COUNT(`ID`) FROM `lttx" . Package::$pdbn . "_news_comments` WHERE `news` = ? and read_allowed='1'");
        $result->execute(array($ID));
        if ($result->rowCount() < 1)
            return 0;
        $result = $result->fetch();
        return $result[0];
    }

    /**
     * Returns options element of news
     * @return Option
     */
    public static function getOptions() {
        if (!self::$_options)
            self::$_options = new Option('news');
        return self::$_options;
    }

}

?>
