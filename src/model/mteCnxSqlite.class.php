<?php

/**
 * Class to connect to SQLite Databases
 *
 * @filesource
 * @package    motte
 * @subpackage model
 * @version    1.0
 * @license    http://opensource.org/licenses/gpl-license.php GPL - GNU Public license
 * @author     Pedro Gauna (pgauna@gmail.com) /
 *             Carlos Gagliardi (carlosgag@gmail.com) /
 *             Braulio Rios (braulioriosf@gmail.com) /
 *             Pablo Erartes (pabloeuy@gmail.com) /
 *             GBoksar/Perro (gustavo@boksar.info)
 * @link       http://motte.codigolibre.net Motte Website
 */
    class mteCnxSqlite extends mteCnx implements mteCnxInterface {

     /**
     *  mode
     *
     * @access 	protected
     * @static
     * @var int
     */
        protected $mode;

     /**
     * errorMessage
     *
     * @access 	protected
     * @static
     * @var string
     */
        protected $errorMessage;

     /**
      * construct()
      *
      * @access	public
      * @param	string $mode
      * @param	boolean $persistent
      * @param string $baseName
      * @param string $log
      * @return mteCnxSqlite
      */
        function __construct($baseName = '', $errorMessage = '', $mode = 0666, $log = NULL, $persistent = false, $autoconnect = true) {
            parent::__construct();
            $this->setMode($mode);
            $this->setErrorMessage($errorMessage);
            $this->setBaseName($baseName);
            $this->setLog($log);
            $this->setPersistent($persistent);
            if($autoconnect){
                $this->connect();
            }
        }

     /**
      * __destruct()
      *
      * @access public
      */
        function __destruct(){
        }

     /**
      * setMode()
      *
      * @access	public
      * @param	int
      * @return	void
      */
        public function setMode($mode=''){
            $this->mode=$mode;
        }

     /**
      * getMode()
      *
      * @access	public
      * @return	int
      */
        public function getMode(){
            return $this->mode;
        }

     /**
      * setErrorMessage()
      *
      * @access	public
      * @param	string
      * @return	void
      */
        public function setErrorMessage($errorMessage=''){
            $this->errorMessage = $errorMessage;
        }

     /**
      * getErrorMessage()
      *
      * @access	public
      * @return	string
      */
        public function getErrorMessage(){
            return $this->errorMessage;
        }

     /**
      * connect()
      *
      * @access public
      * @param
      * @return
      */
        public function connect(){
            $result = false;
            if(is_string($this->getBaseName())){
                if( !$this->getPersistent()){
                    $aux = @sqlite_open($this->getBaseName(),$this->getMode(),$this->errorMessage);
                }
                else{
                    $aux = @sqlite_popen($this->getBaseName(),$this->getMode(),$this->errorMessage);
                }
                if($this->getBaseName()!=':memory:'){
                    if(!file_exists($this->getBaseName())){
                        $this->addLog(mteConst::MTE_ERROR,"Don't exist database file");
                    }
                }

                if(!$aux){
                    $this->addLog(mteConst::MTE_ERROR,$this->errorMessage);
                }
                else{
                    $this->addLog( mteConst::MTE_NOTICE, "Success - Connect to Database SQLite ".$this->getBaseName() );
                    $result = true;
                    $this->setIdDatabase($aux);
                }
            }
            else{
                $this->addLog(mteConst::MTE_ERROR,"Wrong connection parameters");
            }
            // return
            return $result;
        }

     /**
      * disconnect()
      *
      * @access public
      * @param
      * @return bool
      */
        public function disconnect(){
            $actualState = true;
            //sqlite_close($this->getIdDatabase());
            //TODO not working!
            if(!(@sqlite_close())){
                $actualState = false; // Error on closing
                $error = sqlite_last_error($this->getIdDatabase());
                $this->addLog(mteConst::MTE_ERROR,sqlite_error_string($error));
            }
            else{
                $this->addLog( mteConst::MTE_NOTICE, "Success - Disconnect from Database SQLITE ".$this->getBaseName());
            }
            return $actualState;
        }

     /**
      * showTables()
      * Return names of the tables for the defined database
      *
      * @access public
      * @param
      * @return array
      */
        public function showTables(){
			$nomTables = $this->executeSql("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
			$result = array();
			if (isset($nomTables)){
            	foreach($nomTables as $key => $value){
                	$result[]=$value['name'];
            	}
			}
			return $result;
        }
		
	 /**
      * executeSql()
      *
      * @access public
      * @param string $sql
      * @return array or boolean
      */
        public function executeSql($sql = ''){
            $query = @sqlite_query($this->getIdDatabase(),$sql);
            if($query){
                $this->addLog( mteConst::MTE_NOTICE, "Success on execute: ".$sql." on Database SQLITE ".$this->getBaseName());
                while ($row = sqlite_fetch_array($query)){
                    $result[] = $row;
                }
            }
            else{
                $this->addLog( mteConst::MTE_ERROR,"Error on execute: ".$sql." on Database SQLITE ".$this->getBaseName());
                $result = false;
            }
            return $result;
        }

     /**
      * executeSqlLimit()
      *
      * @access public
      * @param string $sql
      * @param int $nRows
      * @param int $offset
      * @return array or boolean
      */
        public function executeSqlLimit($sql = '', $nRows = -1 , $offset = -1){
            $offsetStr = '';
            if ($offset >= 0){
                $offsetStr = "$offset, ";
            }
            if ($nRows < 0){
                $nRows = '18446744073709551615';
            }
            return $this->executeSql($sql." LIMIT ".$offsetStr.$nRows);
        }
		  
		public function getSqlResource($sql = ''){
            $query = @sqlite_query($this->getIdDatabase(), $sql);
				if($query && !is_resource($query)){
					$this->addLog( mteConst::MTE_NOTICE, "Excecution of $sql was succesful, but it didn't return a resource; it's not recomended to use getSqlQuery() in this case. Use executeSql() instead.");
					}
            if ($query === false){
                // Log
               $this->addLog(mteConst::MTE_ERROR, "Error on execute: $sql on Database SQLITE ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName()); //error query
            }
            else{
		         $this->addLog( mteConst::MTE_NOTICE, "Success on execute: $sql on Database SQLITE ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName());
            }
            return $query;
        }
		  
		  public function getSqlResourceLimit($sql = '', $nRows = -1 , $offset = -1){
            $offsetStr = '';
            if ($offset >= 0){
                $offsetStr = "$offset, ";
            }
            if ($nRows < 0){
                $nRows = '18446744073709551615';
            }
            return $this->getSqlResource($sql." LIMIT ".$offsetStr.$nRows);
        }
		  
		  static function fetchRow($query, $row = ''){
		  	if($row !== ''){
		  		if(($row >= 0) && ($row < sqlite_num_rows($query))){
		  			sqlite_seek($query, $row);
					$record = sqlite_fetch_array($query);
					sqlite_seek($query, $row); //sqlite_fetch_array advances one position, we don't want it
		  		}
				else{
					$record = false;
				}
			}
			else{
				$record = sqlite_fetch_array($query);
			}
			return $record;
		  }
		  
		  static function getRecordCount($query){
		  	return sqlite_num_rows($query);
		  }

     /**
      * getConcat()
      *
      * @access public
      * @param array $strings
      * @return string
      */
        public function getConcat($strings = ''){
            $result = '';
            if(is_array($strings)){
                $result = 'CONCAT('.implode(',',$strings).')';
            }
            return($result);
        }

     /**
      * decribeTable()
      *
      * @access public
      * @param string $tableName
      * @return array
      */
        public function describeTable($tableName = ''){
            return $this->executeSql("PRAGMA table_info(".$tableName.")");
        }

     /**
      * getFieldsName()
      *
      * @access public
      * @param string $tableName
      * @return array
      */
        public function getFieldsName($tableName = ''){
            $fields = $this->describeTable($tableName);
            $return = array();
            if (is_array($fields)){
                foreach($fields as $row){
                    $return[] = $row['name'];
                }
            }
            return $return;
        }

     /**
     * getFieldsKeyName()
     *
     * @access public
     * @param string $tableName
     * @return array
     */
        public function getFieldsKeyName($tableName = ''){
            $fields = $this->describeTable($tableName);
            $return = array();
            if (is_array($fields)){
                foreach($fields as $row){
                    if ($row['pk'] == 1){
                        $return[] = $row['name'];
                    }
                }
            }
            return $return;
        }
    }
?>