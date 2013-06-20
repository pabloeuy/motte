<?php
/**
 * Ajax Response XML
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

class mteAjaxXMLResponse {

	/**
	 * status
	 *
	 * @access private
	 * @var integer
	 */
	private $_status;

	/**
	 * Resultado en XML
	 *
	 * @access private
	 * @var array
	 */
	private $_result;

	/**
	 * Resultado
	 *
	 * @access private
	 * @var array
	 */
	private $_error;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->clearResponse();
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
	}

	/**
	 * Limpia respuesta para ajax
	 */
	public function clearResponse() {
		$this->_error  = array ();
		$this->_result = array ();
		$this->_status = 0;
	}

	/**
	 * Agrega error
	 */
	public function addError($error) {
		$this->_error[] = $error;
	}

	/**
	 * Setea status OK
	 */
	public function setStatusOk() {
		$this->setStatus(1);
	}

	/**
	 * Setea status ERROR
	 */
	public function setStatusError() {
		$this->setStatus(0);
	}

	/**
	 * Setea status
	 */
	public function setStatus($status) {
		$this->_status = $status;
	}

	/**
	 * Agrega bloque de xml
	 *
	 * @param string $tag
	 * @param array $data
	 */
	public function addBlock($tag, $record) {
		$xml = '';
		foreach ($record as $field=>$value) {
			$xml .= "<$field>".str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $value)."</$field>";
		}
		$this->_result[] = array ('tag'=>$tag, 'value'=>$xml);
	}

	public function addBlockJson($record){
		$this->_result[] = $record;
	}
	
	/**
	 * Agrega bloque de xml
	 *
	 * @param string $tag
	 * @param array $data
	 */
	public function addBlockString($tag, $string) {
		$this->_result[] = array ('tag'=>$tag, 'value'=>$string);
	}
	

	/**
	 * parsea xml
	 */
	public function parseXml() {
		$xml = "<?xml version=\"1.0\" encoding=\"".MTE_SYSTEM_CHARSET."\"?>";
		$xml .= "<result>";
		// Status
		$xml .= "<status>".$this->_status."</status>";
		// Result
		foreach ($this->_result as $value) {
			$xml .= "<".$value['tag'].">".$value['value']."</".$value['tag'].">";
		}
		// Error
		foreach ($this->_error as $value) {
			$xml .= "<error>".htmlspecialchars($value)."</error>";
		}
		$xml .= "</result>";
		return $xml;
	}

	public function parseJSon(){
		// Result
		return json_encode($this->_result);
	}

	/**
	 * devuelve XML
	 *
	 * @return string
	 */
	public function getXml() {
		return $this->parseXml();
	}

	public function getJSon(){
		return $this->parseJSon();
	}
}
?>