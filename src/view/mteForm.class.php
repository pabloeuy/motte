<?php
/**
 *
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
 *
 */

    class mteForm extends mteTemplate{

        /**
         *
         * @access private
         * @var array
         */
        private $_fields;

        /**
         *
         * @access private
         * @var array
         */
        private $_templates;

        /**
         *
         * @access private
         * @var array
         */
        private $_dirTemplates;
        
        /**
         *
         * @access public
         */
        public function __construct($compileDir, $templateDir = '', $template = ''){
            parent:: __construct($compileDir, $templateDir, $template);
            $this->setUrlPost();
            $this->_clearFields();
            $this->_clearTemplates();
            $this->needConfirmation(false);
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
         *                        P R O P E R T I E S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */
        /**
         * Setea el titulo para el formulario
         * @param string $value
         * @access public
         */
        public function setTitle($value = ''){
            $this->setVar('TITLE', $value);
        }
        
        /**
         * 
         * @param string $value
         * @access public
         */
        public function needConfirmation($value = false){
            $this->setVar('CONFIRMATION', $value);
        }
        
        /**
         * 
         * @param string $value
         * @access public
         */
        public function confirmationMsg($value = ''){
            $this->setVar('CONFIRMATION_MSG', $value);
        }
        
        
        /**
         * Setea el subtitulo para el formulario
         *
         * @param string $value
         * @access public
         */
        public function setSubtitle($value = ''){
            $this->setVar('SUBTITLE', $value);
        }

        /**
         * Setea el nombre para el formulario
         *
         * @param string $value
         * @access public
         */
        public function setName($value = 'mteForm_'){
            $this->setVar('NAME', $value);
        }

        /**
         * Setea el la url para el post
         *
         * @param string $value
         * @access public
         */
        public function setUrlPost($value = '#'){
            $this->setVar('POST', $value);
        }

        /**
         * Setea el link para cerrar el formulario
         *
         * @param string $value
         * @access public
         */
        public function setUrlClose($value = ''){
            $this->setVar('CLOSE', $value);
            $this->setVar('CLOSETAG', __('Close'));
        }

        /**
         * Coloca un mensaje de aviso
         *
         * @param string $value
         * @access public
         */
        public function setNotify($value = ''){
            if(!empty($value)){
                $this->setVar('NOTIFY', $value);
            }
        }

        /**
         * Coloca un mensaje de alerta
         *
         * @param string $value
         * @access public
         */
        public function setWarning($value = ''){
            if(!empty($value)){
                $this->setVar('WARNING', $value);
            }
        }

        /**
         * Coloca un mensaje de leyenda
         *
         * @param string $value
         * @access public
         */
        public function setLegend($value = ''){
            if(!empty($value)){
                $this->setVar('LEGEND', $value);
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *           T E M P L A T E   M A N A G E M E N T   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access private
         */
        private function _clearTemplates(){
            $this->setTemplateField(mteConst::MTE_FIELD_TEXT, MTE_TEMPLATE, 'mteFormText.html');
            $this->setTemplateField(mteConst::MTE_FIELD_NUMBER, MTE_TEMPLATE, 'mteFormNumber.html');
            $this->setTemplateField(mteConst::MTE_FIELD_TEXTAREA, MTE_TEMPLATE, 'mteFormTextArea.html');
            $this->setTemplateField(mteConst::MTE_FIELD_CHECKBOX, MTE_TEMPLATE, 'mteFormCheckBox.html');
            $this->setTemplateField(mteConst::MTE_FIELD_SELECT, MTE_TEMPLATE, 'mteFormSelect.html');
            $this->setTemplateField(mteConst::MTE_FIELD_MULTISELECT, MTE_TEMPLATE, 'mteFormTMultiSelect.html');
            $this->setTemplateField(mteConst::MTE_FIELD_FILE, MTE_TEMPLATE, 'mteFormFile.html');
            $this->setTemplateField(mteConst::MTE_FIELD_IMAGE, MTE_TEMPLATE, 'mteFormImage.html');
            $this->setTemplateField(mteConst::MTE_FIELD_HIDDEN, MTE_TEMPLATE, 'mteFormHidden.html');
            $this->setTemplateField(mteConst::MTE_FIELD_PASSWORD, MTE_TEMPLATE, 'mteFormPassword.html');
            $this->setTemplateField(mteConst::MTE_FIELD_SUBMIT, MTE_TEMPLATE, 'mteFormSubmit.html');
            $this->setTemplateField(mteConst::MTE_FIELD_DATE, MTE_TEMPLATE, 'mteFormDate.html');
            $this->setTemplateField(mteConst::MTE_FIELD_TIME, MTE_TEMPLATE, 'mteFormTime.html');
            $this->setTemplateField(mteConst::MTE_FIELD_CODE, MTE_TEMPLATE, 'mteFormCode.html');
            $this->setTemplateField(mteConst::MTE_FIELD_SUBTITLE, MTE_TEMPLATE, 'mteFormSubtitle.html');
            $this->setTemplateField(mteConst::MTE_FIELD_CAPTCHATEXT, MTE_TEMPLATE, 'mteFormCaptchaText.html');
            $this->setTemplateField(mteConst::MTE_FIELD_DESCRIPTION, MTE_TEMPLATE, 'mteFormDescription.html');
        }

         /**
          *
          * @access public
          * @param string $fieldType
          * @param string $template
          */
        public function setTemplateField($fieldType, $dirTemplate = '', $template = ''){
            $this->_templates[$fieldType]    = $template;
            $this->_dirTemplates[$fieldType] = $dirTemplate;
        }

        /**
         *
         * @access public
         * @param string $fieldType
         * @return string
         */
        public function getTemplateField($fieldType){
            return $this->_templates[$fieldType];
        }

         /**
          *
          * @access public
          * @param string $fieldType
          * @return string
          */
        public function getDirTemplateField($fieldType){
            return $this->_dirTemplates[$fieldType];
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *           F I E L D   M A N A G E M E N T   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

         /**
          *
          * @access private
          * @param string $name
          * @param object $field
          */
        private function _addField($name, $field){
            $this->_fields[$name] = $field;
        }

        /**
         *
         * @access private
         */
        private function _clearFields(){
            $this->_fields = array();
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
         * Add field text
         * @access public
         *
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldText($name, $title = '', $value = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_TEXT, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }


        /**
         *
         * @access public
         *
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $help
         */
        public function addFieldCaptchaText($title = '', $value = '', $size = 30, $max = 100, $help = ''){
            $field = new mteFormField(mteConst::MTE_FIELD_CAPTCHATEXT, $this->getCompileDir());
            $field->setName('captchaText');
            $field->setTitle((empty($title))?'2 + 2 =':$title);
            $field->setValue((empty($value))?'cuatro:4':$value);
            $field->setSize($size);
            $field->setMaxSize($max);
            $field->setHaveConstrains(true);
            $field->setHelp($help);
            $field->setIsReadOnly(false);

            $this->_addField('captchatext', $field);
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldDate($name, $title = '', $value = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = '', $format = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_DATE, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSpecialProperties('START_YEAR', !empty($startYear)?$startYear:1930);
                $field->setSpecialProperties('END_YEAR', !empty($startYear)?$startYear:date('Y') + 1);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);
				$field->setFormat( ($format == '')?MTE_DATE_FORMAT:$format );

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldTime($name, $title = '', $value = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_TIME, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }
        
        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldCode($name, $title = '', $value = '', $valueSearch = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_CODE, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSpecialProperties('VALUE_SEARCH', $valueSearch);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldPassword($name, $title = '', $value = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_PASSWORD, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $size
         * @param <type> $max
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldNumber($name, $title = '', $value = '', $size = 30, $max = 100, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_NUMBER, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $cols
         * @param <type> $rows
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldTextArea($name, $title = '', $value = '', $cols = 30, $rows = 3, $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_TEXTAREA, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);
                $field->setSpecialProperties('COLS', $cols);
                $field->setSpecialProperties('ROWS', $rows);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $checked
         * @param <type> $labelChecked
         * @param <type> $labelUnChecked
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldCheckBox($name, $title = '', $checked = '0', $labelChecked = 1, $labelUnChecked = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_CHECKBOX, $this->getCompileDir());
                $field->setName($name);
                $field->setValue($checked);
                $field->setTitle((empty($title))?$name:$title);
                $field->setIsReadOnly($readOnly);
                $field->setHelp($help);
                $field->setSpecialProperties('LABEL_CHECKED', $labelChecked);
                $field->setSpecialProperties('LABEL_UNCHECKED', $labelUnChecked);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $options
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldSelect($name, $title = '', $value = '', $options = '', $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_SELECT, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setOptions($options);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $options
         * @param <type> $size
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldMultiSelect($name, $title = '', $value = '', $options = '', $size = 4, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_MULTSELECT, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($value);
                $field->setOptions($options);
                $field->setSize($size);
                $field->setMaxSize($max);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $value
         * @param <type> $maxKb
         * @param <type> $size
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldFile($name, $title = '', $value = '', $maxKb = 0, $size = '50', $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_FILE, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setSize($size);
                $field->setMaxSize((!empty($maxKb))?$maxKb:mteConst::MTE_MAX_SIZE * 1024);
                $field->setHaveConstrains($validate);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);
                $field->setSpecialProperties('URL', $value);
                $field->setSpecialProperties('FILE_NAME', (!empty($value))?substr($value, strrpos($value, '/') + 1, strlen($value)):'');

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $title
         * @param <type> $thumb
         * @param <type> $maxKb
         * @param <type> $size
         * @param <type> $validate
         * @param <type> $readOnly
         * @param <type> $help
         */
        public function addFieldImage($name, $title = '', $thumb = '', $maxKb = 0, $size = '10', $validate = 0, $readOnly = 0, $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_IMAGE, $this->getCompileDir());
                $field->setName($name);
                $field->setTitle((empty($title))?$name:$title);
                $field->setValue($thumb);
                $field->setSize($size);
                $field->setMaxSize((!empty($maxKb))?$maxKb:mteConst::MTE_MAX_SIZE * 1024);
                $field->setHelp($help);
                $field->setIsReadOnly($readOnly);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value = ''
         */
        public function addFieldHidden($name, $value = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_HIDDEN, $this->getCompileDir());
                $field->setName($name);
                $field->setValue($value);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name
         * @param string $value = ''
         */
        public function addFieldSubtitle($name, $value = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_SUBTITLE, $this->getCompileDir());
                $field->setName($name);
                $field->setValue($value);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param <type> $name
         * @param <type> $value
         */
        public function addFieldDescription($name, $value = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_DESCRIPTION, $this->getCompileDir());
                $field->setName($name);
                $field->setValue($value);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @access public
         * @param string $name = ''
         * @param string $title = ''
         * @param string $help = ''
         */
        public function addFieldSubmit($name = '', $title = '', $help = ''){
            if (!empty($name)){
                $field = new mteFormField(mteConst::MTE_FIELD_SUBMIT, $this->getCompileDir());
                $field->setName($name);
                $field->setHelp($help);
                $field->setTitle((empty($title))?$name:$title);

                $this->_addField($name, $field);
            }
        }

        /**
         *
         *
         * @param string $fieldName
         * @param variant $value
         */
        private function setFieldData($fieldName, $value = ''){
            if (array_key_exists($fieldName, $this->_fields)) {
                if (method_exists($this->_fields[$fieldName],'setValue')){
                    $this->_fields[$fieldName]->setValue($value);
                }
            }
        }

        /**
         *
         *
         * @param string $fieldName
         * @param boolean $value
         */
        public function setFieldIsReadOnly($fieldName, $value = 0){
            if (array_key_exists($fieldName, $this->_fields)) {
                if (method_exists($this->_fields[$fieldName],'setIsReadOnly')){
                    $this->_fields[$fieldName]->setIsReadOnly($value);
                }
            }
        }

        /**
         *
         *
         * @param string $fieldName
         * @param array $value
         */
        private function setFieldOptions($fieldName, $value = ''){
            if (array_key_exists($fieldName, $this->_fields)) {
                if (method_exists($this->_fields[$fieldName],'setOptions')){
                    $this->_fields[$fieldName]->setOptions($value);
                }
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *           F O R M   M A N A G E M E N T   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

         /**
          *
          *
          * @param boolean $value
          */
        public function setIsReadOnly($value = 0){
            foreach ($this->_fields as $name => $field){
                $this->setFieldIsReadOnly($name, $value);
            }
        }

         /**
          *
          *
          * @param array $data
          */
        public function setFormData($data){
            if (is_array($data)){
                foreach ($this->_fields as $fieldName => $field){
                    if (array_key_exists($fieldName, $data)){
                        $this->setFieldData($fieldName, $data[$fieldName]);
                    }
                }
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                        G U I   M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

         /**
          *
          *
          * @access public
          * @return string
          */
        public function fetchHtmlFields(){
            $htmlFields = '';
            foreach ($this->_fields as $fieldName => $field){
                if (method_exists($this->_fields[$fieldName],'fetchHtml')){
                    $this->_fields[$fieldName]->setTemplateDir($this->getDirTemplateField($this->_fields[$fieldName]->getType()));
                    $this->_fields[$fieldName]->setTemplate($this->getTemplateField($this->_fields[$fieldName]->getType()));
                    $htmlFields .= $this->_fields[$fieldName]->fetchHtml();
                }
            }
            return $htmlFields;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function fetchHtml(){
            if ($this->getTemplate() == ''){
                $this->setTemplateDir(MTE_TEMPLATE);
                $this->setTemplate('mteForm.html');
            }
            $this->setVar('FIELDS', $this->fetchHtmlFields());
            return $this->getHtml();
        }
    }
?>