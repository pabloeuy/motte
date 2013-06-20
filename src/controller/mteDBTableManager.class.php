<?php
/**
 * Table management class
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

    class mteDBTableManager {

        /**
         *
         * @access private
         * @var string
         */
        private $_tplDir;

        /**
         *
         * @access private
         * @var string
         */
        private $_tplCacheDir;

        /**
         *
         * @access private
         * @var mteCnx
         */
        private $_cnx;

        /**
         * @access private
         * @var array
         */
        private $_params;

        /**
         * @access private
         * @var mteTableSql
         */
        private $_model;

        /**
         * @access private
         * @var mteView
         */
        private $_view;

        /**
         *
         * @access private
         * @var string
         */
        private $_error;

        /**
         *
         * @access private
         * @var string
         */
        private $_typeView;

        /**
         * @access private
         * @var integer
         */
        private $_rows;

        /**
         *
         * @access private
         * @var string
         */
        private $_requestMethod;

        /**
         *
         * @access private
         * @var string
         */
        private $_templateGrid;

        /**
         *
         * @access private
         * @var string
         */
        private $_templateForm;

        /**
         *
         * @access private
         * @var string
         */
        private $_templateBrowser;

        /**
         *
         * @access private
         * @var string
         */
        private $_warning;

        /**
         *
         * @access private
         * @var string
         */
        private $_notify;

        /**
         *1
         * @access private
         * @var string
         */
        private $_legend;

        /**
         *
         * @access private
         * @var string
         */
        private $_urlClose;

        /**
         *
         * @access privateName
         * @var string
         */
        private $_urlPost;


        /**
         *
         * @access private
         * @var string
         */
        private $_title;

        /**
         *
         * @access private
         * @var string
         */
        private $_action;

        /**
         *
         * @access private1
         * @var array
         */
        private $_templateFormField;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_browser;


        /**
         *
         * @access private
         * @var string
         */
        private $_methodGrid;

        /**
         *
         * @access private
         * @var string
         */
        private $_methodForm;

        /**
         *
         * @access private
         * @var string
         */
        private $_methodFilter;

        /**
         *
         * @access private
         * @var string
         */
        private $_methodExport;

        /**
         *
         * @access private
         * @var string
         */
        private $_defaultFilter;

        /**
         *
         *
         * @access private
         * @var string
         *
         */
        private $_whereUpdate;

        /**
         *
         * @access private
         * @var string
         */
        private $_gridActions;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportFormat;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportOrientation;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportFont;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportFontSize;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportSubtitle;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_exportHeader;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportHeaderSize;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportHeaderTitle;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportHeaderComment1;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportHeaderComment2;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_exportFooter;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportFooterLeft;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportFooterRigth;

        /**
         *
         * @access private
         * @var string
         */
        private $_exportCalcColumSize;

        /**
         * Margin Left
         *
         * @var integer
         */
        private $_exportMarginLeft;

        /**
         * Margin Rigth
         *
         * @var integer
         */
        private $_exportMarginRigth;

        /**
         * Margin Top
         * @var integer
         *
         */
        private $_exportMarginTop;

        /**
         * Margin Bottom
         *
         * @var integer
         */
        private $_exportMarginBottom;


        /**
         * Constructor
         *
         * @access public
         * @return mteDBTableManager
         */
        public function __construct($cnx, $tplCacheDir, $tplDir, $params, $ctr = '', $model = '', $view = '') {
            // inicialize
            $this->_action = array();
            $this->_action[mteConst::MTE_ACT_INSERT] = '';
            $this->_action[mteConst::MTE_ACT_UPDATE] = '';
            $this->_action[mteConst::MTE_ACT_DELETE] = '';
            $this->_action[mteConst::MTE_ACT_VIEW]   = '';
            $this->_action[mteConst::MTE_ACT_EXPORT] = '';
            $this->_action[mteConst::MTE_ACT_FILTER] = '';

            // templates
            $this->setTemplateDir($tplDir);
            $this->setTplCacheDir($tplCacheDir);
            $this->setTemplateGrid();
            $this->setTemplateForm();
            $this->setTemplateBrowser();
            $this->setTemplateFormField(mteConst::MTE_FIELD_TEXT);
            $this->setTemplateFormField(mteConst::MTE_FIELD_NUMBER);
            $this->setTemplateFormField(mteConst::MTE_FIELD_TEXTAREA);
            $this->setTemplateFormField(mteConst::MTE_FIELD_CHECKBOX);
            $this->setTemplateFormField(mteConst::MTE_FIELD_SELECT);
            $this->setTemplateFormField(mteConst::MTE_FIELD_MULTISELECT);
            $this->setTemplateFormField(mteConst::MTE_FIELD_FILE);
            $this->setTemplateFormField(mteConst::MTE_FIELD_IMAGE);
            $this->setTemplateFormField(mteConst::MTE_FIELD_HIDDEN);
            $this->setTemplateFormField(mteConst::MTE_FIELD_PASSWORD);
            $this->setTemplateFormField(mteConst::MTE_FIELD_SUBMIT);
            $this->setTemplateFormField(mteConst::MTE_FIELD_DATE);
            $this->setTemplateFormField(mteConst::MTE_FIELD_TIME);
            $this->setTemplateFormField(mteConst::MTE_FIELD_CODE);
            $this->setTemplateFormField(mteConst::MTE_FIELD_SUBTITLE);
            $this->setTemplateFormField(mteConst::MTE_FIELD_DESCRIPTION);
            $this->setTemplateFormField(mteConst::MTE_FIELD_CAPTCHATEXT);

            // properties
            $this->setCnx($cnx);
            $this->setParams($params);
            $this->setTypeView();
            $this->setRequestMethod();
            $this->setGridRows(MTE_GRID_ROWS);
            $this->setTitle();
            $this->setNotify();
            $this->setWarning();
            $this->setLegend();
            $this->setUrlClose();
            $this->setUrlPost();
            $this->setViewMethodFilter();
            $this->setViewMethodGrid();
            $this->setViewMethodForm();
            $this->setWhereUpdate();

            // Export
            $this->setExportFormat();
            $this->setExportOrientation();
            $this->setExportFont();
            $this->setExportFontSize();
            $this->setExportSubtitle();
            $this->setExportHeader();
            $this->setExportFooter();
            $this->setExportCalcColumSize();
            $this->setExportMargin();

            // defult where
            $this->setDefaultFilter();

            // add default action
            $this->addActionInsert();
            $this->addActionUpdate();
            $this->addActionDelete();
            $this->addActionExport();
            $this->addActionView();
            $this->addActionFilter();

            // Browser
            $this->activeBrowser();
            $this->activeFooter();

            // Parametros
            if ($model != ''){
                $this->_params->setParamClassModel($model);
            }
            if ($view != '') {
                $this->_params->setParamClassView($view);
            }

            $file = $this->_params->getParamClassModel();
            // Object model
            if (file_exists(MTE_MODEL."/$file.model.php")){
                include_once(MTE_MODEL."/$file.model.php");
                $class = 'tbl_'.$file;
                $this->_model = new $class($this->getCnx());
            }

            // object view
            $file = $this->_params->getParamClassView();
            if (file_exists(MTE_VIEW."/$file.view.php")){
                include_once(MTE_VIEW."/$file.view.php");
                $class = 'vw_'.$file;
                $this->_view = new $class($ctr);
            }

            if ( !$this->_model instanceof mteTableSql || !$this->_view instanceof mteView ) {
                $excep = new mteException();
                $excep->setTitle(__('Class Definition Error'));
                $excep->setProblem(__('Problems detected while Motte try to create an object.'));
                $excep->setExplanation(__('Either needed classes were not found (check model and view path) or something just went wrong during the procudre.'));
                $excep->setVeredict(__('If this problem persist, please contact your developer team.'));
                $pag = new mtePage();
                $pag->setContent($excep->fetchHtml());
                $pag->showHtml();
                exit;
            }

            // field names
            $columns = $this->_view->getFields();
            foreach ($columns as $field){
                $this->_view->setFieldName($field, $this->_model->getFieldName($field));
            }

            // clear errorstype filter
            $this->_clearError();
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
         *
         * @access public
         * @param string $value
         */
        public function setNotify($value = ''){
            $this->_notify = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setWarning($value = ''){
            $this->_warning = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setUrlClose($value = ''){
            $this->_urlClose = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setUrlPost($value = ''){
            $this->_urlPost = $value;
        }


        public function setLegend($value = ''){
            $this->_legend = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getTitle(){
            $result = $this->_title;
            if ($result == ''){
                $result = $this->_view->getTitle();
            }
            return $result;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getWarning(){
            return $this->_warning;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getNotify(){
            return $this->_notify;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getUrlClose(){
            return $this->_urlClose;
        }

        /**
         *
         *type filter s
         * @access public
         * @return string
         */
        public function getUrlPost(){
            return $this->_urlPost;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getLegend(){
            return $this->_legend;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getViewMethodGrid(){
            return $this->_methodGrid;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getViewMethodForm(){
            return $this->_methodForm;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getViewMethodFilter(){
            return $this->_methodFilter;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getViewMethodExport(){
            return $this->_methodExport;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setViewMethodGrid($value = ''){
            if ($value == ''){
                $value = 'getGrid';
            }
            $this->_methodGrid  = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setViewMethodForm($value = ''){
            if ($value == ''){
                $value = 'getForm';
            }
            $this->_methodForm = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setViewMethodFilter($value = ''){
            if ($value == ''){
                $value = 'getFilter';
            }
            $this->_methodFilter = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setViewMethodExport($value = ''){
            if ($value == ''){
                $value = 'getExport';
            }
            $this->_methodExport  = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setTemplateGrid($value = ''){
            $this->_templateGrid = $value;
        }
        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setTemplateForm($value = ''){
            $this->_templateForm = $value;
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setTemplateBrowser($value = ''){
            $this->_templateBrowser = $value;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTemplateGrid(){
            return $this->_templateGrid;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTemplateForm(){
            return $this->_templateForm;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTemplateBrowser(){
            return $this->_templateBrowser;
        }


        /**
         *
         *
         * @param string $type
         * @param string $value
         */
        public function setTemplateFormField($type, $value = ''){
            $this->_templateFormField[$type] = $value;
        }

        /**
         *
         *
         * @param string $type
         * @return string
         */
        public function getTemplateFormField($type){
            return $this->_templateFormField[$type];
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        public function setRequestMethod($value = ''){
            if ($value == ''){
                $value = 'POST';
            }
            $this->_requestMethod = $value;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getRequestMethod(){
            return $this->_requestMethod;
        }

        /**
         *
         *
         * @access public
         * @param string $dir
         */
        public function setTemplateDir($dir = ''){
            $this->_tplDir = $dir;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTemplateDir(){
            return $this->_tplDir;
        }

        /**
         *
         *
         * @access public
         * @param string $type
         */
        public function setTypeView($type = ''){
            if ($type == ''){
                $type = 'HTML';
            }
            $this->_typeView = $type;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTypeView(){
            return $this->_typeView;
        }

        /**
         *
         * @access public
         * @param string $tplCacheDir
         */
        public function setTplCacheDir($tplCacheDir = ''){
            $this->_tplCacheDir = $tplCacheDir;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getTplCacheDir(){
            return $this->_tplCacheDir;
        }

        /**
         *
         *
         * @access public
         * @param mteCnx $Cnx
         */
        public function setCnx($Cnx){
            $this->_cnx = $Cnx;
        }

        /**
         *
         *
         * @access public
         * @return mteCnx
         */
        public function getCnx(){
            return $this->_cnx;
        }

        /**
         *
         *
         * @access public
         * @param mteUrl $params
         */
        public function setParams($params){
            $this->_params = $params;
        }

        /**
         *
         *
         * @access public
         * @return mteUrl
         */
        public function getParams(){
            return $this->_params;
        }

        /**
         *
         *
         * @access public
         * @param mteTableSql $tbl
         */
        public function setModel($tbl){
            $this->_model = $tbl;
        }

        /**
         *
         *
         * @access public
         * @return mteTableSql
         */
        public function getModel(){
            return $this->_model;
        }

        /**
         *
         *
         * @access public
         * @param mteView
         *
         */
        public function setView($view){
            $this->_view = $view;
        }

        /**
         *
         *
         * @access public
         * @return mteTableSql
         */
        public function getView(){
            return $this->_view;
        }

        /**
         *
         * @access public
         * @param string $lang
         */
        public function setGridRows($nro=''){
            if ($nro == ''){
                $nro = 20;
            }
            $this->_rows = $nro;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getGridRows(){
            return $this->_rows;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function setDefaultFilter($value = ''){
            $this->_defaultFilter = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getDefaultFilter(){
            return $this->_defaultFilter;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getWhereUpdate(){
            return $this->_whereUpdate;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function setWhereUpdate($value = ''){
            $this->_whereUpdate = $value;
        }

        public function activeBrowser(){
            $this->_browser = true;
        }

        public function desactiveBrowser(){
            $this->_browser = false;
        }

        public function activeFooter(){
            $this->_footer = true;
        }

        public function desactiveFooter(){
            $this->_footer = false;
        }

        /**
         *
         * @access public
         */
        public function addActionInsert(){
            $this->_action[mteConst::MTE_ACT_INSERT] = true;
        }

        /**
         *
         * @access public
         */
        public function addActionUpdate(){
            $this->_action[mteConst::MTE_ACT_UPDATE] = true;
        }

        /**
         *
         * @access public
         */
        public function addActionDelete(){
            $this->_action[mteConst::MTE_ACT_DELETE] = true;
        }

        /**
         *
         * @access public
         */
        public function addActionExport(){
            $this->_action[mteConst::MTE_ACT_EXPORT] = true;
        }

        /**
         *
         * @access public
         */
        public function addActionView(){
            $this->_action[mteConst::MTE_ACT_VIEW] = true;
        }

        /**
         *
         * @access public
         */
        public function addActionFilter(){
            $this->_action[mteConst::MTE_ACT_FILTER] = true;
        }

        /**
         *
         *
         * @access public
         */
        public function removeActionInsert(){
            $this->_action[mteConst::MTE_ACT_INSERT] = false;
        }

        /**
         *
         * @access public
         */
        public function removeActionUpdate(){
            $this->_action[mteConst::MTE_ACT_UPDATE] = false;
        }

        /**
         *
         * @access public
         */
        public function removeActionDelete(){
            $this->_action[mteConst::MTE_ACT_DELETE] = false;
        }

        /**
         *
         * @access public
         */
        public function removeActionExport(){
            $this->_action[mteConst::MTE_ACT_EXPORT] = false;
        }

        /**
         *
         * @access public
         */
        public function removeActionView(){
            $this->_action[mteConst::MTE_ACT_VIEW] = false;
        }

        /**
         *
         * @access public
         */
        public function removeActionFilter(){
            $this->_action[mteConst::MTE_ACT_FILTER] = false;
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionInsert(){
            return $this->_action[mteConst::MTE_ACT_INSERT];
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionUpdate(){
            return $this->_action[mteConst::MTE_ACT_UPDATE];
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionDelete(){
            return $this->_action[mteConst::MTE_ACT_DELETE];
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionExport(){
            return $this->_action[mteConst::MTE_ACT_EXPORT];
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionView(){
            return $this->_action[mteConst::MTE_ACT_VIEW];
        }

        /**
         *
         * @access private
         */
        private function _isActiveActionFilter(){
            return $this->_action[mteConst::MTE_ACT_FILTER];
        }

        /**
         *
         * @access private
         */
        private function _isActiveBrowser(){
            return $this->_browser;
        }

        /**
         *
         * @access private
         */
        private function _isActiveFooter(){
            return $this->_footer;
        }


        /**
         *
         * @param string $value
         */
        public function setExportFormat($value = ''){
            if ($value == ''){
                $value = mteConst::MTE_PDF_FORMAT_A4;
            }
            $this->_exportFormat = $value;
        }

        /**
         *
         * @param string $value
         */
        public function setExportOrientation($value = ''){
            if ($value == ''){
                $value = mteConst::MTE_PDF_PORTRAIT;
            }
            $this->_exportOrientation = $value;
        }

        /**
         *
         * @param string $value
         */
        public function setExportFont($value = ''){
            if ($value == ''){
                $value = 'Arial';
            }
            $this->_exportFont = $value;
        }

        /**
         *
         * @param string $value
         */
        public function setExportFontSize($value = ''){
            if ($value == ''){
                $value = 'small';
            }
            $this->_exportFontSize = $value;
        }

        /**
         *
         * @param string $value
         */
        public function setExportSubtitle($value = ''){
            $this->_exportSubtitle = $value;
        }

        /**
         *
         * @param string $value
         */
        public function setExportHeader($visible = true, $size = 0, $title = '', $com1 = '', $com2 = ''){
            if ($size == 0){
                $size  = 22;
            }
            if ($title == ''){
                $title = MTE_EXPORT_TITLE;
            }
            if ($com1 == ''){
                $com1  = MTE_EXPORT_COMMENT;
            }
            if ($com2 == ''){
                $com2  = MTE_EXPORT_DATA;
            }

            $this->_exportHeader         = $visible;
            $this->_exportHeaderTitle    = $title;
            $this->_exportHeaderComment1 = $com1;
            $this->_exportHeaderComment2 = $com2;
            $this->_exportHeaderSize     = $size;
        }

        /**
         *
         * @param string $value
         */
        public function setExportFooter($visible = true, $left = '', $rigth = ''){
            // parameters
            if ($left == ''){
                $left = MTE_SYSTEM_TITLE;
            }
            if ($rigth == ''){
                $rigth = mteConst::MTE_PDF_VAR_CURRENTDATETIME;
            }

            $this->_exportFooter      = $visible;
            $this->_exportFooterLeft  = $left;
            $this->_exportFooterRigth = $rigth;
        }

        /**
         * set Margins
         *
         * @param integer $left
         * @param integer $top
         * @param integer $rigth
         * @param integer $bottom
         */
        public function setExportMargin($left = 0, $top = 0, $rigth = 0, $bottom = 0){
            // parameters
            if ($left == 0){
                $left  = 15;
            }
            if ($top == 0){
                $top    = 5;
            }
            if ($rigth == 0){
                $rigth  = 5;
            }
            if ($bottom == 0){
                $bottom = 10;
            }

            // Set margins
            $this->_exportMarginLeft   = $left;
            $this->_exportMarginTop    = $top;
            $this->_exportMarginRigth  = $rigth;
            $this->_exportMarginBottom = $bottom;
        }


        /**
         *
         * @param string $value
         */
        public function setExportCalcColumSize($value = true){
            $this->_exportCalcColumSize = $value;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                            A C T I O N S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @param string $action
         * @return array
         */
        public function getActiveRecord($action){
            $activeRecord = $this->_model->getEmptyRecord();
            if (($action == mteConst::MTE_ACT_DELETE) ||
                ($action == mteConst::MTE_ACT_UPDATE) ||
                ($action == mteConst::MTE_ACT_VIEW)){
                // if exist param id
                if ($this->_params->existsParamActionId()){
                    // parameters
                    $params = $this->_params->getParamActionId();
                    $where = new mteWhereSql();
                    foreach ($this->_model->getFieldsKey() as $element){
                        $where->addAND($this->_model->getTableName().'.'.$element, '=', "'".$params[$element]."'");
                    }
                    $auxWhere = $where->fetch();

                    // default filter
                    if ($this->getDefaultFilter() != ''){
                        if ($auxWhere != ''){
                            $auxWhere .= ' AND ';
                        }
                        $auxWhere .= $this->getDefaultFilter();
                    }

                    // search record
                    if ($this->_model->exists($auxWhere)){
                        $activeRecord = $this->_model->getRecord($auxWhere, $this->_view->getAutoCalcFields());
                    }
                }
            }
            return $activeRecord;
        }

        /**
         *
         * @access public
         * @param array $activeRecord
         * @return array
         */
        public function toRecord($activeRecord) {
            switch ($this->getRequestMethod()) {
                case 'POST':
                $activeRecord = $this->_model->toRecord($activeRecord, $_POST);
                break;
                case 'GET':
                $activeRecord = $this->_model->toRecord($activeRecord, $_GET);
                break;
            }
            if ((method_exists($this->_model, 'onCalcFields')) && ($this->_view->getAutoCalcFields())){
                $error = $this->_model->onCalcFields($activeRecord);
                if ($error != ''){
                    $this->_addError($error);
                }
            }
            return $activeRecord;
        }

        /**
         *
         * @access private
         * @return variant
         */
        private function _grid(){
            $auxMethod = $this->getViewMethodGrid();
            if (method_exists($this->_view, $auxMethod)){
                $result = $this->_view->$auxMethod();
            }
            else{
                $result = $this->_mteGrid();
            }
            return $result;
        }

        /**
         *
         * @access private
         * @return variant
         */
        private function _filter(){
            $auxMethod = $this->getViewMethodFilter();
            if (method_exists($this->_view, $auxMethod)){
                $result = $this->_view->$auxMethod();
            }
            else{
                $result = $this->_mteGridFilter();
            }
            return $result;
        }

        /**
         *
         * @access private
         * @param string $action
         * @param array $record
         * @param boolean $readOnly
         * @param string $error
         * @return variant
         */
        private function _form($action, $record, $readOnly, $error){
            $auxMethod = $this->getViewMethodForm();
            if (method_exists($this->_view, $auxMethod)){
                $result = $this->_view->$auxMethod($action, 
                    $record, 
                    $readOnly, 
                    $error, 
                    $this->_params, 
                    $this->getTplCacheDir(), 
                    $this->getTemplateDir(), 
                    $this->getTemplateForm(), 
                    $this->_getUrlClose(mteConst::MTE_ACT_GRID), 
                    $this->_getUrlPost($action));
            }
            else{
                $result = $this->_mteForm($action, $record, $readOnly, $error);
            }
            return $result;
        }

        /**
         *
         * @access private
         * @param string $action
         * @param array $activeRecord
         * @return boolean
         */
        private function _insert_update($action, &$activeRecord, $whereUpdate = ''){
            $this->_clearError();
            $genForm = true;

            $activeRecord = $this->toRecord($activeRecord);

            if ($_SERVER["REQUEST_METHOD"] == $this->getRequestMethod()){
                // Alta registro que viene por Post o por Get
                if ($action == mteConst::MTE_ACT_INSERT){
                    $error = $this->_model->insertRecord($this->toRecord($activeRecord));
                }
                if ($action == mteConst::MTE_ACT_UPDATE){
                    $error = $this->_model->updateRecord($this->toRecord($activeRecord), $whereUpdate);
                }
                if ($error != ''){
                    $this->_addError($error);
                }
                if (method_exists($this->_model, 'onUpload')){
                    $error = $this->_model->onUpload($activeRecord);
                    if ($error != ''){
                        $this->_addError($error);
                    }
                }
                $genForm = ($this->_countError() > 0);
            }
            return $genForm;
        }

        /**
         *
         * @access private
         * @param array $activeRecord
         * @return boolean
         */
        private function _delete($activeRecord){
            $this->_clearError();
            $genForm = true;
            if ($_SERVER["REQUEST_METHOD"] == $this->getRequestMethod()){
                $error = $this->_model->deleteRecord($activeRecord);
                if ($error != ''){
                    $this->_addError($error);
                }
                $genForm = ($this->_countError() > 0);
            }
            return $genForm;
        }

        /**
         *
         *
         * @access private
         */
        private function _mteGridFilter(){
            $filterField = '';
            $filterText  = '';
            if ($_SERVER["REQUEST_METHOD"] == 'POST'){
                $filterField = $_POST['mteGridNavField'];
                $filterText  = $_POST['mteGridNavKeyword'];
            }
            $this->_params->setParamGridFilterField($filterField);
            $this->_params->setParamGridFilterText($filterText);
            $this->_params->setParamGridPage(1);
        }

        /**
         *
         *
         * @return string
         */
        private function _getUrlClose($action = ''){
            $result = $this->getUrlClose();
            if ($this->getUrlClose() == ''){
                $url = clone $this->_params;
                // Si accion es grid
                if ($url->getParamAction() == mteConst::MTE_ACT_GRID){
                    $result = basename($_SERVER['PHP_SELF']);
                }
                else{
                    $url->setParamAction($action);
                    $url->setParamGridPage();
                    $url->setParamGridOrderField();
                    $url->setParamGridOrderDir();
                    $url->setParamGridFilterField();
                    $url->setParamGridFilterText();
                    $result = $url->getUrl();
                }
            }
            return $result;
        }

        /**
         *
         *
         * @access private
         * @return string
         */
        private function _export(){
            $auxMethod = $this->getViewMethodExport();
            if (method_exists($this->_view, $auxMethod)){
                $result = $this->_view->$auxMethod($this);
            }
            else{
                $result = $this->_mteExport();
            }
            return $result;
        }

        /**
         *
         *
         * @access private
         * @return string
         */
        private function _mteExport(){
            // ----------------------------------------------
            //                    DATA
            // ----------------------------------------------
            $where = '';
            if ($this->_params->getParamGridFilterText() <> ''){
                $oWhere = new mteWhereSql();
                $oWhere->addAND($this->_params->getParamGridFilterField(), mteConst::MTE_LIKE_IN, $this->_params->getParamGridFilterText());
                $where = $oWhere->fetch();
            }

            if ($this->getDefaultFilter() != ''){
                if ($where != ''){
                    $where .= ' AND ';
                }
                $where .= '('.$this->getDefaultFilter().')';
            }
            if ($this->_params->getParamGridOrderField() == ''){
                $this->_params->setParamGridOrderField($this->_view->getOrderDefaultField());
                $this->_params->setParamGridOrderDir($this->_view->getOrderDir($this->_view->getOrderDefaultField()));
            }
            $oOrder = new mteOrderSql();
            if (strtoupper($this->_params->getParamGridOrderDireccion()) == 'DESC'){
                $oOrder->addDesc($this->_params->getParamGridOrderField());
            }
            else{
                $oOrder->addAsc($this->_params->getParamGridOrderField());
            }
            $order = $oOrder->fetch();

            // Asks model for data
            $fieldsAux = $this->_view->getGridFields();
            $fieldsAux = implode(', ', $fieldsAux);

            $data = array();
            $recordSet = $this->_model->getRecordSet($fieldsAux, $where, $order, -1, -1, $this->_view->getAutoCalcFields());
            if ($recordSet instanceof mteRecordSet){
                $data = $recordSet->getArray();
            }

            // ----------------------------------------------
            //                    EXPORT
            // ----------------------------------------------
            switch (MTE_EXPORT_TYPE) {
                case mteconst::MTE_EXPORT_PDF:
                {
                    $exportPDF = new mteExportListPdf();
                    // Add data
                    $exportPDF->setColumns($this->_view->getExportFields(), $this->_view->getExportHeader());
                    $exportPDF->addData($data);
                    // Cfg
                    $exportPDF->setFormat($this->_exportFormat);
                    $exportPDF->setOrientation($this->_exportOrientation);
                    $exportPDF->setFontFamily($this->_exportFont, $this->_exportFontSize);
                    $exportPDF->setTitle($this->getTitle());
                    $exportPDF->setSubtitle($this->_exportSubtitle);
                    $exportPDF->setHeaderContent($this->_exportHeader, $this->_exportHeaderSize, MTE_EXPORT_LOGO, MTE_EXPORT_LOGO_WIDTH, $this->_exportHeaderTitle, $this->_exportHeaderComment1, $this->_exportHeaderComment2);
                    $exportPDF->setFooterContent($this->_exportFooter, $this->_exportFooterLeft, $this->_exportFooterRigth);
                    $exportPDF->setMargin($this->_exportMarginLeft, $this->_exportMarginTop, $this->_exportMarginRigth, $this->_exportMarginBottom);
                    $exportPDF->autoCalcColumSize($this->_exportCalcColumSize);
                    // Export
                    $exportPDF->export(mteConst::MTE_EXPORT_SEND);
                    break;
                }
                case mteconst::MTE_EXPORT_TEXT:
                {
                    $exportTXT = new mteExportListText();
                    // Add data
                    $exportTXT->setColumns($this->_view->getExportFields(), $this->_view->getExportHeader());
                    $exportTXT->addData($data);
                    // Exporto
                    $exportTXT->export(mteConst::MTE_EXPORT_SEND);
                    break;
                }
            }
        }

        /**
         *
         *
         * @access private
         * @return string
         */
        private function _mteGrid(){
            $where = '';
            if ($this->_params->getParamGridFilterText() <> ''){
                $oWhere = new mteWhereSql();
                $oWhere->addAND($this->_params->getParamGridFilterField(), mteConst::MTE_LIKE_IN, $this->_params->getParamGridFilterText());
                $where = $oWhere->fetch();
            }

            if ($this->getDefaultFilter() != ''){
                if ($where != '')
                $where .= ' AND ';
                $where .= '('.$this->getDefaultFilter().')';
            }
            if ($this->_params->getParamGridOrderField() == ''){
                $this->_params->setParamGridOrderField($this->_view->getOrderDefaultField());
                $this->_params->setParamGridOrderDir($this->_view->getOrderDir($this->_view->getOrderDefaultField()));
            }
            $oOrder = new mteOrderSql();
            if (strtoupper($this->_params->getParamGridOrderDireccion()) == 'DESC'){
                $oOrder->addDesc($this->_params->getParamGridOrderField());
            }
            else{
                $oOrder->addAsc($this->_params->getParamGridOrderField());
            }

            $order   = $oOrder->fetch();
            $page    = $this->_params->getParamGridPage();
            $rows    = $this->getGridRows();
            $url     = clone $this->_params;
            $maxPage = $this->_model->getTotalPages($rows, $where);
            $maxRows = $this->_model->recordCount($where);

            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            //             G R I D   B R O W S I N G   O P T I O N S
            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            $resultBrowser = '';
            if ($this->_isActiveBrowser()){
                // Create Browser object
                $browser =  new mteBrowser($this->getTplCacheDir(), $this->getTemplateDir(), $this->getTemplateBrowser());
                $browser->setName('brw');
                $browser->setPage($page);

                // Actions
                if ($this->_isActiveActionInsert()){
                    $url->setParamAction(mteConst::MTE_ACT_INSERT);
                    $url->setParamActionId();
                    $browser->addAction(mteConst::MTE_ACT_INSERT, $url->getUrl());
                }
                if ($this->_isActiveActionExport()){
                    $url->setParamAction(mteConst::MTE_ACT_EXPORT);
                    $browser->addAction(mteConst::MTE_ACT_EXPORT, $url->getUrl());
                }

                // Filters
                if ($this->_isActiveActionFilter()){
                    $browser->showFilter(true);
                    $browser->addFilterFields($this->_view->getFilterField());
                    $url->setParamAction(mteConst::MTE_ACT_FILTER);
                    $browser->setFilterUrl($url->getUrl());
                    $browser->setFilterSelected($this->_params->getParamGridFilterField());
                    $browser->setFilterKeyword($this->_params->getParamGridFilterText());
                }

                // first page button
                $url->setParamAction(mteConst::MTE_ACT_GRID);
                $url->setParamGridPage(1);
                $browser->setPagesUrlFirst($url->getUrl());

                // previous page button
                if ($page-1 > 1){
                    $url->setParamGridPage($page-1);
                }
                $browser->setPagesUrlPrev($url->getUrl());

                // last page button
                $url->setParamGridPage($maxPage);
                $browser->setPagesUrlLast($url->getUrl());

                // next page button
                if ($page+1 < $maxPage){
                    $url->setParamGridPage($page+1);
                }
                $browser->setPagesUrlNext($url->getUrl());

                // page combo
                for ($i=1; $i<=$maxPage; $i++){
                    $url->setParamGridPage($i);
                    $browser->addNavPageItem($i, $url->getUrl());
                }

                // Generate HTML
                if ($this->getTypeView() == 'HTML'){
                    $resultBrowser = $browser->fetchHtml();
                }
            }
            else{
                // Display all rows
                $page = 1;
                $rows = $maxRows;
            }

            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            //                       D A T A   G R I D
            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            // Create Grid object
            $grid = new mteGrid($this->getTplCacheDir(), $this->getTemplateDir(), $this->getTemplateGrid());
            $grid->setTitle($this->getTitle());
            $grid->setNotify($this->getNotify());
            $grid->setWarning($this->getWarning());

			// first page button
			$url->setParamAction(mteConst::MTE_ACT_GRID);
			$url->setParamGridPage(1);
			$grid->gridSetPagesUrlFirst($url->getUrl());

			// previous page button
			if ($page-1 > 1){
				$url->setParamGridPage($page-1);
			}
			$grid->gridSetPagesUrlPrev($url->getUrl());

			// last page button
			$url->setParamGridPage($maxPage);
			$grid->gridSetPagesUrlLast($url->getUrl());

			// next page button
			if ($page+1 < $maxPage){
				$url->setParamGridPage($page+1);
			}
			$grid->gridSetPagesUrlNext($url->getUrl());

			// page combo
			for ($i=1; $i<=$maxPage; $i++){
				$url->setParamGridPage($i);
				$grid->gridAddNavPageItem($i, $url->getUrl());
			}

            // Close Button
            $grid->setUrlClose($this->_getUrlClose());

            // Add columns declared at table view
            $grid->addColumns($this->_view->getGridColumns());
            $grid->setColumnOrder($this->_params->getParamGridOrderField(), $this->_params->getParamGridOrderDireccion());

            // Add grid actions
            $mteConst = new mteConst();
            if ($this->_isActiveActionView()){
                $grid->addMteAction(mteConst::MTE_ACT_VIEW, $mteConst->getActionName(mteConst::MTE_ACT_VIEW));
            }
            if ($this->_isActiveActionUpdate()){
                $grid->addMteAction(mteConst::MTE_ACT_UPDATE, $mteConst->getActionName(mteConst::MTE_ACT_UPDATE));
            }
            if ($this->_isActiveActionDelete()){
                $grid->addMteAction(mteConst::MTE_ACT_DELETE, $mteConst->getActionName(mteConst::MTE_ACT_DELETE));
            }

            // Asks model for data
            $fieldsAux = $this->_view->getGridFields();
            $fieldsAux = implode(', ', $fieldsAux);

            $recordSet = $this->_model->getRecordSet($fieldsAux, $where, $order, $rows, $rows*($page-1), $this->_view->getAutoCalcFields());
            if ($recordSet instanceof mteRecordSet){
                $grid->addData($recordSet->getArray());

                // Record legend
                $canRec = $recordSet->recordCount();

                $fromRec = $rows*($page-1);
                if ($canRec > 0){
                    $fromRec++;
                }

                $toRec = $fromRec+$canRec;
                if ($fromRec > 0){
                    $toRec--;
                }

                if ($this->_isActiveFooter()){
                    $grid->setRecordLegend($this->getLegend()."$fromRec - $toRec / $maxRows");
                }
            }

            // Generate Grid
            $resultGrid = '';
            if ($this->getTypeView() == 'HTML'){
                $resultGrid = $grid->fetchHtml($this->_params, $this->_model->getFieldsKey());
            }

            return $resultBrowser.$resultGrid;
        }

        /**
         *
         * @access private
         * @param string $action
         * @return string
         */
        private function _getUrlPost($action = ''){
            $result = $this->getUrlPost();
            if ($this->getUrlPost() == ''){
                $url = clone $this->_params;
                $url->setParamAction($action);
                $url->setParamGridPage();
                $url->setParamGridOrderField();
                $url->setParamGridOrderDir();
                $url->setParamGridFilterField();
                $url->setParamGridFilterText();
                $result = $url->getUrl();
            }
            return $result;
        }

        /**
         *
         * @access private
         * @param string $action
         * @param array $record
         * @param string $readOnly
         * @param string $error
         * @return string
         */
        private function _mteForm($action, $record, $readOnly, $error){

            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            //                          F O R M
            //  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            $mteConst = new mteConst();

            // Generate objet Form
            $form = new mteForm($this->getTplCacheDir(), $this->getTemplateDir(), $this->getTemplateForm());
            $form->setName('frm_'.get_class($this->_model));
            $form->setTitle($this->getTitle().' ('.$mteConst->getActionName($action).')');
            $form->setNotify($this->getNotify());
            $form->setWarning($error);
            $form->setLegend($this->getLegend());
            $form->setUrlClose($this->_getUrlClose(mteConst::MTE_ACT_GRID));
            $form->setUrlPost($this->_getUrlPost($action));
            $form->needConfirmation($this->_view->getNeedConfirmation());

            if ($this->_view->getNeedConfirmation()){
                if ($this->_view->getConfirmationMsg() == ''){
                    $form->confirmationMsg(__('Confirm').' '.__($mteConst->getActionName($action)));
                }
                else{
                    $form->confirmationMsg($this->_view->getConfirmationMsg());
                }
            }
            
            // Form fields
            $fields = $this->_view->getFormFields($action);

            foreach ($fields as $field) {
                // if it is not a hidden field
                if ($field['formFieldType'] != mteConst::MTE_FIELD_HIDDEN){
                    switch ($field['formFieldType']) {
                        case mteConst::MTE_FIELD_TEXT:
                    {
                            $form->addFieldText($field['name'], $field['title'].':', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_NUMBER:
                        {
                            $form->addFieldNumber($field['name'], $field['title'].':', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_TEXTAREA:
                        {
                            $form->addFieldTextArea($field['name'], $field['title'].':', '', $field['cols'], $field['rows'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_CHECKBOX:
                        {
                            $form->addFieldCheckBox($field['name'], $field['title'].':', '0', $field['valueChecked'], $field['valueUnChecked'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_SELECT:
                        {
                            $form->addFieldSelect($field['name'], $field['title'].':', '', $this->_model->createSelect($field['name'], $field['where'], $field['order']), $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_MULTISELECT:
                        {
                            $form->addFieldMultiSelect($field['name'], $field['title'].':', '', $this->_model->createSelect($field['name']), $field['size'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_PASSWORD:
                        {
                            $form->addFieldPassword($field['name'], $field['title'].':', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_DATE:
                        {
                            $form->addFieldDate($field['name'], $field['title'].':', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help'], $field['format']);
                            break;
                        }
                        case mteConst::MTE_FIELD_TIME:
                        {
                            $form->addFieldTime($field['name'], $field['title'].':', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_CODE:
                        {
                            $form->addFieldCode($field['name'], $field['title'].':', '', '', $field['size'], $field['max'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_FILE:
                        {
                            $form->addFieldFile($field['name'], $field['title'].':', '', $field['maxKb'], $field['size'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_SUBTITLE:
                        {
                            $form->addFieldSubtitle($field['name'], $field['title']);
                            break;
                        }
                        case mteConst::MTE_FIELD_IMAGE:
                        {
                            $form->addFieldImage($field['name'], $field['title'].':', '', $field['maxKb'], $field['size'], $field['validate'], $field['readOnly'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_CAPTCHATEXT:
                        {
                            $form->addFieldCaptchaText($field['title'].':', $field['value'], $field['size'], $field['max'], $field['help']);
                            break;
                        }
                        case mteConst::MTE_FIELD_DESCRIPTION:
                        {
                            $form->addFieldDescription($field['name'], $field['value']);
                            break;
                        }
                    }
                }
                else{
                    $form->addFieldHidden($field['name']);
                }
            }

            // Submit fields
			// Should come "<new_label>,<edit_label>,<delete_label>"
			$submitTextArray = explode(',', $this->_view->getSubmitText());
			$labels=array();
			for($x = 0; $x < 3; $x++) {
				$labels[$x]=(count($submitTextArray)<$x)?$submitTextArray[0]:$submitTextArray[$x];
			}
			if ($action == mteConst::MTE_ACT_INSERT){
                $form->addFieldSubmit($mteConst->getActionName($action), $labels[0]);
            }
			if ($action == mteConst::MTE_ACT_UPDATE){
                $form->addFieldSubmit($mteConst->getActionName($action), $labels[1]);
            }
			if ($action == mteConst::MTE_ACT_DELETE){
                $form->addFieldSubmit($mteConst->getActionName($action), $labels[2]);
            }

            // Load data from record
            $form->setFormData($record);
            $form->setIsReadOnly($readOnly);

            // Set readOnly fields
            foreach ($fields as $field){
                if ($field['readOnly']){
                    $form->setFieldIsReadOnly($field['name'], 1);
                }
            }
            
            // Generate Form
            $resultForm = '';
            if ($this->getTypeView() == 'HTML'){
                $resultForm = $form->fetchHtml();
            }

            return $resultForm;
        }
		  

        /**
         *
         *
         * @access public
         * @return string
         */
        public function execAction(){
            // Obtain active Record
            $activeRecord = $this->getActiveRecord($this->_params->getParamAction());

            // Depending on selected action...
            $result = '';
            switch ($this->_params->getParamAction()) {
                case mteConst::MTE_ACT_GRID:
                {
                    $result = $this->_grid();
                    break;
                }
                case mteConst::MTE_ACT_INSERT:
                {
                    if ($this->_insert_update($this->_params->getParamAction(), $activeRecord)){
                        $result =  $this->_form($this->_params->getParamAction(), $activeRecord, false, $this->_parseError());
                    }
                    else{
                        $this->_params->setParamAction(mteConst::MTE_ACT_GRID);
                        $this->_params->setParamActionId();
                        header('Location: '.$this->_params->getUrl());
                        exit();
                    }
                    break;
                }
                case mteConst::MTE_ACT_UPDATE:
                {
                    if ($this->_insert_update($this->_params->getParamAction(), $activeRecord, $this->getWhereUpdate())){
                         $result =  $this->_form($this->_params->getParamAction(), $activeRecord, false, $this->_parseError());
                    }
                    else{
                        $this->_params->setParamAction(mteConst::MTE_ACT_GRID);
                        header('Location: '.$this->_params->getUrl());
                        exit();
                    }
                    break;
                }
                case mteConst::MTE_ACT_DELETE:
                {
                    if ($this->_delete($activeRecord)){
                        $result =  $this->_form($this->_params->getParamAction(), $activeRecord, true, $this->_parseError());
                    }
                    else{
                        $this->_params->setParamAction(mteConst::MTE_ACT_GRID);
                        header('Location: '.$this->_params->getUrl());
                        exit();
                    }
                    break;
                }
                case mteConst::MTE_ACT_VIEW:
                {
                    $result = $this->_form($this->_params->getParamAction(), $activeRecord, true, $this->_parseError());
                    break;
                }
                case mteConst::MTE_ACT_EXPORT:
                {
                    $result = $this->_export();
                    break;
                }
                case mteConst::MTE_ACT_FILTER:
                {
                    $this->_filter();
                    $this->_params->setParamAction(mteConst::MTE_ACT_GRID);
                    header('Location: '.$this->_params->getUrl());
                    exit();
                    break;
                }
                default:
                {
                    $this->_params->setParamAction(mteConst::MTE_ACT_GRID);
                    $result = $this->_grid();
                    break;
                }
            }
            return $result;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                    E R R O R   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Clear Exec Error array
         *
         * @access private
         * @return void
         *
         */
        private function _clearError(){
            $this->_error = array();
        }

        /**
         * Adds a new error to Exec Error array
         *
         * @access private
         * @param string $error
         * @return void
         *
         */
        private function _addError($error = ''){
            if ($error != ''){
                $this->_error[] = $error;
            }
        }

        /**
         * Returns amount of errors in Exec errors array
         *
         * @access private
         * @return integer
         */
        private function _countError(){
            return count($this->_error);
        }

        /**
         * create a single string with Exec Errors Array content
         *
         * @access public
         * @param string $msgIni
         * @param string $msgEnd
         * @param string $glue
         * @return string
         *
         */
        private function _parseError($msgIni = '', $msgEnd = '', $glue=''){
            if ($this->getWarning() != ''){
                $this->_addError($this->getWarning());
            }
            $error = '';
            if ($this->_countError() > 0){
                $error = $msgIni.$glue.implode($glue, $this->_error).$glue.$msgEnd;
            }
            return $error;
        }
    }
?>
