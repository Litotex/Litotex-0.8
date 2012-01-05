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
class Date {
    
    /**
     * UNIX timestamp
     * @var int
     */
    private $_timestamp = false;

    /**
     * This will create a new object with the given UNIX timestamp
     * @param   int     $unixTime
     */
    public function  __construct($unixTime = false) {
        if(!$unixTime)
            $unixTime = time();
        $this->_timestamp = $unixTime;
        return;
    }

    /**
     * This will fomat the date with typical date syntax
     * @param   string  $format
     * @return  string
     */
    public function formatDate($format = 'd.m.Y H:i'){
        return date($format, $this->_timestamp);
    }

    /**
     * This will get the date in db syntax
     * @return <type>
     */
    public function getDbDate(){
    	return str_replace("'", '', package::$db->DBDate($this->_timestamp));
    }

    /**
     * This will get the timestamp in db syntax
     * @return <type>
     */
    public function getDbTime(){
    	return str_replace("'", '', package::$db->DBTimeStamp($this->_timestamp));
    }
    
    public static function fromDbDate($date){
    	return Date(package::$db->UnixDate($date));
    }

    public static function fromDbTime($time){
    	return Date(package::$db->UnixTimeStamp($time));
    }
}
