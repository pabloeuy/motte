<?php
/**
 * Clase para crear una nueva pagina
 *
 * @filesource
 * @package motte
 * @subpackage view
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */
    class mtePage{

        /**
         *
         *
         * @access private
         * @var mteTemplate
         */
        private $_headerTpl;

        /**
         *
         * @access private
         * @var mteTemplate
         */
        private $_bodyTpl;

        /**
         *
         * @access private
         * @var mteTemplate
         */
        private $_footerTpl;

        /**
         *
         * @access private
         * @var mteTemplate
         */
        private $_pageTpl;

        /**
         * Constructor
         *
         * @access public
         * @param
         * @return mteConst
         */
        public function __construct($compileDir = '', $templateDir = '') {
            // Template Header HTML
            $this->_headerTpl = new mteTemplate($compileDir, $templateDir);
            $this->setCharset(MTE_SYSTEM_CHARSET);
            $this->setTitle('MOTTE');
            $this->addVarHeader('MTE_TEMPLATE', MTE_TEMPLATE);

            // Template Body
            $this->_bodyTpl = new mteTemplate($compileDir, $templateDir);

            // Template Footer
            $this->_footerTpl = new mteTemplate($compileDir, $templateDir);

            // Template Pagina
            $this->_pageTpl = new mteTemplate($compileDir, $templateDir);
            $this->setLanguage('es');

            $this->setFavicon(MTE_FAVICON);
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                             H E A D E R
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        public function setFavicon($favicon='') {
            $this->addVarHeader('FAVICON', $favicon);
        }

        /**
         * Setea template del <header>
         *
         * @access public
         * @param string $name = ''
         */
        public function setTemplateHeader($name = ''){
            $this->_headerTpl->setTemplate($name);
        }

        /**
         * Agrega meta tags
         *
         * @access public
         * @param string $name
         * @param string $content
         */
        public function addMetaTag($name, $content){
            // Si vienen parametros
            if (($name != '') && ($content != '')){
                $aux = $this->_headerTpl->getVar('META');
                if (!is_array($aux)){
                    $aux = array();
                }
                array_unshift($aux, array('name'=>$name, 'content'=>$content));
                $this->addVarHeader('META', $aux);
            }
        }

        /**
         * Agrega hojas de estilo
         *
         * @access public
         * @param string $css
         */
        public function addCssFile($css){
            if(!empty($css)){
                $aux = $this->_headerTpl->getVar('CSS');
                if (!is_array($aux)){
                    $aux = array();
                }
                array_unshift($aux, $css);
                $this->addVarHeader('CSS', $aux);
            }
        }

        /**
         * Agrega hojas de estilo para ser leidas por IE
         *
         * @access public
         * @param string $css
         */
        public function addCssIEFile($css){
            if(!empty($css)){
                $aux = $this->_headerTpl->getVar('CSS_IE');
                if (!is_array($aux)){
                    $aux = array();
                }
                array_unshift($aux, $css);
                $this->addVarHeader('CSS_IE', $aux);
            }
        }

        /**
         * Agrega archivos JS
         *
         * @access public
         * @param string $js
         */
        public function addJsFile($js){
            if(!empty($js)){
                $aux = $this->_headerTpl->getVar('JS');
                if (!is_array($aux)){
                    $aux = array();
                }
                array_unshift($aux, $js);
                $this->addVarHeader('JS', $aux);
            }
        }

        /**
         * Agrega una variable al Header
         *
         * @access public$compileDir
         * @param string $var
         * @param string $value
         */
        public function addVarHeader($var, $value){
            $this->_headerTpl->setVar($var, $value);
        }

        /**
         * Setea el titulo de la pagina
         * @access public
         * @param string $title = ''
         */
        public function setTitle($title = ''){
            $this->_headerTpl->setVar('TITLE', $title);
        }

        /**
         * Setea el charset de la pagina
         *
         * @access public
         * @param string $lang = ''
         */
        public function setCharset($charset = ''){
            $this->_headerTpl->setVar('CHARACTERSET', $charset);
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                            B O D Y
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Setea template del <body>
         *
         * @access public
         * @param string $name = ''
         */
        public function setTemplateBody($name = ''){
            $this->_bodyTpl->setTemplate($name);
        }

        /**
         * Agrega una variable al Body
         *
         * @access public
         * @param string $var
         * @param string $value
         */
        public function addVarBody($var, $value){
            $this->_bodyTpl->setVar($var, $value);
        }


        /**
         * Setea el menu
         *
         * @access public
         * @param string $html = ''
         */
        public function setMenu($html = ''){
            $this->addVarBody('MENU', $html);
        }

        /**
         * Setea la version
         *
         * @access public
         * @param string $html = ''
         */
        public function setSystemVersion($html = ''){
            $this->addVarBody('VERSION', $html);
        }

        /**
         * Setea channels
         *
         * @access public
         * @param string $html = ''
         */
        public function setChannels($html = ''){
            $this->addVarBody('CHANNELS', $html);
        }

        /**
         * Setea el contenido
         *
         * @access public
         * @param string $html = ''
         */
        public function setContent($html = ''){
            $this->addVarBody('CONTENT', $html);
        }

        /**
         * Setea el contenido mediante un archivo html
         *
         * @access public
         * @param string $file = ''
         */
        public function setContentFile($fileName = ''){
            if (is_readable($fileName)) {
                $file = @fopen($fileName, 'r');
                $html = @fread($file, filesize($fileName));
                fclose($file);
                $this->setContent($html);
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                          F O O T E R
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */
        /**
         * Setea template del footer
         *
         * @access public
         * @param string $name = ''
         */
        public function setTemplateFooter($name = ''){
            $this->_footerTpl->setTemplate($name);
        }

        /**
         * Agrega una variable al Footer
         *
         * @access public
         * @param string $var
         * @param string $value
         */
        public function addVarFooter($var, $value){
            $this->_footerTpl->setVar($var, $value);
        }

        /**
         * Setea el texto para el pie
         *
         * @access public
         * @param string $html = ''
         */
        public function setFooterContent($html = ''){
            $this->_footerTpl->setVar('FOOTER', $html);
        }


        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                              P A G E
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Setea template de la page
         *
         * @access public
         * @param string $name = ''
         */
        public function setTemplatePage($name = ''){
            $this->_pageTpl->setTemplate($name);
        }

        /**
         * Setea el lenguaje de la pagina
         *
         * @access public
         * @param string $lang = ''
         */
        public function setLanguage($lang = ''){
            $this->_pageTpl->setVar('LANG', $lang);
        }


        /**
         *
         */
        public function fetchHtml(){
            // Template Header

            if ($this->_headerTpl->getTemplate() == '') {
                // Si no viene template
                $this->_headerTpl->setTemplate('mtePageHeader.html');
                $this->_headerTpl->setTemplateDir(MTE_TEMPLATE);

                // Archivos
                $this->addMetaTag('generator', 'Motte '.MTE_URL);
                $this->addMetaTag('date', date('Y-m-d'));
                $this->addMetaTag('copyrigth', MTE_AUTHOR);
                $this->addCssFile(MTE_TEMPLATE.'/css/mtePage.css');
                $this->addCssFile(MTE_TEMPLATE.'/css/mteGrid.css');
                $this->addCssFile(MTE_TEMPLATE.'/css/mteFormDate.css');
                $this->addCssFile(MTE_TEMPLATE.'/css/mteForms.css');
                $this->addCssFile(MTE_TEMPLATE.'/css/mteBrowser.css');
                $this->addCssFile(MTE_TEMPLATE.'/css/mteTab.css');
                
                // javascripts (libs)
                $this->addJsFile(MOTTE_LIB.'/js/md5.js');
                
                // javascripts (defaults)
                $this->addJsFile(MOTTE_SRC.'/js/mteHTTP.js');
                
                $this->addJsFile(MTE_TEMPLATE.'/js/mteInit.js');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteMisc.js');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteForms.js');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteGrids.js');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteSelect.js');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteFormDate.js');
            }

            // Template Boody
            if ($this->_bodyTpl->getTemplate() == ''){
                $this->_bodyTpl->setTemplateDir(MTE_TEMPLATE);
                $this->_bodyTpl->setTemplate('mtePageBody.html');
            }

            // Template Footer
            if ($this->_footerTpl->getTemplate() == ''){
                $this->_footerTpl->setTemplateDir(MTE_TEMPLATE);
                $this->_footerTpl->setTemplate('mtePageFooter.html');
            }

            // Template Page
            if ($this->_pageTpl->getTemplate() == ''){
                $this->_pageTpl->setTemplateDir(MTE_TEMPLATE);
                $this->_pageTpl->setTemplate('mtePage.html');
            }
            $this->_pageTpl->setVar('HEADER', $this->_headerTpl->getHtml());
            $this->_pageTpl->setVar('BODY', $this->_bodyTpl->getHtml());
            $this->_pageTpl->setVar('FOOTER', $this->_footerTpl->getHtml());
            $this->_pageTpl->setVar('ZONE_LEGEND', __('Motte Powered'));

            // se dibuja
            return $this->_pageTpl->getHtml();
        }

        /**
         *
         */
        public function showHtml(){
            print $this->fetchHtml();
        }
    }
?>