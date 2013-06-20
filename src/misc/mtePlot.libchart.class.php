<?php
/**
 * Plot class
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

    class mtePlot {

        /**
         * Title
         *
         * @access private
         * @var sitring
         */
        private $_title;

        /**
         * logo
         *
         * @access private
         * @var sitring
         */
        private $_logo;


        /**
         * Series
         *
         * @access private
         * @var mtePlotSerie
         */
        private $_serie;

        /**
         * Constructor
         *
         * @access public
         * @return mteCrypt
         */
        function __construct() {
            // Inicialize
            $this->setTitle();
            $this->clearSeries();
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                 P R O P E R T Y   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */
        /**
         * set Title serie
         *
         * @access public
         * @param string $value
         */
        public function setTitle($value = ''){
            $this->_title = $value;
        }

        /**
         * get Title serie
         *
         * @access public
         * @return string
         */
        public function getTitle(){
            return $this->_title;
        }

        /**
         * set Logo serie
         *
         * @access public
         * @param string $value
         */
        public function setCompanyLogo($value = ''){
            $this->_logo = $value;
        }

        /**
         * get Logo serie
         *
         * @access public
         * @return string
         */
        public function getCompanyLogo(){
            return $this->_logo;
        }


        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                 S E R I E   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */

        /**
         * Add a new Serie
         *
         * @access public
         * @param mtePlotSerie $name
         */
        public function addSerie($name = '', $serie = ''){
            if ($name == ''){
                $name = count($this->_serie)+1;
            }

            if ($serie == ''){
                $serie =  new mtePlotSerie();
            }

            // Asign
            $this->_serie[$name] = $serie;
            $this->clearPoints($name);
        }

        /**
         * Returns the serie object matching received name
         *
         * @access private
         * @param string $name
         * @return mtePlotSerie
         */
        private function _getSerie($name){
            return $this->_serie[$name];
        }

        /**
         * Clone a Serie set
         *
         * @access public
         * @param string $source
         * @param string $target
         */
        public function copySerie($target = '', $source){
            $this->addSerie($target, clone $this->_getSerie($source));
        }

        /**
         * Delete a Serie set
         *
         * @access public
         * @param string $name
         */
        public function removeSerie($name){
            unset($this->_serie[$name]);
        }

        /**
         * Delete all Serie set
         *
         * @access public
         */
        public function clearSeries(){
            $this->_serie = array();
        }

        /**
         * clear points of serie
         *
         * @access public
         * @param string $name
         */
        public function clearPoints($name){
            $this->_getSerie($name)->clearPoints();
        }

        /**
         * add point of serie
         *
         * @access public
         * @param string $name
         */
        public function addPoint($name, $valueX, $valueY){
            $this->_getSerie($name)->addPoint($valueX, $valueY);
        }

        /**
         * add points
         *
         * @param string $name
         * @param array $points
         */
        public function addPoints($name, $points){
            if (is_arry($points)){
                foreach ($points as $x => $y) {
                    $this->addPoint($name, $x, $y);
                }
            }
        }

        /**
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         *                  G R A P H   M A N A G E M E N T
         *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
         */
        public function createChart($type, $series, $targetFile, $width = 600, $height = 250){

            // normalize params
            if (!is_array($series)){
                $series = array($series);
            }

            // generate dataset
            if (count($series) > 1){
                // Genero Series
                $dataSerie = array();
                foreach ($series as $idSerie => $nameSerie) {
                    $dataSerie[$nameSerie] = $this->_getSerie($nameSerie)->getDataSetChart();
                }

                // Agrego series
                $dataSet = new XYSeriesDataSet();
                foreach ($dataSerie as $nameSerie => $dataSerie){
                    $dataSet->addSerie($nameSerie, $dataSerie);
                }
            }
            else{
                $dataSet = $this->_getSerie($series[0])->getDataSetChart();
            }

            switch ($type) {
                case mteConst::MTE_GRAPH_VERTICALBAR:
                {
                    // create chart
                    $chart = new VerticalBarChart($width, $height);
                    break;
                }
                case mteConst::MTE_GRAPH_HORIZONTALBAR:
                {
                    // create chart
                    $chart = new HorizontalBarChart($width, $height);
                    break;
                }
                case mteConst::MTE_GRAPH_PIE:
                {
                    // create chart
                    $chart = new PieChart($width, $height);
                    break;
                }
                case mteConst::MTE_GRAPH_LINE:
                {
                    // create chart
                    $chart = new LineChart($width, $height);
                    break;
                }
            }

            // generate image chart
            $chart->setDataSet($dataSet);
            if (is_file($this->getCompanyLogo())){
                $chart->setCompanyLogo($this->getCompanyLogo());
            }
            $chart->setTitle($this->getTitle());
            $chart->render($targetFile);
        }
    }
?>