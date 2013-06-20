<?php
/**
 * Redefinicion de manejador de errores
 *
 * @filesource
 * @package motte
 * @subpackage view
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 		Carlos Gagliardi (carlosgag@gmail.com) /
 * 		Braulio Rios (braulioriosf@gmail.com) /
 * 		Pablo Erartes (pabloeuy@gmail.com) /
 * 		GBoksar/Perro (gustavo@boksar.info)
 */
    /**
     *
     *
     * @param string $typeError
     * @param string $message
     * @param string $file
     * @param string $line
     * @param string $context
     */
    function mteErrorHandler($typeError, $message, $file, $line, $context){
        // If LOG is set to full or an error happens
        if ((MTE_LOG_FULL == true) || ($typeError == E_USER_ERROR)){
            // Write Log
            $log = new mteLogSys('','', MTE_LOG_SUFFIX_DATE, MTE_LOG_SUFFIX_IP);
            $log->setLogState(true);
            $log->addEvent(mteConst::MTE_ERROR, 'UNKNOWN_USER', "$typeError - $message ($file:$line)");
        }
    }
?>