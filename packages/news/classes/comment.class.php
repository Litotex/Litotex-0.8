<?php
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
require_once('news.class.php');
require_once('category.class.php');
class comment {
    /**
     * Saves wheather or not this object is initialized
     * @var bool
     */
    private $_initialized = false;
    /**
     * Title of the comment
     * @var string
     */
    private $_title;
    /**
     * Command text
     * @var string
     */
    private $_text;
    /**
     * Date of writing
     * @var Date
     */
    private $_date;
    /**
     * Written by
     * @var user
     */
    private $_writer;
    /**
     * ID of comment
     * @var int
     */
    private $_ID;
    /**
     * News object
     * @var news
     */
    private $_news;
    /**
     * IP of writer
     * @var string
     */
    private $_IP;
    /**
     * Cache news saved in
     * @var array
     */
    private static $_newsCache = array();
    /**
     * Cache comments are saved in
     * @var array
     */
    private static $_commentsCache = array();
    /**
     * Options saved in options table
     * @var option
     */
    private static $_options = false;
    /**
     * Initialize the comment object
     * @param int $ID
     * @return void
     */
    public function  __construct($ID) {
        if(!$this->_get($ID)){
            throw new Exception('Comment item ' . $ID . ' could not be found');
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
    /**
     * Returns the title
     * @return string
     */
    public function  __toString() {
        return $this->getTitle();
    }
    /**
     * Returns the title
     * @return string
     */
    public function getTitle() {
        if(!$this->_initialized)
            return false;
        return $this->_title;
    }
    /**
     * Returns the comment body text
     * @return string
     */
    public function getText() {
        if(!$this->_initialized)
            return false;
        return $this->_text;
    }
    /**
     * Returns the Date object
     * @return Date
     */
    public function getDate() {
        if(!$this->_initialized)
            return false;
        return $this->_date;
    }
    /**
     * Formated date
     * @return string
     */
    public function getFormatedDate() {
        if(!$this->_initialized)
            return false;
        return $this->_date->formatDate();
    }
    /**
     * Returns the writer
     * @return user
     */
    public function getWriter() {
        if(!$this->_initialized)
            return false;
        return $this->_writer;
    }
    /**
     * Returns the user name of the writer
     * @return string
     */
    public function getWriterName() {
        if(!$this->_initialized)
            return false;
        return $this->_writer->getUsername();
    }
    /**
     * Returns the id of the comment
     * @return int
     */
    public function getID() {
        if(!$this->_initialized)
            return false;
        return $this->_ID;
    }
    /**
     * Returns the IP the writer used
     * @return string
     */
    public function getIP(){
        if(!$this->_initialized)
            return false;
        return $this->_IP;
    }
    /**
     * Returns the news object
     * @return string
     */
    public function getNews() {
        if(!$this->_initialized)
            return false;
        return $this->_news;
    }
    /**
     * Returns the news title
     * @return string
     */
    public function getNewsTitle() {
        if(!$this->_initialized)
            return false;
        return $this->_news->getTitle();
    }
    /**
     * Removes the comment
     * @return bool
     */
    public function delete() {
        if(!$this->_initialized)
            return false;
    }
    /**
     * This will create a new comment and return it's object
     * @param string $title
     * @param string $text
     * @param news $news
     * @param user $writer
     * @return bool on failure | comment
     */
    public static function publish($title, $text, $news, $writer) {
        if(!is_a($news, 'news'))
                return false;
        if(!is_a($writer, 'user'))
                return false;
        $result = package::$db->Execute("INSERT INTO `lttx_news_comments` (`title`, `text`, `date`, `news`, `writer`, `IP`) VALUES (?, ?, " . ", ?, ?, ?", array($title, $text, $news->getID(), $writer->getID(), session::getIPAdress()));
        $news->increaseComments();
        return (!$result || package::$db->Affected_Rows() <= 0)?false:new comment(package::$db->Insert_ID());
    }
    /**
     * This will return all the comments sorted by news
     * @param news $news news to fetch from
     * @return array
     */
    public static function getAll($news = false, $page = 1, $offset = false) {
        if($news && !is_a($news, 'news'))
                return false;
        if(!self::$_options)
            self::$_options = new option('news');
        $return = array();
        if($offset === false)
            $offset = self::$_options->get('commentsPerSite');
        $offset = (int)$offset;
        $start = ($page-1) * $offset;
        if($page === 0){
            $start = 0;
            $offset = -1;
        }
        if($news)
            $result = package::$db->SelectLimit("SELECT `ID`, `title`, `text`, `date`, `news`, `writer`, `IP` FROM `lttx_news_comments` WHERE `news` = ? ORDER BY `date` DESC", $offset, $start, array($news->getID()));
        else
            $result = package::$db->SelectLimit("SELECT `ID`, `title`, `text`, `date`, `news`, `writer`, `IP` FROM `lttx_news_comments` ORDER BY `date` DESC", $offset, $start);
        if(!$result)
            return false;
        while(!$result->EOF){
            self::_writeCache($result->fields[0], $result->fields[1], $result->fields[2], new Date(package::$db->UnixTimeStamp($result->fields[3])), new news($result->fields[4]), new user($result->fields[5]), $result->fields[6]);
            $return[] = new comment($result->fields[0]);
            $result->MoveNext();
        }
        return $return;
    }
    public static function getNumber($news = false){
        if($news && !is_a($news, 'news'))
                return false;
        if($news)
            $result = package::$db->Execute("SELECT COUNT(`ID`) FROM `lttx_news_comments` WHERE `news` = ?", array($news->getID()));
        else
            $result = package::$db->Execute("SELECT COUNT(`ID`) FROM `lttx_news_comments`");
        if(!$result)
            return false;
        return $result->fields[0]*1;
    }
    private function _get($ID) {
        if($this->_getCommentCached($ID))
                return true;
        $ID = (int)$ID;
        $result = package::$db->Execute("SELECT `ID`, `title`, `text`, `date`, `news`, `writer`, `IP` FROM `lttx_news_comments` WHERE `ID` = ?", array($ID));
        if(!$result || !$result->fields[0])
            return false;
        $this->_ID = $result->fields[0];
        $this->_title = $result->fields[1];
        $this->_text = $result->fields[2];
        $this->_date = new Date(package::$db->UnixTimeStamp($result->fields[3]));
        if(($this->_news = $this->_getNewsCached($result->fields[4])) === false){
            $this->_news = new news($result->fields[4]);
            $this->_writeNewsCache($this->_news);
        }
        $this->_writer = new user($result->fields[5]);
        $this->_writer->setLocalBufferPolicy(false);
        $this->_IP = $result->fields[6];
        $this->_writeCache($this->_ID, $this->_title, $this->_text, $this->_date, $this->_news, $this->_writer, $this->_IP);
        return true;
    }
    private function _getNewsCached($ID) {
        return (isset(self::$_newsCache[$ID]))?self::$_newsCache[$ID]:false;
    }
    private function _getCommentCached($ID){
        if(!isset(self::$_commentsCache[$ID]))
            return false;
        $this->_title = self::$_commentsCache[$ID]['title'];
        $this->_text = self::$_commentsCache[$ID]['text'];
        $this->_date = self::$_commentsCache[$ID]['date'];
        $this->_news = self::$_commentsCache[$ID]['news'];
        $this->_writer = self::$_commentsCache[$ID]['writer'];
        $this->_IP = self::$_commentsCache[$ID]['IP'];
        $this->_ID = $ID;
    }
    private function _writeNewsCache($news) {
        if(!is_a($news, 'news'))
                return false;
        self::$_newsCache[$news->getID()] = $news;
        return true;
    }
    private static function _writeCache($ID, $title, $text, $date, $news, $writer, $IP){
        if(!is_a($date, 'Date'))
                return false;
        if(!is_a($news, 'news'))
                return false;
        if(!is_a($writer, 'user'))
                return false;
        $writer->setLocalBufferPolicy(false);
        self::$_commentsCache[(int)$ID] = array(
            'title'     =>  $title,
            'text'      =>  $text,
            'date'      =>  $date,
            'news'      =>  $news,
            'writer'    =>  $writer,
            'IP'        =>  $IP
        );
        return true;
    }
}