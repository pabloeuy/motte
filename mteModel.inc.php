<?php
/**
 * Motte persitent layer include file
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
    include_once(MOTTE.'/mteLog.inc.php');

    // Drivers
    include_once(MOTTE_SRC.'/model/mteCnx.class.php');
    include_once(MOTTE_SRC.'/model/mteCnx.interface.php');
    include_once(MOTTE_SRC.'/model/mteCnxMySql.class.php');
    include_once(MOTTE_SRC.'/model/mteCnxPostgresql.class.php');
    include_once(MOTTE_SRC.'/model/mteCnxSqlite.class.php');

    // RecordSet
    include_once(MOTTE_SRC.'/model/mteRecordSet.class.php');

    //  Sql
    include_once(MOTTE_SRC.'/model/mteDataSql.class.php');
    include_once(MOTTE_SRC.'/model/mteTableSql.class.php');
    include_once(MOTTE_SRC.'/model/mteOrderSql.class.php');
    include_once(MOTTE_SRC.'/model/mteWhereSql.class.php');
?>