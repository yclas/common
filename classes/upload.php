<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana Image
 *
 * @package    OC
 * @category   Image
 * @author     FreebieVectors.com
 * Source: http://www.naun.org/multimedia/NAUN/computers/20-462.pdf
 * J. Marcial-Basilio (2011), Detection of Pornographic Digital Images, International Journal of Computers
 */

class Upload extends Kohana_Upload {


	/**
	* Image nudity detector based on flesh color quantity.
	*
	* @param array $file uploaded file data
	* @param string $threshold Threshold of flesh color in image to consider in pornographic. See page 302
	* @return boolean
	*/

	public static function not_nude_image(array $file, $threshold = .5) {
	    
	    if (Upload::not_empty($file))
	    {
		    try
		    {
			    	// Get the width, height and type from the uploaded image
			    	list($width, $height, $type) = getimagesize($file['tmp_name']);
		    }
		    catch (ErrorException $e)
		    {
			    	// Ignore read errors
		    }

		    if (empty($width) OR empty($height))
		    {
			    	// Cannot get image size, cannot validate
			    	return TRUE;
		    }
		    
		    switch($type) {
			    case IMAGETYPE_JPEG:
				    $resource = imagecreatefromjpeg($file['tmp_name']);
				    break;
			    case IMAGETYPE_GIF:
				    $resource = imagecreatefromgif($file['tmp_name']);
				    break;
			    case IMAGETYPE_PNG:
				    $resource = imagecreatefrompng($file['tmp_name']);
				    break;
			    default:
				    throw new Exception('Image type is not supported');
				    break;
		    }

		    // Init vars
			$inc = 1; // Pixel count to iterate over. To increase speed, set it higher and it will skip some pixels.
			list($Cb1, $Cb2, $Cr1, $Cr2) = array(80, 120, 133, 173); // Cb and Cr value bounds. See page 300
			$white = 255; // Exclude white colors above this RGB color intensity
			$black = 5; // Exclude dark and black colors below this value

			$total = 0;
			$count = 0;

			for($x = 0; $x < $width; $x += $inc)
				for($y = 0; $y < $height; $y += $inc) {
					
					// Get color of a pixel
					$color = imagecolorat($resource, $x, $y);
					// RGB array of pixel's color
					$color = array(($color >> 16) & 0xFF, ($color >> 8) & 0xFF, $color & 0xFF);
					
					list($r, $g, $b) = $color;
					
					// Exclude white/black colors from calculation, presumably background
					if((($r > $white) && ($g > $white) && ($b > $white)) ||
						(($r < $black) && ($g < $black) && ($b < $black))) continue;
					
					// Converg pixel RGB color to YCbCr, coefficients already divided by 255
					$Cb = 128 + (-0.1482 * $r) + (-0.291 * $g) + (0.4392 * $b);
					$Cr = 128 + (0.4392 * $r) + (-0.3678 * $g) + (-0.0714 * $b);
					
					// Increase counter, if necessary
					if(($Cb >= $Cb1) && ($Cb <= $Cb2) && ($Cr >= $Cr1) && ($Cr <= $Cr2))
						$count++;
					$total++;
				}

			return ($count / $total) < $threshold;
		}

		return TRUE;
	}
}
