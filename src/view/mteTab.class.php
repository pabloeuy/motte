<?php
/**
 * Clase para las solapas
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

    class mteTab extends mteTemplate{

        /**
         * Inicializa la clase con los datos de archivo y dir
         *
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            // Parent construct
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
         * Limpia canales
         *
         * @access public
         */
        public function clearTabs(){
            $this->clearVar('TABS');
        }

        /**
         * Agrega una nueva solapa
         *
         * @access public
         * @param string $title
         * @param string $link
         * @param bool $sel
         */
        public function addTab($title = '', $link = '', $sel = 0){
            if ((!empty($title)) && (!empty($link))){
                $this->appendVar('TABS', array('title' => $title, 'link' => $link, 'selected' => $sel));
            }
        }

        /**
         * Agrega el contenido a las solapas
         *
         * @access public
         * @param string $content
         */
        public function setContent($content = ''){
            $this->setVar('CONTENT', $content);
        }

        /**
         * Devuelve el html de las solapas
         *
         * @access public
         * @param string $templateName = ''
         */
        public function fetchHtml(){
            if ($this->getTemplate() == ''){
                $this-> setTemplateDir(MTE_TEMPLATE);
                $this-> setTemplate('mteTab.html');
            }
            return $this->getHtml();
        }
    }
?>