<?php
/**
 * Motte presentation layer include file
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
 * @link       http://motte.codigolibre.net Motte Websites
 */

    // includes necessary classes
    include_once(MOTTE.'/mte.inc.php');
    include_once(MOTTE.'/mteImage.inc.php');

    // Libraries (FPDF)
    include_once(MOTTE_LIB.'/fpdf/fpdf.php');

    // Other needed classes
    include_once(MOTTE_SRC.'/misc/mtePDF.class.php');
    include_once(MOTTE_SRC.'/misc/mteTXT.class.php');
    include_once(MOTTE_SRC.'/export/mteExportList.class.php');
    include_once(MOTTE_SRC.'/export/mteExportListText.class.php');
    include_once(MOTTE_SRC.'/export/mteExportListPdf.class.php');
?>