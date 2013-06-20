<?php
/**
 * Encode class
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mteCrypt {

        /**
         *
         *
         * @var String
         * @access private
         */
        private $_str;

        /**
         * Constructor
         *
         * @access public
         * @return mteCrypt
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
         * @param array $array
         * @return string
         */
        public function arrayToSring($array, $eq = '=', $sep = '&'){
            // Control de parametros
            if (!is_array($array)){
                $array = array($array);
            }

            // Recorro array
            $str = '';
            foreach($array as $key => $value){
                $str .= $key.$eq.$value.$sep;
            }

            // Devuelvo
            $txt = $this->encode(substr($str,0,-1));
            return $this->genCRC($txt).$txt;
        }

        /**
         *
         * @access public
         * @param string $vars
         * @return array
         */
        public function stringToArray($txt, $eq = '=', $sep = '&'){
            // Variables
            $array = array();
            // Separo Variables
            $aux = explode($sep, $this->decode($txt));
            if (is_array($aux)){
                foreach($aux as $key => $value){
                    $value = explode($eq, $value);
                    $array[$value[0]] = $value[1];
                }
            }
            // Devuelve
            return $array;
        }

        /**
         *
         * @access public
         * @param string $txt
         */
        public function setStringCode($txt){
            $this->_str = $txt;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getStringCode(){
            return $this->_str;
        }

        /**
         *
         * @access public
         * @return string
         */
	public function crypt($txt) {
		return $this->genCRC($txt).$this->encode($txt);
	}

        /**
         *
         * @access public
         * @return string
         */
	public function deCrypt($txt) {
		return $this->genCRC($this->decode($this->getValue($txt))) == $this->getCRC($txt)?$this->decode($this->getValue($txt)):__('CRC Error');
	}


        /**
         *
         * @access public
         * @param string $txt
         * @return string
         */
        public function genCRC($txt){
            return md5($txt);
        }

        /**
         *
         * @access public
         * @param string $txt
         * @return string
         */
        public function getCRC($txt = ''){
            if ($txt == ''){
                $txt = $this->getStringCode();
            }
            return substr($txt,0,32);
        }

        /**
         *
         * @access public
         * @param string $txt
         * @return string
         */
        public function getValue($txt = ''){
            if ($txt == ''){
                $txt = $this->getStringCode();
            }
            return substr($txt,32);
        }


        /**
         *
         * @access public
         * @param string $txt
         * @return string
         */
        public function encode($txt){
            // Devuelvo
            return base64_encode($txt);
        }

        /**
         *
         * @access public
         * @param string $txt
         * @return string
         */
        public function decode($txt){
            // Devuelvo
            return base64_decode($txt);
        }

        /**
         *
         *
         * @param integer $length
         * @return string
         */
        public function getRandomCode($length = 10){
            $chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPRQSTUVWXYZ23456789";
            $code = "";
            $clen = strlen($chars) - 1;
            while (strlen($code) < $length){
                $code .= $chars[mt_rand(0,$clen)];
            }
            return $code;
        }
    }
?>
