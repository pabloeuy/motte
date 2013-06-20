<?php
/**
 * Ajax Response String
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

class mteAjaxStringResponse {

	/**
	 * Resultado en XML
	 *
	 * @access private
	 * @var array
	 */
	private $_result;
	
	private $_valueSeparator;
	
	private $_recordSeparator;
	
	private $_substituteCharacter;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->clearResponse();
		$this->setValueSeparator();
		$this->setRecordSeparator();
		$this->setSubstituteCharacter();
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
	}
	
	private function _replaceSeparators($string){
		return str_replace(array($this->_valueSeparator, $this->_recordSeparator), $this->_substituteCharacter, $string);
	}
	
	/**
	 * Set the separator between the key and the value in the response string
	 * @return 
	 * @param string $separator[optional]
	 */
	public function setValueSeparator($separator = '|'){
		$this->_valueSeparator = $separator;
	}
	
	/**
	 * Set the separator between two different records, by default it's a break line
	 * @return 
	 * @param object $separator[optional]
	 */
	public function setRecordSeparator($separator = "\n"){
		$this->_recordSeparator = $separator;
	}
	
	
	/**
	 * If a string contains a value or record separator character, this character will replace it
	 * @return 
	 * @param string  $character[optional]
	 */
	public function setSubstituteCharacter($character = '_'){
		$this->_substituteCharacter = $character;
	}

	/**
	 * Limpia respuesta para ajax
	 */
	public function clearResponse() {
		$this->_result = array ();
	}

	/**
	 * Agrega tupla
	 *
	 * @param string $tag
	 * @param array $data
	 */
	public function addData($key, $value) {
		$this->_result[$key] = $value;
	}

	/**
	 * parsea string
	 */
	public function parseString() {
		$result = array();
		foreach ($this->_result as $key => $value) {
			$result[] = $this->_replaceSeparators($key).$this->_valueSeparator.$this->_replaceSeparators($value);
		}
		return implode($this->_recordSeparator, $result);		
	}

	/**
	 * devuelve String
	 *
	 * @return string
	 */
	public function getString() {
		return $this->parseString();
	}
}
?>