<?php

class LibUpload {
	
	function uploadImageDataUri($rawDataUri, $savePath, &$imgFilename, &$err=null) {
		$dataUri = null; $data = false; $imgPostfix ='';
		
		// Parse data URI string
		if(Corex_DataUri::tryParse($rawDataUri, $dataUri)) {
			// Attempt to decode URI's data
			if($dataUri->tryDecodeData($data)) {
				$mime = $dataUri->getMediaType();
				$imgPostfix = c('mime_extensions')[$mime];
			}
		}
		
		if (empty($imgPostfix) || empty($data)) { 
			$err = 'Not a valid image file.'; return false;
		}
		$imgFilename = $imgFilename.'.'.$imgPostfix;
		if(!is_dir($savePath)) Corex_CLDir::mkDirs($savePath);
		
		return file_put_contents($savePath.DS.$imgFilename, $data);
	}
}