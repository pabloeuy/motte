<?php
/**
 * Clase para dibujo de tablas html
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
    class mteGrid extends mteTemplate{

        /**
         *
         * @access private
         * @var array
         */
        private $_cols;

        /**
         *
         * @access private
         * @var array
         */
        private $_mteActions;

        /**
         *
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            // Parent construct
            parent:: __construct($compileDir, $templateDir, $template);

            // Inicializacion
            $this->_cols       = array();
            $this->_mteActions = array();
            $this->setVar('COLSPAN', 0);
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        private function _colspan($number){
            $this->setVar('COLSPAN', $this->getVar('COLSPAN')+$number);
        }

        /**
         * Setea el titulo para la tabla
         *
         * @param string $title
         * @access public
         */
        public function setTitle($title = ''){
            $this->setVar('TITLE', $title);
        }

        /**
         * Setea el link para cerrar la tabla
         *
         * @param string $url
         * @access public
         */
        public function setUrlClose($url = ''){
            $this->setVar('CLOSE', $url);
            $this->setVar('CLOSETAG', __("Close"));
        }

        /**
         * Coloca un mensaje de aviso
         *
         * @param string $text
         * @access public
         */
        public function setNotify($text = ''){
            if(!empty($text)){
                $this->setVar('NOTIFY', $text);
            }
        }

        /**
         * Coloca un mensaje de aviso
         *
         * @param string $text
         * @access public
         */
        public function setRecordLegend($text = ''){
            if(!empty($text)){
                $this->setVar('RECORDLEGEND', $text);
            }
        }


        /**
         * Coloca un mensaje de alerta
         *
         * @param string $text
         * @access public
         */
        public function setWarning($text = ''){
            if(!empty($text)){
                $this->addVar('WARNING', $text);
            }
        }

        /**
         * Agrega una nueva columna
         *
         * @param string $name
         * @param string $title
         * @param string $type = 'text'
         */
        public function addColumn($name, $title, $type = 'text', $primary = 0, $hide = 0, $order = '', $fieldname = '', $format = ''){
            if(!empty($name) && !empty($title)){
                $fieldname = ($fieldname == ''?$name:$fieldname);
                $this->_cols[] = array(	'name' => $name,
                                        'title' => $title,
                                        'type' => $type,
                                        'primary' => $primary,
                                        'hidden' => $hide,
                                        'order' => $order,
                                        'orderactive' => 0,
                                        'orderdir' => 'DESC',
                                        'fieldName' => $fieldname,
										'format' => $format);
                if($hide == 0){
                    $this->_colspan(1);
                }
            }
        }

        /**
         * add columns
         *
         * @param array $columns
         */
        public function addColumns($columns){
            if (is_array($columns)){
                foreach ($columns as $key=>$element){
					if($element['type'] == 'D' && $element['format']=='') {
						$element['format'] = MTE_DATE_FORMAT;
					}
                    $this->addColumn($element['name'], $element['title'], $element['type'],
                        $element['primary'], $element['hidden'], $element['order'],
                        $element['fieldName'], $element['format'] );
                }
            }
        }

        /**
         * Define una columna como hidden
         *
         * @param string $name
         * @param bool $hide = 0
         */
        public function setColumnHidden($name = '', $hide = 0){
            if(!empty($name)){
                foreach($this->_cols as $key => $element){
                    if($element['name'] == $name){
                        $this->_cols[$key]['hidden'] = $hide;
                    }
                }
                if($hide == 1){
                    $this->_colspan(-1);
                }
            }
        }

        /**
         * Define una columna como primaria
         *
         * @param string $name
         * @param bool $key = 0
         */
        public function setColumnKey($name = '', $primary = 0){
            if(!empty($name)){
                foreach($this->_cols as $key => $element){
                    if($element['name'] == $name){
                        $this->_cols[$key]['primary'] = $primary;
                    }
                }
            }
        }

        /**
         * Define si una columna esta ordenada
         *
         * @param string $key = ''
         */
        public function setColumnOrder($name = '', $orderDir){
            if(!empty($name) && !empty($orderDir)){
                foreach($this->_cols as $key => $element){
                    if($element['fieldName'] == $name){
                        $this->_cols[$key]['orderactive'] = 1;
                        $this->_cols[$key]['orderdir'] 	  = $orderDir;
                    }
                }
            }
        }

        /**
         * Pasa el link para ordenar las columnas
         *
         * @param string $link
         */
        public function setColumnLink($link){
            $this->addVar('COL_LINK', $link);
        }

        /**
         * Agrega las acciones a realizar por cada fila
         *
         * @param string $field
         * @param string $action
         */
        public function addMteAction($action, $title, $cssStyle = ''){
            // Agrego accion
            if(!empty($title) && !empty($action)){
                // Estilos por defecto
                if ($action == mteConst::MTE_ACT_UPDATE){
                    $cssStyle = (empty($cssStyle)?'Edit':$cssStyle);
                }
                if ($action == mteConst::MTE_ACT_DELETE){
                    $cssStyle = (empty($cssStyle)?'Delete':$cssStyle);
                }
                if ($action == mteConst::MTE_ACT_VIEW){
                    $cssStyle = (empty($cssStyle)?'View':$cssStyle);
                }
                $this->_mteActions[] = array('action'=>$action, 'title'=>$title, 'cssStyle'=>$cssStyle);
            }
        }

        /**
         * Agrega los datos
         *
         * @param array $fields
         */
        public function addData($data){
            if(is_array($data)){
                $this->addVar('DATA', $data);
            }
        }

        /**
         *
         *
         */
        public function fetchHtml($paramUrl = '', $primaryKeys = ''){
            $primaryKeys = (!is_array($primaryKeys)?array($primaryKeys):$primaryKeys);
            $paramUrl = ((!$paramUrl instanceof mteUrl)?new mteUrl():$paramUrl);
            $paramOrder = clone $paramUrl;

            // Control de template
            if ($this->getTemplate() == ''){
                $this->setTemplateDir(MTE_TEMPLATE);
                $this->setTemplate('mteGrid.html');
            }

            // Genero campo para acciones motte
            if (count($this->_mteActions) > 0) {
                // Agrego columna de acciones
                $this->addVar('ACTION', 1);
                $this->addColumn('_mteaction_', '&nbsp;');
                $this->_colspan(1);

                // Genero Datos
                $data = $this->getVar('DATA');
                foreach ($data as $key => $element) {
                    // Genero id;
                    $id = array();
                    foreach ($primaryKeys as $field){
                        $id[$field] = $element[$field];
                    }
                    // Genero acciones
                    $actions = array();
                    foreach ($this->_mteActions as $action) {
                        $paramUrl->setParamAction($action['action']);
                        $paramUrl->setParamActionId($id);
                        $actions[] = array('url'=>$paramUrl->getUrl(), 'css'=>$action['cssStyle'], 'title'=>$action['title']);
                    }
                    $data[$key]['_mteaction_'] = $actions;
                }

                // Cargo Data
                $this->clearVar('DATA');
                $this->setVar('DATA', $data);
            }

            foreach($this->_cols as $key => $element){
                if ($element['order']){
                    $aux = 'ASC';
                    if ($element['orderdir'] == 'ASC'){
                        $aux = 'DESC';
                    }
                    $paramOrder->setParamGridPage(1);
                    $paramOrder->setParamGridOrderField($element['fieldName']);
                    $paramOrder->setParamGridOrderDir($aux);
                    $this->_cols[$key]['orderurl'] = $paramOrder->getUrl();
                }
            }
            $this->addVar('COLS', $this->_cols);

            return $this->getHtml();
        }


        /**
         * Add pages combo
         *
         * @param array $pages
         */
        public function gridAddNavPage($pages){
            $this->addVar('PAGES', $pages);
        }

        /**
         * Add pages combo to nav. form
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function gridAddNavPageItem($name = '', $value = ''){
            $this->appendVar('PAGES', array('name' => $name, 'value' => $value));
            $this->setVar('APPLY_TAG', __('Go'));
        }

        /**
         * Sets actual page
         *
         * @access public
         * @param integer $nro
         */
        public function gridSetPage($value = 0){
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
        public function gridSetPagesUrl($firstUrl = '', $prevUrl = '', $nextUrl = '', $lastUrl = ''){
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
        public function gridSetPagesUrlFirst($url = ''){
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
        public function gridSetPagesUrlPrev($url = ''){
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
        public function gridSetPagesUrlNext($url = ''){
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
        public function gridSetPagesUrlLast($url = ''){
            if(!empty($url)){
                $this->setVar('PAGES_URL_LAST', $url);
                $this->setVar('LAST_PAGE_TAG', __('Last page'));
            }
        }
}
?>
