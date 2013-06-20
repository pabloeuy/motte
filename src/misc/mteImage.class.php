<?php
/**
 * Clase para el manejo de im�genes
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
    class mteImage extends mteFile {

        /**
         * Constructor
         *
         * @access public
         * @return mteCrypt
         */
        function __construct() {
        }

        /**
        * Destructor
        *
        * @access public
        */
        function __destruct(){
        }

        /**
         * Devuelve ancho y alto de la imagen
         *
         * @param string $fileName
         * @return array 'width' => x, 'height' => x
         */
        public function getSize($imageFileName = ''){
            $result['width']  = 0;
            $result['height'] = 0;

            if ((!empty($imageFileName)) &&
                (file_exists($imageFileName))){
                $imageSize = getimagesize($imageFileName);
                $result['width']  = $imageSize[0];
                $result['height'] = $imageSize[1];
            }
            return $result;
        }

        /**
         * Cambia el tama�o de la imagen dada,
         * y la guarda con otro nombre, o la sobreescribe
         *
         * @param string $sourceName
         * @param string $targetName
         * @param integer $targetWidth
         * @param integer $targetHeight
         */
        public function setSize($sourceName = '', $targetName = '', $targetWidth = 0, $targetHeight = 0, $quality=100, $lib = ''){
            if($lib <> '' && strtoupper($lib) <> 'GD' && strtoupper($lib) <> 'IMagick'){ // empty, GD or IMagick
                $lib = MTE_GRAPH_LIBRARY;
            }
            if(!is_numeric($quality)) {
                $quality = 100;
            }
            if($quality < 0 || $quality > 100) {
                $quality = 100;
            }
            if($lib == 'IMagick' && extension_loaded('imagick')) {
                $thumb = new Imagick();
                $thumb->readImage($sourceName);
                $thumb->setImageResolution($quality, $quality);
                $thumb->setCompressionQuality($quality);
                $result = $thumb->thumbnailImage($targetWidth, $targetHeight);
                $thumb->writeImage($targetName);
                $thumb->clear();
                $thumb->destroy();
            }else{
                if(!extension_loaded('gd')){
                    die('No se encuentr&oacute; la librer&iacute;a grafica GD');
                }
                $result = 0;
                // Control de parametros
                if (!empty($sourceName) && !empty($targetName) &&
                    !empty($targetWidth) && !empty($targetHeight) &&
                    (is_file($sourceName))){
                    // variables
                    $sourceSize  = $this -> getSize($sourceName);
                    $sourceWidth = $sourceSize['width'];
                    $sourceHeight = $sourceSize['height'];
                        
                    $sourceType = '';
                    if ($sourceType == '' && @imagecreatefromjpeg($sourceName)){
                        $sourceType = 'image/jpeg';
                    }
                    if ($sourceType == '' && @imagecreatefromgif($sourceName)){
                        $sourceType =  'image/gif';
                    }
                    if ($sourceType == '' && @imagecreatefrompng($sourceName)){
                        $sourceType =  'image/png';
                    }
                        
                    // se crean las imagenes en el servidor
                    switch($sourceType){
                        case 'image/jpeg':
                        {
                            $sourceImg = imagecreatefromjpeg($sourceName);
                            break;
                        }
                        case 'image/gif':
                        {
                            $sourceImg = imagecreatefromgif($sourceName);
                            break;
                        }
                        case 'image/png':
                        {
                            $sourceImg = imagecreatefrompng($sourceName);
                            break;
                        }
                    }
                    $targetImg = imagecreatetruecolor($targetWidth, $targetHeight);
                    imagecopyresized($targetImg, $sourceImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
                        
                    // se devuelve la nueva
                    switch($sourceType){
                        case 'image/jpeg':
                        {
                            imagejpeg($targetImg, $targetName);
                            break;
                        }
                        case 'image/gif':
                        {
                            imagegif($targetImg, $targetName);
                            break;
                        }
                        case 'image/png':
                        {
                            imagepng($targetImg, $targetName);
                            break;
                        }
                    }
                    $result = 1;
                }
            }
            return $result;
        }

        /**
         * Cambia el tama�o, basandose en el ancho
         *
         * @param string $sourceName
         * @param string $targetName
         * @param integer $targetWidth
    */
        public function setSizeWidth($sourceName = '', $targetName = '', $targetWidth = 0, $quality=100){
            $result = 0;
            if(!is_numeric($quality)) {
                $quality = 100;
            }
            if($quality < 0 || $quality > 100) {
                $quality = 100;
            }
            // Control de parametros
            if (!empty($sourceName) && !empty($targetName) &&
                !empty($targetWidth) && (is_file($sourceName))){

                $sourceSize   = $this -> getSize($sourceName);
                $sourceWidth  = $sourceSize['width'];
                $sourceHeight = $sourceSize['height'];
                $factor       = $sourceWidth / $targetWidth;
                $targetHeight = $sourceHeight / $factor;

                //$result = $this -> setSizeImageMagick($sourceName, $targetName, $targetWidth, $targetHeight);
                if(!defined('MTE_GRAPH_LIBRARY')) {
                    define('MTE_GRAPH_LIBRARY','IMagick');
                }
                $result = $this -> setSize($sourceName, $targetName, $targetWidth, $targetHeight, $quality, MTE_GRAPH_LIBRARY);
            }
            return $result;
        }

        /**
         * Cambia el tama�o, basandose en el alto
         *
         * @param string $nameOriginal
         * @param string $nameTarget
         * @param integer $targetHeight
    */
        public function setSizeHeight($sourceName = '', $targetName = '', $targetHeight = 0, $quality = 100){
            $result = 0;
            if(!is_numeric($quality)) {
                $quality = 100;
            }
            if($quality < 0 || $quality > 100) {
                $quality = 100;
            }
            // Control de parametros
            if (!empty($sourceName) && !empty($targetName) &&
                !empty($targetHeight) && (is_file($sourceName))){
                $sourceSize   = $this -> getSize($sourceName);
                $sourceWidth  = $sourceSize['width'];
                $sourceHeight = $sourceSize['height'];
                $factor       = $sourceHeight / $targetHeight;
                $targetWidth  = $sourceWidth / $factor;

                //$result = $this -> setSizeImageMagick($sourceName, $targetName, $targetWidth, $targetHeight);
                $result = $this -> setSize($sourceName, $targetName, $targetWidth, $targetHeight, $quality, MTE_GRAPH_LIBRARY);
            }
            return $result;
        }

        /**
         * Devuelve una imagen escrita con el texto y la fuente dados
         *
         * @param string $text
         * @param string $targetName
         * @param integer $targetWidth = 100
         * @param integer $targetHeight = 20
         * @param string $fontName = '/usr/share/fonts/truetype/freefont/FreeSans.ttf'
         * @param integer $fontSize = 12
         * @param hex $fontColor = '000000'
         * @param hex $targetColor = 'FFFFFF'
         */
        public function getText($text = '', $targetName = '', $targetWidth = 100, $targetHeight = 20, $fontName = '/usr/share/fonts/truetype/freefont/FreeSans.ttf', $fontSize = 12, $fontColor = '000000', $targetColor = 'FFFFFF'){
            // control de parametros
            if(empty($text) || empty($targetName)){
                return 'Los parametros no estan completos';
            }
            $targetName = $targetName;
            // ya existe
            if(!$this -> getOverWrite() && file_exists($targetName)){
                return 'La imagen destino ya existe';
            }

            // se crea la imagen y se define el color de fondo
            $targetImg = imagecreatetruecolor($targetWidth, $targetHeight);
            $targetColor = imagecolorallocate($targetImg, hexdec('0x' . $targetColor{0} . $targetColor{1}), hexdec('0x' . $targetColor{2} . $targetColor{3}), hexdec('0x' . $targetColor{4} . $targetColor{5}));
            imagefilledrectangle($targetImg, 0, 0, $targetWidth, $targetHeight, $targetColor);

            // color del texto
            $fontColor = imagecolorallocate($targetImg, hexdec('0x' . $fontColor{0} . $fontColor{1}), hexdec('0x' . $fontColor{2} . $fontColor{3}), hexdec('0x' . $fontColor{4} . $fontColor{5}));

            // se pone el texto
            imagettftext($targetImg, $fontSize, 0, 1, $targetHeight - 1, $fontColor, $fontName, $text);

            // tipo de imagen a realizar
            if(strpos($targetName, 'jpg') > 0 || strpos($targetName, 'jpeg') > 0){
                imagejpeg($targetImg, $targetName);
            }
            if(strpos($targetName, 'gif') > 0){
                imagegif($targetImg, $targetName);
            }
            if(strpos($targetName, 'png') > 0){
                imagepng($targetImg, $targetName);
            }
        }
    }
?>