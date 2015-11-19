<?php
class Image_Model extends CI_Model {
	/**
	*	
	*	@param filename string
	*	@param width 
	*	@param height
	*	@param type char [default, w, h]
	*				default = scale with white space, 
	*				w = fill according to width, 
	*				h = fill according to height
	*	
	*/
	public function resize($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return;
		} 
		
		$info = pathinfo($filename);
		
		$extension = $info['extension'];
		
		$old_image = $filename;
               
		$new_image = substr($filename,0, strripos($filename,'.')) . '-' . $width . 'x' . $height . $type;
		
		if (!file_exists(DIR_IMAGE_CACHE . $new_image.'.'.$extension)) {
			$path = '';
			
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				
				if (!file_exists(DIR_IMAGE_CACHE . $path)) {
					@mkdir(DIR_IMAGE_CACHE . $path, 0777);
				}		
			}

			list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
                            require_once DIR_LIB.'image.php';
                            
				$image = new Image(DIR_IMAGE . $old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE_CACHE . $new_image .'.'. $extension);
			} else {
				copy(DIR_IMAGE . $old_image, DIR_IMAGE_CACHE . $new_image);
			}
		}
		
		
			return HTTP_SERVER_IMG . $new_image.'.'. $extension;
			
	}
        
        public function saveUserImage($image,$e){
            $ext = $extension = end(explode('.',$image));
            $rand = md5($e);
            $name = 'user'.DIRECTORY_SEPARATOR.$rand.'.'.$ext;
            file_put_contents(DIR_IMAGE.$name, file_get_contents($image));
            return $name;
        }
}
?>