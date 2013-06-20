<?php
/**
 * Ajax management class
 *
 * @filesource
 * @package motte
 * @subpackage controller
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author	Pedro Gauna (pgauna@gmail.com) /
 * 		Carlos Gagliardi (carlosgag@gmail.com) /
 * 		Braulio Rios (braulioriosf@gmail.com) /
 * 		Pablo Erartes (pabloeuy@gmail.com) /
 * 		GBoksar/Perro (gustavo@boksar.info)
 */

    class mteAjax extends mteController{

        /**
         * status
         *
         * @access private
         * @var integer
         */
        private $_status;

        /**
         * Response
         *
         * @access private
         * @var array
         */
        private $_result;

        /**
         * Error
         *
         * @access private
         * @var array
         */
        private $_error;

        /**
         * Xml
         *
         * @access private
         * @var string
         *
         */
        private $_xml;

        /**
         * text
         *
         * @access private
         * @var string
         *
         */
        private $_text;

        
        /**
         * 
         * @access public
         * constructor
         */
        public function __construct() {
            parent::__construct();
            $this->clearResponse();
        }

        /**
         * 
         * @access public
         * Destructor
         */
        public function __destruct() {
        }

        /**
         * clear response
         * @access public
         */
        public function clearResponse(){
            $this->_error  = array();
            $this->_result = array();
            $this->_status = 0;
        }

        /**
         * clear special chars
         *
         * @param string $text
         * @return string
         */
        public function clearSpecialChar($text){
            foreach (explode(',',':,|,#') as $char){
                $text = str_replace($char,'_',$text);
            }
            return $text;
        }

        /**
         * 
         * @access public
         * @param string $text
         */
        public function addError($error){
            $this->_error[] = $error;
        }

        /**
         * 
         * @access public
         * @param variant $result
         */
        public function addResult($result){
            $this->_result[] = $result;
        }

        /**
         * 
         * @access public
         */
        public function setStatusOk(){
            $this->_status = 1;
        }

        /**
         * 
         * @access public
         */
        public function setStatusError(){
            $this->_status = 0;
        }

        /**
         * 
         * @access public
         */
        public function parseXml(){
            $this->_xml  = "<?xml version=\"1.0\" standalone=\"yes\"?>";
            $this->_xml .= "<motte>";
            $this->_xml .= "<status>".$this->_status."</status>";

            // Result
            foreach ($this->_result as $data){
                $this->_xml .= "<result><![CDATA[$data]]></result>";
            }

            // Error
            foreach ($this->_error as $data){
                $this->_xml .= "<error><![CDATA[$data]]></error>";
            }

            $this->_xml .= "</motte>";
        }

        /**
         *
         * @access public
         */
        public function parseText(){
            $this->_text = $this->_status.'|'.implode('#',$this->_result).'|'.implode('#',$this->_error);
        }

        /**
         * 
         * @access public
         * @return string
         */
        public function getXml(){
            if (empty($this->_xml)){
                $this->parseXml();
            }
            return $this->_xml;
        }

        /**
         * 
         * @access public
         * @return string
         */
        public function getText(){
            if (empty($this->_text))
            $this->parseText();
            return $this->_text;
        }

        /**
         * 
         *  @access public
         */
        public function sendXml(){
            header('Content-Type: text/xml; charset='.MTE_SYSTEM_CHARSET);
            echo $this->getXml();
        }

        /**
         * 
         *  @access public
         */
        public function sendText(){
            header('Content-Type: text; charset='.MTE_SYSTEM_CHARSET);
            echo $this->getText();
        }

        /**
         * 
         *  @access public
         */
        public function sendSimpleText(){
            header('Content-Type: text; charset='.MTE_SYSTEM_CHARSET);
            echo htmlentities(implode('|',$this->_result));
        }
    }
?>