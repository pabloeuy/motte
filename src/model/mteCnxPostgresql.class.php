<?php
/**
 * Class to connect to PostgreSQL databases
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
    class mteCnxPostgresql extends mteCnx implements mteCnxInterface {
     /**
      * construct()
      *
      * @access public
      * @param 	string 	$hostName
      * @param 	string 	$userName
      * @param 	string 	$password
      * @param 	string 	$baseName
      * @param 	mteLog 	$log
      * @param 	boolean	$persistent
      * @param 	boolean	$autoconnect
      * @return 	mteCnxPostgresql
      */
        function __construct($hostName, $userName, $password, $baseName,$log = NULL, $persistent = false, $autoconnect = true) {
            parent::__construct();
            $this->setHost($hostName);
            $this->setUser($userName);
            $this->setPass($password);
            $this->setBaseName($baseName);
            $this->setLog($log);
            $this->setPersistent($persistent);
            if($autoconnect){
                $this->connect();
            }
        }

     /**
      * destruct()
      *
      * @access public
      */
        function __destruct(){
        }

     /**
      * connect()
      * Connects to DB based on data stored in object attributes
      *
      * @access public
      * @return
      */
        public function connect(){
            $result = false;
            if($this->checkParams()){
                $sqlConnect =  "host=".$this->getHost()." user=".$this->getUser()." password=".$this->getPass()." dbname=".$this->getBaseName();
                if( !$this->getPersistent() ){
                    $aux = @pg_Connect($sqlConnect);
                }
                else{
                    $aux = @pg_pConnect($sqlConnect);
                }
                if(!$aux){
                    $this->addLog(mteConst::MTE_ERROR, "Error on connect to Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName() );
                }
                else{
                    $this->setIdDatabase($aux);
                    $this->addLog(mteConst::MTE_NOTICE, "Success - Connect to Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName() );
                    $result = true;
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
     * Disconnect from DB
     *
     * @access public
     * @return bool
     */
        public function disconnect(){
            $actualState = true;
            if(!(@pg_Close( $this->getIdDatabase()))){
                $actualState = false;//Error on closing
                $this->addLog(mteConst::MTE_ERROR,pg_last_error());
            }
            else{
                $this->addLog(mteConst::MTE_NOTICE, "Success - Disconnect from Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName());
            }
            return $actualState;
        }

     /**
      * showTables()
      * Returns an array with DB table names or false in case of error
      *
      * @access public
      * @return array o boolean
      */
        public function showTables(){
            $sql =    "select tablename,'T' from pg_tables where tablename not like 'pg\_%' ".
                    "and schemaname  not in ( 'pg_catalog','information_schema')	".
                    "union select viewname,'V' from pg_views where viewname not like 'pg\_%'  ".
                    "and schemaname  not in ( 'pg_catalog','information_schema')";
            return $this->executeSql($sql);
        }

     /**
      * executeSql()
      * Execute SQL sentence with and returns data on an array or fals in case of error
      *
      * @access public
      * @param 	string 	$sql
      * @return array or boolean
      */
        public function executeSql($sql = ''){
            if( $query = pg_query( $this->getIdDatabase(), $sql)){
                $result=array();
                while ($row = pg_fetch_assoc($query)){
                    $result[] = $row;
                }
                $this->addLog(mteConst::MTE_NOTICE, "Success on execute: ".$sql." on Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName());
            }
            else{
                $this->addLog(mteConst::MTE_ERROR,"Error on execute: $sql on Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName(), pg_last_error());//error query
                $result = false;
            }
            return $result;
        }

     /**
      * executeSqlLimit()
      * Executes SQL sentence with Filters and limit clauses
      *
      * @access 	public
      * @param 	string 	$sql
      * @param	integer	$nRows
      * @param	integer	$offset
      * @return array or boolean
      */
        public function executeSqlLimit($sql = '', $nRows = -1 , $offset = -1){
            $offsetStr = '';
            if ($offset >= 0){
                $offsetStr = " OFFSET $offset";
            }
            $limitStr = '';
            if ($nRows >= 0){
                $limitStr = " LIMIT $nRows";
            }
            return $this->executeSql($sql.$limitStr.$offsetStr);
        }
		  
		  public function getSqlResource($sql = ''){
            $query = pg_query($this->getIdDatabase(), $sql);
				if($query && !is_resource($query)){
					$this->addLog( mteConst::MTE_NOTICE, "Excecution of $sql was succesful, but it didn't return a resource; it's not recomended to use getSqlQuery() in this case. Use executeSql() instead.");
					}
            if ($query === false){
                // Log
               $this->addLog(mteConst::MTE_ERROR, "Error on execute: $sql on Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName(), pg_last_error()); //error query
            }
            else{
		         $this->addLog( mteConst::MTE_NOTICE, "Success on execute: $sql on Database PostgreSQL ".$this->getUser()."@".$this->getHost()."/".$this->getBaseName() );
            }
            return $query;
        }
		  
		  public function getSqlResourceLimit($sql = '', $nRows = -1 , $offset = -1){
            $offsetStr = '';
            if ($offset >= 0){
                $offsetStr = " OFFSET $offset";
            }
            $limitStr = '';
            if ($nRows >= 0){
                $limitStr = " LIMIT $nRows";
            }
            return $this->getSqlResource($sql.$limitStr.$offsetStr);
        }
		  
		  static function fetchRow($query, $row = ''){
		  	if($row !== ''){
		  		if(($row >= 0) && ($row < pg_num_rows($query))){
		  			pg_result_seek($query, $row);
					$record = pg_fetch_assoc($query);
					pg_result_seek($query, $row); //pg_fetch_assoc advances one position, we don't want it
		  		}
				else{
					$record = false;
				}
			}
			else{
				$record = pg_result_seek($query);
			}
			return $record;
		  }
		  
		  static function getRecordCount($query){
		  	return pg_num_rows($query);
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
            if (is_array($strings)){
                $result = implode('||',$strings);
            }
            return($result);
        }

     /**
      * describeTable()
      * Describes a DB table's structure
      *
      * @access public
      * @param 	string 	$tableName
      * @return array or boolean
      */
        public function describeTable($tableName = ''){
            return $this->executeSql("SELECT * FROM information_schema.columns WHERE table_name ='$tableName'");
        }

     /**
      * Returns field names from a DB table
      *
      * @access public
      * @param 	string 	$tableName
      * @return array
      */
        public function getFieldsName($tableName = ''){
            $fields = $this->describeTable($tableName);
            $return = array();
            if (is_array($fields)){
                foreach($fields as $row){
                    $return[] = $row['column_name'];
                }
            }
            return $return;
        }

     /**
      * Returns key field names from a DB table
      *
      * @access public
      * @param 	string 	$tableName
      * @return  array
      */
        public function getFieldsKeyName($tableName = ''){
            $fields = $this->executeSql("SELECT table_key.column_name FROM information_schema.table_constraints AS table_cons INNER JOIN information_schema.key_column_usage as table_key on table_key.constraint_name=table_cons.constraint_name where table_cons.table_name='".$tableName."' AND table_cons.constraint_type='PRIMARY KEY'");
            if($fields){
                $result=array();
                for($i=0;$i<count($fields);$i++){
                    $result[$i]=$fields[$i]['column_name'];
                }
            }
            else{
                $result=false;
            }
            return $result;
        }
    }
?>