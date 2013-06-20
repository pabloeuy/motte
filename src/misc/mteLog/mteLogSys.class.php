<?php
/**
 * Clase para el manejo de Log's Sys
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

    class mteLogSys extends mteLog{

        /**
         * Constructor
         *
         * @access public
         * @param
         * @return mteLogSys
         */
        public function __construct($logFile = '', $logMail = '', $suffixDate = false, $suffixIP = false) {
            parent::setSuffixDate($suffixDate);
            parent::setSuffixIP($suffixIP);
            parent::setLogType("MTE_SYS");
            if ($logFile == ''){
                $logFile = MTE_LOG_DIR.'/mte_'.strtolower(mteConst::MTE_LOG_SYS).$this->getSuffix().'.log';
            }

            // Constructor padre
            parent::__construct($logFile, $logMail);
        }

       /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        public function addEvent($typeEvent = -1, $user = '', $comm = ''){
            if ((MTE_LOG_FULL == true) || ($typeEvent == mteConst::MTE_ERROR)){
                parent::_writeLog( parent::addEvent($typeEvent, $user, $comm)."\n" );
            }
        }
    }
?>