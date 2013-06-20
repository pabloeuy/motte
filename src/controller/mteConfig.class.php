<?php
/**
 * Motte class for managing App. database and logs configurations
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mteConfig {

        /**
         *
         * @access private
         * @var string
         */
        private $_tplDir;

        /**
         *
         * @access private
         * @var string
         */
        private $_lang;

        /**
         *
         * @access private
         * @var string
         */
        private $_charset;

        /**
         *
         * @access private
         * @var string
         */
        private $_dataBaseDriver;

        /**
         *
         * @access private
         * @var string
         */
        private $_hostName;

        /**
         *
         *
         * @access private
         * @var string
         */
        private $_userName;

        /**
         *
         * @access private
         * @var string
         */
        private $_password;

        /**
         *
         * @access private
         * @var string
         */
        private $_baseName;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_persistent;

        /**
         *
         * @access private
         * @var mteCnx
         */
        private $_cnx;

        /**
         * @access private
         * @var array
         */
        private $_logs;

        /**
         * @access private
         * @var integer
         */
        private $_numRows;

        /**
         *
         * @access private
         * @var string
         */
        private $_requestMethod;

        /**
         * Constructor
         *
         * @access public
         * @return mteConfig
         */
        public function __construct() {
            //Initialize
            $this->setCnx(NULL);
            // Uses Motte default connection values
            if (defined('MTE_DB_DRIVER') && defined('MTE_DB_HOST') &&
                defined('MTE_DB_USER') && defined('MTE_DB_PASS') && defined('MTE_DB_NAME')){
                    $this->setDBDriver(MTE_DB_DRIVER);
                    $this->setDBHost(MTE_DB_HOST);
                    $this->setDBUser(MTE_DB_USER);
                    $this->setDBPass(MTE_DB_PASS);
                    $this->setDBName(MTE_DB_NAME);
                    $this->setDBPersistent(MTE_DB_PERSISTENT);
            }
            if (!defined('MTE_GRID_ROWS')){
                define('MTE_GRID_ROWS', 20);
            }
            $this->setNumRows(MTE_GRID_ROWS);
            $this->setTypeView();
            $this->setRequestMethod();
            $this->setCharset(MTE_SYSTEM_CHARSET);
            $this->setLanguage(MTE_LANG);

            // Added by Perro for Login Block
            if(!defined('TEMPLATES_DIR')){
                define('TEMPLATES_DIR', MTE_TEMPLATE);
            }
            $this->setTemplateDir(TEMPLATES_DIR.'/'.CUSTOM_TEMPLATE);

            // Define Log Objects
            $this->setLogs(MTE_LOG_LEVEL, MTE_LOG_SUFFIX_DATE, MTE_LOG_SUFFIX_IP);
            $this->setLogState(mteConst::MTE_LOG_SQL, MTE_LOG_STATE);
            $this->setLogState(mteConst::MTE_LOG_SYS, MTE_LOG_STATE);
        }

        /**
         * Destructor
         *
         * @access public
         */
        function __destruct(){
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                        S Y S T E M
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param string $type
         */
        function setTypeView($type = ''){
            $this->_typeView = ($type == ''?'HTML':$type);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getTypeView(){
            return $this->_typeView;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        function setRequestMethod($value = ''){
            $this->_requestMethod = ($value == ''?'POST':$value);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getRequestMethod(){
            return $this->_requestMethod;
        }

        /**
         *
         *
         * @access public
         * @param string $dir
         */
        function setTemplateDir($dir = ''){
            $this->_tplDir = $dir;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getTemplateDir(){
            return $this->_tplDir;
        }

        /**
         *
         *
         * @access public
         * @param string $lang
         */
        function setLanguage($lang = ''){
            $this->_lang = $lang;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getLanguage(){
            return $this->_lang;
        }

        /**
         *
         * @access public
         * @param string $charset
         */
        function setCharset($charset = ''){
            $this->_charset = $charset;
        }

        /**
         *
         * @access public
         * @return string
         */
        function getCharset(){
            return $this->_charset;
        }

        /**
         *
         * @access public
         * @param string $lang
         */
        function setNumRows($nro){
            $this->_numRows = $nro;
        }

        /**
         *
         * @access public
         * @return string
         */
        function getNumRows(){
            return $this->_numRows;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *            C O N N E C T I O N   T O   D A T A B A S E
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param string $driver
         */
        public function setDBDriver($driver = ''){
            $this->_dataBaseDriver = $driver;
        }

        /**
         *
         *
         * @access public
         * @param string $hostname
         */
        public function setDBHost($hostName = ''){
            $this->_hostName = $hostName;
        }

        /**
         *
         *
         * @access public
         * @param string $userName
         */
        public function setDBUser($userName = ''){
            $this->_userName = $userName;
        }

        /**
         *
         *
         * @access public
         * @param string $password
         */
        public function setDBPass($password = ''){
            $this->_password = $password;
        }

        /**
         *
         *
         * @access public
         * @param string $baseName
         */
        public function setDBName($baseName = ''){
            $this->_baseName = $baseName;
        }

        /**
         *
         *
         * @access public
         * @param boolean #persistent
         */
        public function setDBPersistent($persistent = false){
            $this->_persistent = $persistent;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBDriver(){
            return $this->_dataBaseDriver;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBHost(){
            return $this->_hostName;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBUser(){
            return $this->_userName;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBPass(){
            return $this->_password;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBName(){
            return $this->_baseName;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getDBPersistent(){
            return $this->_persistent;
        }

        /**
         *
         *
         * @access public
         * @param mteCnx $Cnx
         */
        public function setCnx($Cnx){
            $this->_cnx = $Cnx;
        }

        /**
         *
         *
         * @access public
         * @return mteCnx
         */
        public function getCnx(){
            return $this->_cnx;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *             L O G   M A N A G E M E N T   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */
        public function setLogs($type, $suffixDate = false, $suffixIP = false){
            $this->_logs = mteLog::log($type, $suffixDate, $suffixIP);
        }

        public function setlogFile($type,$file){
            if ($this->_logs[strToUpper($type)] instanceof mteLog){
                $this->_logs[strToUpper($type)]->setLogFile($file);
            }
        }

        public function getlogFile($type){
            if ($this->_logs[strToUpper($type)] instanceof mteLog){
                return $this->_logs[strToUpper($type)]->getLogFile();
            }
        }

        public function getLog($type){
            return $this->_logs[strToUpper($type)];
        }

        public function setLogState($type,$state){
            if ( $this->_logs[strToUpper($type)] instanceof mteLog){
                $this->_logs[strToUpper($type)]->setLogState($state);
            }
        }
    }
?>