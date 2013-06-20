<?php
/**
 * Clase para crear canales de opciones
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

    class mteChannel extends mteTemplate {
        /**
         * Constructor
         *
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            parent:: __construct($compileDir, $templateDir, $template);
            $this->clearChannels();
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
         * clear channels
         *
         */
        public function clearChannels(){
            $this->clearVar('CHANNELS');
        }

        /**
         * Adds a new channel option
         *
         * @param string $key
         * @param string $title
         * @param string $link
         * @param string $target
         */
        public function addChannel($key, $title, $link, $target = ''){
            if(!empty($key) && !empty($title) && !empty($link)){
                $this->appendVar('CHANNELS', array('key' => $key, 'title' => $title, 'link' => $link, 'target' => $target));
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
                $this->setTemplate('mteChannel.html');
            }
            return $this->getHtml();
        }
    }
?>