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

class Math{
	static private function _doOperation(&$operation, &$buffer){
		$return = '';
		if($operation !== false){
			if($operation == '^'){
				$return = pow($buffer[0], $buffer[1]);
				$buffer = array('','');
			} else if($operation == 'r'){
				$return .= pow($buffer[1], 1/$buffer[0]);
				$buffer = array('','');
			}
			$operation = false;
		}
		return $return;
	}
	static public function calculateString($str){
		$str = str_replace(' ', '', $str);
		$str = str_replace('[', '(', $str);
		$str = str_replace(']', ')', $str);
		$newStr = '';
		$strChar = array();
		$openCounter = 0;
		$calcStr = '';
		if(preg_match("/[\(\)]/", $str)){
			$strChar = str_split($str);
			foreach($strChar as $char){
				if(!($char == '(' || $char == ')')){
					if($openCounter == 0){
						$newStr .= $char;
					} else {
						$calcStr .= $char;
					}
				}else if($char == '('){
					if($openCounter > 0){
						$calcStr .= '(';
					}
					$openCounter++;
				}else if($char == ')'){
					$openCounter--;
					if($openCounter == 0){
						$newStr .= self::calculateString($calcStr);
					}else{
						$calcStr .= ')';
					}
				}
			}
		} else {
			$newStr = $str;
		}
		$power = array();
		$root = array();
		$fullReplacedStr = '';
		$buffer = array('','');
		$operation = false;
		if(preg_match('/[\^Rr]/', $newStr)){
			$strChar = str_split($newStr);
			$charCount = count($strChar);
			for($i = 0; $i < $charCount; $i++){
				if($strChar[$i] == (string)($strChar[$i]*1) || $strChar[$i] == '.'){
					if(!$operation){
						$buffer[0] .= $strChar[$i];
					}else{
						$buffer[1] .= $strChar[$i];
					}
				}else if ($strChar[$i] == '^'){
					$operation = '^';
				}else if($strChar[$i] == 'r' || $strChar[$i] == 'R'){
					$operation = 'r';
				}else if($operation != false){
					$fullReplacedStr .= self::_doOperation($operation, $buffer);
					$fullReplacedStr .= $strChar[$i];
					$buffer = array('','');
				} else {
					$fullReplacedStr .= $buffer[0];
					$fullReplacedStr .= $buffer[1];
					$fullReplacedStr .= $strChar[$i];
					$buffer = array('','');
				}
			}
			if($operation !== false){
				$fullReplacedStr .= self::_doOperation($operation, $buffer);
			}
			$fullReplacedStr .= $buffer[0];
			$fullReplacedStr .= $buffer[1];
		} else {
			$fullReplacedStr = $newStr;
		}
		$math = create_function('', 'return ' . $fullReplacedStr . ';');
		return $math();
	}
	public static function verifyFormula($formula){
		if(!preg_match('/^[0-9\+\-\*\/\^xX\(\)\[\]Rr\% ]*$/', $formula))
			return false;
		return true;
	}
	public static function replaceX($formula, $x){
		$formula = str_ireplace('xx', 'x*x', $formula);
		$formula = str_ireplace('x', $x, $formula);
		return $formula;
	}
}