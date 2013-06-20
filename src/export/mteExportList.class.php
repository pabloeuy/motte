<?php
/**
 * Clase para exportar listas
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
    class mteExportList {

        /**
         *
         * @access private
         * @var array
         */
        private $_cols;

        /**
         *
         * @access private
         * @var array
         */
        private $_data;

        /**
         *
         * @access public
         */
        public function __construct(){
            // Initialize
            $this->clearColumns();
            $this->clearData();
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        // ------------------------------------------------------------------------
        //                       C O L U M N    M A N A G E R
        // ------------------------------------------------------------------------
        /**
         * get columns
         *
         * @return array
         */
        public function getColumns(){
            return $this->_cols;
        }

        public function clearColumns(){
            $this->_cols = array();
        }

        /**
         * add column
         *
         * @param string $name
         * @param string $title
         * @param string $type = 'text'
         */
        public function _addColumn($name, $title, $styleTitle = '', $alignTitle = '', $styleData = '', $alignData = '', $size = '20', $fieldname = ''){
            if(!empty($name) && !empty($title)){
                if ($fieldname == ''){
                    $fieldname = $name;
                }
                $this->_cols[$name] = array('title' => $title, 'styleTitle' => $styleTitle, 'styleData' => $styleData, 'alignTitle' => $alignTitle, 'alignData' => $alignData, 'size' => $size, 'fieldName' => $fieldname);
            }
        }

        /**
         * clear alignament
         *
         * @param string $txt
         * @return string
         */
        private function _clearAlign($txt){
            return trim($txt);
        }

        /**
         * clear style
         *
         * @param string $txt
         * @return string
         */
        private function _clearStyle($txt){
            return str_replace('**', '', str_replace('//', '', str_replace('__', '', $txt)));
        }

        /**
         * clear literal
         *
         * @param string $txt
         * @return string
         */
        private function _clearLiteral($txt){
            return str_replace($this->_getLiteral($txt), '', $txt);
        }

        /**
         * clear size
         *
         * @param string $txt
         * @return string
         */
        private function _clearSize($txt){
            $can = 0;
            $result = '';
            for ($i = 0; $i < strlen($txt); $i++){
                // count sharp
                if ($txt[$i] == '#'){
                    $can++;
                    if ($can >= 4) $can = 0;
                }
                // add char
                if (($txt[$i] != '#') && ($can == 0)){
                    $result .= $txt[$i];
                }
            }
            return $result;
        }

        /**
         * Style param
         *
         * @param string $param
         * @return string
         */
        private function _styleParam($param){
            if (substr($param, 0, 1) == '|'){
                $param = substr($param, 1);
            }
            if (substr($param, -1, 1) == '|'){
                $param = substr($param, 0, strlen($param)-1);
            }
            return $param;
        }

        /**
         * get alignament
         *
         * @param string $txt
         * @return string
         */
        private function _getAlign($txt){
            // Initialize
            $result = mteConst::MTE_PDF_ALIGN_LEFT;
            // Align Rigth
            if (substr($txt, 0, 1) == ' ' && substr($txt, -1, 1) != ' '){
                $result = mteConst::MTE_PDF_ALIGN_RIGTH;
            }
            // Align Left
            if (substr($txt, 0, 1) != ' ' && substr($txt, -1, 1) == ' '){
                $result = mteConst::MTE_PDF_ALIGN_LEFT;
            }
            // Align Center
            if (substr($txt, 0, 1) == ' ' && substr($txt, -1, 1) == ' '){
                $result = mteConst::MTE_PDF_ALIGN_CENTER;
            }

            // return
            return $result;
        }

        /**
         * get Style
         *
         * @param string $txt
         * @return string
         */
        private function _getStyle($txt){
            // Initialize
            $txt = $this->_clearLiteral($this->_clearSize($this->_clearAlign($txt)));
            $result = '';

            // bold
            if (substr($txt, 0, 2) == '**'){
                $result .= mteConst::MTE_PDF_FONT_BOLD;
                $txt = str_replace('**', '', $txt);
            }

            // italic
            if (substr($txt, 0, 2) == '//'){
                $result .= mteConst::MTE_PDF_FONT_ITALIC;
                $txt = str_replace('//', '', $txt);
            }

            // Underline
            if (substr($txt, 0, 2) == '__'){
                $result .= mteConst::MTE_PDF_FONT_UNDERLINE;
                $txt = str_replace('__', '', $txt);
            }

            // recursive
            if (substr($txt, 0, 2) == '**' || substr($txt, 0, 2) == '//' || substr($txt, 0, 2) == '__'){
                $result .= $this->_getStyle($txt);
            }

            // return
            if ($result == ''){
                $result = mteConst::MTE_PDF_FONT_REGULAR;
            }
            return $result;
        }

        /**
         * get literal
         *
         * @param string $txt
         * @return string
         *
         */
        private function _getLiteral($txt){
            return $this->_clearSize($this->_clearAlign($this->_clearStyle($txt)));
        }

        /**
         * get size
         *
         * @param unknown_type $txt
         * @return unknown
         */
        private function _getSize($txt){
            // initialize
            $txt = $this->_clearLiteral($this->_clearAlign($this->_clearStyle($txt)));
            $result = str_replace('##', '', $txt);
            if ($result == ''){
                $result = '0';
            }
            return $result;
        }


        /**
         * set header y content de las columnas
         *
         * @param string $txt
         */
        public function setColumns($cols, $header = ''){
            // Normalize params
            $cols   = explode('|', $this->_styleParam($cols));
            $header = explode('|', $this->_styleParam($header));
            while (count($cols) > count($header)){
                $header[] = '';
            }

            // Add Columns
            for($nroCol = 0; $nroCol < count($cols); $nroCol++){
                $this->_addColumn($this->_getLiteral($cols[$nroCol]), 
                    $this->_getLiteral($header[$nroCol]), 	// title
                    $this->_getStyle($header[$nroCol]), 		// style (title)
                    $this->_getAlign($header[$nroCol]), 		// align (title)
                    $this->_getStyle($cols[$nroCol]), 		// style (data)
                    $this->_getAlign($cols[$nroCol]), 		// align (data)
                    $this->_getSize($cols[$nroCol]));
            }
        }

        // ------------------------------------------------------------------------
        //                         D A T A    M A N A G E R
        // ------------------------------------------------------------------------

        /**
         * get Data
         *
         */
        public function getData(){
            return $this->_data;
        }

        /**
         * clear Data
         *
         */
        public function clearData(){
            $this->_data = array();
        }

        /**
         * Agrega los datos
         *
         * @param array $fields
         */
        public function addData($data){
            if(is_array($data)){
                $this->_data = $data;
            }
        }

        /**
         * add record data
         *
         * @param array $record
         */
        public function addRecord($record){
            $this->_data[] = $record;
        }
    }
?>