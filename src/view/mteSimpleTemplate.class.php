<?php
/**
 * Clase para la lectura de plantillas HTML con OB
 *
 * @filesource
 * @package motte
 * @subpackage view
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 		Carlos Gagliardi (carlosgag@gmail.com) /
 * 		Braulio Rios (braulioriosf@gmail.com) /
 * 		Pablo Erartes (pabloeuy@gmail.com) /
 * 		GBoksar/Perro (gustavo@boksar.info)
 */

class mteSimpleTemplate {

	/**
	 *
	 * @var string
	 * @access private
	 */
	private $_template;

	/**
	 *
	 * @var string
	 * @access private 
	 */
	private $_templateDir;

	/**
	 *
	 * @var array
	 * @access private 
	 */
	private $_var;
	
	/**
	 *
	 * @var string
	 * @access private
	 */
	private $_blocks;


	/**
	 *
	 * @access public
 	 * @param string $template[optional]
	 * @param string $templateDir[optional] 
	 */
	public function __construct($template = '', $templateDir = '') {
		$this->setTemplateDir($templateDir == ''?MTE_TEMPLATE:$templateDir);
		$this->setTemplate($template);
		$this->clearVars();
		$this->_blocks = array();
	}
   
   
   private function _cleanHtml(&$buffer){
      if(MTE_CLEAN_HTML){
         $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '   '), '', $buffer);
      }
   }

	/**
	 *
	 * @access public
	 * @param string $name = ''
	 */
	public function setTemplate($name = '') {
		$this->_template = $name;
	}

	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getTemplate() {
		return $this->_template;
	}

	/**
	 *
	 * @access public
	 * @param string $dir = ''
	 */
	public function setTemplateDir($dir = '') {
		$this->_templateDir = $dir;
	}

	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getTemplateDir() {
		return $this->_templateDir;
	}

	/**
	 * Limpia variables del template
	 *
	 * @param string $varName
	 */
	public function clearVar($varName) {
		$this->_engine->clear_assign($varName);
	}

	/**
	 *
	 */
	public function clearVars() {
		$this->_var = array();
	}

	/**
	 *
	 * @access public
	 * @param string $varName
	 * @param variant $varValue
	 */
	public function includeBlock($block) {
		$this->_blocks[] = $block;
	}
	
	
	/**
	 *
	 * @access public
	 * @param string $varName
	 * @param variant $varValue
	 */
	public function addVar($varName, $varValue) {
		$this->_var[$varName] = $varValue;
	}

	/**
	 *
	 * @access public
	 * @param string $varName
	 * @param variant $varValue
	 */
	public function setVar($varName, $varValue) {
		$this->addVar($varName, $varValue);
	}

	/**
	 *
	 * @access public
	 * @param string $varName
	 * @param variant $varValue
	 */
	public function appendVar($varName, $varValue) {
		$this->_var[$varName][] = $varValue;
	}

	/**
	 *
	 * @access public
	 * @param string $varName
	 * @return variant
	 */
	public function getVar($varName) {
		return $this->_var[$varName];
	}

	/**
	 *
	 * @access public
	 * @return string
	 */
	public function getHtml() {
		$html = '';
		$file = $this->getTemplateDir().'/'.$this->getTemplate();
		if (is_file($file)){
			preg_match_all("(\\$[\w|\d]+)", file_get_contents($file), $vars);
			if (is_array($vars)){
				foreach ($vars[0] as $key => $var) {
					$var = substr($var,1);
					if (!array_key_exists($var, $this->_var)){
						$this->setVar($var, '');
					}
				}
			}
         if(DEBUG_MODE_SIMPLE_TEMPLATE){
            $this->setVar('ALL_VARS', $this->_var);
         }
			extract($this->_var);
			ob_start();
			include($this->getTemplateDir().'/'.$this->getTemplate());
			$html = ob_get_clean();
         $this->_cleanHtml($html);
		}
		return $html.implode("\n", $this->_blocks);
	}

	/**
	 *
	 * @access public
	 * @return string
	 */
	public function showHtml() {
		print $this->getHtml();
	}
}
?>