<?php
/**
 * Class for Motte constant definitions
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

    class mteConst {
        /**
        * Temporal directories
        */
        const MTE_CACHE_DIR = '/tmp/motte/cache';
        const MTE_TMP_DIR = '/tmp/motte/cache/cnt';
        const MTE_COMPILE_DIR = '/tmp/motte/compile';
        const MTE_DEFAULT_LOG_DIR = 'log';

        /**
        * Error type values
        */
        const MTE_NOTICE = 100;
        const MTE_ERROR  = 101;

        /**
        * Boolean values
        */
        const MTE_TRUE  = 1;
        const MTE_FALSE = 0;

        /**
        * SQL LIKE options values
        */
        const MTE_LIKE_IN   = 'LIKE_IN';
        const MTE_LIKE_INI  = 'LIKE_INI';
        const MTE_LIKE_END  = 'LIKE_END';

        /**
        * Files management values
        */
        const MTE_MAX_SIZE        = 1024; // Kilobytes
        const MTE_MIME_IMAG       = 'image|png|jpg|gif|bmp';
        const MTE_MIME_DOC        = 'msword|pdf|text|plain|html|excel';
        const MTE_MIME_COMPRESSED = 'zip|x-gzip|x-rar';

        /**
        * System actions values
        */
        const MTE_ACT_ALL    = 0;
        const MTE_ACT_GRID   = 1;
        const MTE_ACT_INSERT = 2;
        const MTE_ACT_UPDATE = 3;
        const MTE_ACT_DELETE = 4;
        const MTE_ACT_VIEW   = 5;
        const MTE_ACT_EXPORT = 6;
        const MTE_ACT_FILTER = 7;

        /**
        * System nav values
        */
        const MTE_FIRST_PAGE = 0;
        const MTE_PREV_PAGE  = 1;
        const MTE_NEXT_PAGE  = 2;
        const MTE_LAST_PAGE  = 3;

        /**
        * CSS buttons values
        */
        const MTE_ACTION_INSERT = 'Nuevo';
        const MTE_ACTION_PDF    = 'PDF';

        /**
        * Logging values
        */
        const MTE_LOG_SEPARATOR = "\t";
        const MTE_LOG_SQL       = "SQL";
        const MTE_LOG_APP       = "APP";
        const MTE_LOG_SYS       = "SYS";
        const MTE_LOG_MAIL      = "MAIL";

        /**
        * Grid columns types
        */
        const MTE_COLUMN_TEXT         = 'T';
        const MTE_COLUMN_NUMBER       = 'N';
        const MTE_COLUMN_DATE         = 'D';
        const MTE_COLUMN_TIME         = 'D';
        const MTE_COLUMN_BOOLEAN      = 'B';
        const MTE_COLUMN_IMAGE        = 'I';
        const MTE_COLUMN_ALIGN_CENTER = 'X';
        const MTE_COLUMN_ALIGN_LEFT   = 'T';
        const MTE_COLUMN_ALIGN_RIGTH  = 'N';

        /**
        * Form fields type
        */
        const MTE_FIELD_TEXT        = 'A';
        const MTE_FIELD_NUMBER      = 'B';
        const MTE_FIELD_TEXTAREA    = 'C';
        const MTE_FIELD_CHECKBOX    = 'D';
        const MTE_FIELD_SELECT      = 'E';
        const MTE_FIELD_MULTISELECT = 'F';
        const MTE_FIELD_FILE        = 'G';
        const MTE_FIELD_IMAGE       = 'H';
        const MTE_FIELD_HIDDEN      = 'I';
        const MTE_FIELD_PASSWORD    = 'J';
        const MTE_FIELD_SUBMIT      = 'K';
        const MTE_FIELD_DATE        = 'L';
        const MTE_FIELD_TIME        = 'M';
        const MTE_FIELD_CODE        = 'N';
        const MTE_FIELD_SUBTITLE    = 'O';
        const MTE_FIELD_CAPTCHATEXT = 'P';
        const MTE_FIELD_DESCRIPTION = 'Q';

        /**
        * Graph type
        */
        const MTE_GRAPH_VERTICALBAR   = 1;
        const MTE_GRAPH_HORIZONTALBAR = 2;
        const MTE_GRAPH_PIE           = 3;
        const MTE_GRAPH_LINE          = 4;
        const MTE_GRAPH_MULTILINE     = 5;

        /**
        * Export type
        */
        const MTE_EXPORT_PDF      = 'F';
        const MTE_EXPORT_TEXT     = 'T';
        const MTE_EXPORT_SEND     = 1;
        const MTE_EXPORT_DOWNLOAD = 2;
        const MTE_EXPORT_SAVE     = 3;
        const MTE_EXPORT_STRING   = 4;

        /**
        * PDF
        */
        const MTE_PDF_PORTRAIT          = 'P';
        const MTE_PDF_LANDSCAPE         = 'L';
        const MTE_PDF_UNIT_PT           = 'pt';
        const MTE_PDF_UNIT_MM           = 'mm';
        const MTE_PDF_UNIT_CM           = 'cm';
        const MTE_PDF_UNIT_INCH         = 'in';
        const MTE_PDF_FORMAT_A3         = 'A3';
        const MTE_PDF_FORMAT_A4         = 'A4';
        const MTE_PDF_FORMAT_A5         = 'A5';
        const MTE_PDF_FORMAT_LETTER     = 'Letter';
        const MTE_PDF_FORMAT_LEGAL      = 'Legal';
        const MTE_PDF_FORMAT_CUSTOMIZED = 'C';
        const MTE_PDF_BORDER            = 1;
        const MTE_PDF_BORDER_TRANS      = 0;
        const MTE_PDF_BORDER_LEFT       = 'L';
        const MTE_PDF_BORDER_RIGTH      = 'R';//error viejo, se deja por las dudas
        const MTE_PDF_BORDER_RIGHT      = 'R';
        const MTE_PDF_BORDER_TOP        = 'T';
        const MTE_PDF_BORDER_BOTTOM     = 'B';
        const MTE_PDF_FILL              = 1;
        const MTE_PDF_FILL_TRANS        = 0;
        const MTE_PDF_ALIGN_LEFT        = 'L';
        const MTE_PDF_ALIGN_RIGTH       = 'R';//error viejo, se deja por las dudas
        const MTE_PDF_ALIGN_RIGHT       = 'R';
        const MTE_PDF_ALIGN_CENTER      = 'C';
        const MTE_PDF_ALIGN_JUSTIF      = 'J';
        const MTE_PDF_FONT_REGULAR      = '';
        const MTE_PDF_FONT_BOLD         = 'B';
        const MTE_PDF_FONT_ITALIC       = 'I';
        const MTE_PDF_FONT_UNDERLINE    = 'U';
        const MTE_PDF_VAR_CURRENTDATE      = '%D%';
        const MTE_PDF_VAR_CURRENTDATETIME  = '%M%';
        const MTE_PDF_VAR_TOTPAGE          = '%T%';
        const MTE_PDF_VAR_CURRENTPAGE      = '%P%';

        /**
        * Constructor
        *
        * @access public
        * @param
        * @return mteConst
        */
        public function __construct() {
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        /**
        * Returns Error type
        *
        * @param integer $errorCode
        * @return string
        */
        public function getErrorName($errorCode){
            switch ($errorCode){
                case self::MTE_ERROR:
                    $value = 'ERROR';
                    break;
                case self::MTE_NOTICE:
                    $value = 'WARNING';
                    break;
                default:
                    $value = 'UNKNOWN';
                    break;
            }
            return($value);
        }

        /**
        * Returns action name
        *
        * @param string $action
        * @return string
        */
        public function getActionName($action){
            // Internationalization
            $lang = new mteInternational();

            $result = '';
            switch ($action){
                case self::MTE_ACT_INSERT:
                    $result = __('new');
                    break;
                case self::MTE_ACT_UPDATE:
                    $result = __('edit');
                    break;
                case self::MTE_ACT_DELETE:
                    $result = __('delete');
                    break;
                case self::MTE_ACT_VIEW:
                    $result = __('view');
                    break;
            }
            return($result);
        }
    }
?>