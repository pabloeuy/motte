<?php
/**
 * Clase para el manejo de pdfs (Wrapper para fpdf)
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

    class mtePDF extends fpdf{

        /**
         *
         * @access private
         * @var boolean
         */
        private $_autoHeader;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_autoFooter;

        /**
         *
         * @access private
         * @var boolean
         */
        private $_autoPageBreak;

        /**
         *
         * @access private
         * @var integer
         */
        private $_headerHeight;

        /**
         *
         * @access private
         * @var string
         */
        private $_headerLogo;

        /**
         *
         * @access private
         * @var integeroutput
         */
        private $_headerLogoWidth;

        /**
         *
         * @access private
         * @var array
         */
        private $_headerText;

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
         *
         * @access private
         * @var string
         */
        private $_footerCenter;

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
         * family font
         *
         * @var string
         */
        private $_fontFamily;

        /**
         * styleFonts
         *
         * @var array
         */
        private $_styleFont;

        /**
         * Spacing
         *
         * @var integer
         */
        private $_spacing;

        /**
         * styleColor
         *
         * @var array
         */
        private $_styleColor;

        /**
         * styleLine
         *
         * @var array
         */
        private $_styleLine;

        /**
         * Constructor
         *
         * @access public
         * @return mtePDF
         */
        public function __construct($orientation = '', $unit = '', $format = '', $width = '', $length='') {

            // Parameters
            if ($orientation == ''){
                $orientation = mteConst::MTE_PDF_PORTRAIT;
            }
            if ($unit == ''){
                $unit = mteConst::MTE_PDF_UNIT_MM;
            }
            if ($format == ''){
                $format = mteConst::MTE_PDF_FORMAT_A4;
            }
            if ($format == mteConst::MTE_PDF_FORMAT_CUSTOMIZED){
                $format = array($width, $length);
            }

            // Inicilize
            $this->autoHeader(false);
            $this->autoFooter(false);
            $this->setHeaderContent();
            $this->setFooterContent();

            // Add font Styles
            $this->setFontFamily('Arial');
            $this->addFontStyle('small',7,3);
            $this->addFontStyle('smallBold',7,3,mteConst::MTE_PDF_FONT_BOLD);
            $this->addFontStyle('smallBoldUnderline',7,3,mteConst::MTE_PDF_FONT_BOLD.mteConst::MTE_PDF_FONT_UNDERLINE);
            $this->addFontStyle('regular',11,4);
            $this->addFontStyle('bold',11,4,mteConst::MTE_PDF_FONT_BOLD);
            $this->addFontStyle('italic',11,4,mteConst::MTE_PDF_FONT_ITALIC);
            $this->addFontStyle('underline',11,4,mteConst::MTE_PDF_FONT_UNDERLINE);
            $this->addFontStyle('subtitle',14,5,mteConst::MTE_PDF_FONT_BOLD);
            $this->addFontStyle('title',17,7,mteConst::MTE_PDF_FONT_BOLD);

            // Add color Style
            $this->addStyleColor('black',0,0,0);
            $this->addStyleColor('red',255,0,0);
            $this->addStyleColor('green',0,255,0);
            $this->addStyleColor('blue',0,0,255);
            $this->addStyleColor('white',255,255,255);
            $this->addStyleColor('gray10',200);
            $this->addStyleColor('gray20',180);
            $this->addStyleColor('gray50',125);
            $this->addStyleColor('gray80',80);

            // Add line width Style
            $this->addStyleLine('thin', 0.1);
            $this->addStyleLine('normal', 0.2);
            $this->addStyleLine('thick', 0.4);

            // create pdf
            parent::fpdf($orientation, $unit, $format);
            $this->AliasNbPages();
        }

        /**
        * Destructor
        *
        * @access public
        */
        public function __destruct(){
        }

        //--------------------------------------------------------------------------
        //                        F O N T   S T Y L E S
        //--------------------------------------------------------------------------
        /**
         * set font family
         *
         * @param string $family
         */
        public function setFontFamily($family = ''){
            $pos = stripos(strToUpper('Courier,Helvetica,Arial,Times,Symbol,ZapfDingbats'), strToUpper($family));
            if ($pos === false){
                $family = 'Arial';
            }
            // Assign
            $this->_fontFamily = $family;
        }

        /**
         * get Font Family
         *
         * @return string
         */
        public function getFontFamily(){
            return $this->_fontFamily;
        }

        /**
         * add style font
         *
         * @param string $name
         * @param integer $size
         * @param string $style
         * @param string $family
         */
        public function addFontStyle($name, $size = 12, $spacing = 7, $style = '', $family = ''){
            $this->_styleFont[$name] = array('size'=>$size, 'spacing'=>$spacing, 'style'=>$style, 'family'=>$family);
        }

        /**0
         * select font style
         *
         * @param string $name
         */
        public function selectFont($name = '', $style = ''){
            if ($name == ''){
                $name = 'regular';
            }

            if (array_key_exists($name, $this->_styleFont)){
                // Family
                $family = $this->_styleFont[$name]['family'];
                if ($family == ''){
                    $family = $this->getFontFamily();
                }
                if ($style == ''){
                    $style = $this->_styleFont[$name]['style'];
                }

                // Set font
                $this->SetFont($family, $style, $this->_styleFont[$name]['size']);
                $this->_spacing = $this->_styleFont[$name]['spacing'];
            }
        }

        //--------------------------------------------------------------------------
        //                        C O L O R   S T Y L E S
        //--------------------------------------------------------------------------
        public function addStyleColor($name, $r, $g='', $b=''){
            $this->_styleColor[$name] = array($r, $g, $b);
        }

        /**
         * Select fill color
         *
         * @param string $name
         */
        public function selectFillColor($name = ''){
            if ($name == ''){
                $name = 'black';
            }

            if (array_key_exists($name, $this->_styleColor)){
                if (($this->_styleColor[$name][1] != '') && ($this->_styleColor[$name][2] != '')){
                    $this->SetFillColor($this->_styleColor[$name][0],$this->_styleColor[$name][1],$this->_styleColor[$name][2]);
                }
                else{
                    $this->SetFillColor($this->_styleColor[$name][0]);
                }
            }
        }

        /**
         * Select border color
         *
         * @param string $name
         */
        public function selectBorderColor($name = ''){
            if ($name == ''){
                $name = 'black';
            }

            if (array_key_exists($name, $this->_styleColor)){
                if (($this->_styleColor[$name][1] != '') && ($this->_styleColor[$name][2] != '')){
                    $this->SetDrawColor($this->_styleColor[$name][0],$this->_styleColor[$name][1],$this->_styleColor[$name][2]);
                }
                else{
                    $this->SetDrawColor($this->_styleColor[$name][0]);
                }
            }
        }

        //--------------------------------------------------------------------------
        //                        L I N E   S T Y L E S
        //--------------------------------------------------------------------------
        public function addStyleLine($name, $width){
            $this->_styleLine[$name] = $width;
        }

        /**
         * Select width line
         *
         * @param string $name
         */
        public function selectBorderWidth($name = ''){
            if ($name == ''){
                $name = 'normal';
            }

            if (array_key_exists($name, $this->_styleLine)){
                $this->SetLineWidth($this->_styleLine[$name]);
            }
        }


        //--------------------------------------------------------------------------
        //                             D O C U M E N T
        //--------------------------------------------------------------------------
        /**
         * Set tags
         *
         * @param string $author
         * @param string $title
         * @param string $creator
         * @param string $keywords
         * @param string $subject
         */
        public function setPDFTags($author = '', $title = '', $creator = '', $keywords = '',  $subject = ''){
            $this->SetAuthor($author);
            $this->SetCreator($creator);
            $this->SetKeywords($keywords);
            $this->SetSubject($subject);
            $this->SetTitle($title);
        }

        /**
         * Set margins
         *
         * @param integer $top
         * @param integer $left
         * @param integer $rigth
         */
        public function setMarginsPage($left = 0, $top = 0, $rigth = 0, $bottom = 0){
            // Set margins
            $this->_marginLeft    = $left;
            $this->_marginRigth   = $rigth;
            $this->_marginTop     = $top;
            $this->_marginBottom  = $bottom;

            // Set margins pdf
            $this->SetMargins($left, $top, $rigth);

            $this->SetAutoPageBreak($this->_autoPageBreak, $bottom);
        }

        /**
         * get Margin
         *
         * @return float
         */
        public function getMarginLeft(){
            return $this->_marginLeft;
        }

        /**
         * get Margin
         *
         * @return float
         */
        public function getMarginRigth(){
            return $this->_marginRigth;
        }

        /**
         * get Margin
         *
         * @return float
         */
        public function getMarginTop(){
            return $this->_marginTop;
        }

        /**
         * get Margin
         *
         * @return float
         */
        public function getMarginBottom(){
            return $this->_marginBottom;
        }

        /**
         * set auto header
         *
         * @param boolean $value
         */
        public function autoPageBreak($value = false){
            $this->_autoPageBreak = $value;
        }

        //--------------------------------------------------------------------------
        //               H E A D E R   y   F O O T E R
        //--------------------------------------------------------------------------
        /**
         * set auto header
         *
         * @param boolean $value
         */
        public function autoHeader($value = false){
            $this->_autoHeader = $value;
        }

        /**
         * set auto footer
         *
         * @param boolean $value
         */
        public function autoFooter($value = false){
            $this->_autoFooter = $value;
        }

        /**
         * set header content
         *
         * @param float $height
         * @param string $logo
         * @param float $logoWidth
         * @param array $lines
         */
        public function setHeaderContent($height = 25, $logo='', $logoWidth = 0){
            $this->_headerLogo        = $logo;
            $this->_headerLogoWidth   = $logoWidth;
            $this->_headerHeight      = $height;
        }

        /**
         * add header text
         *
         * @param string $txt
         * @param string $fontStyle
         * @param string $align
         */
        public function addHeaderText($txt, $fontStyle = '', $align = '', $width = 0, $height = 0, $border= 0, $fill = 0){
            // defualt Align
            if ($align == ''){
                $align = mteConst::MTE_PDF_ALIGN_LEFT;
            }
            // defualt Style
            if ($fontStyle == ''){
                $fontStyle = mteConst::MTE_PDF_FONT_REGULAR;
            }

            //add text
            $this->_headerText[] = array('text'=>$txt, 'font'=>$fontStyle, 'align'=>$align, 'width'=>$width, 'height'=>$height, 'border'=>$border, 'fill'=>$fill);
        }

        /**
         * set Footer content
         *
         * @param float $height
         * @param string $left
         * @param string $center
         * @param string $rigth
         */
        public function setFooterContent($left='', $center='', $rigth=''){
            $this->_footerLeft   = $left;
            $this->_footerRigth  = $rigth;
            $this->_footerCenter = $center;
        }

        /**
         * Header
         *
         */
        public function header(){
            if ($this->_autoHeader){
                $xCalc = $this->getMarginLeft();
                $yCalc = $this->getMarginTop();
                $wCalc = $this->getMaxWidth();

                // Image
                if ((!empty($this->_headerLogo)) && (file_exists($this->_headerLogo))){
                    $this->drawImage($this->_headerLogo, $xCalc, $yCalc, $this->_headerLogoWidth);
                    $xCalc = $xCalc+$this->_headerLogoWidth;
                    $wCalc = $wCalc-$this->_headerLogoWidth;
                }

                // Text
                $this->setPosY($yCalc);
                if (is_array($this->_headerText)){
                    foreach ($this->_headerText as $line) {
                        $this->setPosX($xCalc);
                        $this->selectFont($line['font']);
                        if ($line['width'] == 0){
                            $line['width'] = $wCalc;
                        }
                        $this->drawCell($line['text'], $line['width'], $line['height'], $line['align'], $line['border'], $line['fill']);
                        $this->eol();
                    }
                }

                // Line
                $this->selectBorderWidth('normal');
                $this->drawLine($this->getMarginLeft(), $this->_headerHeight-3, $this->getMaxWidth()+$this->getMarginLeft(), $this->_headerHeight-3);

                // position
                $this->setPosXY(0,$this->_headerHeight);
            }
        }

        /**
         * Footer
         *
         */
        public function footer(){
            if ($this->_autoFooter){
                // Line
                $this->selectBorderWidth('normal');
                $this->drawLine($this->getMarginLeft(), $this->getPageHeight()-$this->getMarginBottom()+1, $this->getMaxWidth()+$this->getMarginLeft(), $this->getPageHeight()-$this->getMarginBottom()+1);
                // Texts
                $this->setPosY($this->getPageHeight()-$this->getMarginBottom()+3);
                $this->selectFont('small');
                $this->drawCell($this->_footerLeft);
                $this->setPosY($this->getPageHeight()-$this->getMarginBottom()+3);
                $this->drawCell($this->_footerCenter, 0, 0,  mteConst::MTE_PDF_ALIGN_CENTER);
                $this->setPosY($this->getPageHeight()-$this->getMarginBottom()+3);
                $this->drawCell($this->_footerRigth, 0, 0,  mteConst::MTE_PDF_ALIGN_RIGTH);
            }
        }

        //--------------------------------------------------------------------------
        //
        //--------------------------------------------------------------------------
        /**
         * add Page
         *
         */
        public function newPage($orientation = ''){
            $this->AddPage($orientation);
            $this->selectFont();
        }

        /**
         * get Page width
         *
         * @return unknown
         */
        public function getPageWidth(){
            return $this->w;
        }

        /**
         * get max width
         *
         */
        public function getMaxWidth(){
            return $this->getPageWidth()-$this->_marginLeft-$this->_marginRigth;
        }


        /**
         * get Page Height
         *
         * @return float
         */
        public function getPageHeight(){
            return $this->h;
        }

        /**
         * get Max Height
         *
         * @return float
         */
        public function getMaxHeight(){
            return $this->getPageHeight()-$this->_marginTop-$this->_marginBottom;
        }


        /**
         * get page number
         *
         * @return unknown
         */
        public function getPageNro(){
            return $this->PageNo();
        }

        /**
         * eol
         *
         * @param float $height
         */
        public function eol($height = ''){
            $this->Ln($height);
        }

         /**
          * set position X Y
          *
          * @param float $x
          * @param float $y
          */
        public function setPosXY($x = 0, $y = 0){
            $this->SetY($y);
            $this->SetX($x);
        }

        /**
         * set position X
         *
         * @param float $value
         */
        public function setPosX($value = 0){
            $this->SetX($value);
        }

        /**
         * get position X
         *
         * @return float
         */
        public function getPosX(){
            return $this->GetX();
        }

        /**
         * set position Y
         *
         * @param float $value
         */
        public function setPosY($value = 0){
            $this->SetY($value);
        }

        /**
         * get position Y
         *
         * @return float
         */
        public function getPosY(){
            return $this->GetY();
        }

        /**
         * get width
         *
         * @param string $text
         * @return float
         */
        public function getTextWidth($text){
            return $this->getStringWidth($text);
        }

        //--------------------------------------------------------------------------
        //                         D R A W   E L E M E N T S
        //--------------------------------------------------------------------------
        /**
         * Draw Cell
         *
         * @param float $width
         * @param float $height
         * @param string $text
         * @param string $align
         * @param mixed $border
         * @param boolean $fill
         */
        public function drawCell($text = '', $width = 0, $height = 0, $align = '', $border= 0, $fill = 0){
            if ($height == 0){
                $height = $this->_spacing;
            }
            $this->Cell($width, $height, $this->_parseText($text), $border, 0, $align, $fill);
        }

        /**
         * Draw Text
         *
         * @param float $width
         * @param float $height
         * @param string $text
         * @param string $align
         * @param mixed $border
         * @param boolean $fill
         */
        public function drawText($text = '', $width = 0, $height = 0, $align = '', $border= 0, $fill = 0){
            if ($height == 0){
                $height = $this->_spacing;
            }
            $this->MultiCell($width, $height, $this->_parseText($text), $border, $align, $fill);
        }

        /**
         * Draw line
         *
         * @param float $xO
         * @param float $yO
         * @param float $xD
         * @param float $yD
         */
        public function drawLine($xO = 0, $yO = 0, $xD = 0, $yD = 0){
            if ($xO == 0 && $yO == 0 && $xD == 0 && $yD == 0){
                $yO = $this->getPosY();
                $yD = $yO;
                $xO = $this->getMarginLeft();
                $xD = $this->getMaxWidth()+$this->getMarginLeft();
            }
            $this->Line($xO, $yO, $xD, $yD);
        }

        /**
         * Draw Rectangle
         *
         * @param float $x
         * @param float $y
         * @param float $wdith
         * @param float $height
         * @param integer $border
         * @param integer $fill
         */
        public function drawRectangle($x = 0, $y = 0, $wdith = 0, $height = 0, $border= 1, $fill = 0){
            // Style
            $style = '';
            if ($border != 0){
                $style = 'D';
            }
            if ($fill != 0){
                $style .= 'F';
            }

            // draw
            $this->Rect($x, $y, $wdith, $height, $style);
        }

        /**
         * Draw Rounded Rectangle
         *
         * @param float $x
         * @param float $y
         * @param float $wdith
         * @param float $height
         * @param float $radiuis
         * @param integer $border
         * @param integer $fill
         */
        public function drawRoundedRectangle($x = 0, $y = 0, $wdith = 0, $height = 0, $radius = 0, $border= 1, $fill = 0){
            // Style
            $style = '';
            if ($border != 0){
                $style = 'D';
            }
            if ($fill != 0){
                $style .= 'F';
            }

            // draw
            $this->_RoundedRect($x, $y, $wdith, $height, $radius, $style);
        }

        /**
         * draw Circle
         *
         * @param float $x
         * @param float $y
         * @param float $r
         * @param integer $border
         * @param integer $fill
         *
         */
        public function drawCircle($xCenter, $yCenter, $radius, $border= 1, $fill = 0){
            // Style
            $style = '';
            if ($border != 0){
                $style = 'D';
            }
            if ($fill != 0){
                $style .= 'F';
            }

            $this->_Ellipse($xCenter, $yCenter, $radius, $radius, $style);
        }

        /**
         * Draw Ellipse
         *
         * @param float $xCenter
         * @param float $yCenter
         * @param float $radiusX
         * @param float $radiusY
         * @param integer $border
         * @param integer $fill
         */
        public function drawEllipse($xCenter, $yCenter, $radiusX, $radiusY, $border= 1, $fill = 0){
            // Style
            $style = '';
            if ($border != 0){
                $style = 'D';
            }
            if ($fill != 0){
                $style .= 'F';
            }

            $this->_Ellipse($xCenter, $yCenter, $radiusX, $radiusY, $style);
        }

        /**
         * Draw image
         *
         * @param string $file
         * @param integer $x
         * @param integer $y
         * @param integer $width
         * @param integer $hight
         */
        public function drawImage($file, $x = 0, $y = 0, $width = 0, $hight = 0){
            $this->Image($file, $x, $y, $width, $hight);
        }

        //--------------------------------------------------------------------------
        //                            OUTPUT PDF
        //--------------------------------------------------------------------------
        /**
         * Send file
         *
         * @param string $name
         */
        public function send($name = ''){
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.pdf';
            }
            $this->Output($name, 'I');
        }

        /**
         * Download file
         *
         * @param string $name
         */
        public function download($name = ''){
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.pdf';
            }
            $this->Output($name, 'D');
        }

        /**
         * Save file
         *
         * @param string $name
         */

        public function save($dir,$name = ''){
            if ($name == '' ){
                $name = date('Ymdhis').'_motte.pdf';
            }
            // create pdf
			@unlink($dir.'/'.$name);
            $this->Output($dir.'/'.$name, 'F');
			@chmod($dir.'/'.$name, 0644);
        }

        /**
         * return the document as a string
         *
         * @param string $name
         */
        public function toString(){
            return $this->Output('motte', 'S');
        }


        //--------------------------------------------------------------------------
        //                       A U X   M E T H O D S
        //--------------------------------------------------------------------------

        private function _parseText($txt){
            // vars
            $date        = date('Y-m-d');
            $dateTime    = date('Y-m-d H:i:s');
            $currentPage = $this->getPageNro();
            $totPage     = '{nb}';

            // Replace
            $txt = str_replace(mteConst::MTE_PDF_VAR_CURRENTDATE,$date,$txt);
            $txt = str_replace(mteConst::MTE_PDF_VAR_CURRENTDATETIME,$dateTime,$txt);
            $txt = str_replace(mteConst::MTE_PDF_VAR_CURRENTPAGE,$currentPage,$txt);
            $txt = str_replace(mteConst::MTE_PDF_VAR_TOTPAGE,$totPage,$txt);

            return (strtolower(MTE_TEXT_ENCODE) == 'utf-8')?iconv('UTF-8', 'ISO-8859-1', $txt):$txt;
        }

        /**
         * Draw circles and ellipse
         * @author oliver@fpdf.org
         *
         * @param float $x
         * @param float $y
         * @param float $rx
         * @param float $ry
         * @param string $style
         */
        private function _Ellipse($x,$y,$rx,$ry,$style='D') {
            if($style=='F'){
                $op='f';
            }
            elseif($style=='FD' or $style=='DF'){
                $op='B';
            }
            else{
                $op='S';
            }
            $lx=4/3*(M_SQRT2-1)*$rx;
            $ly=4/3*(M_SQRT2-1)*$ry;
            $k=$this->k;
            $h=$this->h;
            $this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
                    ($x+$rx)*$k,($h-$y)*$k,
                    ($x+$rx)*$k,($h-($y-$ly))*$k,
                    ($x+$lx)*$k,($h-($y-$ry))*$k,
                    $x*$k,($h-($y-$ry))*$k));
            $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
                    ($x-$lx)*$k,($h-($y-$ry))*$k,
                    ($x-$rx)*$k,($h-($y-$ly))*$k,
                    ($x-$rx)*$k,($h-$y)*$k));
            $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
                    ($x-$rx)*$k,($h-($y+$ly))*$k,
                    ($x-$lx)*$k,($h-($y+$ry))*$k,
                    $x*$k,($h-($y+$ry))*$k));
            $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
                    ($x+$lx)*$k,($h-($y+$ry))*$k,
                    ($x+$rx)*$k,($h-($y+$ly))*$k,
                    ($x+$rx)*$k,($h-$y)*$k,
                    $op));
        }

        /**
         * Rectangle rounded corner
         * @author Maxime Delorme
         *
         * @param float $x
         * @param float $y
         * @param float $w
         * @param float $h
         * @param float $r
         * @param string $style
         */
        private function _RoundedRect($x, $y, $w, $h,$r, $style = ''){
            $k = $this->k;
            $hp = $this->h;
            if($style=='F'){
                $op='f';
            }
            elseif($style=='FD' or $style=='DF'){
                $op='B';
            }
            else{
                $op='S';
            }
            $MyArc = 4/3 * (sqrt(2) - 1);
            $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
            $xc = $x+$w-$r ;
            $yc = $y+$r;
            $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
            $xc = $x+$w-$r ;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
            $xc = $x+$r ;
            $yc = $y+$h-$r;
            $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
            $xc = $x+$r ;
            $yc = $y+$r;
            $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
            $this->_out($op);
        }

    /**
     * drae arc
     * @author Maxime Delorme
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @param float $x3
     * @param float $y3
     */
        private function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
        {
            $h = $this->h;
            $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
        }
    }
?>
