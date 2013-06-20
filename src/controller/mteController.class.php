<?php
/**
 * Controller management class
 *
 * @filesource
 * @package motte
 * @subpackage controller
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author	Pedro Gauna (pgauna@gmail.com) /
 * 		Carlos Gagliardi (carlosgag@gmail.com) /
 * 		Braulio Rios (braulioriosf@gmail.com) /
 * 		Pablo Erartes (pabloeuy@gmail.com) /
 * 		GBoksar/Perro (gustavo@boksar.info)
 */

    class mteController {

        /**
         *
         * @access private
         * @var string
         */
        private $_tplCacheDir;

        /**
         * @access private
         * @var author
         */
        private $_author;

        /**
         * @access private
         * @var version
         */
        private $_version;

        /**
         * @access private
         * @var title
         */
        private $_title;

        /**
         * @access private
         * @var mteConfig
         */
        private $_config;

        /**
         * @access private
         * @var string
         */
        private $_activeConfig;

        /**
         * @access private
         * @var string
         */
        private $_bodyContent;

        /**
         * @access private
         * @var array
         */
        private $_params;

        /**
         * @access private
         * @var array
         */
        private $_css;

        /**
         * @access private
         * @var array
         */
        private $_js;
        
        /**
         * 
         * @access private
         * @var mteSession
         */
        private $_session;

        /**
         * Constructor
         *
         * @access public
         * @return mteController
         */
        function __construct() {
            // Timezone
            date_default_timezone_set(MTE_TIME_ZONE);

            // Uses Motte default system values
            $this->setAuthor(MTE_SYSTEM_AUTHOR);
            $this->setTitle(MTE_SYSTEM_TITLE);
            $this->setVersion(MTE_SYSTEM_VERSION);
            $this->setTplCacheDir(MTE_CACHE_HTML);

            // Initialize
            $this->_config  = array();
            $this->_params  = $this->generateParamsUrl();
            $this->_css     = array();
            $this->_js      = array();
            $this->_session = new mteSession();
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }

        /**
         *
         *
         * @access public
         * @param string $author
         */
        public function setAuthor($author){
            $this->_author = $author;
        }

        /**
         *
         *add
         * @access public
         * @return string
         */
        public function getAuthor(){
            return $this->_author;
        }

        /**
         *
         *
         * @access public
         * @param string $title
         */
        public function setTitle($title){
            $this->_title = $title;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getTitle(){
            return $this->_title;
        }

        /**
         *
         *
         * @access public
         * @param string $bodyContent
         */
        public function setBodyContent($bodyContent){
            $this->_bodyContent = $bodyContent;
        }

        /**
         *
         *
         * @access public
         * @param string $bodyContent
         */
        public function setContent($content = ''){
            $this->setBodyContent($content);
        }

        /**
         * Sets body content with a received .html file
         *
         * @access public
         * @param string $file = ''
         */
        public function bodyIncludeFile($fileName = ''){
            if (is_readable($fileName)) {
                $file = fopen($fileName,'r');
                $html = fread($file,filesize($fileName));
                fclose($file);
                $this->setBodyContent($html);

                $aux = explode('.',basename($fileName));
                $this->addCssFile($this->getTemplateDir().'/css/'.$aux[0].'.css');

                $aux = explode('.',basename($fileName));
                $this->addJsFile($this->getTemplateDir().'/js/'.$aux[0].'.js');
            }
        }

        /**
         *
         *
         * @access public
         */
        public function clearBodyContent(){
            $this->setBodyContent('');
        }

        /**
         *
         *
         * @access public
         */
        public function clearContent(){
            $this->setBodyContent('');
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getBodyContent(){
            return $this->_bodyContent;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getContent(){
            return $this->_bodyContent;
        }

        /**
         *
         *
         * @access public
         * @param string $version
         */
        public function setVersion($version){
            $this->_version = $version;
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        public function getVersion(){
            return $this->_version;
        }

        /**
         *
         * @access public
         * @param string $rows
         */
        function setNumRows($rows){
            $this->_getConfig()->_numRows = $rows;
        }

        /**
         *
         * @access public
         * @return string
         */
        function getNumRows(){
            return $this->_getConfig()->_numRows;
        }

        /**
         *
         *
         * @access public
         * @param string $lang
         */
        function setLanguage($lang = ''){
            $this->_getConfig()->setLanguage($lang);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getLanguage(){
            return $this->_getConfig()->getLanguage();
        }

        /**
         *
         * @access public
         * @param string $lang
         */
        function setCharset($charset = ''){
            $this->_getConfig()->setCharset($charset);
        }

        /**
         *
         *
         * @access public
         * @param string $value
         */
        function setRequestMethod($value = ''){
            $this->_getConfig()->setRequestMethod($value);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getRequestMethod(){
            return $this->_getConfig()->getRequestMethod();
        }


        /**
         *
         *
         * @access public
         * @param string $type
         */
        function setTypeView($type = ''){
            $this->_getConfig()->setTypeView($type);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getTypeView(){
            return $this->_getConfig()->getTypeView();
        }

        /**
         *
         * @access public
         * @return string
         */
        function getCharset(){
            return $this->_getConfig()->getCharset();
        }

        /**
         *
         * @access public
         * @param string $tplCacheDir
         */
        function setTplCacheDir($tplCacheDir = ''){
            $this->_tplCacheDir = $tplCacheDir;
        }

        /**
         *
         * @access public
         * @return string
         */
        function getTplCacheDir(){
            return $this->_tplCacheDir;
        }

        /**
         *
         *
         * @access public
         * @param string $dir
         */
        function setTemplateDir($dir = ''){
            $this->_getConfig()->setTemplateDir($dir);
        }

        /**
         *
         *
         * @access public
         * @return string
         */
        function getTemplateDir(){
            return $this->_getConfig()->getTemplateDir();
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *               C O N F I G S   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Add a new Configuracion for App
         *
         * @access public
         * @param mteConfig $name
         */
        public function addConfig($name = '', $cfg = ''){
            // If no name is received sets new config name to CFGn
            if ($name == ''){
                $name = count($this->_config)+1;
            }

            // Create instance of new config
            if ($cfg == ''){
                $cfg =  new mteConfig();
            }

            // Asign
            $this->_config[$name] = $cfg;

            // If there are no config set as default, actives the new one
            if ($this->_activeConfig == ''){
                $this->activeConfig($name);
            }
        }

        /**
         * Returns the config object matching received name
         *
         * @access private
         * @param string $name
         * @return mteConfig
         */
        private function _getConfig($name = ''){
            // if no name is received returns controller activeConfig
            if ($name == ''){
                $name = $this->_activeConfig;
            }
            return $this->_config[$name];
        }

        /**
         * Clone a config set
         *
         * @access public
         * @param string $source
         * @param string $target
         */
        public function copyConfig($target = '', $source = ''){
            $this->addConfig($target, clone $this->_getConfig($source));
        }

        /**
         * Delete a config set
         *
         * @access public
         * @param string $name
         */
        public function removeConfig($name){
            unset($this->_config[$name]);
        }

        /**
         * Activates the config set that matches received name
         *
         * @access public
         * @param string $name
         */
        public function activeConfig($name){
            $this->_activeConfig = $name;
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *        D A T A B A S E   C O N N E C T I O N    M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Sets DB to default config set
         *
         * @access public
         * @param mteCnx $DB
         */
        public function setCnx($DB){
            $this->_getConfig()->setCnx($DB);
        }

        /**
         * Gets DB from default config set
         *
         * @access public
         * @return mteCnx
         */
        public function getCnx(){
            return $this->_getConfig()->getCnx();
        }


        /**
         * Establish connection to DB set on default config set
         *
         * @access public
         */
        public function connectDB($url = ''){
            // Get active Driver
            $driver = $this->_getConfig()->getDBDriver();
            if (file_exists(MOTTE_SRC."/model/mteCnx".$driver.".class.php")){
                include_once(MOTTE_SRC."/model/mteCnx".$driver.".class.php");
                $driver = 'mteCnx'.$driver;
                $broker = new $driver($this->_getConfig()->getDBHost(),
                    $this->_getConfig()->getDBUser(),
                    $this->_getConfig()->getDBPass(),
                    $this->_getConfig()->getDBName(),
                    $this->_getConfig()->getLog('SQL'),
                    $this->_getConfig()->getDBPersistent(), false);
                $this->setCnx($broker);
                // Connect
                if (!$broker->connect()){
                    $this->killSession();
                    $excep = $this->generateHtmlException($template);
                    $excep->setTitle(__('Database Connection Error'));
                    $excep->setProblem(__('Problems detected while Motte try to connect to database.'));
                    $excep->setExplanation(__('Either connection data provided are incorrect or database engine is down.'));
                    $excep->setVeredict(__('Motte <strong>has killed the active session</strong> for your security. If this problem persist, please contact your developer team.'));
                    $pag = $this->generateHtmlPage();
                    $pag->setContent($excep->fetchHtml());
                    $pag->showHtml();
                    exit;
                }
            }
        }

        /**
         * Disconnect from DB declared at default set
         *
         * @access public
         */
        public function disconnectDB(){
            $this->getCnx()->disconnect();
        }

        /**
         * Set DB Connection values for default config set
         *
         * @access public
         * @param string $dataBaseDriver
         * @param string $hostName
         * @param string $userName
         * @param string $password
         * @param string $baseName
         * @param boolean $persistent
         */
        public function setDBCnx($dataBaseDriver = '', $hostName = '', $userName = '', $password = '', $baseName = '', $persistent = false){
            // Seteo Conexion
            $this->setDBDriver($dataBaseDriver);
            $this->setDBHost($hostName);
            $this->setDBUser($userName);
            $this->setDBPass($password);
            $this->setDBName($baseName);
            $this->setDBPersistent($persistent);
        }

        /**
         * Set DB driver for default config set
         *
         * @access public
         * @param string $host
         */
        public function setDBDriver($driver = ''){
            $this->_getConfig()->setDBDriver($driver);
        }

        /**
         * Sets Hostname for default config set
         *
         * @access public
         * @param string $host
         */
        public function setDBHost($hostName = ''){
            $this->_getConfig()->setDBHost($hostName);
        }

        /**
         * Sets userName for default config set
         *
         * @access public
         * @param string $user
         */
        public function setDBUser($userName = ''){
            $this->_getConfig()->setDBUser($userName);
        }

        /**
         * Sets user password for default config set
         *
         * @access public
         * @param string $password
         */
        public function setDBPass($password = ''){
            $this->_getConfig()->setDBPass($password);
        }

        /**
         * Sets DataBaseName for default config set
         *
         * @access public
         * @param string $basename
         */
        public function setDBName($baseName = ''){
            $this->_getConfig()->setDBName($baseName);
        }

        /**
         * Set persistent DB connection for default config set
         *
         * @access public
         * @param boolean $persistent
         */
        public function setPersistentDB($persistent = false){
            $this->_getConfig()->setPersistentDB($persistent);
        }

        /**
         * Gets DB Driver for default config set
         *
         * @access public
         * @return string
         */
        public function getCnxDriver(){
            return $this->_getConfig()->getDriverDB();
        }

        /**
         * Gets DB Host for default config set
         *
         * @access public
         * @return string
         */
        public function getCnxHost(){
            return $this->_getConfig()->getCnxHost();
        }

        /**
         * Gets User for default config set
         *
         * @access public
         * @return string
         */
        public function getCnxUser(){
            return $this->_getConfig()->getCnxUser();
        }

        /**
         * Gets DB user password for default config set
         *
         * @access public
         * @return string
         */
        public function getCnxPass(){
            return $this->_getConfig()->getCnxPass();
        }

        /**
         * Gets DB name for default config set
         *
         * @access public
         * @return string
         */
        public function getCnxName(){
            return $this->_getConfig()->getBaseNameDB();
        }

        /**
         * Gets DB persistent state for default config set
         *
         * @access public
         * @return boolean
         */
        public function getPersistentDB(){
            return $this->_getConfig()->getPersistentDB();
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                     L O G   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         *
         * @access public
         * @param boolean $state
         * @param string  $nameFunction
         */
        public function errorHandling($state, $nameFunction = '') {
            if ($state === false ){
                $nameFunction = '';
            }
            else{
                if ($nameFunction == ''){
                    $nameFunction = 'mteErrorHandler';
                }
            }
            set_error_handler($nameFunction);
        }

        /**
         *
         * @access public
         * @param string $type
         * @param string $file
         */
        public function setlogFile($type,$file){
            $this->_getConfig()->setLogFile($type,$file);
        }

        /**
         *
         * @access public
         * @param string $type
         * @return string
         */
        public function getlogFile($type){
            $this->_getConfig()->getLogFile($type);
        }

        /**
         *
         * @access public
         * @param string $type
         * @param string $state
         * @return string
         */
        public function setLogState($type,$state){
            $this->_getConfig()->setLogState($type,$state);
        }

        /**
         *
         * @access public
         * @param string $state
         * @return string
         */
        public function sqlLog($state){
            $this->_getConfig()->setLogState(mteConst::MTE_LOG_SQL,$state);
        }

        /**
         *
         * @access public
         * @param string $state
         * @return string
         */
        public function appLog($state){
            $this->_getConfig()->setLogState(mteConst::MTE_LOG_APP,$state);
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *               T E M P L A T E    M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @return mtePageHTML
         */
        public function generateHtmlPage($bodyContent = ''){
            $pag = new mtePage($this->getTplCacheDir(),$this->getTemplateDir());
            $pag->setTitle($this->getTitle());
            $pag->addMetaTag('author',$this->getAuthor());
            $pag->setCharset($this->getCharset());
            $pag->setLanguage($this->getLanguage());

            // Templates
            if (file_exists($this->getTemplateDir().'/header.html')){
                $pag->setTemplateHeader('header.html');
            }
            if (file_exists($this->getTemplateDir().'/body.html')){
                $pag->setTemplateBody('body.html');
            }
            if (file_exists($this->getTemplateDir().'/footer.html')){
                $pag->setTemplateFooter('footer.html');
            }
            if (file_exists($this->getTemplateDir().'/page.html')){
                $pag->setTemplatePage('page.html');
            }

            // Asigno datos dinamicos
            if ( empty($bodyContent) ){
                $bodyContent = $this->getBodyContent();
            }
            $pag->setContent($bodyContent);
            $pag->addVarBody('DIRTEMPLATE', $this->getTemplateDir());

            // Asigo hoja de estilo por defecto
            $this->addCssFile($this->getTemplateDir().'/css/'.CUSTOM_TEMPLATE.'.css');

            // Asigno javascript por defecto
            $this->addJsFile($this->getTemplateDir().'/js/'.CUSTOM_TEMPLATE.'.js');

            // Asigno Hojas de estilos
            if (is_array($this->_css)){
                foreach ($this->_css as $key => $file) {
                    $pag->addCssFile($file);
                }
            }
            // Asigno javascripts
            if (is_array($this->_js)){
                foreach ($this->_js as $key => $file) {
                    $pag->addJsFile($file);
                }
            }

            return $pag;
        }

        /**
         *
         * @access public
         * @return mteTemplateHtml
         */
        public function generateHtmlBlock($template = ''){
            $block = new mteTemplate($this->getTplCacheDir(),$this->getTemplateDir(),$template);
            $block->setVar('DIRTEMPLATE', $this->getTemplateDir());

            // Cargo css dinamico
            $aux = explode('.',basename($template));
            $this->addCssFile($this->getTemplateDir().'/css/'.$aux[0].'.css');

            // Cargo js dinamico
            $aux = explode('.',basename($template));
            $this->addJsFile($this->getTemplateDir().'/js/'.$aux[0].'.js');

            // Cargo variables dinamicas
            $block->setVar('TEMPLATES_DIR',$this->getTemplateDir());

            return $block;
        }

        /**
         * Added by Perro(20/Apr/08)
         * @access public
         * @return mteTemplateHtml
         */
        public function generateLoginBlock($template = ''){
            if (empty($template)){
                $template = 'mteLogin.html';
            }

            $templateDir = $this->getTemplateDir();
            if (!file_exists($this->getTemplateDir()."/".$template)) {
                $template    = 'mteLogin.html';
                $templateDir = MTE_TEMPLATE;
            }

            $block = new mteTemplate($this->getTplCacheDir(),$templateDir,$template);
            $aux = explode('.',basename($template));
            $this->addCssFile(MTE_TEMPLATE.'/css/mteLogin.css');
            if (file_exists($this->getTemplateDir()."/css/".$aux[0].'.css')) {
                $this->addCssFile($this->getTemplateDir().'/css/'.$aux[0].'.css');
            }
            $this->addJsFile(MTE_TEMPLATE.'/js/mteLogin.js');
            if (file_exists($this->getTemplateDir()."/js/".$aux[0].'.js')) {
                $this->addJsFile($this->getTemplateDir().'/js/'.$aux[0].'.js');
            }
            $block->setVar('TEMPLATES_DIR',$templateDir);
            $block->setVar('TITLE_TAG',__('System Login'));
            $block->setVar('USER_TAG',__('Username:'));
            $block->setVar('PASS_TAG',__('Password:'));
            $block->setVar('SUBMIT_TAG',__('Send'));
            $block->setVar('FORGOT_TAG',__("I've forgotten my password"));
            $block->setVar('SCRIPT_POST','index.php');
            $block->setVar('SESSION',MTE_SESSION_NAME);
            // Variables needed for AJAX
            $block->setVar('URLAJAX',MTE_AJAX);
            $block->setVar('NOLOGIN',__('Invalid User/Password'));
            $block->setVar('EMPTYFIELDS',__('Both fields must be completed'));
            $block->setVar('PROBLEMS',__("Sorry, an error has occured during login process. Can't proceed"));
            $block->setVar('SEARCHING',__('Searching...'));

            return $block;
        }


        /**
         *
         * @access public
         * @return mteTemplateHtml
         */
        public function generateHtmlException($template = ''){
            // Templates
            if (file_exists($this->getTemplateDir().'/exception.html')){
                $templateFile = 'exception.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/exception.css');
                $this->addJsFile($this->getTemplateDir().'/js/exception.js');
            }
            else{
                $templateFile = '';
                // Asigo hoja de estilo y javascript MOTTE
                $this->addCssFile(MTE_TEMPLATE.'/css/mteException.css');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteException.js');
            }
            // Objeto
            return new mteException($this->getTplCacheDir(),$this->getTemplateDir(),$templateFile);
        }

        /**
         *
         * @access public
         * @return mteChannelHtml
         */
        public function generateHtmlChannel(){
            // Templates
            if (file_exists($this->getTemplateDir().'/channel.html')){
                $templateFile = 'channel.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/channel.css');
                $this->addJsFile($this->getTemplateDir().'/js/channel.js');
            }
            else{
                $templateFile = '';
                // Asigo hoja de estilo y javascript MOTTE
                $this->addCssFile(MTE_TEMPLATE.'/css/mteChannel.css');
                $this->addJsFile(MTE_TEMPLATE.'/js/mteChannel.js');
            }
            // Objeto
            return new mteChannel($this->getTplCacheDir(),$this->getTemplateDir(),$templateFile);
        }

        /**
         *
         * @access public
         * @return mteMenu
         */
        public function generateHtmlMenu(){
            // Templates
            $templateFile = '';
            if (file_exists($this->getTemplateDir().'/menu.html')){
                $templateFile = 'menu.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/menu.css');
                $this->addJsFile($this->getTemplateDir().'/js/menu.js');
            }
            // Objeto
            return new mteMenu($this->getTplCacheDir(),$this->getTemplateDir(),$templateFile);
        }

        /**
         * Perro (03/Oct/07) Creates full table management for received table.
         *          Grid, New, Edit, Delete, View
         * @access public
         * @param string $thisTable
         * @return string
         */
        public function manageTable($thisTable) {
            $this->setParamClassModel($thisTable);
            $this->setParamClassView('tbl_'.$thisTable);
            $value = $this->generateDBTableManager();
            return $value->execAction();
        }

        /**
         *
         * @access public
         * @return mteDBTableManager
         *
         */
        public function generateDBTableManager(){
            // Templates

            $templateGrid = '';
            if (file_exists($this->getTemplateDir().'/grid.html')){
                $templateGrid = 'grid.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/grid.css');
                $this->addJsFile($this->getTemplateDir().'/js/grid.js');
            }

            $templateForm = '';
            if (file_exists($this->getTemplateDir().'/form.html')){
                $templateForm = 'form.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/form.css');
                $this->addJsFile($this->getTemplateDir().'/js/form.js');
            }

            $templateBrowser = '';
            if (file_exists($this->getTemplateDir().'/browser.html')){
                $templateBrowser = 'browser.html';
                // Asigo hoja de estilo y javascript por defecto
                $this->addCssFile($this->getTemplateDir().'/css/browser.css');
                $this->addJsFile($this->getTemplateDir().'/js/browser.js');
            }

            // Templates de los campos
            $templateField[mteConst::MTE_FIELD_TEXT]        = array('tplUser'=>'fieldText', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_NUMBER]      = array('tplUser'=>'fieldName', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_TEXTAREA]    = array('tplUser'=>'fieldTextArea', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_CHECKBOX]    = array('tplUser'=>'fieldCheckBox', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_SELECT]      = array('tplUser'=>'fieldSelect', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_MULTISELECT] = array('tplUser'=>'fieldMultiSelect', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_FILE]        = array('tplUser'=>'fieldFile', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_IMAGE]       = array('tplUser'=>'fieldImage', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_HIDDEN]      = array('tplUser'=>'fieldFidden', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_PASSWORD]    = array('tplUser'=>'fieldPassword', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_SUBMIT]      = array('tplUser'=>'fieldSubmit', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_DATE]        = array('tplUser'=>'fieldPassword', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_TIME]        = array('tplUser'=>'fieldSubmit', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_SUBTITLE]    = array('tplUser'=>'fieldSubtitle', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_DESCRIPTION] = array('tplUser'=>'fieldDescription', 'tplName'=>'');
            $templateField[mteConst::MTE_FIELD_CAPTCHATEXT] = array('tplUser'=>'fieldCaptchaText', 'tplName'=>'');


            foreach ($templateField as $type => $element) {
                if (file_exists($this->getTemplateDir().$element['tplUser'].'.html')){
                    $templateField[$type]['tplName'] = $element['tplUser'].'.html';
                    // Asigo hoja de estilo y javascript por defecto
                    $this->addCssFile($this->getTemplateDir().'/css/'.$element['tplUser'].'.css');
                    $this->addJsFile($this->getTemplateDir().'/js/'.$element['tplUser'].'.js');
                }
            }

            // Creo y configuro objeto de mantenimiento
            $manager = new mteDBTableManager($this->getCnx(), $this->getTplCacheDir(), $this->getTemplateDir(), $this->_params, $this);

            $manager->setTypeView($this->getTypeView());
            $manager->setRequestMethod($this->getRequestMethod());
            $manager->setTemplateGrid($templateGrid);
            $manager->setTemplateBrowser($templateBrowser);
            $manager->setTemplateForm($templateForm);
            // Template fields form
            foreach ($templateField as $type => $element){
                $manager->setTemplateFormField($type, $element['tplName']);
            }

            // devuelvo
            return $manager;
        }

        /**
         * Agrega hojas de estilo
         *
         * @access public
         * @param string $css
         */
        public function addCssFile($css){
            if (file_exists($css)){
                array_unshift($this->_css,$css);
            }
        }

        /**
         * Agrega archivos JS
         *
         * @access public
         * @param string $js
         */
        public function addJsFile($js){
            if (file_exists($js)){
                array_unshift($this->_js,$js);
            }
        }


        public function getCssFiles(){
            return array_reverse($this->_css);
        }


        public function getJsFiles(){
            return array_reverse($this->_js);
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                     U R L   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Determines if URL is valid, if it's not, kills active Motte Session
         *
         * @access public
         * @param string $template
         * @return void
         */
        public function validateURL($template=''){
            if (!$this->decodeVarURL()){
                $this->killSession();
                $excep = $this->generateHtmlException($template);
                $excep->setTitle(__('INVALID URL'));
                $excep->setProblem(__('Problems detected when decoding')." Motte URL");
                $excep->setExplanation(__('Something happened when Motte decode received URL. Either the URL get lost in some point or someone tried to alter it and become compromised.'));
                $excep->setVeredict(__('Motte <strong>has killed the active session</strong> for your security. If this problem persist, please contact your developer team.'));
                $excep->setReturn($_SERVER['PHP_SELF']);
                $pag = $this->generateHtmlPage();
                $pag->setContent($excep->fetchHtml());
                $pag->showHtml();
                exit;
            }
        }

        public function generateParamsUrl($varName = ''){
            // Name var
            if ($varName == ''){
                $varName = MTE_URL_VAR;
            }
            return new mteUrl($varName);
        }

        /**
         *
         * @access public
         * @param array $vars
         * @return boolean
         */
        public function decodeVarURL($vars=''){
            // parametros
            if (!is_array($vars)){
                $vars = $_GET;
            }
            return $this->_params->decodeVarURL($vars);
        }

        /**
         *
         * @access public
         * @return array
         */
        public function getParams(){
            return $this->_params;
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamModule(){
            return $this->_params->getParamModule();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamOption(){
            return $this->_params->getParamOption();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamSuboption(){
            return $this->_params->getParamSuboption();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamTab(){
            return $this->_params->getParamTab();
        }

        /**
         *
         * @access public
         * @return boolean
         */
        public function getParamInitial(){
            return $this->_params->getParamInitial();
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamAction(){
            return $this->_params->getParamAction();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridPage(){
            return $this->_params->getParamGridPage();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridOrderField(){
            return $this->_params->getParamGridOrderField();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridOrderDireccion(){
            return $this->_params->getParamGridOrderDireccion();
        }

         /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridFilterField(){
            return $this->_params->getParamGridFilterField();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamGridFilterText(){
            return $this->_params->getParamGridFilterText();
        }

        /**
         *
         * @access public
         * @return integer
         */
        public function getParamActionId($value = ''){
            return $this->_params->getParamActionId($value);
        }

        /**
         *
         * @access public
         * @return boolean
         */
        public function existsParamActionId(){
            return $this->_params->existsParamActionId();
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamClassModel(){
            return $this->_params->getParamClassModel();
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getParamClassView(){
            return $this->_params->getParamClassView();
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamModule($value=0){
            return $this->_params->setParamModule($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamOption($value=0){
            return $this->_params->setParamOption($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamSuboption($value=0){
            return $this->_params->setParamSuboption($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamTab($value=0){
            return $this->_params->setParamTab($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamInitial($value=false){
            return $this->_params->setParamInitial($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamAction($value=''){
            return $this->_params->setParamAction($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridPage($value=''){
            return $this->_params->setParamGridPage($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridOrderField($value=''){
            return $this->_params->setParamGridOrderField($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridOrderDir($value=''){
            return $this->_params->setParamGridOrderDir($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridFilterField($value=''){
            return $this->_params->setParamGridFilterField($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamGridFilterText($value=''){
            return $this->_params->setParamGridFilterText($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamActionId($value=''){
            return $this->_params->setParamActionId($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamClassModel($value=''){
            return $this->_params->setParamClassModel($value);
        }

        /**
         *
         * @access public
         * @param  $value
         */
        public function setParamClassView($value=''){
            return $this->_params->setParamClassView($value);
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                  S E S S I O N    M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         *
         * @access public
         * @param  string $name
         */
        public function createSession(){
            $this->_session->createSession();
        }

        /**
         *
         * @access public
         */
        public function killSession($nameSession = ''){
            $this->_session->killSession($nameSession);
        }

        /**
         *
         * @access public
         * @param  string $name
         * @param  variant $value
         */
        public function addSessionVar($name, $value, $nameSession = ''){
            $this->_session->addSessionVar($name, $value, $nameSession);
        }

        /**
         *
         * @access public
         * @param  string $name
         * @param  variant $value
         */
        public function setSessionVar($name, $value, $nameSession = ''){
            $this->addSessionVar($name, $value, $nameSession);
        }

        /**
         *
         * @access public
         * @param  string $name
         * @return variant
         */
        public function getSessionVar($name, $nameSession = ''){
            return $this->_session->getSessionVar($name, $nameSession);
        }

        /**
         *
         * @access public
         * @param  string $name
         */
        public function removeSessionVar($name, $nameSession = ''){
            $this->_session->removeSessionVar($name, $nameSession);
        }
    }
?>