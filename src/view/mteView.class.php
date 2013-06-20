<?php
/**
 * View management class
 *
 * @filesource
 * @package motte
 * @subpackage controller
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mteView {

        /**
         *
         * @access private
         * @var array
         */
        public $_fields;

        /**
         *
         * @access private
         * @var string
         */
        private $_title;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_autoCalcFields;

        /**
         *
         * @access private
         * @var string
         */
        private $_submitText;

        /**
         *
         * @access private
         * @var string
         */
        private $_orderFieldGrid;

        /**
         *
         * @access private
         * @var string
         */
        private $_orderFieldForm;

        /**
         * @var mteController
         * @access private
         */
        private $_ctr;

        /**
         *
         * @var string
         * @access private
         */
        private $_fieldsExport;

        /**
         *
         * @var string
         * @access private
         */
        private $_headerExport;
        
        /**
         *
         * @access private
         * @var array
         */        
        private $_needConfirmation;
        
        /**
         *
         * @access private
         * @var array
         */        
        private $_msgConfirmation;

        /**
         * Constructor
         *
         * @access public
         * @return mteTableManager
         */
        function __construct($ctr = '') {
            // Inicializo
            $this->clearFields();
            $this->setAutoCalcFields(true);
            $this->setNeedConfirmation(false);
            $this->setController($ctr);
            $this->setOrderFieldGrid();
            $this->setOrderFieldForm();
            $this->setExportFields();
            $this->setExportHeader();
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                        P A R A M E T E R S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setTitle($value = ''){
            $this->_title = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getTitle(){
            return $this->_title;
        }
        
        /**
         * 
         * @param string $value
         * @access public
         */
        public function setNeedConfirmation($value = false, $msg = ''){
            $this->_needConfirmation = $value;
            $this->_msgConfirmation  = $msg;
        }        
        
        /**
         * 
         * @param string $value
         * @access public
         */
        public function getNeedConfirmation(){
            return $this->_needConfirmation;
        }        

        /**
         * 
         * @param string $value
         * @access public
         */
        public function getConfirmationMsg(){
            return $this->_msgConfirmation;
        }        
        
        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setOrderFieldForm($fields = ''){
            // parameters
            if ($fields != ''){
                if (!is_array($fields)){
                    $fields = $this->_exlodeParam($fields);
                }
            }
            $this->_orderFieldForm = $fields;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getOrderFieldForm(){
            return $this->_orderFieldForm;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getSubmitText(){
            return $this->_submitText;
        }

        /**
         *
         * @access public
		 * @param string
         * @return string
         */
        public function setSubmitText($submitTextString = ''){
            $this->_submitText = $submitTextString;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setOrderFieldGrid($fields = ''){
            // parameters
            if ($fields != ''){
                if (!is_array($fields)){
                    $fields = $this->_exlodeParam($fields);
                }
            }
            $this->_orderFieldGrid = $fields;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getOrderFieldGrid(){
            return $this->_orderFieldGrid;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setAutoCalcFields($value = 1){
            $this->_autoCalcFields = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getAutoCalcFields(){
            return $this->_autoCalcFields;
        }

        /**
         *
         *
         * @param mteController $ctr
         */
        public function setController($ctr){
            $this->_ctr = $ctr;
        }

        /**
         *
         *
         * @return mteController
         */
        public function getController(){
            return $this->_ctr;
        }

        /**
         *
         * @access private
         * @param string $param
         * @return array
         */
        private function _exlodeParam($param){
            $param = ereg_replace("\t", "",ereg_replace(" ", "",ereg_replace("\n", "", ereg_replace("\r", "", $param))));
            $result = explode(',',$param);
            if (is_array($result)){
                foreach ($result as $key=>$element){
                    $result[$key] = str_replace(' ','',$element);
                }
            }
            return $result;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                 F I E L D S     A D D     M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access protected
         */
        protected function clearFields(){
            $this->fields = array();
        }

         /**
          *
          * @access public
          * @param string $name
          */
        public function removeFormField($name){
            unset($this->_fields[$name]);
        }

         /**
          *
          * @access private
          * @param string $name
          * @param array $field
          */
        private function _addField($name, $formFieldType, $gridFieldType, $field){
            // add field
            $this->_fields[$name] = array();
            foreach ($field as $key => $value){
                $this->_fields[$name][$key] = $value;
            }

            $this->_fields[$name]['name']            = $name;
            $this->_fields[$name]['gridField']       = 0;
            $this->_fields[$name]['gridFieldType']   = $gridFieldType;
            $this->_fields[$name]['gridColumn']      = 1;
            $this->_fields[$name]['isFilter']        = 0;
            $this->_fields[$name]['isOrder']         = 0;
            $this->_fields[$name]['isOrderDefault']  = 0;
            $this->_fields[$name]['orderDir']        = 'ASC';
            $this->_fields[$name]['formFieldInsert'] = 0;
            $this->_fields[$name]['formFieldUpdate'] = 0;
            $this->_fields[$name]['formFieldDelete'] = 0;
            $this->_fields[$name]['formFieldView']   = 0;
            $this->_fields[$name]['formFieldType']   = $formFieldType;
            $this->_fields[$name]['exportField']     = 0;
            $this->_fields[$name]['exportFieldType'] = $gridFieldType;
            $this->_fields[$name]['exportColumn']    = 0;
            $this->_fields[$name]['readOnly']        = 0;
            $this->_fields[$name]['primaryKey']      = 0;
			$this->_fields[$name]['where']           = '';
			$this->_fields[$name]['order']           = '';
        }

        /**
         * Agrega un campo del tipo text
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = '100'
         * @param boolean $validate = 0
         */
        public function addFieldString($name, $title = '', $size = 30, $max = 100, $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_TEXT, mteConst::MTE_COLUMN_TEXT, array('title'=>$title, 'size'=>$size, 'max'=>$max, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo number
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = 100
         * @param boolean $validate = 0
         */
        public function addFieldDecimal($name, $title = '', $size = 30, $max = 100, $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_NUMBER, mteConst::MTE_COLUMN_NUMBER,array('title'=>$title, 'size'=>$size, 'max'=>$max, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo integer
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = 100
         * @param boolean $validate = 0
         */
        public function addFieldInteger($name, $title = '', $size = 30, $max = 100, $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_NUMBER, mteConst::MTE_COLUMN_NUMBER,array('title'=>$title, 'size'=>$size, 'max'=>$max, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo date
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param boolean $validate = 0
         */
        public function addFieldDate($name, $title = '', $validate = 0, $help = '', $dateFormat = ''){
			if($dateFormat == ""){
				$dateFormat = MTE_DATE_FORMAT;
			}
            $this->_addField($name, mteConst::MTE_FIELD_DATE, mteConst::MTE_COLUMN_DATE,array('title'=>$title, 'size'=>10, 'max'=>10, 'validate'=>$validate, 'help'=>$help, 'format'=>$dateFormat));
        }

        /**
         * Agrega un campo del tipo time
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param boolean $validate = 0
         */
        public function addFieldTime($name, $title = '', $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_TIME, mteConst::MTE_COLUMN_TIME,array('title'=>$title, 'size'=>8, 'max'=>8, 'validate'=>$validate, 'help'=>$help));
        }
        
        /**
         * Agrega un campo del tipo code
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = '100'
         * @param boolean $validate = 0
         */
        public function addFieldCode($name, $title = '', $size = 30, $max = 100, $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_CODE, mteConst::MTE_COLUMN_TEXT, array('title'=>$title, 'size'=>$size, 'max'=>$max, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo file
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param boolean $validate = 0
         */
        public function addFieldFile($name, $title = '', $maxKb = 0, $size = '50', $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_FILE, mteConst::MTE_COLUMN_TEXT,array('title'=>$title, 'value'=>$value, 'maxKb'=>$maxKb, 'size'=>$size, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo text
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $cols = '30'
         * @param integer $rows = '3'
         * @param boolean $validate = 0
         */
        public function addFieldText($name, $title = '', $cols = 30, $rows = 3, $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_TEXTAREA, mteConst::MTE_COLUMN_TEXT,array('title'=>$title, 'cols'=>$cols, 'rows'=>$rows, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega campo tipo imagen
         *
         * @param string $name
         * @param string $title
         * @param string $value
         * @param string $thumb
         * @param integer $maxKb
         * @param integer $size
         * @param boolean $validate
         */
        public function addFieldImage($name, $title = '', $maxKb = 0, $size = '10', $validate = 0, $help = ''){
            $this->_addField($name, mteConst::MTE_FIELD_IMAGE, mteConst::MTE_COLUMN_IMAGE,array('title'=>$title, 'maxKb'=>$maxKb, 'size'=>$size, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo text
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = '100'
         * @param boolean $validate = 0
         */
        public function addFieldBoolean($name, $title = '', $size = 30, $max = 100, $validate = 0, $help = ''){
            $this->_addField($name,mteConst::MTE_FIELD_TEXT, mteConst::MTE_COLUMN_TEXT,array('title'=>$title, 'size'=>$size, 'max'=>$max, 'validate'=>$validate, 'help'=>$help));
        }

        /**
         * Agrega un campo del tipo captchaText
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $cols = '30'
         * @param integer $rows = '3'
         * @param boolean $validate = 0
         */

        public function addFieldCaptchaText($question = '', $answer = '', $cols = 30, $max = 3, $help = ''){
            $this->_addField('captchaText', mteConst::MTE_FIELD_TEXT, mteConst::MTE_COLUMN_TEXT,array('title'=>$question, 'cols'=>$cols, 'rows'=>$rows, 'validate'=>true, 'help'=>$help, 'value'=>$answer));
        }

        /**
         * Agrega un campo del tipo subtitle
         *
         * @access public
         * @param string  $name
         * @param string  $title = ''
         * @param integer $size = '30'
         * @param integer $max = '100'
         * @param boolean $validate = 0
         */
        public function addFieldSubtitle($name, $title = ''){
            $this->_addField($name, mteConst::MTE_FIELD_SUBTITLE, mteConst::MTE_COLUMN_TEXT,array('title'=>$title));
        }


        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                 F I E L D S    M A N A G E R    M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setGridFieldType($name, $value = '') {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['gridFieldType'] = ($value == '')?mteConst::MTE_COLUMN_TEXT:$value;
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setExportFieldType($name, $value = '') {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['exportFieldType'] = ($value == '')?mteConst::MTE_COLUMN_TEXT:$value;
            }
        }


        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setFormFieldType($name, $value = '') {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['formFieldType'] = ($value == '')?mteConst::MTE_FIELD_TEXT:$value;
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *           G R I D  F I E L D   M A N A G E R    M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param string $name
         * @param boolean $value
         */
        public function setGridDefaultOrder($name, $order = '', $value = 1) {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['isOrderDefault'] = $value;
                $this->setOrderDir($name, $order);
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setOrderDir($name, $value = '') {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['orderDir'] = (strtoupper($value) == 'DESC')?'DESC':'ASC';
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setFieldName($name, $value = '') {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['fieldName'] = $value;
            }
        }

        /**
         *
         *
         * @access public
         * @param array $fields
         * @param boolean $value
         */
        public function setGridFields($fields, $value = 1) {
            // parameters
            if (!is_array($fields)){
                $fields = $this->_exlodeParam($fields);
            }

            $this->setOrderFieldGrid($fields);

            foreach ($fields as $key => $name){
                if (array_key_exists($name, $this->_fields)){
                    $this->_fields[$name]['gridField'] = $value;
                }
            }
        }

        /**
         *
         * @access public
         * @param array $fields
         * @param boolean $value
         */
        public function setGridColumnsHidden($fields, $value = 0) {
            // parameters
            if (!is_array($fields)){
                $fields = $this->_exlodeParam($fields);
            }

            foreach ($fields as $key => $name){
                if (array_key_exists($name, $this->_fields)){
                    $this->_fields[$name]['gridColumn'] = $value;
                }
            }
        }
 
       /**
         *
         *
         * @access public
         * @param array $fields
         * @param boolean $value
         */
        public function setGridColumnsFilter($fields, $value = 1) {
            // parameters
            if (!is_array($fields)){
                $fields = $this->_exlodeParam($fields);
            }

            foreach ($fields as $key => $name){
                if (array_key_exists($name, $this->_fields)){
                    $this->_fields[$name]['isFilter'] = $value;
                }
            }
        }

       /**
        *
        *
        * @access public
        * @param array $fields
        * @param boolean $value
        */
        public function setGridColumnsOrder($fields, $value = 1) {
            // parameters
            if (!is_array($fields)){
                $fields = $this->_exlodeParam($fields);
            }

            foreach ($fields as $key => $name){
                if (array_key_exists($name, $this->_fields)){
                    $this->_fields[$name]['isOrder'] = $value;
                }
            }
        }


        /**
         *
         * @access public
         * @return array
         */
        public function getFields(){
            return array_keys($this->_fields);
        }

        /**
         *
         * @access public
         * @return array
         */
        public function getGridFields(){
            // inicializo
            $result = array();

            // Orden
            if (is_array($this->getOrderFieldGrid())){
                // Recorro campos ordenados
                foreach ($this->getOrderFieldGrid() as $fieldName){
                    if ($this->_fields[$fieldName]['gridField']){
                        $result[] = $fieldName;
                    }
                }

            }
            else{
                // Recorro campos
                foreach ($this->_fields as $key=>$element){
                    if ($element['gridField']){
                        $result[] = $key;
                    }
                }
            }

            // devuelvo
            return $result;
        }

        /**
         *
         * @access public
         * @return array
         */
        public function getGridColumns(){
            // inicializo
            $result = array();
            // Orden
            if (is_array($this->getOrderFieldGrid())){
                // Recorro campos ordenados
                foreach ($this->getOrderFieldGrid() as $fieldName){
                    if ($this->_fields[$fieldName]['gridField']){
                        $result[] = array('name'=>$fieldName,
                                        'title'=>$this->_fields[$fieldName]['title'],
                                        'type'=>$this->_fields[$fieldName]['gridFieldType'],
                                        'primary'=>$this->_fields[$fieldName]['primaryKey'],
                                        'hidden'=>!$this->_fields[$fieldName]['gridColumn'],
                                        'order'=>$this->_fields[$fieldName]['isOrder'],
                                        'fieldName'=>$this->_fields[$fieldName]['fieldName']);
                    }
                }
            }
            else{
                // Recorro campos
                foreach ($this->_fields as $key=>$element){
                    if ($element['gridField']){
                        $result[] = array('name'=>$key,
                                        'title'=>$element['title'],
                                        'type'=>$element['gridFieldType'],
                                        'primary'=>$element['primaryKey'],
                                        'hidden'=>!$element['gridColumn'],
                                        'order'=>$element['isOrder'],
                                        'fieldName'=>$element['fieldName']);
                    }
                }
            }
            // devuelvo
            return $result;
        }

        /**
         *
         * @access public
         * @return array
         */
        public function getFilterField(){
            // inicializo
            $result = array();
            // Recorro campos
            foreach ($this->_fields as $key=>$element){
                if ($element['isFilter']){
                    $result[] = array('name'=>$element['fieldName'], 'title'=>$element['title']);
                }
            }
            // devuelvo
            return $result;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getOrderDefaultField(){
            // inicializo
            $result = '';
            // Recorro campos
            foreach ($this->_fields as $key=>$element){
                if ($element['isOrderDefault']){
                    $result = $element['fieldName'];
                }
            }

            // Si no hay por defecto tomo el primero
            if ($result == ''){
                foreach ($this->_fields as $key=>$element) {
                    if (($element['isOrder']) && ($result == '')){
                        $result = $element['fieldName'];
                    }
                }
            }

            // devuelvo
            return $result;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getOrderDir($fieldName){
            // inicializo
            $result = '';
            // Recorro campos
            foreach ($this->_fields as $key=>$element){
                if ($element['fieldName'] == $fieldName){
                    $result = $element['orderDir'];
                }
            }
            return $result;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *        F O R M    F I E L D    M A N A G E R    M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @param string $name
         * @param variant $valueChecked
         * @param variant $valueUnChecked
         */
        public function setFormFieldCheckBox($name, $valueChecked = true, $valueUnChecked = false){
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['formFieldType']     = mteConst::MTE_FIELD_CHECKBOX;
                $this->_fields[$name]['valueChecked']      = $valueChecked;
                $this->_fields[$name]['valueUnChecked']    = $valueUnChecked;
            }
        }

        /**
         *
         *
         * @param string $name
         */
        public function setFieldFormSelect($name, $where = '', $order = ''){
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['formFieldType'] = mteConst::MTE_FIELD_SELECT;
                if ( $where != ''){
                    $this->_fields[$name]['where'] = $where;
                }
                if ( $order != ''){
                    $this->_fields[$name]['order'] = $order;
                }
            }
        }

        /**
         *
         *
         * @param string $name
         */
        public function setFieldFormMultiSelect($name, $size = 4){
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['formFieldType'] = mteConst::MTE_FIELD_MULTISELECT;
                $this->_fields[$name]['size']          = $size;
            }
        }

        /**
         *
         *
         * @access public
         * @param array $fields
         * @param boolean $value
         */
        public function setFormFields($fields, $actions = 0, $value = 1) {
            if (!is_array($fields)){
                $fields = $this->_exlodeParam($fields);
            }

            if (!is_array($actions)){
                // Todos
                if ($actions == mteConst::MTE_ACT_ALL){
                    $actions = 	array(mteConst::MTE_ACT_INSERT, mteConst::MTE_ACT_UPDATE,mteConst::MTE_ACT_DELETE, mteConst::MTE_ACT_VIEW);
                    $this->setOrderFieldForm($fields);
                }
                else{
                    $actions = array($actions);
                }
            }

            foreach ($fields as $key => $name){
                if (array_key_exists($name, $this->_fields)){
                    // acciones
                    foreach ($actions as $keyA => $action){
                        if ($action == mteConst::MTE_ACT_INSERT){
                            $this->_fields[$name]['formFieldInsert'] = $value;
                        }
                        if ($action == mteConst::MTE_ACT_UPDATE) {
                            $this->_fields[$name]['formFieldUpdate'] = $value;
                        }
                        if ($action == mteConst::MTE_ACT_DELETE){
                            $this->_fields[$name]['formFieldDelete'] = $value;
                        }
                        if ($action == mteConst::MTE_ACT_VIEW){
                            $this->_fields[$name]['formFieldView'] = $value;
                        }
                    }
                }
            }
        }

        /**
         *
         * @access public
         * @return array
         */
        public function getFormFields($action){
            // inicializo
            $result = array();

            if (is_array($this->getOrderFieldForm())){
                // Recorro campos ordenados
                foreach ($this->getOrderFieldForm() as $fieldName){
                    if (($action == mteConst::MTE_ACT_INSERT) && ($this->_fields[$fieldName]['formFieldInsert'])){
                        $result[] = $this->_fields[$fieldName];
                    }
                    if (($action == mteConst::MTE_ACT_UPDATE) && ($this->_fields[$fieldName]['formFieldUpdate'])){
                        $result[] = $this->_fields[$fieldName];
                    }
                    if (($action == mteConst::MTE_ACT_DELETE) && ($this->_fields[$fieldName]['formFieldDelete'])){
                        $result[] = $this->_fields[$fieldName];
                    }
                    if (($action == mteConst::MTE_ACT_VIEW) && ($this->_fields[$fieldName]['formFieldView'])){
                        $result[] = $this->_fields[$fieldName];
                    }
                }
            }
            else{
                // Recorro campos
                foreach ($this->_fields as $name=>$element){
                    if (($action == mteConst::MTE_ACT_INSERT) && ($this->_fields[$name]['formFieldInsert'])){
                        $result[] = $element;
                    }
                    if (($action == mteConst::MTE_ACT_UPDATE) && ($this->_fields[$name]['formFieldUpdate'])){
                        $result[] = $element;
                    }
                    if (($action == mteConst::MTE_ACT_DELETE) && ($this->_fields[$name]['formFieldDelete'])){
                        $result[] = $element;
                    }
                    if (($action == mteConst::MTE_ACT_VIEW) && ($this->_fields[$name]['formFieldView'])){
                        $result[] = $element;
                    }
                }
            }
            // devuelvo
            return $result;
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setFormFieldReadOnly($name, $value = 1) {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['readOnly'] = $value;
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value
         */
        public function setFormFieldHidden($name) {
            if (array_key_exists($name, $this->_fields)){
                $this->_fields[$name]['formFieldType'] = mteConst::MTE_FIELD_HIDDEN;
            }
        }


        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *      E X P O R T   F I E L D    M A N A G E R    M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @param string $fields
         */
        public function setExportFields($fields = '') {
            $this->_fieldsExport = $fields;
        }

        /**
         *
         * @access public
         * @param string $header
         */
        public function setExportHeader($header = '') {
            $this->_headerExport = $header;
        }

   /**
        *
        * @access public
        * @param string $fields
        */
        public function getExportFields() {
            $result = $this->_fieldsExport;
            if ($result == '') {
                // Recorro array
                $aux = array();
                foreach ($this->_fields as $key=>$element){
                    if ($element['gridField'] && $element['gridColumn']){
                        $aux[] = $key;
                    }
                }
                // devuelvo
                $result = '|'.implode('|', $aux).'|';
            }
            return $result;
        }

        /**
         *
         * @access public
         * @param string $header
         */
        public function getExportHeader() {
            $result = $this->_headerExport;
            if ($result == '') {
                // Recorro array
                $aux = array();
                foreach ($this->_fields as $key=>$element){
                    if ($element['gridField'] && $element['gridColumn']){
                        $aux[] = $element['title'];
                    }
                }
                // devuelvo
                $result = '| **'.implode('** | **', $aux).'** |';
            }
            return $result;
        }
    }
?>