<?php
/**
 * HTML Navigation class
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

    class mteBrowser extends mteTemplate{
        /**
         * Constructor
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            parent:: __construct($compileDir, $templateDir, $template);
        }

        /**
        * Destructor
        * @access public
        */
        public function __destruct(){
        }

        /**
         * Defines name for Navigation form
         *
         * @access public
         * @param string $name
         */
        public function setName($name = ''){
            if(!empty($name)){
                $this->setVar('NAME', $name);
            }
        }

        /**
         * Defines if filter form is to be shown
         *
         * @access public
         * @param bool $show
         */
        public function showFilter($show = 1){
            $this->setVar('FILTER', ($show == 1?1:0));
        }

        /**
         * Add one filter field
         *
         * @access public
         * @param string $name
         * @param string $title
         */
        public function addFilterField($name = '', $title = ''){
            if(!empty($name)){
                $this->appendVar('FILTER_FIELDS', array('name' => $name, 'title' => (empty($title))?$name:$title));
            }
        }

        /**
         * Add multiple filter fields from an array. Uses addFilterField
         *
         * @param array $fields
         */
        public function addFilterFields($fields){
            if (is_array($fields)){
                foreach ($fields as $key=>$element){
                    $this->addFilterField($element['name'], $element['title']);
                }
            }
        }

        /**
         * Sets active filter
         *
         * @access public
         * @param string $name
         */
        public function setFilterSelected($name = ''){
            if(!empty($name)){
                $this->setVar('FILTER_SEL', $name);
            }
        }

        /**
         * Sets search criteria
         *
         * @access public
         * @param string $keyword
         */
        public function setFilterKeyword($keyword = ''){
            $this->setVar('FILTER_KEYWORD', $keyword);
        }

        /**
         * Sets URL form filter form
         *
         * @access public
         * @param string $url
         */
        public function setFilterUrl($url = ''){
            if (!empty($url)){
                $this->setVar('FILTER_URL', $url);
                $this->setVar('APPLY_FILTER_TAG', __('Aplicar filtro'));
                $this->setVar('REMOVE_FILTER_TAG', __('Quitar filtro'));
            }
        }

        /**
         * Adds action fields (new record, print preview, export)
         *
         * @access public
         * @param string $title
         * @param string $link
         */
        public function addAction($name = '', $link = ''){
            // css
            if ($name == mteConst::MTE_ACT_INSERT){
                $name = mteConst::MTE_ACTION_INSERT;
            }
            if ($name == mteConst::MTE_ACT_EXPORT){
                $name = mteConst::MTE_ACTION_PDF;
            }

            if(!empty($name) && !empty($link)){
                $this->appendVar('ACTIONS', array('name' => $name, 'link' => $link));
                $this->setVar('ACTION', 1);
            }
        }

        /**
         * Add pages combo
         *
         * @param array $pages
         */
        public function addNavPage($pages){
            $this->addVar('PAGES', $pages);
        }

        /**
         * Add pages combo to nav. form
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function addNavPageItem($name = '', $value = ''){
            $this->appendVar('PAGES', array('name' => $name, 'value' => $value));
            $this->setVar('APPLY_TAG', __('Go'));
        }

        /**
         * Sets actual page
         *
         * @access public
         * @param integer $nro
         */
        public function setPage($value = 0){
            if(is_integer($value)){
                $this->setVar('PAGE', $value);
            }
        }

        /**
         * Sets link for page on nav form
         *
         * @access public
         * @param string $firstUrl
         * @param string $prevUrl
         * @param string $nextUrl
         * @param string $lastUrl
         */
        public function setPagesUrl($firstUrl = '', $prevUrl = '', $nextUrl = '', $lastUrl = ''){
            $this->setPagesUrlFirst($firstUrl);
            $this->setPagesUrlPrev($prevUrl);
            $this->setPagesUrlNext($nextUrl);
            $this->setPagesUrlLast($lastUrl);
        }

        /**
     * Sets link for first page  on nav form
         *
         * @access public
         * @param string $url
         */
        public function setPagesUrlFirst($url = ''){
            if(!empty($url)){
                $this->setVar('PAGES_URL_FIRST', $url);
                $this->setVar('FIRST_PAGE_TAG', __('First page'));
            }
        }

        /**
         * Sets link for previous page on nav form
         *
         * @access public
         * @param string $url
         */
        public function setPagesUrlPrev($url = ''){
            if(!empty($url)){
                $this->setVar('PAGES_URL_PREV', $url);
                $this->setVar('PREV_PAGE_TAG', __('Previuos page'));
            }
        }

        /**
         * Sets link for next page on nav form
         *
         * @access public
         * @param string $url
         */
        public function setPagesUrlNext($url = ''){
            if(!empty($url)){
                $this->setVar('PAGES_URL_NEXT', $url);
                $this->setVar('NEXT_PAGE_TAG', __('Next page'));
            }
        }

        /**
         * Sets link for last page on nav form
         *
         * @access public
         * @param string $url
         */
        public function setPagesUrlLast($url = ''){
            if(!empty($url)){
                $this->setVar('PAGES_URL_LAST', $url);
                $this->setVar('LAST_PAGE_TAG', __('Last page'));
            }
        }

        /**
         * Generates HTML based on a template. If none is specified, will use Motte's default template.
         *
         * @access public
         * @param string $templateName = ''
         */
        public function fetchHtml(){
            // Control de template
            if ($this->getTemplate() == ''){
                $this ->setTemplateDir(MTE_TEMPLATE);
                $this ->setTemplate('mteBrowser.html');
            }
            // se dibuja
            return $this->getHtml();
        }
    }
?>