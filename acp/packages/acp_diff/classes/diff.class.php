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
	Ross Scrivener http://scrivna.com
	PHP file diff implementation

	Much credit goes to...

	Paul's Simple Diff Algorithm v 0.1
	(C) Paul Butler 2007 <http://www.paulbutler.org/>
	May be used and distributed under the zlib/libpng license.

	... for the actual diff code, i changed a few things and implemented a pretty interface to it.

        Modified by Jonas Schwabe <j.s@cascaded-web.com> to match the needs of Litotex in 2011
*/
class diff {

	var $changes = array();
	var $diff = array();
	var $linepadding = null;

	function doDiff($old, $new){
            $maxlen = NULL;
		if (!is_array($old)) $old = file($old);
		if (!is_array($new)) $new = file($new);

		foreach($old as $oindex => $ovalue){
			$nkeys = array_keys($new, $ovalue);
			foreach($nkeys as $nindex){
				$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
				if($matrix[$oindex][$nindex] > $maxlen){
					$maxlen = $matrix[$oindex][$nindex];
					$omax = $oindex + 1 - $maxlen;
					$nmax = $nindex + 1 - $maxlen;
				}
			}
		}
		if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));

		return array_merge(
						$this->doDiff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
						array_slice($new, $nmax, $maxlen),
						$this->doDiff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));

	}

	function diffWrap($old, $new){
		$this->diff = $this->doDiff($old, $new);
		$this->changes = array();
		$ndiff = array();
		foreach ($this->diff as $line => $k){
			if(is_array($k)){
				if (isset($k['d'][0]) || isset($k['i'][0])){
					$this->changes[] = $line;
					$ndiff[$line] = $k;
				}
			} else {
				$ndiff[$line] = $k;
			}
		}
		$this->diff = $ndiff;
		return $this->diff;
	}

	function formatcode($code){
		$code = htmlentities($code);
		$code = str_replace(" ",'&nbsp;',$code);
		$code = str_replace("\t",'&nbsp;&nbsp;&nbsp;&nbsp;',$code);
		return $code;
	}

	function showline($line){
		if ($this->linepadding === 0){
			if (in_array($line,$this->changes)) return true;
			return false;
		}
		if(is_null($this->linepadding)) return true;

		$start = (($line - $this->linepadding) > 0) ? ($line - $this->linepadding) : 0;
		$end = ($line + $this->linepadding);
		//echo '<br />'.$line.': '.$start.': '.$end;
		$search = range($start,$end);
		//pr($search);
		foreach($search as $k){
			if (in_array($k,$this->changes)) return true;
		}
		return false;

	}

	function inline($old, $new, $linepadding=null){
		$this->linepadding = $linepadding;
                $ret = Package::$tpl->fetch(Package::getTplDir('acp_diff') . 'tableHeader.tpl');
		$count_old = 1;
		$count_new = 1;

		$insert = false;
		$delete = false;
		$truncate = false;

		$diff = $this->diffWrap($old, $new);

		foreach($diff as $line => $k){
			if ($this->showline($line)){
				$truncate = false;
				if(is_array($k)){
					foreach ($k['d'] as $val){
						$class = '';
						if (!$delete){
							$delete = true;
							$class = 'first';
							if ($insert) $class = '';
							$insert = false;
						}
						Package::$tpl->assign('oldLine', $count_old);
                                                Package::$tpl->assign('newLine', ' ');
                                                Package::$tpl->assign('code', $this->formatcode($val));
                                                Package::$tpl->assign('cssClass', 'del ' . $class);
                                                $ret .= Package::$tpl->fetch(Package::getTplDir('acp_diff').'lineElement.tpl');
						$count_old++;
					}
					foreach ($k['i'] as $val){
						$class = '';
						if (!$insert){
							$insert = true;
							$class = 'first';
							if ($delete) $class = '';
							$delete = false;
						}
						Package::$tpl->assign('oldLine', ' ');
                                                Package::$tpl->assign('newLine', $count_new);
                                                Package::$tpl->assign('code', $this->formatcode($val));
                                                Package::$tpl->assign('cssClass', 'ins ' . $class);
                                                $ret .= Package::$tpl->fetch(Package::getTplDir('acp_diff').'lineElement.tpl');
						$count_new++;
					}
				} else {
					$class = ($delete) ? 'del_end' : '';
					$class = ($insert) ? 'ins_end' : $class;
					$delete = false;
					$insert = false;
                                        Package::$tpl->assign('oldLine', $count_old);
                                        Package::$tpl->assign('newLine', $count_new);
                                        Package::$tpl->assign('code', $this->formatcode($k));
                                        Package::$tpl->assign('cssClass', $class);
                                        $ret .= Package::$tpl->fetch(Package::getTplDir('acp_diff').'lineElement.tpl');
					$count_old++;
					$count_new++;
				}
			} else {
				$class = ($delete) ? 'del_end' : '';
				$class = ($insert) ? 'ins_end' : $class;
				$delete = false;
				$insert = false;

				if (!$truncate){
					$truncate = true;
                                        Package::$tpl->assign('oldLine', '...');
                                        Package::$tpl->assign('newLine', '...');
                                        Package::$tpl->assign('code', ' ');
                                        Package::$tpl->assign('cssClass', 'truncated '.$class);
                                        $ret .= Package::$tpl->fetch(Package::getTplDir('acp_diff').'lineElement.tpl');
				}
				$count_old++;
				$count_new++;

			}
		}
                $ret .= Package::$tpl->fetch(Package::getTplDir('acp_diff').'tableFooter.tpl');
		return $ret;
	}
}
?>