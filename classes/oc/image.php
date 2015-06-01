<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Image manipulation support.
 *
 * @package    Kohana/Image
 * @category   Base
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
 */
abstract class OC_Image extends Kohana_Image {

	/**
	* Correct image orientation according to Exif data
	*
	* @return  $this
	* @uses    Image::flip, Image::rotate
	*/
	public function orientate()
	{
		$exif = read_exif_data($this->file);
		
		$exif_orientation = isset($exif['Orientation'])?$exif['Orientation']:0;
		
		$rotate = 0;
		$flip = FALSE;
		
		switch($exif_orientation) { 
			case 1: 
				$rotate = 0;
				$flip = FALSE;
			break; 
		
			case 2: 
				$rotate = 0;
				$flip = TRUE;
			break; 
		
			case 3: 
				$rotate = 180;
				$flip = FALSE;
			break; 
			
			case 4: 
				$rotate = 180;
				$flip = TRUE;
			break; 
			
			case 5: 
				$rotate = 90;
				$flip = TRUE;
			break; 
			
			case 6: 
				$rotate = 90;
				$flip = FALSE;
			break; 
			
			case 7: 
				$rotate = 270;
				$flip = TRUE;
			break; 
			
			case 8: 
				$rotate = 270;
				$flip = FALSE;
			break; 
		}
		
		if ($flip)
			$this->flip(Image::HORIZONTAL);
			
		if ($rotate > 0)
			$this->rotate($rotate);
		
		return $this;
		
	}
	
} // End Image
