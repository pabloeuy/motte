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

    // Libraries
    if (MTE_PLOT_LIBRARY == 'libChart'){
        include_once(MOTTE_LIB.'/libchart/classes/libchart.php');
        //  Other needed classes
        include_once(MOTTE_SRC.'/misc/mtePlotSerie.libchart.class.php');
        include_once(MOTTE_SRC.'/misc/mtePlot.libchart.class.php');}
    else {
        //Pchart library
        include_once(MOTTE_LIB."/pchart/pChart/pData.class");
        include_once(MOTTE_LIB."/pchart/pChart/pChart.class");
        //  Other needed classes
        include_once(MOTTE_SRC.'/misc/mtePlotSerie.class.php');
        include_once(MOTTE_SRC.'/misc/mtePlot.class.php');
    }
    ?>
