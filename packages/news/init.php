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
		package::addCssFile('news.css', 'news');	
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
    	self::_registerTplModification(__CLASS__, 'showNewsBlock', 'news');
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
		package::addCssFile('news.css', 'news');	
       
		$news = self::getNews(false, 1, $n);
		
        self::loadLang(self::$tpl, 'news');
        self::$tpl->assign('news', $news);
        self::$tpl->display(self::getTplDir('news') . 'newsblock.tpl');
        
		return true;
    }
	public static function  __tpl_showNewsBlock() {
        return self::__hook_showNewsBlock(-1);
    }
}
