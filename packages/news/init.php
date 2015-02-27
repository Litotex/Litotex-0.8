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

require_once('classes/category.class.php');
require_once('classes/news.class.php');
require_once('classes/comment.class.php');

class package_news extends Package {

    protected $_packageName = 'news';
    protected $_availableActions = array('main', 'showComments', 'comment_submit');

    private static function _newsPageExists($page, $category = false, $newsPerSite = false) {
        if ($category !== false && !is_a($category, 'category'))
            return false;
        if ($newsPerSite === false) {
            $newsPerSite = news::getOptions()->get('newsPerSite');
        }
        $page = (int) $page;
        if ($page <= 1 && $page >= 0)
            return true;
        $n = news::getNumber($category);
        if ((int) ($n / $newsPerSite) >= $page - 1)
            return true;
        return false;
    }

    public function __action_main() {
    
        Package::addCssFile('news.css', 'news');
        $category = false;
        $page = 1;
        if (isset($_GET['page']))
            $page = (int) $_GET['page'];
        if ($page <= 0)
            $page = 1;
        if (!self::_newsPageExists($page, $category))
            $page = 1;
            
        $category=1; 
       
        $news = news::getAll($category, $page);
       
        self::$tpl->assign('news', $news);
        return true;
    }

    public static function registerHooks() {
        self::_registerHook(__CLASS__, 'getNews', 2);
        self::_registerHook(__CLASS__, 'showNewsBlock', 1);
        return true;
    }

    public static function registerTplModifications() {
        self::_registerTplModification(__CLASS__, 'showNewsBlock', 'news');
        return true;
    }

    /**
     * This will show a selected news and all comments
     * @param id from news 
     * @return bool
     */
    public function __action_showComments() {
        if (!isset($_GET['id']))
            $this->_referMain();
        $current_news_id = $_GET['id'];
        $this->_theme = 'comments.tpl';
        try {
            $newsItem = new news($current_news_id);
        } catch (Exception $error) {
            $this->_referMain();
        }
        $comments = comment::getAll($newsItem);

        if (Package::$user)
            $comment_guest = 0;
        else
            $comment_guest = 1;

        Package::$tpl->assign('news_id', $current_news_id);
        Package::$tpl->assign('comment_guest', $comment_guest);
        Package::$tpl->assign('comments', $comments);
        Package::$tpl->assign('newsItem', $newsItem);
        return true;
    }

    /**
     * This will show all news
     * @return boolean
     */
    public static function getNews($category = false, $page = 1, $newsPerSite = false) {
        if ($category && !is_a($category, 'category'))
            return false;
        if (!self::_newsPageExists($page, $category, $newsPerSite))
            return false;
        return news::getAll($category, $page, $newsPerSite);
    }

    public static function __hook_getNews(&$news, $n) {
        $news = self::getNews(false, 1, $n);
        return true;
    }

    public static function __hook_showNewsBlock($n) {
        Package::addCssFile('news.css', 'news');
        $news = self::getNews(false, 1, $n);
        self::loadLang(self::$tpl, 'news');
        self::$tpl->assign('news', $news);
        self::$tpl->display(self::getTplDir('news') . 'newsblock.tpl');

        return true;
    }

    public static function __tpl_showNewsBlock() {
        return self::__hook_showNewsBlock(-1);
    }

    /**
     * Comment a news
     * @param news id 
     * @return boolean
     */
    public function __action_comment_submit() {
        $commentAuthor = '';
        $commentAuthor_mail = '';

        if (isset($_GET['id']))
            $news_id = $_GET['id'];
        else
            throw new LitotexError('LN_NEWS_ERROR_ID');

        //check for guests or registered users 
        if (Package::$user) {
            if (!isset($_POST['new_comment'])) {
                throw new LitotexError('LN_NEWS_ERROR');
            } else {
                $saveUserID = Package::$user->getUserID();
                $commentNews = $_POST['new_comment'];
            }
        } else {
            if (!isset($_POST['author']) || !isset($_POST['author_mail'])) {
                throw new LitotexError('LN_NEWS_ERROR');
            } else {
                $saveUserID = 0;
                $commentNews = $_POST['new_comment'];
                $commentAuthor = $_POST['author'];
                $commentAuthor_mail = $_POST['author_mail'];
            }
        }
        $news = new news($news_id);
        $comments_ret = comment::publish($news, $saveUserID, $commentNews, $commentAuthor, $commentAuthor_mail);
        if ($comments_ret == true)
            throw new LitotexInfo('LN_NEWS_COMMENT_OK');
        else
            throw new LitotexError('LN_NEWS_COMMENT_ERROR');
    }

}
