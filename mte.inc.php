<?php
/**
 * Motte General Config File - This file is automatically included from others
 *
 * @filesource
 * @package    motte
 * @version    1.0
 * @license    http://opensource.org/licenses/gpl-license.php GPL - GNU Public license
 * @author     Pedro Gauna (pgauna@gmail.com) /
 *             Carlos Gagliardi (carlosgag@gmail.com) /
 *             Braulio Rios (braulioriosf@gmail.com) /
 *             Pablo Erartes (pabloeuy@gmail.com) /
 *             GBoksar/Perro (gustavo@boksar.info)
 * @link       http://motte.codigolibre.net Motte Website
 */

// Defines SQL Debug Mode
if(!defined('DEBUG_MODE')) {
    define('DEBUG_MODE',false);
}

//Defines simpleTemplate Debug Mode
if(!defined('DEBUG_MODE_SIMPLE_TEMPLATE')) {
    define('DEBUG_MODE_SIMPLE_TEMPLATE', false);
}

// Defines Plot library
if (!defined('MTE_PLOT_LIBRARY')) {
    define('MTE_PLOT_LIBRARY', 'libChart');
}

// Defines whether clean or not the output html, extracting tabs, spaces, etc. Currently only avaiable in simpleTemplate
if (!defined('MTE_CLEAN_HTML')) {
    define('MTE_CLEAN_HTML', false);
}

// Defines date format for grids and forms
// for info visit http://www.smarty.net/manual/es/language.modifier.date.format.php
if(!defined('MTE_DATE_FORMAT')) {
    define('MTE_DATE_FORMAT', '%Y-%m-%d %H:%I');
}

// Defines Graphic library
if (!defined('MTE_GRAPH_LIBRARY')) {
    if(extension_loaded('imagick')) {
	define('MTE_GRAPH_LIBRARY', 'IMagick');
    }else {
	if(extension_loaded('gd')) {
	    define('MTE_GRAPH_LIBRARY', 'GD');
	}else {
	    die('No se encuentr&oacute; ninguna de las librerías gráficas(GD o ImageMagick) requeridas.');
	}
    }
}

if(!defined('SESSION_TTL')) {
    define('SESSION_TTL',300);
}

// Defines default values for general purpose Constants
// This values can be override by the app config by re-declaring them
define('MTE_URL','<http://motte.codigolibre.net>');
define('MTE_AUTHOR', 'Pedro Gauna / Carlos Gagliardi / Mauro Dodero / Pablo Erartes / Gustavo Boksar '.MTE_URL);

// source
define('MOTTE_SRC',MOTTE.'/src');

// libs
define('MOTTE_LIB',MOTTE.'/lib');

// defualt includes (i18n)
//mteInternational values
if (!defined('MTE_LANG')) {
    define('MTE_LANG', 'en');
}

if (!defined('MTE_LANG_DIR')) {
    define('MTE_LANG_DIR', MOTTE.'/langs');
}
include_once(MOTTE.'/mteInternational.inc.php');
include_once(MOTTE.'/mteConst.class.php');

// Default template. This can be override from app configuration file
if (!defined('MTE_SKIN')) {
    define('MTE_SKIN','motte');
}
define('MTE_TEMPLATE', MOTTE.'/template/'.MTE_SKIN);

// mteController values
if (!defined('MTE_GRID_ROWS')) {
    define('MTE_GRID_ROWS',30);
}

if (!defined('MTE_UPLOAD_MAXSIZE')) {
    define('MTE_UPLOAD_MAXSIZE', 3);
}

if (!defined('MTE_DB_PERSISTENT')) {
    define('MTE_DB_PERSISTENT', false);
}

if (!defined('MTE_DB_DRIVER')) {
    define('MTE_DB_DRIVER', 'MySql');
}

if (!defined('MTE_SYSTEM_AUTHOR')) {
    define('MTE_SYSTEM_AUTHOR',__('Motte Core Team'));
}
if (!defined('MTE_SYSTEM_TITLE')) {
    define('MTE_SYSTEM_TITLE',__('Motte Application'));
}
if (!defined('MTE_SYSTEM_VERSION')) {
    define('MTE_SYSTEM_VERSION','1.0');
}
if (!defined('MTE_SYSTEM_NAME')) {
    define('MTE_SYSTEM_NAME','Motte');
}

if (!defined('MTE_SESSION_NAME')) {
    define('MTE_SESSION_NAME',MTE_SYSTEM_NAME);
}
if (!defined('CUSTOM_TEMPLATE')) {
    define('CUSTOM_TEMPLATE','');
}

// set enviroment options
if (!defined('MTE_TIME_ZONE')) {
    define('MTE_TIME_ZONE','GMT');
}
if (!defined('MTE_FAVICON')) {
    define('MTE_FAVICON',MTE_TEMPLATE.'/img/favicon');
}

// set smarty cache dirs
if (!defined('MTE_CACHE_HTML')) {
    define('MTE_CACHE_HTML',mteConst::MTE_CACHE_DIR);
}

// set smarty compile dirs
if (!defined('MTE_COMPILE_HTML')) {
    define('MTE_COMPILE_HTML',MTE_CACHE_HTML);
}

// set cache dirs
if (!defined('MTE_CACHE')) {
    define('MTE_CACHE',mteConst::MTE_TMP_DIR);
}

if (!defined('MTE_URL_VAR')) {
    define('MTE_URL_VAR','MTE');
}

