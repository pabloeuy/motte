<?php
/**
 * Serie Plot class
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mtePlotSerie {

        /**
         *
         * @access private
         * @var array
         */
        private $_points;


        /**
         * Constructor
         *
         * @access public
         * @return mteCrypt
         */
        function __construct() {
            // Initialize
            $this->clearPoints();
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }


        /**
         * clear points series
         *
         */
        public function clearPoints(){
            $this->_points = array();
        }

        /**
         * add point
         *
         * @param unknown_type $valueX
         * @param unknown_type $valueY
         */
        public function addPoint($valueX, $valueY){
            $this->_points[$valueX] = $valueY;
        }


        /**
         * get data set dor chart
         *
         */
        public function getDataSetChart(){
            // create dataset
            $dataSet = new XYDataSet();
            foreach ($this->_points as $x => $y){
                $dataSet->addPoint(new Point($x, $y));
            }
            
            // Devuelve
            return $dataSet;
        }
    }
?>