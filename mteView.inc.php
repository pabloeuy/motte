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
 * @link       http://motte.codigolibre.net Motte Website
 */

    // includes necessary classes
    include_once(MOTTE.'/mte.inc.php');

    // Libraries (HTML Smarty)
    include_once(MOTTE_LIB.'/smarty/libs/Smarty.class.php');

    // Other needed classes
    include_once(MOTTE_SRC.'/view/mteView.class.php');
    include_once(MOTTE_SRC.'/view/mteTemplate.class.php');
    include_once(MOTTE_SRC.'/view/mteSimpleTemplate.class.php');
    include_once(MOTTE_SRC.'/view/mteBrowser.class.php');
    include_once(MOTTE_SRC.'/view/mteGrid.class.php');
    include_once(MOTTE_SRC.'/view/mteMenu.class.php');
    include_once(MOTTE_SRC.'/view/mteChannel.class.php');
    include_once(MOTTE_SRC.'/view/mteTab.class.php');
    include_once(MOTTE_SRC.'/view/mtePage.class.php');
    include_once(MOTTE_SRC.'/view/mteForm.class.php');
    include_once(MOTTE_SRC.'/view/mteFormField.class.php');
?>