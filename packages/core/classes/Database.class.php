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

/* This is an extension of the PDO-Class and supports some more methods,
 * than those which are provided.
 */

class Database extends PDO {
	/*
	 * We'll count all performed Queries
	 * for profiling issues
	 */
	private $queryCount = 0;
	
	/*
	 * Creates an instance of the original PDO-Class and
	 * sets some attributes
	 */
	public function __construct($dsn, 
								$username=null, 
								$password=null, 
								$driver_options=null) {
		try {
			parent::__construct($dsn, $username, $password, $driver_options);
			parent::query("SET NAMES 'utf8'");
		} catch (PDOException $e) {
			echo('Could not connect to Dabase via PDO: '.$e->getMessage().'; Settings:'.$dsn.';'.$username.';'.$driver_options);
                        exit();
                }
		try {
			// throw exceptions instead of raise warnings
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			// use our own (extended) Statement Class
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('DBStatement', array($this)));
		} catch (PDOException $e) {
			throw new LitotexError("Could not set Attributes on PDO: " . $e->getMessage());
		}
	}
	
	/*
	 * Extending the execution methods for logging and counting
	 */
	public function exec($sql, $logIt = false) {
		$sql = str_replace('lttx1_', 'lttx' . Package::$pdbn . '_', $sql);
		$this->queryCount++;
		$retValue = parent::exec($sql);
            	if ($logIt)
                    Logger::debug($sql, LOG_DEBUG, TRUE);
		return $retValue;
	}
	public function query($sql, $logIt = false) {
                if ($logIt)
                    Logger::debug('Query: '. $sql, LOG_DEBUG);
		$this->queryCount++;
		return parent::query($sql);
	}
	public function prepare($statement, $options = array(), $logIt = false) {
		if ($logIt)	// replace all tabs and multiple whitespaces with just one space
                    Logger::debug('Prepare: '.preg_replace('/\s\s+|\t/', ' ', $statement).' Options: ' . implode(',', $options), LOG_DEBUG);
		$retValue = parent::prepare($statement, $options);
		return $retValue;
	}
	
	/* Get an associative array of results for the query
	 * This nice code is from here: http://stackoverflow.com/questions/5175357/extending-pdo-class
	 */
	public function getAssoc($sql, $params = array()) {
		try {
			$stmt = $this->prepare($sql);
			$params = is_array($params) ? $params : array($params);
			$stmt->execute($params);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new LitotexException(
				__METHOD__ . ' Exception Raised for this SQL: ' . implode($sql) .
				' Params: ' . var_export($params, true) .
				' Error_Info: ' . var_export($this->errorInfo(), true) . $e);
		}
	}
	
	/*
	 * @return	int	number of performed queries
	 */
	public function getQueryCount() {
		return $this->queryCount;
	}
}

class DBStatement extends PDOStatement {
	private $pdo;
	
	protected function __construct(Database $pdo) {
		$this->pdo = $pdo;
	}

	public function execute($params = array(), $logIt = false) {
                if ($logIt)
                   Logger::debug('DBStatement execute: '.implode(', ', $params), LOG_DEBUG);
	return parent::execute($params);
	}
}

?>
