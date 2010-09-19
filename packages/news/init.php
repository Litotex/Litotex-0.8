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
require_once('classes/category.class.php');
require_once('classes/news.class.php');
require_once('classes/comment.class.php');
class package_news extends package {
    protected $_packageName = 'news';
    protected $_availableActions = array('main', 'showComments');
    private static function _newsPageExists($page, $category = false, $newsPerSite = false) {
        if($category !== false && !is_a($category, 'category'))
            return false;
        if($newsPerSite === false) {
            $newsPerSite = news::getOptions()->get('newsPerSite');
        }
        $page = (int)$page;
        if($page <= 1 && $page >= 0)
            return true;
        $n = news::getNumber($category);
        if((int)($n / $newsPerSite) >= $page - 1)
            return true;
        return false;
    }
    public function __action_main() {
        $category = false;
        $page = 1;
        if(isset($_GET['page']))
            $page = (int)$_GET['page'];
        if($page <= 0)
            $page = 1;
        if(!self::_newsPageExists($page, $category))
            $page = 1;
        $categories = category::getAll();
        self::$tpl->assign('categories', $categories);
        $news = news::getAll($category, $page);
        self::$tpl->assign('news', $news);
        return true;
    }
    public static function registerHooks() {
        self::_registerHook(__CLASS__, 'getNews', 2);
        self::_registerHook(__CLASS__, 'showNewsBlock', 1);
        return true;
    }
    public static function registerTplModifications(){
    	self::_registerTplModification(__CLASS__, 'showNewsBlock');
    	return true;
    }
    public function __action_showComments() {
        if(!isset($_GET['id']))
            $this->_referMain();
        $this->_theme = 'comments.tpl';
        try {
            $newsItem = new news($_GET['id']);
        }catch (Exception $error) {
            $this->_referMain();
        }
        $comments = comment::getAll($newsItem);
        package::$tpl->assign('comments', $comments);
        package::$tpl->assign('newsItem', $newsItem);
        return true;
    }
    public static function getNews($category = false, $page = 1, $newsPerSite = false) {
        if($category && !is_a($category, 'category'))
            return false;
        if(!self::_newsPageExists($page, $category, $newsPerSite))
            return false;
        return news::getAll($category, $page, $newsPerSite);
    }
    public static function __hook_getNews(&$news, $n) {
        $news = self::getNews(false, 1, $n);
        return true;
    }
    public static function  __hook_showNewsBlock($n) {
        $news = self::getNews(false, 1, $n);
        self::loadLang($tpl, 'news');
        self::$tpl->assign('news', $news);
        self::$tpl->display(self::getTplDir('news') . 'newsblock.tpl');
        return true;
    }
	public static function  __tpl_showNewsBlock() {
        return self::__hook_showNewsBlock(2);
    }
}
