<?php
/**
 * export list pdf
 *
 * @filesource
 * @package motte
 * @subpackage misc
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 			Carlos Gagliardi (carlosgag@gmail.com) /
 * 			Braulio Rios (braulioriosf@gmail.com) /
 * 			Pablo Erartes (pabloeuy@gmail.com) /
 * 			GBoksar/Perro (gustavo@boksar.info)
 */

    class mteExportListPdf extends mteExportList {

        /**
         *
         * @access private
         * @var boolean
         */
        private $_autoCalcColumSize;

        /**
         *
         * @access private
         * @var string
         */
        private $_orientation;

        /**
         *
         * @access private
         * @var string
         */
        private $_format;

        /**
         *
         * @access private
         * @var string
         */
        private $_title;

        /**
         *
         * @access private
         * @var string
         */
        private $_fontTitle;

        /**
         *
         * @access private
         * @var string
         */
        private $_subtitle;

        /**
         *
         * @access private
         * @var string
         */
        private $_fontSubtitle;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_header;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerHeight;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerImage;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerImageWidth;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerName;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerData;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerComment;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_footer;

        /**
         *
         * @access private
         * @var string
         */
        private $_footerLeft;

        /**
         *
         * @access private
         * @var string
         */
        private $_footerRigth;

        /**
         * Margin Left
         *
         * @var integer
         */
        private $_marginLeft;

        /**
         * Margin Rigth
         *
         * @var integer
         */
        private $_marginRigth;

        /**
         * Margin Top
         *output
         * @var integer
         *
         */
        private $_marginTop;

        /**
         * Margin Bottom
         *
         * @var integer
         */
        private $_marginBottom;

        /**
         *
         * @access private
         * @var string
         */
        private $_fontFamily;

        /**
         *
         * @access private
         * @var string
         */
        private $_fontSize;

		/**
         *
         * @access private
         * @var mtePDF
		 */
		 private $_pdf;


        /**
         * Constructor
         *
         * @access public
         * @return mteExportListPdf
         */
        public function __construct() {
            // Initialize
            $this->setTitle();
            $this->setSubTitle();
            $this->autoCalcColumSize(0);
            $this->setHeaderContent();
            $this->setFooterContent();
            $this->setMargin();
            $this->setFontFamily('Arial', 'regular');
            $this->setFormat(mteConst::MTE_PDF_FORMAT_A4);
            $this->setOrientation(mteConst::MTE_PDF_PORTRAIT);
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        // ------------------------------------------------------------------------
        //                          P R O P E R T I E S
        // ------------------------------------------------------------------------
        /**
         * set autocalc colum size
         *
         * @param boolean $value
         * @access public
         */
        public function autoCalcColumSize($value = ''){
            $this->_autoCalcColumSize = $value;
        }

        /**
         * set title
         *
         * @param string $title
         * @access public
         */
        public function setTitle($title = '', $font = ''){
            $this->_title = $title;
            $this->_fontTitle = ($font == ''?'title':$font);
        }

        /**
         * set subtitle
         *
         * @param string $subtitle
         * @access public
         */
        public function setSubTitle($subtitle = '', $font = ''){
            $this->_subtitle = $subtitle;
            $this->_fontSubtitle = ($font == ''?'subtitle':$font);
        }

        /**
         * set header content
         *
         * @param string $img
         * @param string $name
         * @param string $data
         * @param string $comment
         */
        public function setHeaderContent($active = true, $height = 30,  $img = '', $imgWidth = 20, $name = '', $data = '', $comment = ''){
            $this->_header           = $active;
            $this->_headerHeight     = $height;
            $this->_headerImage      = $img;
            $this->_headerImageWidth = $imgWidth;
            $this->_headerName       = $name;
            $this->_headerData       = $data;
            $this->_headerComment    = $comment;
        }

        /**
         * set footer content
         *
         * @param string $left
         * @param string $center
         */
        public function setFooterContent($active = true, $left = '', $rigth = ''){
            $this->_footer       = $active;
            $this->_footerLeft   = $left;
            $this->_footerRigth  = $rigth;
        }

        /**
         * set Margins
         *
         * @param integer $left
         * @param integer $top
         * @param integer $rigth
         * @param integer $bottom
         */
        public function setMargin($left = 0, $top = 0, $rigth = 0, $bottom = 0){
            // Set margins
            $this->_marginLeft   = $left;
            $this->_marginTop    = $top;
            $this->_marginRigth  = $rigth;
            $this->_marginBottom = $bottom;
        }

        /**
         * Set font family
         *
         * @param string $value
         */
        public function setFontFamily($value, $size){
            $this->_fontFamily = $value;
            $this->_fontSize   = $size;
        }

        /**
         * Set orientation
         *
         * @param string $value
         */
        public function setOrientation($value = ''){
            $this->_orientation = ($value == ''?mteConst::MTE_PDF_PORTRAIT:$value);
        }

        /**
         * set format
         *
         * @param string $value
         */
        public function setFormat($value = ''){
            $this->_format = ($value == ''?mteConst::MTE_PDF_FORMAT_A4:$value);
        }


        /**
         * set Document
         *
         * @param mtePdf $value
         */
		public function setDocument($pdf){
			$this->_pdf = $pdf;
		}

        // ------------------------------------------------------------------------
        //                              O U T P U T
        // ------------------------------------------------------------------------

		/**
		 * Create document
		 *
		 */
		private function _createDocument(){
			if (!($this->_pdf instanceof mtePDF)){
				// Creo pdf
				$this->_pdf = new mtePDF($this->_orientation, '', $this->_format);

				// --------------------
				//        Header
				// --------------------
				$this->_pdf->autoHeader($this->_header);
				if ($this->_header){
					$this->_pdf->setHeaderContent($this->_headerHeight, $this->_headerImage, $this->_headerImageWidth);
					$this->_pdf->addHeaderText($this->_headerName, 'subtitle');
					$this->_pdf->addHeaderText($this->_headerData, 'regular');
					$this->_pdf->addHeaderText($this->_headerComment, 'small');
				}
				// --------------------
				//       Footer
				// --------------------
				$this->_pdf->autoFooter($this->_footer);
				if ($this->_footer){
					$this->_pdf->setFooterContent($this->_footerLeft, mteConst::MTE_PDF_VAR_CURRENTDATETIME.' - '.'Pag: '.mteConst::MTE_PDF_VAR_CURRENTPAGE.' / '.mteConst::MTE_PDF_VAR_TOTPAGE, $this->_footerRigth);
				}

				// --------------------
				//      Document
				// --------------------
				$this->_pdf->autoPageBreak(true);
				$this->_pdf->setMarginsPage($this->_marginLeft, $this->_marginTop, $this->_marginRigth, $this->_marginBottom);				
			}
		}


        /**
         * Calc Colum Size
         *
         * @param boolean $autoSize
         * @return integer
         */
        private function _calcColumnSize($autoSize){
            $result = array();
            foreach ($this->getColumns() as $columName => $col){
                $result[$columName] = $col['size'];
                if ($autoSize){
                    $this->_pdf->selectFont($this->_fontSize, $col['styleTitle']);
                    $result[$columName] = $this->_pdf->getTextWidth($col['title'])+2;
                }
            }

            // Auto size
            if ($autoSize){
                // data
                foreach ($this->getData() as $record) {
                    foreach ($this->getColumns() as $field => $col){
                        if (isset($record[$col['fieldName']])){
                            $this->_pdf->selectFont($this->_fontSize, $col['styleData']);
                            $sizeCalc = $this->_pdf->getTextWidth($record[$col['fieldName']])+2;
                            if ($result[$col['fieldName']] < $sizeCalc){
                                $result[$col['fieldName']] = $sizeCalc;
                            }
                        }
                    }
                }

				$sizeU = $this->_sumSizeCols($result);
				while ($sizeU > $this->_pdf->getMaxWidth()){
					$colM = '';
					foreach ($result as $field => $size) {
						if (!isset($result[$colM]) ||  $result[$colM] < $size){
							$colM = $field;
						}
					}
					$result[$colM] = $result[$colM]-($result[$colM]*5/100);
					$sizeU = $this->_sumSizeCols($result);
				}
            }

            // return
            return $result;
        }

        /**
         * Sum sizes
         *
         * @param integer $size
         * @return integer
         */
        private function _sumSizeCols($size){
            $result = 0;
            foreach ($size as $value){
                $result = $result + $value;
            }
            // result
            return $result;
        }

        /**
         * export list
         *
         * @param string $type
         * @param string $dir
         * @param string $name
         * @param string $ext
         * @return string
         */
        public function export($type, $dir = '', $name = ''){
			// Create pdf document
			$this->_createDocument();
			$this->_pdf->newPage();
            $this->_pdf->setFontFamily($this->_fontFamily);

            // --------------------------
            //    Title & subtitle
            // --------------------------
            if ($this->_title != ''){
                $this->_pdf->selectFont($this->_fontTitle);
                $this->_pdf->setPosX($this->_pdf->getMarginLeft());
                $this->_pdf->drawCell($this->_title, 0, 0, mteConst::MTE_PDF_ALIGN_CENTER);
                $this->_pdf->eol();
            }
            if ($this->_subtitle != ''){
                $this->_pdf->selectFont($this->_fontSubtitle);
                $this->_pdf->setPosX($this->_pdf->getMarginLeft());
                $this->_pdf->drawCell($this->_subtitle, 0, 0, mteConst::MTE_PDF_ALIGN_CENTER);
                $this->_pdf->eol();
            }
            $this->_pdf->eol();

            // --------------------------
            //     Colum size
            // --------------------------
            $sizeCols = $this->_calcColumnSize($this->_autoCalcColumSize);
            $totSize  = $this->_sumSizeCols($sizeCols);
            $posX = (($this->_pdf->getMaxWidth()-$totSize)/2)+$this->_pdf->getMarginLeft();

            // --------------------------
            //     Colum Header
            // --------------------------
            $posXAux = $posX;
            foreach ($this->getColumns() as $columName => $col) {
                // display
                $this->_pdf->setPosX($posXAux);
                $this->_pdf->selectFont($this->_fontSize, $col['styleTitle']);
                $this->_pdf->drawCell($col['title'], $sizeCols[$columName], 6, $col['alignTitle'], 1, 0);
                // position
                $posXAux = $posXAux + $sizeCols[$columName];
            }
            $this->_pdf->eol();

            // --------------------------
            //           Data
            // --------------------------
            foreach ($this->getData() as $record) {
                $posXAux = $posX;
                foreach ($this->getColumns() as $columName => $col){
                    // exists
                    $value = '';
                    if (isset($record[$col['fieldName']])){
                        $value = $record[$col['fieldName']];
                    }
                    // display
                    $this->_pdf->setPosX($posXAux);
                    $this->_pdf->selectFont($this->_fontSize, $col['styleData']);
                    $this->_pdf->drawCell($value, $sizeCols[$columName], 6, $col['alignData'], 0, 0);
                    // position
                    $posXAux = $posXAux + $sizeCols[$columName];
                }
                $this->_pdf->eol();
            }

            // type
            $result = '';
            switch ($type) {
                case mteConst::MTE_EXPORT_SEND:
                {
                    $this->_pdf->send($name);
                    break;
                }
                case mteConst::MTE_EXPORT_DOWNLOAD:
                {
                    $this->_pdf->download($name);
                    break;
                }
                case mteConst::MTE_EXPORT_SAVE:
                {
                    // parameters
                    if ($dir == '' ){
                        $dir = MTE_CACHE;
                    }
                    $this->_pdf->save($dir, $name);
                    break;
                }
                case mteConst::MTE_EXPORT_STRING:
                {
                    $result = $this->_pdf->toString();
                    break;
                }
            }
            return $result;
        }
    }
?>