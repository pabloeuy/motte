<?php
/**
 * export list TXT
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

    class mteExportListText extends mteExportList {

        /**
         *
         * @access private
         * @var string
         */
        private $_separator;

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
         * @return mteExportListText
         */
        public function __construct() {
            // parent
            parent::__construct();

            // Initialize
            $this->setFieldSeparator('|');
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
         * @param unknown_type $value
         */
        public function setFieldSeparator($value){
            $this->_separator = $value;
        }

        /**
         * get field separator
         *
         * @return string
         */
        public function getFieldSeparator(){
            return $this->_separator;
        }

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

        // ------------------------------------------------------------------------
        //                              O U T P U T
        // ------------------------------------------------------------------------
        /**
         * create arry list
         *
         * @return array
         */
        public function createList(){
            // Initialize
            $list = array();

            // data
            foreach ($this->getData() as $record) {
                // Data
                $data = array();
                foreach ($this->getColumns() as $field => $col){
                    $value = '';
                    if (isset($record[$col['fieldName']])){
                        $value = $record[$col['fieldName']];
                    }
                    $data[$col['fieldName']] = $value;
                }
                $list[] = implode($this->getFieldSeparator(), $data);
            }
            // result
            return $list;
        }

        /**
         * export list
         *
         * @param string $type
         * @param string $dir
         * @param string $name
         * @param string $ext
         * @return string
         */
        public function export($type, $dir = '', $name = '', $ext = 'txt'){
            // export
            $export = new mteTXT();
            $export->setEndOfLine($this->getEndOfLine());
            $export->clearLines();
            $export->addLines($this->createList());

            // type
            $result = '';
            switch ($type) {
                case mteConst::MTE_EXPORT_SEND:
                {
                    $export->send($name, $ext);
                    break;
                }
                case mteConst::MTE_EXPORT_DOWNLOAD:
                {
                    $export->download($name, $dir, $ext);
                    break;
                }
                case mteConst::MTE_EXPORT_SAVE:
                {
                    // parameters
                    if ($dir == '' ){
                        $dir = MTE_CACHE;
                    }
                    $export->save($dir, $name, $ext);
                    break;
                }
                case mteConst::MTE_EXPORT_STRING:
                {
                    $result = $export->toString();
                    break;
                }
            }
            return $result;
        }
    }
?>