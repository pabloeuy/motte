<?php
/**
 * Clase para el manejo de txt's
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

    class mteTXT {

        /**
         *
         * @access private
         * @var array
         */
        private $_lines;

        /**
         *
         * @access private
         * @var string
         */
        private $_eol;

        /**
         * Constructor
         *
         * @access public
         * @return mteTXT
         */
        public function __construct() {
            // Initialize
            $this->clearLines();
            $this->setEndOfLine("\n");
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        // ------------------------------------------------------------------------
        //                          P R O P E R T I E S
        // ------------------------------------------------------------------------
        /**
         * set field separator
         *
         * @param string $value
         */
        public function setEndOfLine($value){
            $this->_eol = $value;
        }

        /**
         * get end of line
         *
         * @return string
         */
        public function getEndOfLine(){
            return $this->_eol;
        }

        //--------------------------------------------------------------------------
        //                          L I N E   M A N A G E R
        //--------------------------------------------------------------------------
        /**
         * clear lines
         *
         */
        public function clearLines(){
            $this->_lines = array();
        }
        /**
         * add line
         *
         * @param string $txt
         */
        public function addLine($txt){
            $this->_lines[] = $txt;
        }

        /**
         * add lines
         *
         * @param string $txt
         */
        public function addLines($txt){
            if (is_array($txt)){
                foreach ($txt as $line){
                    $this->addLine($line);
                }
            }
        }

        //--------------------------------------------------------------------------
        //                             O U T P U T
        //--------------------------------------------------------------------------
        /**
         * create file
         *
         * @param string $fileName
         */
        private function _linesToFile($fileName, $eol = ''){
            $file = fopen($fileName, "w+");
            fwrite ($file, $this->_linesToString());
            fclose($file);
        }

        /**
         * lines to string
         *
         * @return string
         */
        private function _linesToString($eol = ''){
            if ($eol == ''){
                $eol = $this->getEndOfLine();
            }
            return implode($eol, $this->_lines);
        }

        /**
         * Send file
         *
         * @param string $dir
         * @param string $name
         * @param string $extension
         */
        public function send($name = '', $ext = 'txt'){
            // parameters
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.'.$ext;
            }
            // send
            header("Content-type: text");
            header("Content-Length: ".strlen($this->_linesToString()));
            header("Content-Disposition: attachment; filename=$name");
            echo $this->_linesToString();
        }

        /**
         * Download file
         *
         * @param string $name
         */
        public function download($name = '', $dir = '', $ext = 'txt'){
            // parameters
            if ($dir == '' ){
                $dir = MTE_CACHE;
            }
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.'.$ext;
            }

            // Create File
            $this->_linesToFile($dir.'/'.$name);

            // Send file
            header("Content-type: text");
            header("Content-Length: ".filesize($dir.'/'.$name));
            header("Content-Disposition: attachment; filename=$name");
            readfile($dir.'/'.$name);
        }

        /**
         * Save file
         *
         * @param string $name
         */
        public function save($dir, $name = '', $ext = 'txt'){
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.'.$ext;
            }
            // Create File
            $this->_linesToFile($dir.'/'.$name);
        }

        /**
         * return the document as a string
         *
         * @param string $name
         */
        public function toString(){
            return $this->_linesToString();
        }
    }
?>