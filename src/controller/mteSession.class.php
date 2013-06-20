<?php
/**
 * session management class
 *
 * @filesource
 * @package motte
 * @subpackage controller
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */
    class mteSession {
        
        /**
         * Constructor
         *
         * @access public
         * @return mteUrl
         */
        function __construct() {
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }        
        
        /**
         *
         * @access public
         * @param  string $name
         */
        public function createSession(){
            session_start();
        }

        /**
         *
         * @access public
         */
        public function killSession($nameSession = ''){
            if (empty($nameSession)){
                $nameSession = MTE_SESSION_NAME;
            }
            $_SESSION[$nameSession] = array();
            unset($_SESSION[$nameSession]);
        }

        /**
         *
         * @access public
         * @param  string $name
         * @param  variant $value
         */
        public function addSessionVar($name, $value, $nameSession = ''){
            if (empty($nameSession)){
                $nameSession = MTE_SESSION_NAME;
            }
            $_SESSION[$nameSession][$name] = $value;
        }

        /**
         *
         * @access public
         * @param  string $name
         * @param  variant $value
         */
        public function setSessionVar($name, $value, $nameSession = ''){
            $this->addSessionVar($name, $value, $nameSession);
        }

        /**
         *
         * @access public
         * @param  string $name
         * @return variant
         */
        public function getSessionVar($name, $nameSession = ''){
            if (empty($nameSession)){
                $nameSession = MTE_SESSION_NAME;
            }
            $result = '';
            if (isset($_SESSION[$nameSession][$name])){
                $result = $_SESSION[$nameSession][$name];
            }
            return $result;
        }

        /**
         *
         * @access public
         * @param  string $name
         */
        public function removeSessionVar($name, $nameSession = ''){
            if (empty($nameSession)){
                $nameSession = MTE_SESSION_NAME;
            }
            unset($_SESSION[$nameSession][$name]);
        }        
    }


?>
