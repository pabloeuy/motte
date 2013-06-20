<?php
/**
 * Plot class
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license GPLv2 http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author  Pedro Gauna (pgauna@gmail.com) /
 *          Carlos Gagliardi (carlosgag@gmail.com) /
 *          Braulio Rios (braulioriosf@gmail.com) /
 *          Pablo Erartes (pabloeuy@gmail.com) /
 *          GBoksar/Perro (gustavo@boksar.info)
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
	 * Transparency
	 * @var integer
	 */
	private $_transparency;

	/**
	 * Font sizes for axes, legend and title
	 * @var integer
	 */
	private $_fontSizes;

	/**
	 * Saves the max and min values, and the number of intervals
	 * @var array
	 */
	private $_fixedScale;

	/**
	 * Abscise labels
	 * @var array
	 */
	private $_abscise;

	/**
	 * Serie colors
	 * @var array
	 */
	private $_colors;

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
		$this->setFontSizes();
		$this->clearFixedScale();
	}

	/**
	 * Destructor
	 *
	 * @access public
	 */
	function __destruct() {
	}

	/**
	 *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	 *                 S T Y L E   M A N A G E M E N T
	 *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	 */
	 
	 /**
	 * Set each font size
	 * @param integer $title[optional]
	 * @param integer $axes[optional]
	 * @param integer $legend[optional]
	 */
	public function setFontSizes($title = 0, $axes = 0, $legend = 0) {
		$this->_fontSizes['title'] = $title;
		$this->_fontSizes['axes'] = $axes;
		$this->_fontSizes['legend'] = $legend;
	}

	/**
	 * Set the size of the title font
	 * @param integer $size
	 */
	public function setTitleFontSize($size) {
		$this->_fontSizes['title'] = $size;
	}

	/**
	 * Set the size of the x and y axes font
	 * @param integer $size
	 */
	public function setAxesFontSize($size) {
		$this->_fontSizes['axes'] = $size;
	}

	/**
	 * Set the size of the legend font
	 * @param integer $size
	 */
	public function setLegendFontSize($size) {
		$this->_fontSizes['legend'] = $size;
	}


	public function setTransparency($alpha) {
		$this->_transparency = $alpha;
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

	public function setTitle($value = '') {
		$this->_title = $value;
	}

	/**
	 * Set the lesser, upper and number of intervals of the vertical axis
	 * @param integer $minValue
	 * @param integer $maxValue
	 * @param integer $partitions[optional]
	 */
	public function setFixedScale($minValue, $maxValue, $partitions = 5) {
		$this->_fixedScale = array ();
		$this->_fixedScale['min'] = $minValue;
		$this->_fixedScale['max'] = $maxValue;
		$this->_fixedScale['partitions'] = $partitions;
		$this->_fixedScale['manual'] = true;

	}


	public function clearFixedScale() {
		$this->_fixedScale = 0;
	}

	/**
	 * Set the elements of the array as abscise labels
	 * @param array $elements
	 */
	public function setAbscise($elements) {
		if (!is_array($elements))
		$elements = array ($elements);
		$this->_abscise = $elements;
	}

	/**
	 * get Title serie
	 *
	 * @access public
	 * @return string
	 */
	public function getTitle() {
		return $this->_title;
	}

	/**
	 * set Logo serie
	 *
	 * @access public
	 * @param string $value
	 */
	public function setCompanyLogo($value = '') {
		$this->_logo = $value;
	}

	/**
	 * get Logo serie
	 *
	 * @access public
	 * @return string
	 */
	public function getCompanyLogo() {
		return $this->_logo;
	}

	public function setColour($serie, $R, $G, $B) {
		$this->_colors[$serie] = array ($R, $G, $B);
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
	public function addSerie($name = '', $serie = '') {
		if ($name == '') {
			$name = count($this->_serie)+1;
		}

		if ($serie == '') {
			$serie = new mtePlotSerie();
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
	private function _getSerie($name) {
		return $this->_serie[$name];
	}

	/**
	 * Clone a Serie set
	 *
	 * @access public
	 * @param string $source
	 * @param string $target
	 */
	public function copySerie($target = '', $source) {
		$this->addSerie($target, clone $this->_getSerie($source));
	}

	/**
	 * Delete a Serie set
	 *
	 * @access public
	 * @param string $name
	 */
	public function removeSerie($name) {
		unset ($this->_serie[$name]);
	}

	/**
	 * Delete all Serie set
	 *
	 * @access public
	 */
	public function clearSeries() {
		$this->_serie = array ();
	}

	/**
	 * clear points of serie
	 *
	 * @access public
	 * @param string $name
	 */
	public function clearPoints($name) {
		$this->_getSerie($name)->clearPoints();
	}

	/**
	 * add point of serie
	 *
	 * @access public
	 * @param string $name
	 */
	public function addPoint($name, $valueX, $valueY) {
		$this->_getSerie($name)->addPoint($valueX, $valueY);
	}

	/**
	 * add points
	 *
	 * @param string $name
	 * @param array $points
	 */
	public function addPoints($name, $points) {
		if (is_arry($points)) {
			foreach ($points as $x=>$y) {
				$this->addPoint($name, $x, $y);
			}
		}
	}

	/**
	 *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	 *                  G R A P H   M A N A G E M E N T
	 *  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	 */

	//function to set an apropiate scale in the Y axis. The default pChart's result is ugly!
	private function _fixScale($maxValue, $minValue) {
		$maxInt = (int)$maxValue;
		if ($maxInt != $maxValue) {
			$maxInt += 1;
		}
		$e = substr("$maxInt", 1);
		$c = strlen($e);
		$e = (int)$e;
		$t = pow(10, $c-1);
		$max = $maxInt;
		if (fmod($e, $t)) {
			$max = $maxInt-fmod($e, $t)+$t;
		}

		$min = 0;
		if ($minValue < 0) {
			$minInt = (int)$minValue;
			if ($minInt != $minValue) {
				$minInt -= 1;
			}
			$e = substr(''.abs($minInt), 1);
			$c = strlen($e);
			$e = (int)$e;
			$t = pow(10, $c-1);
			$min = $minInt;
			if (fmod($e, $t)) {
				$min = $minInt+fmod($e, $t)-$t;
			}
		}

		$this->_fixedScale = array ();
		$this->_fixedScale['min'] = $min;
		$this->_fixedScale['max'] = $max;
		$this->_fixedScale['partitions'] = 5;
		$this->_fixedScale['manual'] = false;
	}


	/**
	 * function to create and render the chart
	 * @return
	 * @param const $type
	 * @param array $series
	 * @param string $targetFile
	 * @param integer $width[optional]
	 * @param integer $height[optional]
	 * @param boolean $drawGrid[optional]
	 * @param boolean $shadow[optional]
	 * @param boolean $drawPoints[optional] (only for mteConst::MTE_PLOT_LINE)
	 * @param boolean $curve[optional] (only for mteConst::MTE_PLOT_LINE)
	 * @param boolean $drawZeroLine[optional] (only for mteConst::MTE_PLOT_LINE)
	 */
    public function createChart($type, $series, $targetFile, $width = 600, $height = 250, $drawGrid = false, $shadow = false, $drawPoints = false, $curve = false, $drawZeroLine = false, $explode = false) {
    
        // normalize params
        if (!is_array($series)) {
            $series = array ($series);
        }
    
        $dataSet = new pData;
        $chart = new pChart($width, $height);
    
    
        //-----DATASET SETUP-------
        //add points
        $i = 0;
        $maxValue = 0; //used to determine the number of characters of the max value, in order to place the Y axis in the right place.
        $max = 0;
        $min = 0;
    
        $longestLegendText = 0;
    
        foreach ($series as $serie) {
            foreach ($this->_getSerie($serie)->getPoints() as $xValue=>$point) {
                $dataSet->AddPoint($point, $serie);
    
                //measures stuff...
                if (abs($point) > abs($maxValue)) {
                    $maxValue = abs($point);
                }
                if ($point > $max) {
                    $max = $point;
                }
                if ($point < $min) {
                    $min = $point;
                }
                if ($type != mteConst::MTE_GRAPH_PIE and strlen("$serie") > $longestLegendText) {
                    $longestLegendText = strlen("$serie");
                }
                elseif (strlen("$xValue") > $longestLegendText) { //In pie graphs the legend contents are the name of the  points
                    $longestLegendText = strlen("$xValue");
                }
            }
            $dataSet->AddSerie($serie);
    
            //Colour for the serie, if defined
            if (is_array($this->_colors) and array_key_exists($serie, $this->_colors)) {
                $colors = $this->_colors[$serie];
                $chart->setColorPalette($i, $colors[0], $colors[1], $colors[2]);
            }
    
            //IMPROVE: THE NAME OF THE SERIE SHOULDN'T BE THE SAME THAN ITS IDENTIFIER
            $dataSet->SetSerieName($serie, $serie);
            $i++;
        }
    
    
        //only will be taken the first serie's abscise points if not set
        if (is_array($this->_abscise))
            $dataSet->AddPoint($this->_abscise, 'abscise');
        else
            $dataSet->AddPoint(array_keys($this->_getSerie(array_shift($series))->getPoints()), 'abscise');
        $dataSet->SetAbsciseLabelSerie('abscise');
    
    //----GRAPH SETUP----------
    //AUTO CALCULATIONS
    //font sizes
    if ($this->_fontSizes['axes'] == 0) {
        $this->_fontSizes['axes'] = (int)($width/70);
    }
    
    if ($this->_fontSizes['title'] == 0) {
        $this->_fontSizes['title'] = (int)($width/40);
    }
    
    if ($this->_fontSizes['legend'] == 0) {
        $this->_fontSizes['legend'] = (int)($width/45);
    }
    //POSITIONING
    
    //Legend position
    //The getLegendBox() and getLegendBoxSize methods in pchart doesn't work!
    $lw = ($longestLegendText+3)*$this->_fontSizes['legend']; //the legend box width
    $position['Legend'] = array ('x'=>$width-$lw, 'y'=>2*(int)($this->_fontSizes['title']));
    
    //Chart position
    $posibleMax = array (6, strlen("$maxValue")+2); //posible greatest values in the scale
    
    //if not set the Y scale, auto calculate here instead of pchart, because pchart calculation is ugly.
    if (!is_array($this->_fixedScale) and $this->_fixedScale['manual']) {
        $this->_fixScale($max, $min);
    }
    $posibleMax[] = strlen(abs($this->_fixedScale['max'])+2);
    $posibleMax[] = strlen(abs($this->_fixedScale['min'])+2);
    $chart->setFixedScale($this->_fixedScale['min'], $this->_fixedScale['max'], $this->_fixedScale['partitions']);
    
    
    $maxDigits = max($posibleMax);
    $x1 = $maxDigits*$this->_fontSizes['axes']; //there should be space for the greatest value in the scale
    //echo "maximo: ".$maxScaleValue." espacio: $x1 - ";
    $position['Chart'] = array ('x1'=>$x1, 'x2'=>$position['Legend']['x']-5, 'y1'=>(int)($this->_fontSizes['title']+5), 'y2'=>$height-4*$this->_fontSizes['axes']);
    
    //Title position
    $position['Title'] = array ('x'=>$position['Chart']['x1'], 'y'=>$this->_fontSizes['title']+2);
    
    //TITLE
    $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['title']);
    $chart->drawTitle($position['Title']['x'], $position['Title']['y'], $this->getTitle(), 0, 0, 0);
    
    //DETAILS
    //DEPENDING ON THE CHART TYPE
    switch($type) {
        case mteConst::MTE_GRAPH_VERTICALBAR:
            {
                // create chart
                $chart->setGraphArea($position['Chart']['x1'], $position['Chart']['y1'], $position['Chart']['x2'], $position['Chart']['y2']);
                //$chart->drawGraphArea(255,255,255,TRUE);
    
                if ($drawGrid) {
                    $chart->drawGrid(4, TRUE, 150, 150, 150);
                }
    
                //AXES
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['axes']);
                $chart->drawScale($dataSet->GetData(), $dataSet->GetDataDescription(), SCALE_ADDALL, 150, 150, 150, TRUE, 0, 2, TRUE);
    
    
    
                //LEGEND
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['legend']);
                $chart->drawLegend($position['Legend']['x'], $position['Legend']['y'], $dataSet->GetDataDescription(), 255, 255, 255);
    
                if ($shadow) {
                    $chart->setShadowProperties(3, 3, 0, 0, 0, 30, 4);
                }
                $chart->drawBarGraph($dataSet->GetData(), $dataSet->GetDataDescription(), TRUE);
                break;
            }
        case mteConst::MTE_GRAPH_HORIZONTALBAR:
            {
                // create chart
                $chart->setGraphArea($position['Chart']['x1'], $position['Chart']['y1'], $position['Chart']['x2'], $position['Chart']['y2']);
                //$chart->drawGraphArea(255,255,255,TRUE);
                if ($drawGrid) {
                    $chart->drawGrid(10, TRUE, 150, 150, 150);
                }
    
                //AXES
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['axes']);
                $chart->drawScale($dataSet->GetData(), $dataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 0, 2, TRUE);
    
    
    
                //LEGEND
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['legend']);
                $chart->drawLegend($position['Legend']['x'], $position['Legend']['y'], $dataSet->GetDataDescription(), 255, 255, 255);
    
                if ($shadow) {
                    $chart->setShadowProperties(3, 3, 0, 0, 0, 30, 4);
                }
                $chart->drawBarGraph($dataSet->GetData(), $dataSet->GetDataDescription(), TRUE);
                break;
            }
        case mteConst::MTE_GRAPH_PIE:
            { //auto calculate pie radius
                $rad = min(($position['Chart']['x1']+$position['Chart']['x2'])/2-7*$this->_fontSizes['legend'], ($position['Chart']['y1']+$position['Chart']['y2']-$position['Title']['y'])/2);
                // create chart
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['legend']);
                if ($drawGrid) {
                    $chart->drawGrid(2, TRUE, 100, 100, 100, 150);
                }
                if ($shadow) {
                    $chart->setShadowProperties(3, 3, 0, 0, 0, 30, 4);
                }
                $chart->drawPieLegend($position['Legend']['x'], $position['Legend']['y'], $dataSet->GetData(), $dataSet->GetDataDescription(), 250, 250, 250);
                $chart->drawPieGraph($dataSet->GetData(), $dataSet->GetDataDescription(), $rad+5*$this->_fontSizes['legend'], $rad+$position['Title']['y'], $rad, PIE_PERCENTAGE, TRUE, 60, 20, $explode*10);
                break;
            }
        case mteConst::MTE_GRAPH_LINE:
            {
                // create chart
                //GRAPHIC
                $chart->setGraphArea($position['Chart']['x1'], $position['Chart']['y1'], $position['Chart']['x2'], $position['Chart']['y2']);
    
                $chart->drawGraphArea(255, 255, 255, TRUE);
    
    
    
    
                //AXES
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['axes']);
                $chart->drawScale($dataSet->GetData(), $dataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 0, 2);
                if ($drawZeroLine) {
                    $chart->drawTreshold(0, 150, 150, 150, TRUE, TRUE);
                }
    
    
    
    
                //LEGEND
                $chart->setFontProperties(MOTTE_LIB."/pchart/Fonts/tahoma.ttf", $this->_fontSizes['legend']);
                $chart->drawLegend($position['Legend']['x'], $position['Legend']['y'], $dataSet->GetDataDescription(), 255, 255, 255);
    
                if ($drawGrid) {
                    $chart->drawGrid(2, TRUE, 230, 230, 230, 150);
                }
                if ($shadow) {
                    $chart->setShadowProperties(3, 3, 0, 0, 0, 30, 4);
                }
                if ($curve)
                    $chart->drawCubicCurve($dataSet->GetData(), $dataSet->GetDataDescription());
                else
                    $chart->drawLineGraph($dataSet->GetData(), $dataSet->GetDataDescription());
                if ($drawPoints)
                    $chart->drawPlotGraph($dataSet->GetData(), $dataSet->GetDataDescription(), 3, 1);
    
    
                break;
        }
    }
    
    $chart->Render($targetFile);
    }

}
