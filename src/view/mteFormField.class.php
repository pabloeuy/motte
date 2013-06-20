<?php
/**
 *
 *
 * @filesource
 * @package motte
 * @subpackage view
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author Pedro Gauna (pgauna@gmail.com)
 * 		   Carlos Gagliardi (carlosgag@gmail.com)
 * 		   GBoksar/Perro (gustavo@boksar.info)
 * 		   Mauro Dodero (maurodo@gmail.com)
 * 		   Pablo Erartes (pabloeuy@gmail.com)
 */

    class mteFormField extends mteTemplate{
        
        /**
         *
         * @access private
         * @var string
         */
        private $_type;
        

        /**
         *
         * @access public
         */
        public function __construct($type, $compileDir, $templateDir = '', $template = ''){
            parent:: __construct($compileDir, $templateDir, $template);
            $this->setType($type);
        }

        /**
         * Destructor
         *
         * @access public
         */
        public function __destruct(){
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                        P R O P E R T I E S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @param string $value
         */
        public function setName($value){
            $this->setVar('NAME', $value);
        }
        
        /**
         *
         * @access public
         * @param string $value
         */
        public function setType($value){
            $this->_type = $value;
        }
        
        /**
         *
         * @access public
         * @result string
         */
        public function getType(){
            return $this->_type;
        }        

        /**
         *
         * @access public
         * @param booelan $value
         */
        public function setIsReadOnly($value = 0){
            $this->setVar('READ_ONLY', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setValue($value = ''){
            $this->setVar('VALUE', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setTitle($value){
            $this->setVar('TITLE', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setSize($value){
            $this->setVar('SIZE', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setFormat($value){
            $this->setVar('FORMAT', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setMaxSize($value){
            $this->setVar('MAX', $value);
        }

        /**
         *
         * @access public
         * @param string $value
         */
        public function setHaveConstrains($value = 0){
            $this->setVar('VALIDATE', $value);
        }

        /**
         *
         * @access public
         * @param string $text
         */
        public function setHelp($text = '', $helptitle = ''){
            $this->setVar('HELP', $text);
            $this->setVar('HELPTITLE', (empty($helptitle))?__('Click here for help on this field'):$helptitle);
        }
        
        
        /**
         * 
         * @param <type> $name
         * @param <type> $value
         */
        public function setSpecialProperties($name, $value){
            $this->setVar($name, $value);
        }
        
        /**
         *
         * @access public
         * @param array $value
         */
        public function setOptions($value = ''){
            $this->setVar('OPTIONS', $value);
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                        G U I   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @return string
         */
        public function fetchHtml(){
            return $this->getHtml();
        }
    }
?>