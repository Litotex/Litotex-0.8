<?php
class Date{
    private $_timestamp = false;
    public function  __construct($unixTime = false) {
        if(!$unixTime)
            $unixTime = time();
        $this->_timestamp = $unixTime;
        return;
    }
    public function formatDate($format = 'd.m.Y H:i'){
        return date($format, $this->_timestamp);
    }
}
