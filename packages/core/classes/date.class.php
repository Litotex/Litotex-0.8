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
