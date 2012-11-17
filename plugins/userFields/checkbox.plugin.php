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
class plugin_checkbox extends Plugin{
	public static $handlerName = 'userFields';
	public static $name = 'checkbox';
	public static $availableFunctions = array('getHTML', 'checkValid', 'setContent', 'getContent', 'validateContent');
	public static function getHTML(UserField $field, User $user){
                return '<input type="checkbox" value="'.self::getContent($user->getUserFieldData($field->getID())).'" name="userfield['.$user->getUserID().']['.$field->getID().']" />';
	}
	public static function checkValid($value){
		return true;
	}
	public static function setContent($value){
		if(!self::checkValid($value))
			return false;
		return ($value)?true:false;
	}
	public static function getContent($value){
		return ($value)?true:false;
	}
        public static function validateContent($value){
            return true;
        }
}

