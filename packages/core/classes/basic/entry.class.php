<?php
class Basis_Entry {

	protected $_aData = array();
	protected $_sTableName = 'lttx_DB_TABLENAME';
	
	private static $_aInstance = array();
	protected static $_sClassName = 'Basis_Entry';

	
    /**
     * Returns the classname of the child class extending this class
     *
     * @return string The class name
     */
    protected function getClass() {
        return get_class($this);
    }

	/**
	 * Get an Instance of the Class if in Cache
	 * @param int $iFieldId
	 * @return Basis_Entry
	 */
	static public function getInstance($iDataId = 0) {

		$sClass = self::$_sClassName;

		if(
			empty($sClass) ||
			$sClass == 'Basis_Entry'
		) {
			$sClass = get_called_class();
		}

		if(empty($sClass)) {
			$sClass = 'Basis_Entry';
		}

		if($iDataId == 0) {
			return new $sClass($iDataId, $sTable);
		}

		if(!isset(self::$_aInstance[$sClass][$iDataId])) {
			try {
				self::$_aInstance[$sClass][$iDataId] = new $sClass($iDataId);
			} catch(Exception $e) {
				throw new lttxError($e->getMessage());
			}
		}

		return self::$_aInstance[$sClass][$iDataId];
	}

	/**
	 * Get a List of all Entrys
	 * @return self
	 */
	public static function getList($sClass){

		$oTemp = new $sClass(0);

		$sSql = " SELECT * FROM `".$oTemp->_sTableName."` ";
		$aSql = array();

		$aResult = package::$db->GetAssoc($sSql, $aSql, true);

		$aBack = array();
		if(!empty($aResult)){
			foreach((array)$aResult as $aData){
				$aBack[] = new $sClass($aData['ID']);
			}
		}

		return $aBack;
	}

	/**
	 * Load the Data for the given ID
	 * @param int $iFieldId
	 */
	public function  __construct($iFieldId = 0) {
		$this->_aData['ID'] = (int)$iFieldId;
		$this->_loadData();
	}

	/**
	 * Set Data
	 * @param string $sColumn
	 * @param mixed $mValue
	 */
	public function  __set($sColumn, $mValue) {
		$this->_aData[$sColumn] = $mValue;
	}

	/**
	 * Get Data
	 * @param string $sColumn
	 * @return mixed
	 */
	public function  __get($sColumn) {
		if(isset ($this->_aData[$sColumn])){
			return $this->_aData[$sColumn];
		}
		return '';
	}

	/**
	 * Validate all Data who was set
	 * @return bol
	 */
	public function validate(){
		return true;
	}

	/**
	 * Save the Current Data
	 * @return mixed
	 */
	public function save(){

		$mValidate = $this->validate();

		if($mValidate === true){

			$sSql = "";
			$aSql = array();

			if($this->ID > 0){
				$sSql = " UPDATE `".$this->_sTableName."` SET ";
			} else {
				$sSql = " INSERT INTO `".$this->_sTableName."` SET ";
			}

			foreach((array)$this->_aData as $sColumn => $mValue ){

				if($sColumn == 'ID'){
					continue;
				}

				$sSql .= " `".$sColumn."` = ? ,";
				$aSql[] = $mValue;
			}

			$sSql = rtrim($sSql, ',');

			if($this->ID > 0){
				$sSql .= " WHERE ID = ? ";
				$aSql[] = (int)$this->ID;
			}

			package::$db->Execute($sSql, $aSql);

			return true;

		} else {
			return $mValidate;
		}

	}

	/**
	 * Delete the current Entry
	 */
	public function delete(){
		$sSql = " DELETE FROM `".$this->_sTableName."` WHERE `ID` = ?";
		$aSql = array($this->ID);

		package::$db->Execute($sSql, $aSql, true);
	}

	/**
	 * Load the Data for the Current ID
	 */
	protected function _loadData(){

		if($this->_aData['ID'] > 0){

			$sSql = " SELECT * FROM `".$this->_sTableName."` WHERE ID = ? ";
			$aSql = array($this->_aData['ID']);

			package::$db->SetFetchMode(ADODB_FETCH_ASSOC);
			$aResult = package::$db->GetRow($sSql, $aSql);
			package::$db->SetFetchMode(ADODB_FETCH_DEFAULT);

			if($aResult === false){
				throw new lttxDBError();
			} else {
				$this->_aData = $aResult;
			}

		}

	}


}


if(!function_exists('get_called_class'))
{
	function get_called_class ($i_level = 1)
	{
	    $a_debug = debug_backtrace();
		$a_called = array();
	    $a_called_function = $a_debug[$i_level]['function'];
	    for ($i = 1, $n = sizeof($a_debug); $i < $n; $i++)
	    {
	        if (in_array($a_debug[$i]['function'], array('eval')) ||
	            strpos($a_debug[$i]['function'], 'eval()') !== false) {
	            continue;
			}
	        if (in_array($a_debug[$i]['function'], array('__call', '__callStatic'))) {
	            $a_called_function = $a_debug[$i]['args'][0];
			}
	        if (in_array($a_debug[$i]['function'], array('call_user_func', 'call_user_func_array'))) {
	            $a_called['class'] = $a_debug[$i]['args'][0][0];
				$a_called['object'] = true;
			}
	        if ($a_debug[$i]['function'] == $a_called_function) {
	            $a_called = $a_debug[$i];
			}
	    }

	    if (isset($a_called['object']) && isset($a_called['class'])) {
	        return (string)$a_called['class'];
	    }

	    $i_line = (int)$a_called['line'] - 1;
	    $a_lines = explode("\n", file_get_contents($a_called['file']));
	    preg_match("#([a-zA-Z0-9_]+){$a_called['type']}{$a_called['function']}( )*\(#", $a_lines[$i_line], $a_match);
	    unset($a_debug, $a_called, $a_called_function, $i_line, $a_lines);
	    if (sizeof($a_match) > 0) {
			$s_class = (string)trim($a_match[1]);
		} else {
			$s_class = (string)$a_called['class'];
		}
		if ($s_class == 'self') {
			return get_called_class($i_level + 2);
		}
		return $s_class;
	}
}
