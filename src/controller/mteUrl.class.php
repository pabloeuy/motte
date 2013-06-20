<?php
/**
 * url management class
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
    class mteUrl {

        /**
         * @access private
         * @var array
         */
        private $_params = '';

        /**
         * @access private
         * @var array
         */
        private $_varName = '';

        /**
         * Constructor
         *
         * @access public
         * @return mteUrl
         */
        function __construct($varName) {
            // Initialize
            $this->_params  = array();
            $this->_varName = $varName;
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }

        /**
         * @access private
         * @param $name string
         * @param $default variant
        */
        private function _setParam($name,$value){
            $this->_params[$name] = $value;
        }

        /**
         *
         * @access private
         * @param $name string
         * @param $default variant
         * @return variant
         */
        private function _getParam($name, $default=''){
            return ((isset($this->_params[$name])) && ($this->_params[$name] != ''))?$this->_params[$name]:$default;
        }


        /**
         *
         * @access public
         * @return array
         */
        public function getParams(){
            return $this->_params;
        }

        /**
         *
         * @access public
         * @param $array array
         */
        public function setParams($array){
            $this->_params = $array;
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamModule(){
            return $this->_getParam('M',0);
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamOption(){
            return $this->_getParam('O',0);
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamSuboption(){
            return $this->_getParam('S',0);
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamTab(){
            return $this->_getParam('T',0);
        }

        /**
         *
         * @access public
         * @return boolean
         */
        public function getParamInitial(){
            return $this->_getParam('Z',false);
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamAction(){
            return $this->_getParam('A');
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridPage(){
            $page = $this->_getParam('GP');
            if (($page == '') || ($page == 0)){
                $page = 1;
            }
            return $page*1;
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridOrderField(){
            return $this->_getParam('GO');
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridOrderDireccion(){
            $result = $this->_getParam('GOD');

            if ($result != ''){
                if (($result == '-') || ($result == '-1') || (strtoupper($result) == 'DESC')){
                    $result = 'DESC';
                }
                else{
                    $result = 'ASC';
                }
            }
            return $result;
        }


        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridFilterField(){
            return $this->_getParam('GF');
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridFilterText(){
            return $this->_getParam('GX');
        }

        /**
         *
         * @access public
         * @return variant
         */
        public function getParamActionId($var = ''){
            // Parametros
            $result = array();

            $array = explode('|',$this->_getParam('ID'));
            foreach ($array as $element) {
                $element = explode(':',$element);
                if ($var == ''){
                    $result[$element[0]] = $element[1];
                }
                else{
                    if ($element[0] == $var){
                        $result = $element[1];
                    }
                }
            }
            return $result;
        }

        /**
         *
         * @access public
         * @return boolean
         */
        public function existsParamActionId(){
            return count($this->getParamActionId()) > 0 ? true : false;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamClassModel(){
            return $this->_getParam('D');
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamClassView(){
            return $this->_getParam('W');
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamModule($value=0){
            $this->_setParam('M',$value);
        }


        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamOption($value=0){
            $this->_setParam('O',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamSuboption($value=0){
            $this->_setParam('S',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamTab($value=0){
            $this->_setParam('T',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamInitial($value=false){
            $this->_setParam('Z',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamAction($value = ''){
            $this->_setParam('A',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridPage($value = ''){
            $this->_setParam('GP',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridOrderField($value = ''){
            $this->_setParam('GO',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridOrderDir($value = ''){
            $this->_setParam('GOD',$value);
        }

        /**
         *
         * @access public
         * @param  string $value
         */
        public function setParamGridFilterField($value = ''){
            $this->_setParam('GF',$value);
        }

        /**
         *
         * @access public
         * @param  string $value
         */
        public function setParamGridFilterText($value = ''){
            $this->_setParam('GX',$value);
        }

        /**
         *
         * @access public
         * @param  array $value
         */
        public function setParamActionId($value = ''){
            // Si no es un array
            if (!is_array($value))
            $value = array($value);

            $result = array();
            foreach ($value as $key => $val){
                $result[] = $key.':'.$val;
            }

            $this->_setParam('ID', implode('|',$result));
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamClassModel($value = ''){
            $this->_setParam('D',$value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamClassView($value = ''){
            $this->_setParam('W',$value);
        }

        /**
         *
         * @access public
         * @param array $vars
         * @return string
         */
        public function encodeVarURL($vars){
            // Encriptador
            $crypt = new mteCrypt();
            return $this->_varName.'='.$crypt->arrayToSring($vars);
        }

        /**
         *
         * @access public
         * @param array $vars
         * @return boolean
         */
        public function decodeVarURL($vars){
            if (isset($vars[$this->_varName])){
                $result = false;
                if ($this->checkURL($vars)){
                    $result = true;
                    $crypt = new mteCrypt();
                    $this->setParams($crypt->stringToArray($crypt->getValue($vars[$this->_varName])));
                }
            }
            else{
                $result = true;
            }
            return $result;
        }

        /**
         *
         * @access public
         * @param array $vars
         * @return boolean
         */
        public function checkURL($vars=''){
            $valid = true;

            if ($vars[$this->_varName] != ''){
                $crypt = new mteCrypt();
                $valid = $crypt->genCRC($crypt->getValue($vars[$this->_varName])) == $crypt->getCRC($vars[$this->_varName]);
            }
            return $valid;
        }

        /**
         *
         * @access public
         * @param string $script
         * @param array $vars
         * @return boolean
         */
        public function getUrl($script = '',$vars = ''){
            // poarametros
            if ($script == ''){
                $script = basename($_SERVER['PHP_SELF']);
            }
            if ($vars == ''){
                $vars = $this->getParams();
            }

            // devuelvo
            return $script.'?'.$this->encodeVarUrl($vars);
        }
    }
?>