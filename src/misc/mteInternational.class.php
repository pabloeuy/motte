<?php
/**
 * Class for Motte internationalization
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

/**
 * Translation funcion for easy access from apps.
 *
 * @access public
 * @param $text
 * @param $lang
 * @param $lanDir
 * @return string
 */
    function __($text = '', $lang = '', $langDir = '') {
        $intl = new mteInternational($lang, $langDir);
        return($intl->_($text));
    }

    function _e($text = '') {
        $result = $text;
        if (defined('APP_LANG') && defined('APP_LANG_DIR')) {
            $intl = new mteInternational(APP_LANG, APP_LANG_DIR);
            $result = $intl->_($text);
        }
        return($result);
    }

    class mteInternational {
        /**
         *
         *
         * @access private
         * @var string
         */
        private $_lang;

        /**
         *
         *
         * @access private
         * @var string
         */
        private $_langDir;

        /**
         *
         *
         * @access private
         * @var string
         */
        private $_textDomain;

        /**
         *
         *
         * @access private
         * @var array
         */
        private $l10n;

        /**
         *
         *
         * @access private
         * @var array
         */
        private $merged_filters;

        /**
         *
         *
         * @access private
         * @var array
         */
        private $mte_filter;

        /**
         * Constructor
         *
         * @access public
         * @param
         * @return mteConst
         */
        public function __construct($lang = '', $langDir = '', $textDomain = '') {
			if( $lang == "" ){
				$lang = MTE_LANG;
			}
			if( $langDir == "" ){
				$langDir = MTE_LANG_DIR;
			}
            $this->setLang($lang);
            $this->setLangDir($langDir);
            $this->setTextDomain($textDomain);
            $this->setl10n($lang);
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
         * @access public
         * @param string $value
         */
        public function setLang($value = ''){
            if ($value == ''){
                $value = MTE_LANG;
            }
            $this->_lang = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getLang(){
            return $this->_lang;
        }

        /**
          *
          * @access public
          * @param string $value
          */
        public function setLangDir($value = ''){
            if ($value == ''){
                $value = MTE_LANG_DIR;
            }
            $this->_langDir = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getLangDir(){
            return $this->_langDir;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function setTextDomain($value = ''){
            if ($value == ''){
                $value = 'default';
            }
            $this->_textDomain = $value;
        }

        /**
         *
         * @access public
         * @return string
         */
        public function getTextDomain(){
            return $this->_textDomain;
        }

        /**
         *
         * @access public
         */
        public function setl10n($lang = ''){
            $locale = $lang;
            if ( empty($lang) ){
                $locale = $this->getLang();
            }
                           
            $mofile = $this->getLangDir()."/$locale/LC_MESSAGES/$locale.mo";
            if (defined('APP_LANG_FILE')){
                if ( is_readable($this->getLangDir()."/$locale/LC_MESSAGES/".APP_LANG_FILE.".mo") ){
                    $mofile = $this->getLangDir()."/$locale/LC_MESSAGES/".APP_LANG_FILE.".mo";
                }
            }
            $this->_loadTextDomain($this->getTextDomain(), $mofile);
        }

        private function _loadTextDomain($domain, $mofile) {
            if (!isset($this->l10n[$domain])){
                if (is_readable($mofile)){
                    $this->l10n[$domain] = new gettext_reader(new CachedFileReader($mofile));
                }
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                           M E T H O D S
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * _getLocale()
         * Guess browser locale settings and set it up as app lang.
         *
         * @return array
         */

        private function _getLocale() {
            if (isset($this->lang)){
                return $this->_applyFilters('locale', $lang);
            }
            $locale = $this->_applyFilters('locale', $lang);
            return $lang;
        }

        /**
         * i18n()
         * Internazionalization function. Based on .mo files and gettext.
         *
         * @param string $text
         * @return string
         */
        public function i18n($text) { 
            $domain = $this->getTextDomain();
            if (isset($this->l10n[$domain])){
				return $this->_applyFilters('gettext', $this->l10n[$domain]->translate($text));
            }
            else{
                return $text;
            }
        }

        public function _($text) {
            return($this->i18n($text));
        }

        private function _applyFilters($tag, $string) { 
            if (!isset( $this->mergedFilters[$tag])){
                $this->_mergeFilters($tag);
            }

            if (!isset($this->mte_filter[$tag])){
                return $string;
            }

            reset( $this->mte_filter[ $tag ] );
            $args = func_get_args();

            do{
                foreach((array) current($this->mte_filter[$tag]) as $the_)
                if ( !is_null($the_['function']) ){
                    $args[1] = $string;
                    $string = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
                }
            } while ( next($this->mte_filter[$tag]) !== false );
            return $string;
        }

        private function _mergeFilters($tag) {
            if ( isset($this->mte_filter['all']) && is_array($this->mte_filter['all']) ){
                $this->mte_filter[$tag] = array_merge($this->mte_filter['all'], (array) $this->mte_filter[$tag]);
            }

            if ( isset($this->mte_filter[$tag]) ){
                reset($this->mte_filter[$tag]);
                uksort($this->mte_filter[$tag], "strnatcasecmp");
            }
            $this->mergedFilters[ $tag ] = true;
        }
    }
?>
