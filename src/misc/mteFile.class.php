<?php
/**
 * File management class
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
class mteFile {

	/**
	 * Variables y constants
	 */	
	private $_dir = '';
	private $_dirTmp = '';
	private $_maxSize = mteConst::MTE_MAX_SIZE;
	private $_mimeType = '';
	private $_overWrite = 1;
	
	public function __construct($dir = '', $dirTmp = '', $maxSize = 0, $mimeType= '', $overWrite = 1){
		$this -> setDir($dir);
		$this -> setDirTmp($dirTmp);
		$this -> setMaxSize($maxSize);
		$this -> setMimeType($mimeType);
		$this -> setOverWrite($overWrite);
	}

	
	/**
	 * File upload
	 *
	 * @param string $nameInput
	 * @param string $nameFinal
	 */
	public function upload($inputName = '', $uploadName = ''){
		if(empty($inputName))
			return __('Field name must be defined');
		if(empty($this -> _dir))
			return __('Target directory must be specified');
		if(empty($_FILES)||!is_array($_FILES))
			return __('No se enviaron datos de subida');
		$fileName	= $_FILES[$inputName]['name'];
		$fileSize	= $_FILES[$inputName]['size'];
		$fileTmp	= $_FILES[$inputName]['tmp_name'];
		$fileType	= $_FILES[$inputName]['type'];
		$fileError	= $_FILES[$inputName]['error'];
		$uploadName	= empty($uploadName)?$fileName:$uploadName;
		$nameTarget	= $this -> _dir ."/". $uploadName;

		// PHP Errors
		switch($fileError){
			case UPLOAD_ERR_INI_SIZE:
				return __('Attached file exceeded allowed max upload size');
				break;
			case UPLOAD_ERR_FORM_SIZE:
				return __('Attached file exceeded max size specified by form');
				break;
			case UPLOAD_ERR_PARTIAL:
				return __('Attached file was partially uploaded');
				break;
			case UPLOAD_ERR_NO_FILE:
				return __('Attached file cannot be uploaded');
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				return __('An error occur while copying attached file to temporary directory');
				break;
			case UPLOAD_ERR_NO_FILE:
				return __('Disk Error');
				break;
		}

		if(!is_uploaded_file($fileTmp))
			return __('Attached file cannot be uploaded');
			
//		if($fileSize > $this -> _maxSize)
//			return __('Attached file exceeded max size specified by application');

		if(!empty($this -> _mimeType) && !ereg($this -> _mimeType, $fileType))
			return __('File type not allowed');

		if(!$this -> _overWrite && file_exists($nameTarget))
			return $uploadName.': '.__('already exists a file with this name');

		if(!@move_uploaded_file($fileTmp, $nameTarget))
			return __('An error occur while moving file to target directory');
		return 1;
		
	}
	
	/**
	 * Download specified file
	 *
	 * @param string $name
	 * @param string $nameDownload
	 */
	public function download($name = '', $nameDownload = '', $fileType = ''){
		if(empty($name))
			return __('You must specify file to download');

		if(empty($this -> _dir))
			return __('You must specify a directory');

		$nameDownload = (empty($nameDownload))?$name:$nameDownload;
		$filePath = $this -> _dir."/".$name;
		$filePathTmp = $this -> _dirTmp."/".$name;

		if(!file_exists($filePath))
			return __('File does not exist');

		if(!empty($this -> _dirTmp) && !@copy($filePath, $filePathTmp))
			return __('Cannot copy file to temporary folder');
		
		$filePath = (!empty($this -> _dirTmp))?$filePathTmp:$filePath;

		if(!empty($this -> _mimeType) && !ereg($this -> _mimeType, $fileType))
			return __('Download of this file type is not allowed');

		header('Content-type: '.$fileType);

		header('Content-Disposition: attachment; filename="'.$nameDownload.'"');

		readfile($filePath);				
	}
	
	
	/**
	 * Elimina uno o varios archivos, acepta el nombre completo del archivo
	 * y tambien asteriscos y comodines
	 *
	 * @param string $name
	 */
	public function delete($name = ''){
		if(empty($name))
			return __('You must specified file(s) to be deleted');
	
		if(empty($this -> _dir))
			return __('You must specified file folder');
		

		if(ereg("\*|\?", $name)){
			$name = ereg_replace('\.', '\.', $name);
			$name = ereg_replace('\?', '.??', $name);
			$name = ereg_replace('\*', '.*', $name);
			$name = '/\b'.$name.'\b/';

			$dir = opendir($this -> _dir);
			$error = '';
			while(false !== ($file = readdir($dir))){
				if($file != '.' && $file != '..' && preg_match($name, $file)){
					$error .= (!unlink($this -> _dir.'/'.$file))?$file.', ':'';
				}
			}
			if(!empty($error))
				return __('The following files could not be deleted: ').$error;
		}
		else{
			$filePath = $this -> _dir."/".$name;

			if(!file_exists($filePath))
				return __('File does not exits');

			if(!unlink($filePath))
				return __('An error occur while deleting file');
		}
		
		return 1;
	}
	
	
	/**
	 * Sets directory location
	 *
	 * @param string $dir
	 */
	public function setDir($dir = ''){
		if(!empty($dir)){		
			$this -> _dir = $dir;
		}
	}
	/**
	 * Returns directory location
	 *
	 * @param string $dir
	 */
	public function getDir(){
		return $this -> _dir;
	}

	/**
	 * Sets temporary download directory
	 */
	public function setDirTmp($dir = ''){
		if(!empty($dir)){		
			$this -> _dirTmp = $dir;
		}
	}
	/**
	 * Returns temporary download directory
	 */
	public function getDirTmp(){
		return $this -> _dirTmp;
	}

	/**
	 * Sets max allowed file size
	 *
	 * @param number $size in kb
	 */
	public function setMaxSize($size = 0){
		if(!empty($size) || !is_numeric($size)){
			if ($size == 0)
				$size = MTE_UPLOAD_MAXSIZE;
			$this -> _maxSize = $size * 1024;
		}
	}
	/**
	 * Returns max allowed file size
	 */
	public function getMaxSize(){
		return $this -> _maxSize;
	}

	/**
	 * Sets allowed file types
	 *
	 * @param string $mime
	 */
	public function setMimeType($mime = ''){
		$this -> _mimeType = $mime;
	}	
	/**
	 * Retruns allowed file types
	 */
	public function getMimeType(){
		return $this -> _mimeType;
	}	

	/**
	 * Sets overwrite file flag
	 *
	 * @param bool $overWrite
	 */
	public function setOverWrite($overWrite = 1){
		$this -> _overWrite = ($overWrite)?1:0;
	}	
	/**
	 * Returns overwrite flag status
	 */
	public function getOverWrite(){
		return $this -> _overWrite;
	}	

}
?>
