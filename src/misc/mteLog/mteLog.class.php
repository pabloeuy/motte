<?php
/**
 * Clase para el manejo de Log's
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

    class mteLog {
        
        /**
         *  Archivo de log
         * 
         * @access private
         * @var string
         */
        private $_logFile;
        
        /**
         *  
         * 
         * @access private
         * @var string
         */
        private $_suffixDate;
        
        /**
         *  
         * 
         * @access private
         * @var string
         */
        private $_suffixIP;

        /**
         *  
         * 
         * @access private
         * @var string
         */
        private $_logType;

        /**
         *  Estado del Log
         * 
         * @access private
         * @var boolean
         */
        private $_logState;
        
        /**
         *  Estado del mail notification
         * 
         * @access private
         * @var boolean
         */
        private $_mailNotificationState;
        
        
        /**
         *  mail de notificaciones
         * 
         * @access private
         * @var string
         */
        private $_mailLog;
        
        /**
         * Constructor
         *
         * @access public
         * @param 
         * @return mteLog
         */
        public function __construct($logFile = '', $logMail = '') {
            $this->initialize();
            $this->setLogFile($logFile);
            $this->setMailNotification($logMail);
        }

       /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }
        
        /**
         * Initialize class attributes
         * @access 	public
         * @return 	void
         */
        public function initialize(){
            $this->setLogState(false);
            $this->setMailNotificationState(false);
        }
        
       /**
        * Setea LogFile
        *
        * @access public
        * @param string $logFile
        */
        public function setLogFile($file){
            $this->_logFile = $file;
        }
   
       /**
        * Setea suffixDate
        *
        * @access public
        * @param boolean $value
        */
        public function setSuffixDate($value = false){
            $this->_suffixDate = $value;
        }
   
       /**
        * Setea suffixDate
        *
        * @access public
        * @param boolean $suffixDate
        */
        public function setSuffixIP($value = false){
            $this->_suffixIP = $value;
        }
   

       /**
        * Devuelve suffixDate
        *
        * @access public
        * @param boolean $suffixDate
        */
        public function getSuffix(){
            $result = '';
            if ($this->_suffixDate){
                $result .= '_'.date("Ymd");
            }
            if ($this->_suffixIP){
                $result .= '_'.$_SERVER['REMOTE_ADDR'];
            }
            return $result; 
        }   
   
        
       /**
        * Setea Mail
        *
        * @access public
        * @param string $mailLog
        */
        public function setMailNotification($mailLog){
            $this->_mailLog = $mailLog;
        }
   
       /**
        * Setea estado de log
        *
        * @access public
        * @param string $state
        */
        public function setLogState($state){
            $this->_logState = (($state === true) || (strToUpper($state) == '0N'));
        }   
   
       /**
        * Setea estado de mail
        *
        * @access public
        * @param string $state
        */
        public function setMailNotificationState($state){
            $this->_mailNotificationState = (($state === true) || (strToUpper($state) == '0N'));
        }   
   
       /**
        * Devuelve estado de log
        *
        * @access public$_suffixIP
        * @param 
        * @return boolean
        */
        public function getLogState(){
            return $this->_logState;
        }   
   
       /**
        * Devuelve estado de mail
        *
        * @access public
        * @param 
        * @return boolean
        */
        public function getMailNotificationState(){
            return $this->_mailNotificationState;
        }   
   
       /**
         * Devuelve LogFile
         *
         * @access public
         * @param
         * @return string
         */
        public function getLogFile(){
            return $this->_logFile;
        }
        
       /**
        * Sets Mail
        *
        * @access public
        * @param 
        * @return string
        */
        public function getMailNotificationFile(){
            return $this->_mailLog;
        }

       /**
        * Sets Log Type
        *
        * @access public
        * @param string $logType
        */
        public function setLogType($logType = "UNDEFINED"){
            $this->_logType=$logType;
        }

       /**
        * Returns Log Type
        *
        * @access public
        * @param string $logType
        */
        public function getLogType(){
            return($this->_logType);
        }


       /**
        * Fabrica abstracta 
        *
        * @param string $type ALL DB SYS APP
        * @return array de logs
        */
        public static function log($type, $suffixDate = false, $suffixIP = false){
            $type = strToUpper($type);
            if ($type == 'ALL'){
                $type = mteConst::MTE_LOG_SQL.mteConst::MTE_LOG_APP.mteConst::MTE_LOG_SYS.mteConst::MTE_LOG_MAIL;
            }

            $logs = array();
            if (!(strpos($type,mteConst::MTE_LOG_SQL) === false)){
                $logs[mteConst::MTE_LOG_SQL] = new mteLogSql('', '', $suffixDate, $suffixIP);
            }
            if (!(strpos($type,mteConst::MTE_LOG_APP) === false)){
                $logs[mteConst::MTE_LOG_APP] = new mteLogApp('', '', $suffixDate, $suffixIP);	
            }
            if (!(strpos($type,mteConst::MTE_LOG_SYS) === false)){
                $logs[mteConst::MTE_LOG_SYS] = new mteLogSys('', '', $suffixDate, $suffixIP);
            }
            if (!(strpos($type,mteConst::MTE_LOG_MAIL) === false))  {
                $logs[mteConst::MTE_LOG_MAIL] = new mteLogMail('', '', $suffixDate, $suffixIP);
            }
                        
            return $logs;  	
        }
   
       /**
        * 
        */
        protected function _writeLog($event){
            if ($this->getLogState()){
                // Size max
                $sizeAux = 0;
                if (file_exists($this->getLogFile())){
                    $sizeAux = filesize($this->getLogFile());
                }
                        
                if ( $sizeAux >= MTE_LOG_MAXSIZE){
                    // file name zip
                    $fileNameZip = $this->getLogFile().'.gz';
                    $version = 1;
                    while (file_exists($fileNameZip)){
                        $fileNameZip = $this->getLogFile().'_'.$version.'.gz';
                        $version++;
                    }
                                
                    // Compact
                    $zp = gzopen($fileNameZip, "w9");
                    gzwrite($zp, file_get_contents($this->getLogFile()));
                    gzclose($zp);
                                        
                    // remove 
                    @unlink($this->getLogFile());
                }
                                
                $file = @fopen($this->getLogFile(),'a+');
                if ($file){
                    fwrite($file,$event);
                    fclose($file);
                }
            }
            $this->_sendMailNotification($event);
        }
   
       /**
        * 
        */
        protected function _sendMailNotification($event){
            // Manda mail
            if ($this->getMailNotificationState()){
                // Creates mail and send it
                $mail = new mteMail();
                $this->mail->setBody($event);
                $this->mail->setSubject(MTE_LOG_MAIL_SUBJECT);
                $this->mail->send();
            }
        }

        /**
         * Agrega un evento al log
         * 
         * @access public
         */
        public function addEvent($typeEvent = -1, $user = '', $comm = ''){		
            // Parametros
            $user = ($user == '')?'UNKNOWN_USER':$user;
            $comm = ($comm == '')?'UNKNOWN_COMM':$comm;

            $error = new mteConst();
            return date('Y-m-d H:i:s').mteConst::MTE_LOG_SEPARATOR.
                    $this->getLogType().mteConst::MTE_LOG_SEPARATOR.
                    $error->getErrorName($typeEvent).mteConst::MTE_LOG_SEPARATOR.
                    $user.mteConst::MTE_LOG_SEPARATOR.
                    $_SERVER["REMOTE_ADDR"].mteConst::MTE_LOG_SEPARATOR.
                    $_SERVER["PHP_SELF"].mteConst::MTE_LOG_SEPARATOR.
                    $comm.mteConst::MTE_LOG_SEPARATOR;
        }
    }
?>