<?php
/**
 * Funciones varias
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

    function mteYears($date1, $date2 = ''){

        // Parameters
        if ($date2 == ''){
            $date2 = time();
        }
        
        $years = date('Y',$date2)-date('Y',$date1);
        if ((date('n',$date1) > date('n',$date2)) ||
            (date('n',$date1) == date('n',$date2) && date('j',$date1) > date('j',$date2))){
            $years--;
        }

        // return
        return ($years);
    }

    function mteStr2date($date, $format = 'Ymd', $sep = '-'){
        $format = strtoupper($format);
        $aux = explode($sep,$date);
        return mktime(0,0,0,$aux[strPos($format,'M')],$aux[strPos($format,'D')],$aux[strPos($format,'Y')]);
    }
?>