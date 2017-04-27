<?php
namespace AppBundle;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFunctions extends Controller
{
	private $targetDir, $misc_functions;
	public function __construct($targetDir, $misc_functions)
	{
		$this->targetDir = $targetDir;
		$this->misc_functions = $misc_functions;
	}
	
	public function uploadImage(UploadedFile $file, $alias)
	{
		$ext = strtolower($file->getClientOriginalExtension());
		$fileName = $alias .'.'. $ext;
		
		$types = array('jpg','jpeg','gif','png','docx','pdf','bmp');
		if (in_array($ext, $types)) {
			$file->move($this->targetDir, $fileName);
			return $fileName;
		}
		else {
			$this->addFlash(
					'danger',
					"File '". $fileName ."' not uploaded. Please upload images only."
					);
			
			return null;
		}
	}
	
	public function setTargetDir($targetDir) {
		$this->targetDir = $targetDir;
	}
	
	public function getTargetDir() {
		
		return $this->targetDir;
	}
    
    public function upload($mode, $folder, $name, $image, $old_image = '') {
    	$fs = new Filesystem();
    	$rootDir = $this->targetDir;
    	
    	if ($fs->exists($rootDir . '/../web/images/'. $folder))
    		$imagesDir = $rootDir . '/../web/images/'. $folder;
   		else
   			$imagesDir = $rootDir . '/../public_html/images/'. $folder;
    			
    	$this->setTargetDir($imagesDir);
    			
    	// if has old_image, delete
    	if (!empty($old_image)) {
    		switch ($mode) {
    			case 'banner_thumb':
    				$fs->remove($imagesDir .'/banner/'. $old_image);
    				break;
    		}
    				
    		$fs->remove($imagesDir .'/'. $old_image);
    		$fs->remove($imagesDir .'/thumb/'. $old_image);
    	}
    			
    	// process image
    	$alias = $this->misc_functions->slug($name);
    	$filename = $this->uploadImage($image, $alias);
    			
    	$source = $imagesDir . '/' . $filename;
    			
    	switch ($mode) {
    	case 'product':
    		$resized = $imagesDir . '/full/' . $filename;
    		$this->resize($source, null, 590, 800, true, $resized, false, false, 90);
    		
    		$resized = $imagesDir . '/thumb/' . $filename;
    		$this->resize($source, null, 600, 655, false, $resized, false, false, 90);
    		break;
    	case 'banner_thumb':
    		$resized = $imagesDir . '/banner/' . $filename;
    		$this->resize($source, null, 1180, 447, false, $resized, false, false, 90);
    		
    		$resized = $imagesDir . '/thumb/' . $filename;
    		$this->resize($source, null, 600, 600, true, $resized, false, false, 90);
    		break;
    	case 'thumb':
    		$resized = $imagesDir . '/thumb/' . $filename;
	    	$this->resize($source, null, 600, 600, true, $resized, false, false, 90);
			break;
		}
    		
    	return $filename;
    }
    
    public function deleteProductImage($image) {
    	$fs = new Filesystem();
    	$rootDir = $this->container->getParameter('kernel.root_dir');
    	
    	if ($fs->exists($rootDir . '/../web/images'))
    		$imagesDir = $rootDir . '/../web/images';
    	else
    		$imagesDir = $rootDir . '/../public_html/images';
    			
    	$fs->remove($imagesDir .'/products/'. $image);
		$fs->remove($imagesDir .'/products/full/'. $image);
    	$fs->remove($imagesDir .'/products/thumb/'. $image);
    			
    	return true;
    }
    
    public function resize($file,
    		$string             = null,
    		$width              = 0,
    		$height             = 0,
    		$proportional       = false,
    		$output             = 'file',
    		$delete_original    = true,
    		$use_linux_commands = false,
    		$quality = 90
    		) {
    			if ( $height <= 0 && $width <= 0 ) return false;
    			if ( $file === null && $string === null ) return false;
    			
    			# Setting defaults and meta
    			$info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    			$image                        = '';
    			$final_width                  = 0;
    			$final_height                 = 0;
    			list($width_old, $height_old) = $info;
    			$cropHeight = $cropWidth = 0;
    			
    			# Calculating proportionality
    			if ($proportional) {
    				if      ($width  == 0)  $factor = $height/$height_old;
    				elseif  ($height == 0)  $factor = $width/$width_old;
    				else                    $factor = min( $width / $width_old, $height / $height_old );
    				
    				$final_width  = round( $width_old * $factor );
    				$final_height = round( $height_old * $factor );
    			}
    			else {
    				$final_width = ( $width <= 0 ) ? $width_old : $width;
    				$final_height = ( $height <= 0 ) ? $height_old : $height;
    				$widthX = $width_old / $width;
    				$heightX = $height_old / $height;
    				
    				$x = min($widthX, $heightX);
    				$cropWidth = ($width_old - $width * $x) / 2;
    				$cropHeight = ($height_old - $height * $x) / 2;
    			}
    			
    			# Loading image to memory according to type
    			switch ( $info[2] ) {
    				case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
    				case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
    				case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
    				default: return false;
    			}
    			
    			
    			# This is the resizing/resampling/transparency-preserving magic
    			$image_resized = imagecreatetruecolor( $final_width, $final_height );
    			if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
    				$transparency = imagecolortransparent($image);
    				$palletsize = imagecolorstotal($image);
    				
    				if ($transparency >= 0 && $transparency < $palletsize) {
    					$transparent_color  = imagecolorsforindex($image, $transparency);
    					$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
    					imagefill($image_resized, 0, 0, $transparency);
    					imagecolortransparent($image_resized, $transparency);
    				}
    				elseif ($info[2] == IMAGETYPE_PNG) {
    					imagealphablending($image_resized, false);
    					$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
    					imagefill($image_resized, 0, 0, $color);
    					imagesavealpha($image_resized, true);
    				}
    			}
    			imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
    			
    			
    			# Taking care of original, if needed
    			if ( $delete_original ) {
    				if ( $use_linux_commands ) exec('rm '.$file);
    				else @unlink($file);
    			}
    			
    			# Preparing a method of providing result
    			switch ( strtolower($output) ) {
    				case 'browser':
    					$mime = image_type_to_mime_type($info[2]);
    					header("Content-type: $mime");
    					$output = NULL;
    					break;
    				case 'file':
    					$output = $file;
    					break;
    				case 'return':
    					return $image_resized;
    					break;
    				default:
    					break;
    			}
    			
    			# Writing image according to type to the output destination and image quality
    			switch ( $info[2] ) {
    				case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
    				case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
    				case IMAGETYPE_PNG:
    					$quality = 9 - (int)((0.9*$quality)/10.0);
    					imagepng($image_resized, $output, $quality);
    					break;
    				default: return false;
    			}
    			
    			return true;
    }
}