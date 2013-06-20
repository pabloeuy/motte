<?php
/**
 * Clase para dibujo de menues de opciones
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
    class mteMenu extends mteTemplate{

        /**
         * @access private
         * @var array
         */
        private $_options;

        /**
         * @access private
         * @var integer
         */
        private $_titCount;

        /**
         *
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            // Parent construct
            parent:: __construct($compileDir, $templateDir, $template);

            // Inicializo propiedades
            $this->clearOptions();
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
         * Clean options
         *
         * @access public
         */
        public function clearOptions(){
            $this->_options  = array();
            $this->_titCount = 0;
        }

        /**
         * Set Menu Title
         *
         * @access public
         * @param string $title
         */
        public function setTitle($title){
            $this->setVar('TITLE', $title);
        }

        /**
         *
         * @param string $title
         * @param string $parentKey
        */
        public function addTitle($title = '', $parentKey = ''){
            // control
            if (!empty($title)){
                // add tit
                $this->_titCount ++;
                if ($parentKey == ''){
                    $this->_options['TIT'.$this->_titCount] = array('type'=>'title', 'key' => 'TIT'.$this->_titCount, 'title' => $title, 'link' => '', 'subOptions' => array());
                }
                else{
                    $this->_options[$parentKey]['subOptions']['TIT'.$this->_titCount] = array('type'=>'title', 'key' => 'TIT'.$this->_titCount, 'title' => $title, 'link' => '', 'selected' => '');
                }
            }
        }

        public function selectOption($key){
            foreach ($this->_options as $keyOption => $option) {
                if ($keyOption == $key){
                    $this->_options[$keyOption]['selected'] = 1;
                }

                if (is_array($this->_options[$keyOption]['subOptions'])){
                    foreach ($this->_options[$keyOption]['subOptions'] as $keySubOption => $subOption) {
                        if ($keySubOption == $key){
                            $this->_options[$keyOption]['selected'] = 1;
                            $this->_options[$keyOption]['subOptions'][$keySubOption]['selected'] = 1;
                        }
                    }
                }
            }
        }

        /**
         * Add new option to menu
         *
         * @access public
         * @param string $key = '' Identificador del menu
         * @param string $title = '' Tiitulo a mostrar
         * @param string $link = '' Enlace
         * @param bool $selected Se muestra seleccionado
         */
        public function addOption($key = '', $title = '', $link = '', $selected = 0){
            if ((!empty($key)) && (!empty($title)) && (!empty($link))){
                $this->_options[$key] = array('type'=>'option', 'key'=>$key, 'title' => $title, 'link' => $link, 'selected' => $selected, 'subOptions' => array());
            }
        }

        /**
         * Add a sub-option under a menu option
         *
         * @access public
         * @param string $key = '' Identificador del menu
         * @param string $title = '' Titulo a mostrar
         * @param string $link = '' Enlace
         * @param string $parentKey = '' Identificador del menu padre
         * @param bool $selected Se muestra seleccionado
         */
        public function addSubOption($key = '', $title = '', $link = '', $parentKey = '', $selected = 0){
            if ((!empty($key)) && (!empty($title)) && (!empty($link)) && (!empty($parentKey))){
                $this->_options[$parentKey]['subOptions'][$key] = array('type'=>'option', 'key' => $key, 'title' => $title, 'link' => $link, 'selected' => $selected);
            }
        }


        /**
         * Generate menu HTML
         *
         * @access public
         * @param string $templateName = ''
         */
        public function fetchHtml(){
            if ($this->getTemplate() == ''){
                $this-> setTemplateDir(MTE_TEMPLATE);
                $this-> setTemplate('mteMenu.html');
            }
            $this->addVar('OPTIONS', $this->_options);
            return $this->getHtml();
        }
    }
?>