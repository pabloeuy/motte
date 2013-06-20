<?php
/**
 * Clase para el manejo de exepciones
 *
 *
 * @filesource
 * @package    motte
 * @subpackage view
 * @version    1.0
 * @license    http://opensource.org/licenses/gpl-license.php GPL - GNU Public license
 * @author     Pedro Gauna (pgauna@gmail.com) /
 *             Carlos Gagliardi (carlosgag@gmail.com) /
 *             Braulio Rios (braulioriosf@gmail.com) /
 *             Pablo Erartes (pabloeuy@gmail.com) /
 *             GBoksar/Perro (gustavo@boksar.info)
 * @link       http://motte.codigolibre.net Motte Website
 */

    class mteException extends mteTemplate {
        /**
         * Constructor
         *
         * @access public
         */
        public function __construct($compileDir = '', $templateDir = '', $template = ''){
            parent:: __construct($compileDir, $templateDir, $template);
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setTitle($value = ''){
            $this->setVar('TITLE', $value);
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setProblem($value = ''){
            $this->setVar('PROBLEM', $value);
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setExplanation($value = ''){
            $this->setVar('EXPLANATION', $value);
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setVeredict($value = ''){
            $this->setVar('VEREDICT', $value);
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setReturn($value = '', $tag = ''){
            if ($value != '') {
                $this->setVar('RETURN', $value);
                $this->setVar('RETURN_TAG', ($tag == ''?__('Back'):$tag));
            }
        }

        /**
         * Generates HTML based on a template. If none is specified, Motte's default template will be used.
         *
         * @return string (HTML)
         */
        public function fetchHtml(){
            if ($this->getTemplate() == ''){
                $this->setTemplateDir(MTE_TEMPLATE);
                $this->setTemplate('mteException.html');
            }
            return $this->getHtml();
        }
    }
?>