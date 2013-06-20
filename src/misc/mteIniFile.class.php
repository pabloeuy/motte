<?php
/**
 * Clase para el manejo de archivos tipo INI
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
    class mteIniFile {

        /**
         *
         * @var String
         * @access private
         */
        var $_fileName;

        /**
         *
         * @var String
         * @access private
         */
        var $_vars;

        /**
         * Constructor
         *
         * @access public
         * @return mteCrypt
         */
        function __construct($fileName = '') {
            $this->setFileName($fileName);
            $this->clearVars();
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
         *                        P R O P E R T I E S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * set file name
         *
         * @access public
         * @param string $value
         */
        public function setFileName($value){
            $this->_fileName = $value;
        }

        /**
         * get file name
         *
         * @access public
         * @return string
         */
        public function getFileName(){
            return $this->_fileName;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                      V A R S    M A N A G M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * clear vars
         *
         * @access public
         */
        public function clearVars(){
            $this->_vars = array();
        }

        /**
         * set value var
         *
         * @access public
         * @param string $name
         * @param variant $value
         */
        public function setVar($name, $value = ''){
            $this->_vars[$name] = $value;
        }

        /**
         * get var
         *
         * @access public
         * @return variant
         */
        public function getVar($name){
            return $this->_vars[$name];
        }

        /**
         * load vars from ini file
         *
         */
        public function load(){
            if (is_readable($this->getFileName())){
                $vars = file($this->getFileName());
                if (is_array($vars)){
                    foreach ($vars as $var){
                        $var = explode('=',$var);
                        $this->setVar($var[0],$var[1]);
                    }
                }
            }
        }

        /**
         * save vars in ini file
         *
         */
        public function save(){
            if (is_writeable($this->getFileName())){
                $file = fopen($this->getFileName(),'w');
                foreach ($this->_vars as $key=>$value)
                fwrite($file,$key.'='.$value);
                fclose($file);
            }
        }
    }
?>