// Uses Motte default system values
if (!defined('MTE_SYSTEM_CHARSET')) {
    define('MTE_SYSTEM_CHARSET','utf-8');
}
if (!defined('MTE_LOG_STATE')) {
    define('MTE_LOG_STATE',true);
}

// mteDBTableManager values
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR','.');
}
if (!defined('MTE_MODEL')) {
    define('MTE_MODEL',ROOT_DIR.'/model');
}
if (!defined('MTE_VIEW')) {
    define('MTE_VIEW',ROOT_DIR.'/view');
}

// Logs
if (!defined('MTE_LOG_FULL')) {
    define('MTE_LOG_FULL',false);
}

if (!defined('MTE_LOG_MAXSIZE')) {
    define('MTE_LOG_MAXSIZE', 1*1024*1024);
}

if (!defined('MTE_LOG_MAIL_SUBJECT')) {
    define('MTE_LOG_MAIL_SUBJECT',__('[Motte] Error Log Mail Service'));
}

if (!defined('MTE_LOG_DIR')) {
    define('MTE_LOG_DIR',mteConst::MTE_DEFAULT_LOG_DIR);
}

if (!defined('MTE_LOG_LEVEL')) {
    define('MTE_LOG_LEVEL','SQLSYS');
}

if (!defined('MTE_MAIL_NOTIFICATION_LEVEL')) {
    define('MTE_MAIL_NOTIFICATION_LEVEL','NONE');
}

if (!defined('MTE_LOG_SUFFIX_DATE')) {
    define('MTE_LOG_SUFFIX_DATE', false);
}

if (!defined('MTE_LOG_SUFFIX_IP')) {
    define('MTE_LOG_SUFFIX_IP', false);
}

// mteMail values
if (!defined('MTE_MAIL_FROM_ADDRESS')) {
    define('MTE_MAIL_FROM_ADDRESS',"motte@".$_SERVER["HTTP_HOST"]);
}

if (!defined('MTE_MAIL_FROM_NAME')) {
    define('MTE_MAIL_FROM_NAME',"Motte - ".$_SERVER["HTTP_HOST"]);
}

if (!defined('MTE_MAIL_REPLYTO_ADDRESS')) {
    define('MTE_MAIL_REPLYTO_ADDRESS',MTE_MAIL_FROM_ADDRESS);
}

if (!defined('MTE_MAIL_REPLYTO_NAME')) {
    define('MTE_MAIL_REPLYTO_NAME',MTE_MAIL_FROM_NAME);
}

if (!defined('MTE_MAIL_SEND_METHOD')) {
    define('MTE_MAIL_SEND_METHOD','mail');
}

if (!defined('MTE_MAIL_SMTP_USER')) {
    define('MTE_MAIL_SMTP_USER','');
}

if (!defined('MTE_MAIL_SMTP_PASSWORD')) {
    define('MTE_MAIL_SMTP_PASSWORD','');
}

if (!defined('MTE_MAIL_SMTP_HOST')) {
    define('MTE_MAIL_SMTP_HOST','localhost');
}

if (!defined('MTE_MAIL_SMTP_PORT')) {
    define('MTE_MAIL_SMTP_PORT','25');
}

/**
 * mteExport values
 *
 */
if (!defined('MTE_EXPORT_TYPE')) {
    define('MTE_EXPORT_TYPE', 'F');
}

if (!defined('MTE_EXPORT_LOGO')) {
    define('MTE_EXPORT_LOGO', '');
}

if (!defined('MTE_EXPORT_LOGO_WIDTH')) {
    define('MTE_EXPORT_LOGO_WIDTH', '');
}

if (!defined('MTE_EXPORT_TITLE')) {
    define('MTE_EXPORT_TITLE', MTE_SYSTEM_NAME);
}

if (!defined('MTE_EXPORT_COMMENT')) {
    define('MTE_EXPORT_COMMENT', '');
}

if (!defined('MTE_EXPORT_DATA')) {
    define('MTE_EXPORT_DATA', MTE_SYSTEM_VERSION);
}

if (!defined('MTE_TEXT_ENCODE')) {
	//iso by default for compatibility reasons
    define('MTE_TEXT_ENCODE', 'iso-8859-1');
}

/**
 *
 * Creates needed directory structure
 *
 */

// Creates Smarty cache dirs
if (!is_dir(MTE_CACHE_HTML)) {
    mkdir(MTE_CACHE_HTML, 0775,true);
}
if (!is_dir(MTE_COMPILE_HTML)) {
    mkdir(MTE_COMPILE_HTML, 0775,true);
}

// Creates cache dirs
if (!is_dir(MTE_CACHE)) {
    mkdir(MTE_CACHE, 0775,true);
}

// Creates log dirs
if (!is_dir(MTE_LOG_DIR)) {
    if (!@mkdir(MTE_LOG_DIR, 0777,true)) {
	include_once('mteView.inc.php');
	include_once('mteException.inc.php');
	$excep = new mteException();
	$excep->setTitle(__('Error creating log directory'));
	$excep->setProblem(__('Problems were detected when Motte try to create the log directory at').'<br/><br/><p><strong>'.MTE_LOG_DIR.'</strong></p><br/>');
	$excep->setExplanation(__('Check your permissions over that directory or ask your system administrator for help.'));
	$excep->setVeredict(__('If this problem persist, please contact your developer team.'));
	$pag = new mtePage();
	$pag->setContent($excep->fetchHtml());
	$pag->showHtml();
	exit();
    }
}
?>
