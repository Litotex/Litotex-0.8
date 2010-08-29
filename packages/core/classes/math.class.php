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
class math{
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