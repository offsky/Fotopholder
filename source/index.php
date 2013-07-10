<?php
//================================================================================================
//================================================================================================
//==================FOTOPHOLDER 2.51===============================================================
/*
	Released September 15, 2006 by Jake Olefsky
	Copyright by Jake Olefsky
	http://www.jakeo.com/software/fotopholder/index.php

	This software is free to use and modify for non-commercial purposes only.  For commercial use
	of this software please contact me.  Please keep this readme intact at the top of this file,
	and if possible, please keep the link to fotopholder intact at the bottom of the page so other
	people can discover this great script.
	
	If you improve this script, please email me a copy of the improvement so everyone can benefit. 
	
	REQUIREMENTS:
				PHP 4.3+ compiled with GD support for thumbnail creation.
				You'll also need to give this script permission to access the images and permissions 
				to create and fill the cache directory.
	
	SUMMARY:
				This script will look for jpeg or png images and sub-directories of images starting at the 
				same level as this script and descendind as many levels as necessary.  It will display 
				thumbnails and folders in a hierarchical way. Clicking on a folder will take you to the 
				thumbnail view of the folder.  Clicking on a thumbnail will take you to a screen resolution 
				version of that image.
				
	INSTALL:
				Just drop this file inside the top level directory containing images and/or directories
				of images and point your browser to this file.  Thats it.
				
				If you need to fuss with permissions, this script will tell you.  Once you have it working,
				there are a few configurable options you might want to check out lower down in this file.
				
				The following are reserved names that you should not use as folder names: 
					_cache, _screen, _thumbnails, _photoinfo, _icons
					
				To edit the css style, you can check out this file _cache/index_css.css which is created
				the first time you run the script.  The first time you run this script it will ask you to
				set some settings. If you want to change these at a later date, they will be located in the
				_cache/config.php file.
				
	UN-INSTALL: 
				Simply remove this index.php script and delete the cache directory
	
	
	VERSION HISTORY:
	2.51	Sept 15, 2006
	
			+ Added a fix for a small security flaw (unlikely to cause any harm)
			
	2.5		Sept 11, 2005
	
			+ Addedd page navigation to top of page in addition to bottom of page
			+ Speed up under certain circumstances
			+ Fixed bug when sorting by date that would cause some photos to be invisible
			+ Fixed a security hole
			+ Added ability to view photos as a slideshow
			+ Made the script easily localizable.  If you translate it, please let me know.
			
	2.4		August 20, 2005
	
			+ When you delete a photo on a multipage listing, you stay on that page
			+ Shows the ISO value if set in the EXIF
			+ From the admin panel you can now flatten subfolders into the current folder
			+ Allows you to turn off auto-rotation if it is causing problems for you
			+ Option to password protect viewing of your photos
			+ All your settings are stored in an external file now to make upgrades easier
			+ First time users get a nice walkthrough of the setup process
			+ You can sort folders and photos by modification date now
			
	2.3		July 8, 2005
	
			+ Rotates thumbnail and screen sized images if the camera sets the orientation flag.
			+ Shows the Exif UserComment if it is set
			+ Ability to display large folders with multiple pages
			+ Mac OS X users can turn on SIPS image processing which will make image generation much faster
			+ Improved display speed (especially when you have large subfolders)
			+ Fixed bug with & and " in a folder's name
			+ You can now zip an entire folder up so it can be downloaded all at once
			+ Folder counts and photo counts now include its subdirectories
			+ Misc bug fixes
			
	More version history at... http://www.jakeo.com/software/fotopholder/index.php
	
*/


//================================================================================================	
//================================================================================================
//==================LEAVE EVERYTHING BELOW THIS LINE ALONE========================================
//================================================================================================
if(file_exists("./_cache/config.txt")) rename("./_cache/config.txt","./_cache/config.php");
if(file_exists("./_cache/config.php")) require_once("./_cache/config.php");


//START LANGUAGE
$l_del_fol_err = "Could not delete the folder because this script does not have permission.  Under unix, 'chmod -R 777 *' will work if you are in the same directory as the index.php file.";
$l_del_fil_err = "Could not delete the file because this script does not have permission.  Under unix, 'chmod -R 777 *' will work if you are in the same directory as the index.php file.";
$l_rename_err = "Could not rename because this script does not have permission.  Under unix, 'chmod -R 777 *' will work if you are in the same directory as the index.php file.";
$l_make_err = "Could not create a folder because this script does not have permission.  Under unix, 'chmod -R 777 *' will work if you are in the same directory as the index.php file.";
$l_error = "Error";
$l_perm_err = "Permissions Problem";
$l_not_jpeg = "Not a JPG Image";
$l_time = "Time";
$l_shutter = "Shutter Speed";
$l_aperture = "Aperture";
$l_focal = "Focal Length";
$l_iso = "ISO Value";
$l_flash = "Flash";
$l_camera = "Camera";
$l_comments = "Comment";
$l_rating = "Rating";
$l_download = "download photo";
$l_download2 = "download";
$l_prev = "Prev";
$l_next = "Next";
$l_slide_start = "Play Slideshow";
$l_slide_stop = "Stop Slideshow";
$l_pass = "Admin Password";
$l_tools = "Admin Tools";
$l_login = "Log in";
$l_logout = "Log out";
$l_del_photo = "Delete this photo";
$l_regen = "Regenerate Cache";
$l_rename = "Rename Photo";
$l_norateing = "no rating";
$l_save = "Save";
$l_generated = "Photo gallery generated by";
$l_private = "This is a private directory. Please enter the password below.";
$l_bad_dir = "Invalid directory";
$l_bad_dir2 = "Could not open directory";
$l_make_dir = "Creating directory";
$l_photos = "photos";
$l_subfold = "subfolders";
$l_enlarge = "Click to Enlarge";
$l_more = "more...";
$l_prev_pg = "Previous Page";
$l_next_pg = "Next Page";
$l_confirm_del = "Are you sure you want to delete this photo?";
$l_confirm_del2 = "Are you sure you want to delete this folder and all of its contents?";
$l_fol_empty = "This folder is empty.";
$l_friend = "Friend's Fotopholders";
$l_gen_cache = "Generating Entire Cache";
$l_gen_cache2 = "Generate Entire Cache";
$l_gen_zip = "Generating gzip Archive";
$l_gen_zip2 = "Generate gzip Archive";
$l_complete = "Complete";
$l_del_folder = "Delete this folder";
$l_flatten = "Flatten Subfolders";
$l_move_to = "Move Folder To";
$l_move_top = "TOP LEVEL";
$l_no_move = "DONT MOVE";
$l_rename_fold = "Rename Folder";
$l_add_friend = "Add a Friend's Fotopholder Library";
$l_url = "URL";
$l_add_friend2 = "Add Friend";
$l_download_zip = "Download Archive Of This Folder";
//To finish translation you will also need to translate:
//1. Script installation starting on line 732
//2. Definition of Flash starting on line 1123


//START FUNCTIONS	
	function checkGDversion() {
		$capabilities = get_extension_funcs("gd");
		if(!$capabilities) return 0;
		if(in_array ("imagegd2",$capabilities)) return 2;
		return 1;
	}
	function checkExifversion() {
		$capabilities = get_extension_funcs("exif");
		if(!$capabilities) return 0;
		if(in_array ("exif_read_data",$capabilities)) return 1;
		return 0;
	}
	function myURLencode($in) {
		$out = rawurlencode($in);
		$out = str_replace("%2F","/",$out);
		return $out;
	}
	function makeSquare($path, $dim) {
		//This function will create a square image with the photo either cropped or with buffer space added
		//depending on the value of the image_crop variable
		global $image_bkgd, $image_crop;
	
		$gd_Version = checkGDversion();
		if($gd_Version<1) return -1;
			
		if(stristr($path,".png")) $im = @imagecreatefrompng($path);
		else $im = @imagecreatefromjpeg($path);
		
		if(!$im) return -1;
		
		$size = @GetImageSize($path);
		
		if($size[0]==$dim && $size[1]==$dim) return $im;
		
		if($image_crop==1) {
			if($size[0]>$size[1]) {//fat images
				$srcSizeX = $size[1];
				$srcSizeY = $size[1];
				$srcY = 0;
				$srcX = ($size[0]-$size[1])/2;
			} else if($size[0]<$size[1]) { //tall images
				$srcSizeX = $size[0];
				$srcSizeY = $size[0];
				$srcX = 0;
				$srcY = ($size[1]-$size[0])/2;
			} else { //square
				$srcY = 0;
				$srcX = 0;
				$srcSizeX = $size[0];
				$srcSizeY = $size[0];
			}
			$yoffset = 0; //draw in dest at 0,0
			$xoffset = 0;
			$newWidth = $dim;
			$newHeight = $dim;
		} else {
			if($size[0]>$size[1]) { //fat images
				$ratio = $size[0]/$dim;
				$newWidth=$dim;
				$newHeight = round($size[1]/$ratio,0);
			} else if($size[0]<$size[1]) {	//tall images
				$ratio = $size[1]/$dim;
				$newWidth= round($size[0]/$ratio,0);
				$newHeight = $dim;
			} else { //square
				$newWidth = $dim;
				$newHeight = $dim;
			}
			
			$srcSizeX = $size[0];
			$srcSizeY = $size[1];
			$srcY = 0; //draw from src at 0,0
			$srcX = 0;
			$yoffset = ($dim-$newHeight)/2; //draw in dest at offset
			$xoffset = ($dim-$newWidth)/2;
			if($yoffset<0) $yoffset=0;
			if($xoffset<0) $xoffset=0;
		}
		
		//make the new image
		if($gd_Version>=2) $destImage = imagecreatetruecolor( $dim, $dim);  
		else $destImage = ImageCreate( $dim, $dim); 
	
		$color = ImageColorAllocate($destImage,$image_bkgd[0],$image_bkgd[1],$image_bkgd[2]);	
		imagefill($destImage, 0, 0, $color);				//paint it white first
		
		//copy the passed image into the new image at the proper scale
		if($gd_Version>=2) imagecopyresampled( $destImage, $im, $xoffset, $yoffset, $srcX, $srcY, $newWidth, $newHeight, $srcSizeX, $srcSizeY );
		else ImageCopyResized( $destImage, $im, $xoffset, $yoffset, $srcX, $srcY, $newWidth+1, $newHeight+1, $srcSizeX, $srcSizeY );
		
		ImageDestroy($im); //clean up the old image
		
		if(checkExifversion()==1 && $autorotate==1) {
			$details = @exif_read_data($path);
			if(!empty($details['Orientation'])) {
				switch($details['Orientation']) {
					case 1: $rotation = 0; break;
					case 3: $rotation = 180; break;
					case 8: $rotation = 90; break;
					case 6: $rotation = 270; break;
					default: $rotation = 0; break;
				}
				if ($rotation>0) $destImage = @imagerotate($destImage,$rotation,$color);
			}
		}
		
		return $destImage;	//return the new image
	}
	function makeSquareSIPS($path, $save, $dim) {
		global $image_crop;
	
		if($image_crop==1) { //zoom in and crop square
			$size = @GetImageSize($path);
			if($size[0]>$size[1]) $size[0] = $size[1];
			$command = "sips -s format jpeg --cropToHeightWidth ".$size[0]." ".$size[0]." ".escapeshellarg($path)."  --out ".escapeshellarg($save);
			exec($command);
			$command = "sips --resampleHeightWidthMax ".$dim." ".escapeshellarg($save);
			exec($command);
		} else { //pad it
			$command = "sips -s format jpeg --resampleHeightWidthMax ".$dim." --padToHeightWidth ".$dim." ".$dim." ".escapeshellarg($path)."  --out ".escapeshellarg($save);
			exec($command);
		}
		
		if(checkExifversion()==1) {
			$details = @exif_read_data($path);
			if(!empty($details['Orientation'])) {
				switch($details['Orientation']) {
					case 1: $rotation = 0; break;
					case 3: $rotation = 180; break;
					case 6: $rotation = 90; break;
					case 8: $rotation = 270; break;
					default: $rotation = 0; break;
				}
				if($rotation>0)  { //rotate it
					$command = "sips --rotate ".$rotation." ".escapeshellarg($save);
					exec($command);
				}
			}
		}
	}
	function makeScaled($path, $dim) {
		//This function will resize the image keeping the same aspect ratio
		//the max width or height will be $dim
		global $image_bkgd;
	
		$gd_Version = checkGDversion();
		if($gd_Version<1) return -1;
		
		if(stristr($path,".png")) $im = @imagecreatefrompng($path);
		else $im = @imagecreatefromjpeg($path);
		if(!$im) return -1;
		
		$size = @GetImageSize($path);
		
		if(checkExifversion()==1 && $autorotate==1) {
			$details = @exif_read_data($path);
			if(!empty($details['Orientation'])) {
				switch($details['Orientation']) {
					case 1: $rotation = 0; break;
					case 3: $rotation = 180; break;
					case 8: $rotation = 90; break;
					case 6: $rotation = 270; break;
					default: $rotation = 0; break;
				}
				if ($rotation>0) {
					$im = @imagerotate($im,$rotation,0);
					if($rotation==90 || $rotation==270) {
						$temp = $size[0];
						$size[0]=$size[1];
						$size[1]=$temp;
					}
				}
			}
		}
		
		if($size[0]<=$dim && $size[1]<=$dim) return $im;
		
		if($size[0]>$size[1]) { //fat images
			$ratio = $size[0]/$dim;
			$newWidth=$dim;
			$newHeight = round($size[1]/$ratio,0);
		} else if($size[0]<$size[1]) {	//tall images
			$ratio = $size[1]/$dim;
			$newWidth= round($size[0]/$ratio,0);
			$newHeight = $dim;
		} else { //square
			$newWidth = $dim;
			$newHeight = $dim;
		}
		
		//make the new image
		if($gd_Version>=2) $destImage = imagecreatetruecolor( $newWidth, $newHeight);  
		else $destImage = ImageCreate( $newWidth, $newHeight); 
	
		$color = ImageColorAllocate($destImage,$image_bkgd[0],$image_bkgd[1],$image_bkgd[2]);	
		imagefill($destImage, 0, 0, $color);				//paint it white first
		
		//copy the passed image into the new image at the proper scale
		if($gd_Version>=2) imagecopyresampled( $destImage, $im, 0, 0, 0, 0, $newWidth, $newHeight, $size[0], $size[1] );
		else ImageCopyResized( $destImage, $im, 0, 0, 0, 0, $newWidth+1, $newHeight+1, $size[0], $size[1] );
		
		ImageDestroy($im); //clean up the old image
		
		return $destImage;	//return the new image
	}
	
	function makeScaledSIPS($path, $save, $dim) {	
		$command = "sips -s format jpeg --resampleHeightWidthMax ".$dim." ".escapeshellarg($path)."  --out ".escapeshellarg($save);
		exec($command);
		
		if(checkExifversion()==1) {
			$details = @exif_read_data($path);
			if(!empty($details['Orientation'])) {
				switch($details['Orientation']) {
					case 1: $rotation = 0; break;
					case 3: $rotation = 180; break;
					case 6: $rotation = 90; break;
					case 8: $rotation = 270; break;
					default: $rotation = 0; break;
				}
				if($rotation>0)  { //rotate it
					$command = "sips --rotate ".$rotation." ".escapeshellarg($save);
					exec($command);
				}
			}
		}
	}

	//Scans the directory and puts the photo names in an array
	function getPhotoList($path) {
		global $sort_method;
		$photos = array();
		$srcTime=0;
		$extra = 1000;
		
		if(!is_dir($path)) return -1;
		
		if($d = dir($path)) {
	 		while (false !== ($entry = $d->read())) {
	 			$extra++;
	 			if(stristr($entry,".jpg") || stristr($entry,".png")) {
	 				if($sort_method>2) $srcTime = @filemtime($path."/".$entry).$extra;
	 				else $srcTime++;
	 				$photos[$srcTime] = $entry;
	 			}
	 		}
	 		$d->close();
	 	} else {
	 		return -2;
	 	}	 	
	 	if($sort_method==1) asort($photos);
	 	if($sort_method==2) arsort($photos);
	 	if($sort_method==3) ksort($photos);
	 	if($sort_method==4) krsort($photos);
		 
		return $photos;
	}

	//Scans the directory and puts the sub-directory names in an array
	function getDirList($path) {
		global $sort_method;
		$dirs = array();
		$srcTime=0;
		$extra = 1000;
		
		if(!is_dir($path)) return -1;
		
		if($d = dir($path)) {
	 		while (false !== ($entry = $d->read())) {
	 			$extra++;
	 			if(substr($entry,0,1)!="." && !eregi("_cache",$entry) && is_dir($path."/".$entry)) {
	 				if($sort_method>2) $srcTime = filemtime($path."/".$entry).$extra;
	 				else $srcTime++;
	 				$dirs[$srcTime] = $entry;
	 			}
	 		}
	 		$d->close();
	 	} else {
	 		return -2;
	 	}	
	 	if($sort_method==1) asort($dirs);
	 	if($sort_method==2) arsort($dirs);
	 	if($sort_method==3) ksort($dirs);
	 	if($sort_method==4) krsort($dirs);
	 	
	 	return $dirs;
	}
	
	function truncate($name, $len) {
		global $show_extension;
		if($show_extension==0) $name = substr($name,0, strrpos($name,"."));
		
		if(strlen($name)<=$len) return $name;
		return substr($name,0,$len-1)."...";
	}
	
	function rmdirR($dir) {
	  $handle = opendir($dir);
	  while (false!==($e = readdir($handle))) {
		 if($e != "." && $e != "..") { 
		   if(is_dir("$dir/$e")) rmdirR("$dir/$e");  
		   else @unlink("$dir/$e");
		 } 
	  }
	  closedir($handle);
	  $worked = @rmdir($dir);
	  return $worked;
	} 
					
	function sanatizePath($path) {
		$path = str_replace("..","",$path); //remove .. to prevent viewing higher directories
		$path = strip_tags($path); //remove html tags to prevent XSS
		return $path;
	}				
//END FUNCTIONS
	
//START OUTPUT GENERATION
	$version = "2.51";
  	
  	//login
  	if(!empty($HTTP_POST_VARS['admin'])) {
  		if(!empty($password) && $HTTP_POST_VARS['admin']==$password) {
  			setcookie ("admin", md5($HTTP_POST_VARS['admin']), time() + 7200);
  			$_COOKIE['admin']=md5($HTTP_POST_VARS['admin']);
  		} else {
  			setcookie ("admin", '', time() - 7200);
  			$_COOKIE['admin']="";
  		}
  		if(!empty($HTTP_POST_VARS['path'])) $HTTP_GET_VARS['path'] = $HTTP_POST_VARS['path'];
  		if(!empty($HTTP_POST_VARS['file'])) $HTTP_GET_VARS['file'] = $HTTP_POST_VARS['file'];
  		if(!empty($HTTP_POST_VARS['op'])) $HTTP_GET_VARS['op'] = $HTTP_POST_VARS['op'];
  	}
  	//logout
  	if(!empty($HTTP_GET_VARS['admin'])) {
  		setcookie ("admin", '', time() - 7200);
  		$_COOKIE['admin']="";
  	}
  	
  	if(!isset($HTTP_GET_VARS['op'])) $HTTP_GET_VARS['op']=0;
  	
  	if(!empty($HTTP_GET_VARS['path'])) $HTTP_GET_VARS['path'] = sanatizePath($HTTP_GET_VARS['path']);
  	if(!empty($HTTP_GET_VARS['file'])) $HTTP_GET_VARS['file'] = sanatizePath($HTTP_GET_VARS['file']);
  	if(!empty($HTTP_GET_VARS['rename'])) $HTTP_GET_VARS['rename'] = sanatizePath($HTTP_GET_VARS['rename']);
  	
  	if(empty($HTTP_GET_VARS['path'])) $file_path = ".";
	else $file_path = "./".$HTTP_GET_VARS['path'];
		
	if(empty($HTTP_GET_VARS['path'])) $web_path = "";
	else $web_path = $HTTP_GET_VARS['path'];
	
	if(!empty($HTTP_GET_VARS['file'])) $HTTP_GET_VARS['file'] = $HTTP_GET_VARS['file'];
	
	if(empty($web_path)) $slash="";
	else $slash = "/";
		
	//delete file or folder
	if(!empty($admin) && $admin==1 && isset($HTTP_GET_VARS['del']) && $HTTP_GET_VARS['del']==1 && (!empty($HTTP_GET_VARS['path']) || !empty($HTTP_GET_VARS['file'])) && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		if(empty($HTTP_GET_VARS['file'])) {
			rmdirR($file_path);
			rmdirR("./_cache/".$HTTP_GET_VARS['path']);
			if($display_errors==1 && file_exists($file_path)) {
				echo "<table border='0' cellpadding='2' cellspacing='1' bgcolor='#000000'><tr><td bgcolor='#ff9999' colspan='2' class='info'>".$l_del_fol_err."</td></tr></table>";
			} else {
				$array = split("/",$HTTP_GET_VARS['path']);
				array_pop($array);
				$HTTP_GET_VARS['path'] = implode("/",$array);
				
				if(empty($HTTP_GET_VARS['path'])) $file_path = ".";
				else $file_path = "./".$HTTP_GET_VARS['path'];
		
				if(empty($HTTP_GET_VARS['path'])) $web_path = "";
				else $web_path = ($HTTP_GET_VARS['path']);
	
				if(empty($web_path)) $slash="";
				else $slash = "/";
			}
		} else {
			@unlink($file_path."/".$HTTP_GET_VARS['file']);
			@unlink("./_cache/".$HTTP_GET_VARS['path']."/_thumbnails/".$HTTP_GET_VARS['file']);
			@unlink("./_cache/".$HTTP_GET_VARS['path']."/_screen/".$HTTP_GET_VARS['file']);
			if($display_errors==1 && file_exists($file_path."/".$HTTP_GET_VARS['file'])) {
				echo "<table border='0' cellpadding='2' cellspacing='1' bgcolor='#000000'><tr><td bgcolor='#ff9999' colspan='2' class='info'>".$l_del_fil_err."</td></tr></table>";
			}
		}
	}
	
	//regenerate cache for photo
	if(!empty($admin) && $admin==1 && isset($HTTP_GET_VARS['regen']) && $HTTP_GET_VARS['regen']==1 && (!empty($HTTP_GET_VARS['path']) || !empty($HTTP_GET_VARS['file'])) && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		@unlink("./_cache/".$HTTP_GET_VARS['path']."/_thumbnails/".$HTTP_GET_VARS['file']);
		@unlink("./_cache/".$HTTP_GET_VARS['path']."/_screen/".$HTTP_GET_VARS['file']);
		$HTTP_GET_VARS['op'] = 5;
	}
	
	
	//rename folder or rename and add keyword/rating to file
	if(!empty($admin) && $admin==1 && !empty($HTTP_GET_VARS['rename']) && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		
		if($HTTP_GET_VARS['rename']!=$HTTP_GET_VARS['file'] || $HTTP_GET_VARS['path']!=$HTTP_GET_VARS['movepath']) {
			$rename_path = str_replace("index.php","",$HTTP_SERVER_VARS["SCRIPT_FILENAME"]);
			if(empty($HTTP_GET_VARS['path'])) $slash=""; else $slash = "/";
			if(empty($HTTP_GET_VARS['movepath'])) $moveslash=""; else $moveslash = "/";
			
			$worked = @rename($rename_path.$HTTP_GET_VARS['path'].$slash.$HTTP_GET_VARS['file'],$rename_path.$HTTP_GET_VARS['movepath'].$moveslash.$HTTP_GET_VARS['rename']);
			if($worked===FALSE) {
				echo "<table border='0' cellpadding='2' cellspacing='1' bgcolor='#000000'><tr><td bgcolor='#ff9999' colspan='2' class='info'>".$l_rename_err."</td></tr></table>";
			} 
			
			if($HTTP_GET_VARS['renamewhat']==2) { //its a folder
				if($worked) @rename($rename_path."_cache/".$HTTP_GET_VARS['path'].$slash.$HTTP_GET_VARS['file'],$rename_path."_cache/".$HTTP_GET_VARS['movepath'].$moveslash.$HTTP_GET_VARS['rename']);
				if($worked) $HTTP_GET_VARS['path'] = $HTTP_GET_VARS['movepath'].$moveslash.$HTTP_GET_VARS['rename'];
				$file_path = "./".$HTTP_GET_VARS['path'];
				$web_path = $HTTP_GET_VARS['path'];
			} else if($HTTP_GET_VARS['renamewhat']==1) { //its a file
				if($worked) @rename($rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_thumbnails/".$HTTP_GET_VARS['file'],$rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_thumbnails/".$HTTP_GET_VARS['rename']);
				if($worked) @rename($rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_screen/".$HTTP_GET_VARS['file'],$rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_screen/".$HTTP_GET_VARS['rename']);
				if($worked) @rename($rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_photoinfo/".$HTTP_GET_VARS['file'].".txt",$rename_path."_cache/".$HTTP_GET_VARS['path'].$slash."_photoinfo/".$HTTP_GET_VARS['rename'].".txt");
				if($worked) $HTTP_GET_VARS['file'] = $HTTP_GET_VARS['rename'];
				$HTTP_GET_VARS['op'] = 5;
			}
			
		} else {
			$HTTP_GET_VARS['op'] = 5;
		}
		$handle = @fopen("./_cache/".$web_path.$slash."/_photoinfo/".$HTTP_GET_VARS['file'].".txt", "wb");
		@fwrite($handle,$HTTP_GET_VARS['rating']."\n");
		@fwrite($handle,stripslashes($HTTP_GET_VARS['keywords'])."\n");
		@fclose($handle);
	}
	
	//add a friend
	if(!empty($admin) && $admin==1 && !empty($HTTP_GET_VARS['friend']) && $HTTP_GET_VARS['friend']!="http://" && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		$handle = @fopen($HTTP_GET_VARS['friend'], "rb");
		$temp = @fgets($handle,1024);
		while(!stristr($temp,"<title>")) {
			$temp = @fgets($handle,1024);
		}
		$title = trim(strip_tags($temp));
		fclose($handle);
		
		$handle = @fopen("./_cache/friend.txt", "a");
		@fwrite($handle,$HTTP_GET_VARS['friend']."\n");
		@fwrite($handle,$title."\n");
		fclose($handle);
	}
	
	//flatten subdirectories
	//LIMITATIONS: if the file/folder exist, it prefixes some random letters.  Doesnt work on root. 
	if(!empty($_GET['flatten'])) {
		$subdirs = getDirList($_GET['path']);
		foreach($subdirs as $s) {
			$flat_path = $_GET['path']."/".$s;
			if(is_dir($flat_path)) {
				if($d = dir($flat_path)) {
					while (false !== ($entry = $d->read())) {
						if(substr($entry,0,1)!="." && !eregi("_cache",$entry)) {
							$prefx='';
							if(file_exists($_GET['path']."/".$entry)) {
								$prefx = substr(md5(uniqid("_")),0,3)."_";
							}
							if(!file_exists($_GET['path']."/".$prefx.$entry)) {
								$worked = @rename($flat_path."/".$entry,$_GET['path']."/".$prefx.$entry);
								if($worked && is_dir($_GET['path']."/".$entry)) { //moved dir
									@rename("_cache/".$flat_path."/".$entry,"_cache/".$_GET['path']."/".$prefx.$entry);
								} else if($worked) { //moved photo
									@rename("_cache/".$flat_path."/_thumbnails/".$entry,"_cache/".$_GET['path']."/_thumbnails/".$prefx.$entry);
									@rename("_cache/".$flat_path."/_screen/".$entry,"_cache/".$_GET['path']."/_screen/".$prefx.$entry);
									@rename("_cache/".$flat_path."/_photoinfo/".$entry.".txt","_cache/".$_GET['path']."/_photoinfo/".$prefx.$entry.".txt");
								} else {
									//error
								}
							}
						}
					}
					$d->close();
				}
			}
		}
	}
		
	if(isset($HTTP_GET_VARS['op']) && ($HTTP_GET_VARS['op']==1 || $HTTP_GET_VARS['op']==2)) { //image generation
		if($HTTP_GET_VARS['op']==1) {
			$subdir = "_thumbnails";
			$image_size = $thumb_size;
		} else if($HTTP_GET_VARS['op']==2) {
			$subdir = "_screen";
			$image_size = $screen_size;
		}
		
		if(!isset($HTTP_GET_VARS['path']) || $HTTP_GET_VARS['path']=="") {
			$file_path = $HTTP_GET_VARS['file'];
			$save_path = "./_cache/".$subdir."/".$HTTP_GET_VARS['file'];
		} else {
			$file_path = $HTTP_GET_VARS['path']."/".$HTTP_GET_VARS['file'];
			$save_path = "./_cache/".$HTTP_GET_VARS['path']."/".$subdir."/".$HTTP_GET_VARS['file'];
		}
		
		$image = 0;
		if(!file_exists($save_path)) { //thumbnail does not exist.  Lets make it!
		
			if($use_sips) {
				if($HTTP_GET_VARS['op']==1) { //thumbnail
					makeSquareSIPS($file_path,$save_path,$image_size);
				}
				if($HTTP_GET_VARS['op']==2) { //screen
					makeScaledSIPS($file_path,$save_path,$image_size);
				}
			} else {
				if($HTTP_GET_VARS['op']==1) $image = makeSquare($file_path, $image_size);
				if($HTTP_GET_VARS['op']==2) $image = makeScaled($file_path, $image_size);
				
				if($image!=-1) {
					$success = @Imagejpeg($image,$save_path,$jpeg_compression);
				}
			}
		}
	
		Header("Content-type: image/jpeg");
		$im = @imagecreatefromjpeg($save_path);
		if($im) {
			Imagejpeg($im,'',$jpeg_compression);
		} else if($image) {
			Imagejpeg($image,'',$jpeg_compression);
		} else {
			$im = ImageCreate($image_size,$image_size); 
			$white = ImageColorAllocate($im,255,255,255);	
			$black = ImageColorAllocate($im,0,0,0);	
			imagefill($im, 0, 0, $white);
			imagestring($im,1,2,2,$l_error,$black);
			if(!isset($success)) imagestring($im,1,2,10,$l_not_jpeg,$black);
			else imagestring($im,1,2,10,$l_perm_err,$black);
			Imagejpeg($im,'',80);
		}
		if($im) ImageDestroy($im); 
		if($image) ImageDestroy($image);
		
	} else if(isset($HTTP_GET_VARS['op']) && $HTTP_GET_VARS['op']==4) { //folder icon generation
		Header("Content-type: image/jpeg");
		$im = ImageCreate(15,14); 
		$white = ImageColorAllocate($im,255,255,255);	
		$tan = ImageColorAllocate($im,192,128,64);	
		$black = ImageColorAllocate($im,0,0,0);	
		imagefill($im, 0, 0, $white);
		
		imageline($im,2,4,4,2,$black); //left tab edge
		imageline($im,7,2,9,4,$black); //right tab edge
		imageline($im,4,2,7,2,$black); //tab top
		imageline($im,2,4,2,13,$black); //left side
		imageline($im,2,13,14,13,$black); //bottom
		imageline($im,14,13,14,5,$black); //right
		imageline($im,13,4,9,4,$black); //top
		
		imagefill($im, 10, 10, $tan);
		
		Imagejpeg($im,'',80);
		ImageDestroy($im); 
	} else { //directory output

	
	//Please leave the following invisible html comments in place.
?>
<!-- This photo gallery was generated with Fotopholder <?php echo $version?> -->
<!-- http://www.jakeo.com/software/fotopholder/index.php -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<?
	$hasGD = checkGDversion();

	if(!file_exists("./_cache") || !file_exists("./_cache/config.php")) { //cache doesnt exist.  First time through.
		if(!file_exists("./_cache")) $success = @mkdir("./_cache",0777);
		
		if(is_writable (".") && is_writable ("./_cache") && $hasGD) {
			
			if(!empty($_POST['settings'])) {
				$handle = fopen("./_cache/config.php", "wb");
				$data = "<?php\n";
				$data .= "\$album_name = \"".$_POST['album_name']."\";	//The name of your album\n";
				$data .= "\$display_errors = ".$_POST['display_errors'].";	//set to 0 once you are satisfied that the script is installed correctly\n";
				$data .= "\$no_screen = ".$_POST['no_screen'].";	//set to 1 and the original image will be used for display when you click the thumbnail instead of a medium sized generated image\n";
				$data .= "\$use_sips = ".$_POST['use_sips'].";	//For Mac os X users only. Try setting to 1 to see if you get a speedup of thumbnail creation\n";
				$data .= "\$screen_size = ".$_POST['screen_size'].";	//max width or height of the screen sized image\n";
				$data .= "\$thumb_size = ".$_POST['thumb_size'].";	//width and height of the thumbnail\n";
				$data .= "\$tiny_size = ".$_POST['tiny_size'].";	//width and height of the tiny thumbnail used in folder listings\n";
				$data .= "\$num_tinys = ".$_POST['num_tinys'].";	//number of tiny thumnials to display in a folder listing\n";
				$data .= "\$thumb_cols = ".$_POST['thumb_cols'].";	//number of regular thumbnails to show per row\n";
				$data .= "\$thumbs_page = ".$_POST['thumbs_page'].";	//number of thumbnails to display per page for big folders\n";
				$data .= "\$autorotate = ".$_POST['autorotate'].";	//set to 1 to attempt to rotate an image if the camera sets the exif orientation\n";
				$data .= "\$sort_method = ".$_POST['sort_method'].";	//how to sort the directories and filenames Possible Values: 0=no sort, 1=alphabetical ascending, 2=alphabetical descending 3=date asc 4-date desc\n";
				$data .= "\$show_filename = ".$_POST['show_filename']."; //1 shows filename of photos in list view. 0 hides filename \n";
				$data .= "\$display_rating = ".$_POST['display_rating']."; //1 shows the rating stars.  0 hides them\n";
				$data .= "\$show_extension = ".$_POST['show_extension']."; //1 shows filename extension. 0 hides extension \n";
				$data .= "\$max_file_length = ".$_POST['max_file_length']."; //filenames longer than this will be truncated for display purposes\n";
				$data .= "\$show_extra_info = ".$_POST['show_extra_info']."; //1=show extra info about an image if available (aperture, shutter speed, etc)\n";
				$data .= "\$jpeg_compression = ".$_POST['jpeg_compression']."; //image compression of thumbails. 100=big, 0=ugly\n";
				$data .= "\$image_crop = ".$_POST['image_crop']."; //1=thumbnail is zoomed and cropped to a square 0=thumbnail is padded to a square\n";
				$data .= "\$admin = ".$_POST['admin']."; //set to 1 to turn on admin tools for deleting and renaming folders and files\n";
				$data .= "\$admin_only = ".$_POST['admin_only']."; //set to 1 to make a private album with photos hidden till you type the password\n";
				$data .= "\$password = '".$_POST['password']."'; //this is the admin password needed to make changes\n";
				$data .= "\$delete_tiny_files = ".$_POST['delete_tiny_files']."; //set to 1 to automatically delete itty bitty images (probably old thumbnails)\n";
				$data .= "\$tiny_file_max_kbytes = ".$_POST['tiny_file_max_kbytes']."; //this is the max size for the itty bitty images that will be deleted\n";
				$data .= "\$delete_empty_folders = ".$_POST['delete_empty_folders']."; //set to 1 to automatically delete empty folders\n";
				$data .= "\$gallery_link = ".$_POST['gallery_link']."; //set to 1 to display a link to your photo gallery on the fotopholder home page\n";
				$data .= "\$gallery_link_url = \"".$_POST['gallery_link_url']."\"; //the url of your photo gallery if you want to share it on the fotopholder website.\n";
				
				if(!empty($_POST['image_bkgd']) && $_POST['image_bkgd']==1) $data .= "\$image_bkgd = array( 0,0,0 ); //(red, green, blue) color of filler space in padded thumbnails\n";
				else if(!empty($_POST['image_bkgd']) && $_POST['image_bkgd']==2) $data .= "\$image_bkgd = array( 128,128,128 ); //(red, green, blue) color of filler space in padded thumbnails\n";
				else $data .= "\$image_bkgd = array( 255,255,255 ); //(red, green, blue) color of filler space in padded thumbnails\n";

				$data .= "?>";
				fwrite($handle,$data."\n");
				fclose($handle);
			
				if(file_exists("./_cache/config.php")) {
					?>
					<title>FotoPholder <?php echo $version?></title>
					</head><body>
					<h1>Success</h1>
					<p>Your gallery has been setup.</p>
					<br />
					<b><a href="index.php">View Gallery</a></b>
					</body></html>
					<?php
					exit();
				}
			} else {
				?>
				<title>FotoPholder <?php echo $version?></title>
				</head><body bgcolor="#eeeeff">
				<h1>Welcome to FotoPholder <?php echo $version?></h1>
				
				<p>Everything looks good so far.  Please check these default settings for your new gallery.</p>
	
				<form action="index.php" method="post">
				<input type="hidden" name="settings" value="1" />
				
				<table border="1" cellpadding="5" cellspacing="0">
				<tr><td width="300"><b>Album Name</b></td><td>
				<input type="text" name="album_name" size="30" value="My Album" />
				
				</td></tr><tr><td><b>Screen Sized Image</b><br />Whether to use the original or make a smaller version for display on screen.</td><td>
				<input type="radio" name="no_screen" value="1" />Use Original
				<input type="radio" name="no_screen" value="0" checked="checked" />Shrink to below dimensions
				<br />
				Max width or height: <input type="text" name="screen_size" size="10" value="600" />
				
				</td></tr><tr><td><b>Thumbnail Size</b><br />Width and height of the thumbnail.</td><td>
				<input type="text" name="thumb_size" size="10" value="100" />
				
				</td></tr><tr><td><b>Tiny Size</b><br />Width and height of the tiny thumbnail used in folder listings.</td><td>
				<input type="text" name="tiny_size" size="10" value="50" />
			
				</td></tr><tr><td><b>Number of Tinys</b><br />Number of tiny thumbnails to display in a folder listing.</td><td>
				<input type="text" name="num_tinys" size="10" value="10" />
				
				</td></tr><tr><td><b>Columns</b><br />Number of columns of thumbnails.</td><td>
				<input type="text" name="thumb_cols" size="10" value="7" />
				
				</td></tr><tr><td><b>Thumbnails per Page</b><br />Number of thumbnails per page. Make it a multiple of the number of columns for pretty pages.</td><td>
				<input type="text" name="thumbs_page" size="10" value="49" />
				
				</td></tr><tr><td><b>Show Filename</b><br />Whether to show the filename in the folder listing.</td><td>
				<input type="radio" name="show_filename" value="1" checked="checked" />Yes
				<input type="radio" name="show_filename" value="0" />No
				
				</td></tr><tr><td><b>Max Filename Length</b><br />Filenames longer than this will be truncated.</td><td>
				<input type="text" name="max_file_length" size="10" value="20" />
				
				</td></tr><tr><td><b>Display Star Ratings</b><br />Displays the star ratings that you set for each photo.</td><td>
				<input type="radio" name="display_rating" value="1" checked="checked" />Yes
				<input type="radio" name="display_rating" value="0" />No
				
				</td></tr><tr><td><b>Show Extensions</b><br />Shows the file extension of the photo.</td><td>
				<input type="radio" name="show_extension" value="1" />Yes
				<input type="radio" name="show_extension" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Show Extra (EXIF) Photo Info</b><br />Show extra info about an image if available (aperture, shutter speed, etc)</td><td>
				<input type="radio" name="show_extra_info" value="1" checked="checked" />Yes
				<input type="radio" name="show_extra_info" value="0" />No
				
				</td></tr><tr><td colspan="2" bgcolor="#ccccff">Technical Settings

				</td></tr><tr><td><b>JPEG Compression</b><br />Enter the amount of compression you want to apply as a percentage.  100=big and 0=ugly.</td><td>
				<input type="text" name="jpeg_compression" size="4" value="80" />%
				
				</td></tr><tr><td><b>Use SIPS</b><br />For Mac OS X users only. Try setting to see if you get a speedup of thumbnail creation.</td><td>
				<input type="radio" name="use_sips" value="1" />Yes
				<input type="radio" name="use_sips" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Autorotate Images</b><br />Attempt to rotate an image if the camera sets the orientation flag.</td><td>
				<input type="radio" name="autorotate" value="1" />Yes
				<input type="radio" name="autorotate" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Sort Method</b><br />How to sort the directories and filenames.</td><td>
				<input type="radio" name="sort_method" value="0" />No Sorting<br />
				<input type="radio" name="sort_method" value="1" checked="checked" />Alphabetical A-Z<br />
				<input type="radio" name="sort_method" value="2" />Alphabetical Z-A<br />
				<input type="radio" name="sort_method" value="3" />File Modification Date (oldest first)<br />
				<input type="radio" name="sort_method" value="4" />File Modification Date (newest first)
				
				</td></tr><tr><td><b>Thumbnail Cropping</b><br />How to make square thumbnails out of rectangular photos.</td><td>
				<input type="radio" name="image_crop" value="1" checked="checked" />Zoom and crop images to a square.<br />
				<input type="radio" name="image_crop" value="0" />Pad images to a square with Image Background Color
				
				</td></tr><tr><td><b>Image Background Color</b><br />Only used when thumbnail padding is set above</td><td>
				<input type="radio" name="image_bkgd" value="0" checked="checked" />White
				<input type="radio" name="image_bkgd" value="1" />Black
				<input type="radio" name="image_bkgd" value="2" />Grey
				
				</td></tr><tr><td colspan="2" bgcolor="#ccccff">Administrative Settings
				
				</td></tr><tr><td><b>Enable Admin Utilities</b><br />Allow admin to sign in and use utilities to manage photos</td><td>
				<input type="radio" name="admin" value="1" />Yes
				<input type="radio" name="admin" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Admin Password</b><br />The password used to enable the admin utilities</td><td>
				<input type="text" name="password" size="10" value="secret" />
				
				</td></tr><tr><td><b>Make Photos Private</b><br />This will make your entire album password protected.</td><td>
				<input type="radio" name="admin_only" value="1" />Yes
				<input type="radio" name="admin_only" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Delete Tiny Files</b><br />Any files smaller than the minimum filesize will be deleted. Use this to clean out old thumbnail images created by other gallery software.</td><td>
				<input type="radio" name="delete_tiny_files" value="1" />Yes
				<input type="radio" name="delete_tiny_files" value="0" checked="checked" />No
				<br />Minimum Filesize: <input type="text" name="tiny_file_max_kbytes" size="5" value="10" />Kilobytes
				
				</td></tr><tr><td><b>Delete Empty Folders</b><br />Any empty folders will be deleted.</td><td>
				<input type="radio" name="delete_empty_folders" value="1" />Yes
				<input type="radio" name="delete_empty_folders" value="0" checked="checked" />No
				
				</td></tr><tr><td><b>Display Errors</b><br />If this script encounters any errors, do you want to display them?
				</td><td>
				<input type="radio" name="display_errors" value="1" checked="checked" />Yes
				<input type="radio" name="display_errors" value="0" />No
				
				</td></tr><tr><td><b>Share my Gallery</b><br />If you select 'Yes' a link to your photo gallery will be displayed on the home page for the fotopholder script.</td><td>
				<input type="radio" name="gallery_link" value="1" />Yes
				<input type="radio" name="gallery_link" value="0" checked="checked" />No<br />
				Your gallery's url: <input type="text" name="gallery_link_url" size="30" value="http://" />
				</td></tr>
				
				</table>
			
				<p>These settings will be stored in the following file: <b><?php echo $_SERVER["DOCUMENT_ROOT"].str_replace("index.php","",$_SERVER["SCRIPT_NAME"])?>_cache/config.php</b> . You can edit this file to change your settings in the future.</p>
				<input type="submit" value="Save Settings" />
				</form>
				
				</body></html>
				<?php
				exit();
			}
		} else {
			$success=FALSE;
		}

		if(empty($success) && $hasGD) {
		?>
		<title>FotoPholder <?php echo $version?></title>
		</head><body>
		<h1>Welcome to FotoPholder <?php echo $version?></h1>
		<p>Your file permissions are setup incorrectly. This script needs read and write access to your photos directory.</p>
		<br /><b>Under Unix</b><br />
		<p>You'll need to use a telnet application to signin to your server as root. Use this command to give read and write access to this script: <b>chmod -R 777 <?php echo $_SERVER["DOCUMENT_ROOT"].str_replace("/index.php","",$_SERVER["SCRIPT_NAME"])?></b></p>
		<br /><b>Under Mac OS X</b><br />
		<p>Find the <b><?php echo $_SERVER["DOCUMENT_ROOT"].str_replace("index.php","",$_SERVER["SCRIPT_NAME"])?></b> directory.  Control click on the directory and select "Get Info".  At the bottom of the info window under 'Details' make sure it says 'Read &amp; Write' for all three sections. Then click 'Apply to enclosed items'.</p>
		<br /><b>Under Windows</b><br />
		<p>Find the <b><?php echo $_SERVER["DOCUMENT_ROOT"].str_replace("index.php","",$_SERVER["SCRIPT_NAME"])?></b> directory and give this script write access to it.</p>
		</body></html>
		<?php
		exit();
		} else {
		?>
		<title>FotoPholder <?php echo $version?></title>
		</head><body>
		<h1>Welcome to FotoPholder <?php echo $version?></h1>
		<p>It doesn't look like your system is capable of running this software.  You need to have PHP compiled with the GD library.  More information about GD and PHP can be found <a href="http://us2.php.net/manual/en/ref.image.php">Here</a>.</p>
		</body></html>
		<?php
		exit();
		}
	}
?>


<title><?php echo $album_name?> <?php if(!empty($web_path)) echo ":"; ?> <?php echo str_replace("/"," : ",$web_path)?></title>
<?php
	if(!file_exists("./_cache/index_css.css")) {
		$handle = @fopen("./_cache/index_css.css", "wb");
		$data = "html, body, h1, h2, h3, h4, h5, h6, div, span, iframe { margin:0px; padding:0px; }
img  { margin:0px; padding:0px; border: 0px; }
form { display:inline; margin:0px; padding:0px; }
hr { height: 1px; margin: 10px 0px 10px 0px; }

body {
	background-color:#eeeeff;
	font-family:arial, sans-serif;
	font-size:90%;
}

.dir_table { border: 1px solid #000000; background-color:#ffffff; width:98%; margin: 0px 0px 0px 5px;}
.dir_table_info { width:150px; text-align: top; padding: 3px 0px 3px 0px;}
.dir_table_thumbs { text-align: middle; font-size: 1em; padding: 3px 0px 3px 0px;}

.photo_table { background-color:#ffffff; border: 1px solid #000000; width:98%; margin: 0px 0px 0px 5px;}
.photo_table_cell { border-right: 1px solid #000000; padding: 0px 0px 5px 0px;}
.photo_table_cell_l { border-right: 1px solid #000000;padding: 0px 0px 5px 0px; }
.photo_table_cell_r { padding: 0px 0px 5px 0px; }
.photo_table_cell_e { padding: 0px 0px 5px 0px; }
.photo_table_cell_n { padding: 5px 5px 5px 5px; }
.photo_table_spacer { background-color:#cccccc; height:10px; border-bottom: 1px solid #000000; border-top: 1px solid #000000;}

.details_table { background-color:#ffffff; border: 1px solid #000000; width:98%; margin: 0px 0px 0px 5px;}
.details_table_cell_l { border-right: 1px solid #000000; padding: 5px 5px 5px 5px; width:200px; }
.details_table_cell_r { padding: 0px; }

.crumbs { font-size: 1.1em; font-weight:bold; margin: 0px 0px 10px 5px;}

.admin_table { border: 1px solid #000000; background-color:#ffffff; width:98%; margin: 0px 0px 0px 5px;}
.admin_heading { border-bottom: 2px solid #aa7777; background-color:#cc9999; padding: 2px 0px 3px 5px; font-size: 1.1em; font-weight:bold;}
.admin_cell { background-color:#ffcccc; padding: 10px 0px 10px 5px;}

.friend_table { border: 1px solid #000000; background-color:#ffffff; width:98%; margin: 0px 0px 0px 5px;}
.friend_heading { border-bottom: 2px solid #77aa77; background-color:#99cc99; padding: 2px 0px 3px 5px; font-size: 1.1em; font-weight:bold;}
.friend_cell { background-color:#ccffcc; padding: 10px 0px 10px 5px;}

.form_btn { border: 1px solid #000000; background-color:#ccccff; font-size: 0.8em; }
.form_btn:hover { border: 1px solid #000000; background-color:#9999ff; font-size: 0.8em; }
.form_text { border: 1px solid #000000; background-color:#ffffff; font-size: 0.8em; padding: 2px;}
.form_text:hover { border: 1px solid #000066; background-color:#ffffcc; font-size: 0.8em; padding: 2px;}

.error { border: 1px solid #000000; background-color:#ffcccc; width:98%; margin: 0px 0px 0px 5px;}

a { color: #000099; text-decoration: none;}
a:hover { color: #660099; text-decoration: underline;}
 
b { font-size: 1.1em; font-weight:bold;}
.folder { font-size: 1em;}
.info { font-size: 0.8em; }
.info_bold { font-size: 0.8em; font-weight:bold;}
.tag { color: #666666; font-size: 0.9em; font-weight:normal;  margin: 0px 0px 0px 5px;}
a.tag_lnk { color: #666666; text-decoration: underline;}";
		@fwrite($handle,$data."\n");
		@fclose($handle);
		echo "<style type='text/css'>".$data."</style>";
	} else { 
	?>
<link rel="stylesheet" type="text/css" href="_cache/index_css.css" media="screen" />
<?php } ?>

</head>
<body>

<div class="crumbs">
<?php 
	// START CRUMBS
		$crumbs = split("/", $web_path);
		if($crumbs[0]=="" && $HTTP_GET_VARS['op']!=5) echo $album_name;
		else echo "<a href='index.php'>".$album_name."</a>";
	
		$total = "";
		$num=0;
		foreach ($crumbs as $crumb) {
			if($crumb!="" && $crumb!=".") {
				$num++;
				if($total!="") $total.="/";
				$total.=$crumb;
				if($num!=sizeof($crumbs)) echo " : <a href='index.php?path=".rawurlencode($total)."'>".$crumb."</a>";
				else if($HTTP_GET_VARS['op']==5) echo " : <a href='index.php?path=".rawurlencode($total)."'>".$crumb."</a>";
				else echo " : ".$crumb;
			}
		}
		if($HTTP_GET_VARS['op']==5) echo " : ".$HTTP_GET_VARS['file'];
	// END CRUMBS
?>
</div>

<?php
//IMAGE DETAILS
if($HTTP_GET_VARS['op']==5) {
	if($no_screen==1) { 
		if(empty($web_path)) $screen_path = "./".$HTTP_GET_VARS['file'];
		else $screen_path=$web_path."/".$HTTP_GET_VARS['file'];
		$size = @getimagesize($screen_path);
		$screen_size = $size[0];
		$dim = "width='".$size[0]."' height='".$size[1]."'";
	} else {
		$screen_path = "index.php?op=2&amp;path=".rawurlencode($web_path)."&amp;file=".rawurlencode($HTTP_GET_VARS['file']);
		
		if(empty($web_path)) $size=@getimagesize("./_cache/_screen/".$HTTP_GET_VARS['file']);
		else $size=@getimagesize("./_cache/".$web_path."/_screen/".$HTTP_GET_VARS['file']);
		
		if(!empty($size)) $dim = "width='".$size[0]."' height='".$size[1]."'";
		else $dim="";
	}
	
	//get previous and next photos
	$photos = getPhotoList("./".$web_path);
	if(is_array($photos)) {
		$last = '';
		foreach ($photos as $photo) {
			$prev = $last;
			$last = $photo;
			if($photo==$HTTP_GET_VARS['file']) break;
		}
		$next = current($photos);
	}
	if(file_exists("./_cache/_icons/prev.gif")) {
		$prev_icon = "<img src='./_cache/_icons/prev.gif' width='9' height='10' border='0' alt='&lt;' />";
	} else {
		$prev_icon = "&lt;--";
	}
	if(file_exists("./_cache/_icons/next.gif")) {
		$next_icon = "<img src='./_cache/_icons/next.gif' width='9' height='10' border='0' alt='&gt;' />";
	} else {
		$next_icon = "--&gt;";
	}
		
	?>

	<table border="0" cellpadding="0" cellspacing="0" class="details_table">
	<tr><td class="details_table_cell_l" valign="top">
	<?php 
	if(checkExifversion()==1) {
		$details = @exif_read_data($file_path."/".$HTTP_GET_VARS['file']);
		if(!empty($details['UserComment'])) $details['UserComment'] = trim($details['UserComment']);
	}
	echo "<b>".truncate($HTTP_GET_VARS['file'],strlen($HTTP_GET_VARS['file']))."<br /><br /></b>";
		
	if(!empty($details) && $show_extra_info==1) {
		?>
		<?php if(!empty($details['DateTime'])) { ?><span class="info_bold"><?php echo $l_time;?>: </span><span class="info"><?php echo $details['DateTime']?> <br /></span><?php } ?>
		
		<?php if(!empty($details['ExposureTime'])) { 
			$fraction = split("/",$details['ExposureTime']);
			if($fraction[0]/10 == 1) $exposureTime="1/".round($fraction[1]/10, 0)." sec"; //top is 10
			else $exposureTime=$fraction[0]."/".$fraction[1]." sec"; //top is some other number
			?>
		<span class="info_bold"><?php echo $l_shutter;?>: </span><span class="info"><?php echo $exposureTime?><br /></span><?php } ?>
		
		<?php if(!empty($details['FNumber'])) { 
			$fraction = split("/",$details['FNumber']);
			if($fraction[1]!=0) $fnumber="f ".($fraction[0]/$fraction[1]); 
			else $fnumber="f ".$details['FNumber']; 
			?>
		<span class="info_bold"><?php echo $l_aperture;?>: </span><span class="info"><?php echo $fnumber?><br /></span><?php } ?>
		
		<?php if(!empty($details['FocalLength'])) { 
			$fraction = split("/",$details['FocalLength']);
			if($fraction[1]!=0) $fnumber=($fraction[0]/$fraction[1])."mm"; 
			else $fnumber=$details['FocalLength']."mm"; 
			?>
		<span class="info_bold"><?php echo $l_focal;?>: </span><span class="info"><?php echo $fnumber?><br /></span><?php } ?>
		<?php if(!empty($details['ISOSpeedRatings'])) { ?>
		<span class="info_bold"><?php echo $l_iso;?>: </span><span class="info"><?php echo $details['ISOSpeedRatings']?><br /></span><?php } ?>
		
		<?php if(!empty($details['Flash'])) { 
			switch($details['Flash']) {
			case 0: $flash = "No Flash"; break;
			case 1: $flash = "Flash"; break;
			case 5: $flash = "Flash, strobe return light not detected"; break;
			case 7: $flash = "Flash, strob return light detected"; break;
			case 9: $flash = "Compulsory Flash"; break;
			case 13: $flash = "Compulsory Flash, Return light not detected"; break;
			case 15: $flash = "Compulsory Flash, Return light detected"; break;
			case 16: $flash = "No Flash"; break;
			case 24: $flash = "No Flash"; break;
			case 25: $flash = "Flash, Auto-Mode"; break;
			case 29: $flash = "Flash, Auto-Mode, Return light not detected"; break;
			case 31: $flash = "Flash, Auto-Mode, Return light detected"; break;
			case 32: $flash = "No Flash"; break;
			case 65: $flash = "Red Eye"; break;
			case 69: $flash = "Red Eye, Return light not detected"; break;
			case 71: $flash = "Red Eye, Return light detected"; break;
			case 73: $flash = "Red Eye, Compulsory Flash"; break;
			case 77: $flash = "Red Eye, Compulsory Flash, Return light not detected"; break;
			case 79: $flash = "Red Eye, Compulsory Flash, Return light detected"; break;
			case 89: $flash = "Red Eye, Auto-Mode"; break;
			case 93: $flash = "Red Eye, Auto-Mode, Return light not detected"; break;
			case 95: $flash = "Red Eye, Auto-Mode, Return light detected"; break;
			default : $flash = "Unknown"; break;
			}
			?>
		<span class="info_bold"><?php echo $l_flash;?>: </span><span class="info"><?php echo $flash?><br /></span><?php } ?>
		
		<?php if(!empty($details['Make']) && !empty($details['Model'])) { ?><span class="info_bold"><?php echo $l_camera;?>: </span><span class="info"><?php echo $details['Make']." ".$details['Model']; ?> <br /></span><?php } ?>
		<?php if(!empty($details['UserComment'])) { ?><span class="info_bold"><?php echo $l_comments;?>: </span><span class="info"><?php echo $details['UserComment']; ?> <br /></span><?php } ?>
		
		<?php
	}

		//tries to grab keywords and rating for photo
		$info_rating = 0;
		$info_keywords = '';
		if(file_exists("./_cache/".$web_path.$slash."/_photoinfo/".$HTTP_GET_VARS['file'].".txt")) {
			$handle = @fopen("./_cache/".$web_path.$slash."/_photoinfo/".$HTTP_GET_VARS['file'].".txt", "rb");
			$info_rating = @fgets($handle,1024);
			$info_keywords = @fgets($handle,1024);
			$info_rating = str_replace("\n","",$info_rating);
			$info_keywords = str_replace("\n","",$info_keywords);
			@fclose($handle);
		}
		
	?>
	
	<?php 

		if(file_exists("./_cache/_icons/star.gif")) {
			$star_icon = "<img src='./_cache/_icons/star.gif' width='11' height='8' border='0' alt='*' />";
		} else {
			$star_icon = "*";
		}
				
		if(!empty($info_rating) && $display_rating) {
			echo "<span class='info_bold'>".$l_rating.": </span><span class='info'>";
			for($i=0;$i<$info_rating;$i++) echo $star_icon;
			echo "<br /></span>";
		}
	?> 
	<?php 
		if(!empty($info_keywords)) {
			echo "<span class='info_bold'>".$l_comments.": </span><span class='info'>".$info_keywords."<br /></span>";
		}
	?> 
	<hr />
	<span class="info">
	<?php 
		if(file_exists("./_cache/_icons/download.gif")) {
			$download = "<img src='./_cache/_icons/download.gif' width='9' height='10' border='0' alt='*' /> ".$l_download;
		} else {
			$download = $l_download;
		}
		
	?>
	<a href="<?php if(empty($web_path)) echo "."; echo myURLencode($web_path)."/".rawurlencode($HTTP_GET_VARS['file'])?>"><?php echo $download?></a>
	(<?php echo round(filesize($file_path."/".$HTTP_GET_VARS['file'])/1024,0)?> k)
	</span>
	
	</td><td valign="top" class="details_table_cell_r">

	<?php if(!empty($_GET['auto']) && !empty($next)) { ?>
	<script language="JavaScript" type="text/javascript">
	<!--
	function advance() {
		window.location="index.php?op=5&path=<?php echo rawurlencode($web_path)?>&file=<?php echo rawurlencode($next)?>&auto=1";
	}
	
	setTimeout(advance,5000); //5 seconds
	
	//-->
	</script>
	<?php } ?>
	
	<table width="<?php echo $screen_size ?>" border="0" cellpadding="0" cellspacing="0"><tr>
	<td align="left" class="crumbs" width="100"><?php if(!empty($prev)) { ?><a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($prev)?>"><?php echo $prev_icon?> <?php if($show_filename==1) echo truncate($prev,$max_file_length); else echo $l_prev;?></a><?php } ?></td>
	<td align="center" class="crumbs"><?php if(empty($_GET['auto']) && !empty($next)) { ?><a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($next)?>&amp;auto=1"><?php echo $l_slide_start;?></a><?php } else if(!empty($next)) {?><a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($HTTP_GET_VARS['file'])?>"><?php echo $l_slide_stop;?></a><?php } ?></td>
	<td align="right" class="crumbs" width="100"><?php if(!empty($next)) { ?><a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($next)?>"><?php if($show_filename==1)  echo truncate($next,$max_file_length); else echo $l_next;?> <?php echo $next_icon?></a><?php } ?></td>
	</tr></table>
	
	<img src="<?php echo $screen_path?>" border="0" <?php echo $dim; ?> alt="" />
	</td></tr></table>
	<br />
	<?php
	//if admin is turned on, display login box
	if($admin==1 && empty($_COOKIE['admin'])) {
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<tr><td class="admin_cell">
		<form action="index.php" method="post">
		<input type="hidden" name="op" value="5" />
		<input type="hidden" name="path" value="<?php if(!empty($HTTP_GET_VARS['path'])) echo $HTTP_GET_VARS['path']?>" />
		<input type="hidden" name="file" value="<?php if(!empty($HTTP_GET_VARS['file'])) echo $HTTP_GET_VARS['file']?>" />
		<span class='info'><?php echo $l_pass;?>: </span><input type="password" name="admin" size="10" class='form_text' />
		<input type="submit" value="<?php echo $l_login;?>" class='form_btn' />
		</form>
		</td></tr></table>
		<?php
	} else if($admin==1 && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		if(file_exists("./_cache/_icons/trash.gif")) {
			$trash = "<img src='./_cache/_icons/trash.gif' width='9' height='10' border='0' alt='del' />";
		} else {
			$trash = "";
		}
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<tr><td class="admin_heading" colspan="3">
		<b><?php echo $l_tools;?></b><span class='info'>&nbsp;&nbsp;&nbsp; <a href="index.php?admin=logout&amp;op=5<?php if(!empty($HTTP_GET_VARS['path'])) echo "&amp;path=".rawurlencode($HTTP_GET_VARS['path'])?><?php if(!empty($HTTP_GET_VARS['file'])) echo "&amp;file=".rawurlencode($HTTP_GET_VARS['file'])?>"><?php echo $l_logout;?></a></span>
		</td></tr>
		<tr><td class="admin_cell" rowspan="3" valign="top" width="150"><span class="info">
			<a onclick="return confirm('<?php echo $l_confirm_del;?>');" href="index.php?del=1&amp;path=<?php echo rawurlencode($web_path);?>&amp;file=<?php echo rawurlencode($HTTP_GET_VARS['file'])?>"><?php echo $l_del_photo;?> <?php echo $trash;?></a><br /><br />
			<a href="index.php?regen=1&amp;path=<?php echo rawurlencode($web_path);?>&amp;file=<?php echo rawurlencode($HTTP_GET_VARS['file'])?>"><?php echo $l_regen;?></a>
		</span></td><td class="admin_cell" valign="top" align="right" width="100"><span class='info'><?php echo $l_rename;?>: </span></td><td class="admin_cell">
			<form action="index.php" method="get">
			<input type="hidden" name="renamewhat" value="1" />
			<input type="hidden" name="path" value="<?php echo htmlspecialchars($web_path)?>" />
			<input type="hidden" name="file" value="<?php echo htmlspecialchars($HTTP_GET_VARS['file'])?>" />
			<input type="hidden" name="movepath" value="<?php echo htmlspecialchars($web_path)?>" />
			<input type="text" name="rename" size="32" class='form_text' value="<?php echo htmlspecialchars($HTTP_GET_VARS['file'])?>" />
			
		</td></tr><tr><td class="admin_cell" valign="top" align="right"><span class='info'><?php echo $l_comments;?>: </span></td><td class="admin_cell">
		
			<textarea name="keywords" cols="40" rows="4" class='form_text'><?php echo htmlspecialchars($info_keywords); ?></textarea>
			
		</td></tr><tr><td class="admin_cell" valign="top" align="right"><span class='info'><?php echo $l_rating;?>: </span></td><td class="admin_cell">
			
			<select name="rating" class="form_text">
			<option value="0" <?php if(empty($info_rating)) echo "selected='selected'"; ?>><?php echo $l_norateing;?></option>
			<option value="1" <?php if($info_rating==1) echo "selected='selected'"; ?>>*</option>
			<option value="2" <?php if($info_rating==2) echo "selected='selected'"; ?>>**</option>
			<option value="3" <?php if($info_rating==3) echo "selected='selected'"; ?>>***</option>
			<option value="4" <?php if($info_rating==4) echo "selected='selected'"; ?>>****</option>
			<option value="5" <?php if($info_rating==5) echo "selected='selected'"; ?>>*****</option>
			</select>
			<br /><br />
			<input type="submit" value="<?php echo $l_save;?>" class='form_btn' />
			</form>
			
		</td></tr></table>
		<?php
	}
	?>
	<br /><br />
	<!-- Please leave this tag here so other people can find Fotopholder.  Thanks. -->
	<div class="tag"><?php echo $l_generated;?> <a href="http://www.jakeo.com/software/fotopholder/index.php" class="tag_lnk">Fotopholder <?php echo $version?></a></div>
	</body></html>
	<?php
	exit();
}
?>


<?php
	// START DIRECTORY DISPLAY
	if(empty($web_path)) $slash="";
	else $slash = "/";
	$print_errors=0;
	
	if($admin==0 || $admin_only==0 || (!empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password))) {
		$dirs = getDirList($file_path);
	} else {
		$dirs = -3;
	}
	if($dirs==-1) {
		if($display_errors==1) echo "<div class='error'>".$l_bad_dir.": ".$file_path."</div>";
	}
	if($dirs==-2) {
		if($display_errors==1) echo "<div class='error'>".$l_bad_dir2.": ".$file_path."</div>";
		$print_errors=1;
	}
	if($dirs==-3) {
		echo "<div class='error'>".$l_private."</div>";
	}
	//tries to make the cache sub directory if its not there already
	if(is_dir($file_path) && !file_exists("./_cache/".$web_path)) {
		if($display_errors == 1) echo "<div class='error'>".$l_make_dir.": ./_cache/".$web_path."</div>";
		umask(0);
		$success = @mkdir("./_cache/".$web_path,0777);
		if(!$success) $print_errors = 1;
	}
	
	//tries to make the thumbnail sub directory if its not there already
	if(is_dir($file_path) && !file_exists("./_cache/".$web_path.$slash."_thumbnails")) {
		if($display_errors == 1) echo "<div class='error'>".$l_make_dir.": ./_cache/".$web_path.$slash."_thumbnails</div>";
		umask(0);
		$success = @mkdir("./_cache/".$web_path.$slash."_thumbnails",0777);
		if(!$success) $print_errors = 1;
	}
	
 	//tries to make the screen sub directory if its not there already
	if(is_dir($file_path) && !file_exists("./_cache/".$web_path.$slash."_screen"))  {
		if($display_errors == 1) echo "<div class='error'>".$l_make_dir.": ./_cache/".$web_path.$slash."_screen</div>";
		umask(0);
		$success = @mkdir("./_cache/".$web_path.$slash."_screen",0777);
		if(!$success) $print_errors = 1;
	}
	
	//tries to make the photoinfo sub directory if its not there already
	if(is_dir($file_path) && !file_exists("./_cache/".$web_path.$slash."_photoinfo"))  {
		if($display_errors == 1) echo "<div class='error'>".$l_make_dir.": ./_cache/".$web_path.$slash."_photoinfo</div>";
		umask(0);
		$success = @mkdir("./_cache/".$web_path.$slash."_photoinfo",0777);
		if(!$success) $print_errors = 1;
	}
	
	//tries to make the icon sub directory if its not there already
	if(!file_exists("./_cache/_icons"))  {
		if($display_errors == 1) echo "<div class='error'>".$l_make_dir.": ./_cache/_icons</div>";
		umask(0);
		$success = @mkdir("./_cache/_icons",0777);
		if(!$success) $print_errors = 1;
	}
	
	function download_icon($icon) {
		$handle = @fopen("http://jakeo.com/software/fotopholder/icons/".$icon, "rb");
		$downloaded_icon = @fread($handle, 10000);
		@fclose($handle);

		if(!empty($downloaded_icon)) {
			$handle = @fopen("./_cache/_icons/".$icon, "wb");
			@fwrite($handle,$downloaded_icon);
			@fclose($handle);
		}
	}
	//download icons if  not there
	if(!file_exists("./_cache/_icons/folder.gif")) download_icon("folder.gif");
	if(!file_exists("./_cache/_icons/trash.gif")) download_icon("trash.gif");
	if(!file_exists("./_cache/_icons/download.gif")) download_icon("download.gif");
	if(!file_exists("./_cache/_icons/prev.gif")) download_icon("prev.gif");
	if(!file_exists("./_cache/_icons/next.gif")) download_icon("next.gif");
	if(!file_exists("./_cache/_icons/star.gif")) download_icon("star.gif");
	
	if($print_errors==1 && $display_errors == 1) { //prints errors of it could make the above folders
		echo "<div class='error'>";
		echo $l_make_err;
		echo "</div>";
	}
	
	if(is_array($dirs) && sizeof($dirs)>0) {
		?><table border="0" cellpadding="0" cellspacing="0" class="dir_table"><?php
	}
	
	$dir_sub_cnt = 0;
	$dir_pho_cnt = 0;
	while (is_array($dirs) && false !== ($entry = current($dirs))) {
		$subdir = getDirList($file_path."/".$entry);
		$photos = getPhotoList("./".$web_path.$slash.$entry);
	
		if(count($subdir)==0 && count($photos)==0) {
			//safe to delete this folder because it is totally empty
			if($admin==1 && $delete_empty_folders==1 && $_COOKIE['admin']==md5($password)) {
				$worked = rmdirR($file_path."/".$entry);
				if($worked==FALSE) echo " (".$l_perm_err.")";
			}
			next($dirs);
			continue;
		}
		if(!file_exists("./_cache/".$web_path.$slash.$entry)) { //tries to make the cache sub directory if its not there already
			umask(0);
			$success = @mkdir("./_cache/".$web_path.$slash.$entry,0777);
		}
		
		if(!file_exists("./_cache/".$web_path.$slash.$entry."/_thumbnails")) { //tries to make the thumbnail sub directory if its not there already
			umask(0);
			$success = @mkdir("./_cache/".$web_path.$slash.$entry."/_thumbnails",0777);
		}
		
		//try to cache the number of photos and folders per subdirectory (if it doesnt already exist)
		if(!file_exists("./_cache/".$web_path.$slash.$entry."/info.txt")) {
			$handle = @fopen("./_cache/".$web_path.$slash.$entry."/info.txt", "wb");
			@fwrite($handle,count($photos)."\n");
			@fwrite($handle,count($subdir)."\n");
			@fclose($handle);
		}
		
		?>
		<tr><td class="dir_table_info">
			<table border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td width="15" valign="top">
			<?php if(file_exists("./_cache/_icons/folder.gif")) { ?>
				<a href="index.php?path=<?php echo rawurlencode($web_path.$slash.$entry)?>"><img src="_cache/_icons/folder.gif" width="15" height="14" border="0" align="left" alt="" /></a>
			<?php } else { ?>
				<a href="index.php?path=<?php echo rawurlencode($web_path.$slash.$entry)?>"><img src="index.php?op=4" width="15" height="14" border="0" align="left" alt="" /></a>
			<?php } ?>
			</td><td>
			<span class='folder'><a href="index.php?path=<?php echo rawurlencode($web_path.$slash.$entry)?>"><?php echo $entry?></a></span><br />
			<?php
				//tries to grab cached information about the directory
				if(file_exists("./_cache/".$web_path.$slash.$entry."/info.txt")) {
					$handle = @fopen("./_cache/".$web_path.$slash.$entry."/info.txt", "rb");
					$num_photos = @fgets($handle,1024);
					$num_dirs = @fgets($handle,1024);
					$dir_sub_cnt += $num_dirs;
					$dir_pho_cnt += $num_photos;
					@fclose($handle);
					echo "<span class='info'>".$num_photos." ".$l_photos."<br />".$num_dirs." ".$l_subfold."</span>";
				}
			?>
			</td></tr></table>
		</td>
		<td class="dir_table_thumbs">
		<?php
		if(file_exists("./".$web_path.$slash.$entry) && $num_tinys>0) {
			$count=0;
			while (is_array($photos) && false !== ($thumb = current($photos)) && $count<$num_tinys) { //cycle through photos in subdir to make tinys
				//delete too-small images
				if($admin==1 && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password) && $delete_tiny_files==1 && round(filesize($file_path."/".$entry."/".$thumb)/1024,0)<$tiny_file_max_kbytes) {
					@unlink($file_path."/".$entry."/".$thumb);
					next($photos);
					continue;
				}
		
				$count++;
				if(file_exists("./_cache/".$web_path.$slash.$entry."/_thumbnails/".$thumb))  { ?>
				<a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path.$slash.$entry)?>&amp;file=<?php echo rawurlencode($thumb)?>"><img src="_cache/<?php echo myURLencode($web_path.$slash.$entry)?>/_thumbnails/<?php echo rawurlencode($thumb)?>" width="<?php echo $tiny_size?>" height="<?php echo $tiny_size?>" title="<?php echo $l_enlarge;?>" align="middle" alt="" /></a>
				<?php } else { ?>
				<a href="index.php?op=5&amp;path=<?php echo rawurlencode($web_path.$slash.$entry)?>&amp;file=<?php echo rawurlencode($thumb)?>"><img src="index.php?op=1&amp;path=<?php echo rawurlencode($web_path.$slash.$entry)?>&amp;file=<?php echo rawurlencode($thumb)?>" width="<?php echo $tiny_size?>" height="<?php echo $tiny_size?>" title="<?php echo $l_enlarge;?>" align="middle" alt="" /></a>
				<?php 
				} 
				next($photos);
			}
		}
		next($dirs);
		if($count==$num_tinys) {
			echo "&nbsp;<a href='index.php?path=".rawurlencode($web_path.$slash.$entry)."'>".$l_more."</a></td></tr>";
		} else if($count==0) {
			if(count($subdir)==0) {
				echo "Empty";
				if($admin==1 && $delete_empty_folders==1 && $_COOKIE['admin']==md5($password)) {
					$worked = rmdirR($file_path."/".$entry);
					if($worked==FALSE) echo " (".$l_perm_err.")";
				}
			} else { 
				$count = 0;
				foreach($subdir as $sd) {
					$count++;
					if($count>$num_tinys) {
						echo "<a href='index.php?path=".rawurlencode($web_path.$slash.$entry)."'>".$l_more."</a>";
						break;
					}
					if(file_exists("./_cache/_icons/folder.gif")) { 
						?><a href="index.php?path=<?php echo rawurlencode($web_path.$slash.$entry."/".$sd)?>"><img src="_cache/_icons/folder.gif" width="15" height="14" border="0" align="left" alt="" /></a><?php 
					} else { 
						?><a href="index.php?path=<?php echo rawurlencode($web_path.$slash.$entry."/".$sd)?>"><img src="index.php?op=4" width="15" height="14" border="0" align="left" alt="" /></a><?php 
					}
				}
			}
			echo "</td></tr>";
		} else {
			echo "</td></tr>";
		}
	}
	
	if(is_array($dirs) && sizeof($dirs)>0) echo "</table>";
	
	// END DIRECTORY DISPLAY
?>


<br />

<?php
	// START PHOTO DISPLAY
	if($admin==0 || $admin_only==0 || (!empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password))) {
		$photos = getPhotoList($file_path);
	} else {
		$photos = null;
	}
	$col = 0;
	$images=0;
	$max_images = sizeof($photos);
	if($max_images < $thumbs_page) $thumbs_page = $max_images;
	
	
	if(!empty($_GET['page'])) {
		$skip = ($_GET['page']-1)*$thumbs_page;
		for($skip;$skip>0;$skip--) next($photos);
	} else {
		$_GET['page'] = 1;
	}
	
	//next and previous links
	if($max_images>$thumbs_page || $_GET['page']>1) {
		$last_page = ceil($max_images/$thumbs_page);
		?><table border="0" cellpadding="0" cellspacing="0" class="photo_table"><tr><?php
		
		if($_GET['page']>1) {
			echo "<td class='photo_table_cell_n' width='100'><a href='index.php?path=".rawurlencode($web_path)."&amp;page=".($_GET['page']-1)."'>".$l_prev_pg."</a></td>";
		}
		
		echo "<td class='photo_table_cell_n' align='center'>";
		for($i=1;$i<=$last_page;$i++) {
		 if($i==$_GET['page']) echo "<b>".$i."</b>&nbsp;&nbsp;";
		 else echo "<a href='index.php?path=".rawurlencode($web_path)."&amp;page=".$i."'>".$i."</a>&nbsp;&nbsp;";
		}
		echo "</td>";
		
		if($max_images>$_GET['page']*$thumbs_page) {
			echo "<td class='photo_table_cell_n' align='right' width='100'><a href='index.php?path=".rawurlencode($web_path)."&amp;page=".($_GET['page']+1)."'>".$l_next_pg."</a></td>";
		}
		?></tr></table><br /><?php
	}
	
	if(is_array($photos) && sizeof($photos)>0) {
		?><table border="0" cellpadding="0" cellspacing="0" class="photo_table"><?php
	}
	
	while ($images<$thumbs_page && is_array($photos) && false !== ($entry = current($photos))) {
		
		//delete too-small images
		if($admin==1 && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password) && $delete_tiny_files==1 && round(filesize($file_path."/".$entry)/1024,0)<$tiny_file_max_kbytes) {
			@unlink($file_path."/".$entry);
			next($photos);
			continue;
		}
		
		//delete cache if original has changed
		$cacheTime = @filemtime("./_cache/".$web_path.$slash."_thumbnails/".$entry);
		$srcTime = @filemtime($web_path.$slash.$entry);
		$now = time();
		if($cacheTime && $srcTime && $cacheTime<$now && $srcTime<$now && $cacheTime<$srcTime) {
			@unlink("./_cache/".$web_path.$slash."_thumbnails/".$entry);
			@unlink("./_cache/".$web_path.$slash."_screen/".$entry);
		}
		
		if($col==0) echo "<tr>";
		$col++;
		$images++;
		?>
			<td align="center" valign="top" class="photo_table_cell<?php if($col==1) echo"_l"; else if($col==$thumb_cols) echo "_r"; ?>" width="<?php echo $thumb_size?>">
				<?php 
					if(empty($web_path)) $download_path = ".".myURLencode($web_path)."/".rawurlencode($entry);
					else $download_path=myURLencode($web_path)."/".rawurlencode($entry);
					$link_path = "index.php?op=5&amp;path=".rawurlencode($web_path)."&amp;file=".rawurlencode($entry);
						
					if(file_exists("./_cache/".$web_path.$slash."_thumbnails/".$entry))  {
				?>
					<a href="<?php echo $link_path; ?>"><img src="_cache/<?php echo myURLencode($web_path)?>/_thumbnails/<?php echo rawurlencode($entry)?>" width="<?php echo $thumb_size?>" height="<?php echo $thumb_size?>" title="<?php echo $l_enlarge;?>" class="photo_thumb" alt="" /></a><br />
				<?php } else { ?>
					<a href="<?php echo $link_path; ?>"><img src="index.php?op=1&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($entry)?>" width="<?php echo $thumb_size?>" height="<?php echo $thumb_size?>" title="<?php echo $l_enlarge;?>" class="photo_thumb" alt="" /></a><br />
				<?php } ?>
				<span class='info'>
				<?php if($show_filename==1) echo truncate($entry,$max_file_length)."<br />"; ?>				
				<?php 
					if($display_rating) {
						if(file_exists("./_cache/_icons/star.gif")) {
							$star_icon = "<img src='./_cache/_icons/star.gif' width='11' height='8' border='0' alt='*' />";
						} else {
							$star_icon = "*";
						}
						if(file_exists("./_cache/".$web_path.$slash."/_photoinfo/".$entry.".txt")) {
							$handle = @fopen("./_cache/".$web_path.$slash."/_photoinfo/".$entry.".txt", "rb");
							$info_rating = @fgets($handle,1024);
							for($i=0;$i<$info_rating;$i++) echo $star_icon;
							if($info_rating>0) echo "<br />";
							@fclose($handle);
						}
					}
				?>
				
				<a href="<?php echo $download_path; ?>"><?php echo $l_download2;?></a>
				(<?php echo round(filesize($file_path."/".$entry)/1024,0)?> k)
				<?php 
					if(file_exists("./_cache/_icons/trash.gif")) {
						$trash = "<img src='./_cache/_icons/trash.gif' width='9' height='10' border='0' alt='del' />";
					} else {
						$trash = "del";
					}
				?>
				<?php if($admin==1 && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) { ?><a onclick="return confirm('<?php echo $l_confirm_del;?>');" href="index.php?del=1&amp;path=<?php echo rawurlencode($web_path)?>&amp;file=<?php echo rawurlencode($entry)?>&amp;page=<?php echo $_GET['page']?>"><?php echo $trash;?></a><?php } ?>
				</span> 
			</td>
		<?php
		next($photos);
		
		if($col==$thumb_cols && current($photos)) {
			echo "</tr><tr><td class='photo_table_spacer' colspan='".$thumb_cols."'></td></tr>";
			$col=0;
		}
	}

	if($col>0) {
		for($col;$col<$thumb_cols;$col++) echo "<td class='photo_table_cell_e' width='".$thumb_size."'>&nbsp;</td>";
		echo "</tr>";
	}

	//empty folder
	if(count($photos)==0 && count($dirs)==0) {
		echo "<tr><td colspan='".$thumb_cols."'><span class='folder'><br />".$l_fol_empty."<br /><br /></span></td></tr>";
	}
	
	//try to cache the number of photos and folders per directory
	$handle = @fopen("./_cache/".$web_path.$slash."info.txt", "wb");
	@fwrite($handle,(count($photos)+$dir_pho_cnt)."\n");
	@fwrite($handle,(count($dirs)+$dir_sub_cnt)."\n");
	@fclose($handle);
	
	if($images>0) echo "</table>";
	
	//next and previous links
	if($max_images>$thumbs_page || $_GET['page']>1) {
		$last_page = ceil($max_images/$thumbs_page);
		?><br /><table border="0" cellpadding="0" cellspacing="0" class="photo_table"><tr><?php
		
		if($_GET['page']>1) {
			echo "<td class='photo_table_cell_n' width='100'><a href='index.php?path=".rawurlencode($web_path)."&amp;page=".($_GET['page']-1)."'>".$l_prev_pg."</a></td>";
		}
		
		echo "<td class='photo_table_cell_n' align='center'>";
		for($i=1;$i<=$last_page;$i++) {
		 if($i==$_GET['page']) echo "<b>".$i."</b>&nbsp;&nbsp;";
		 else echo "<a href='index.php?path=".rawurlencode($web_path)."&amp;page=".$i."'>".$i."</a>&nbsp;&nbsp;";
		}
		echo "</td>";
		
		if($max_images>$_GET['page']*$thumbs_page) {
			echo "<td class='photo_table_cell_n' align='right' width='100'><a href='index.php?path=".rawurlencode($web_path)."&amp;page=".($_GET['page']+1)."'>".$l_next_pg."</a></td>";
		}
		?></tr></table><?php
	}
	
	// END PHOTO DISPLAY
	
?>

<br />
<?php
	if(empty($web_path) && file_exists("./_cache/friend.txt")) {
	?>
	
	<table border="0" cellpadding="0" cellspacing="0" class="friend_table">
		<tr><td class="friend_heading"><?php echo $l_friend;?></td></tr>
		<tr><td class="friend_cell">
		<?php
		$handle = fopen("./_cache/friend.txt", "rb");
		$url = @fgets($handle,1024);
		$title = @fgets($handle,1024);
		while($url) {
			?>
			<a href="<?php echo $url?>"><?php echo $title?></a><br />
			<?php
			$url = @fgets($handle,1024);
			$title = @fgets($handle,1024);
		}
		fclose($handle);
		?>
		</td></tr>
	</table>
		
	<?php
	}
?>
<br />

<?php
	//if admin is turned on, display login box
	if($admin==1 && empty($_COOKIE['admin'])) {
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<tr><td class="admin_cell">
		<form action="index.php" method="post">
		<input type="hidden" name="path" value="<?php if(!empty($HTTP_GET_VARS['path'])) echo $HTTP_GET_VARS['path']?>" />
		<span class='info'><?php echo $l_pass;?>: </span><input type="password" name="admin" size="10" class='form_text' />
		<input type="submit" value="<?php echo $l_login;?>" class='form_btn' />
		</form>
		</td></tr></table>
		<?php
	} else if($admin==1 && !empty($_COOKIE['admin']) && $_COOKIE['admin']==md5($password)) {
		if(file_exists("./_cache/_icons/trash.gif")) {
			$trash = "<img src='./_cache/_icons/trash.gif' width='9' height='10' border='0' alt='del' />";
		} else {
			$trash = "";
		}
		
		if(!empty($_GET['cache']) && $no_screen==0) {
			?>
			<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
			<tr><td class="admin_heading"><?php echo $l_gen_cache;?></td></tr>
			<tr><td class="admin_cell">
				<?php 
				$photos = getPhotoList($file_path);
				while (is_array($photos) && false !== ($entry = current($photos))) {
					echo "<img src='index.php?op=1&amp;path=".rawurlencode($web_path)."&amp;file=".rawurlencode($entry)."' width='10' height='10' alt='' />";
					echo "<img src='index.php?op=2&amp;path=".rawurlencode($web_path)."&amp;file=".rawurlencode($entry)."' width='10' height='10' alt='' />";
					next($photos);
				}
				?>
			</td></tr>
			</table><br />
		<?php } ?>
		
		<?php
		if(!empty($_GET['archive'])) {
			$command = "tar -czvf ".escapeshellarg("_cache/".$web_path.".tgz")." ".escapeshellarg($web_path);
			exec($command);
			?>
			<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
			<tr><td class="admin_heading"><?php echo $l_gen_zip;?></td></tr>
			<tr><td class="admin_cell"><?php echo $l_complete;?></td></tr>
			</table><br />
		<?php } ?>
		
		<table border="0" cellpadding="0" cellspacing="0" class="admin_table">
		<tr><td class="admin_heading" colspan="2">
		<?php echo $l_tools;?><span class='info'>&nbsp;&nbsp;&nbsp; <a href="index.php?admin=logout<?php if(!empty($HTTP_GET_VARS['path'])) echo "&amp;path=".rawurlencode($HTTP_GET_VARS['path'])?>"><?php echo $l_logout;?></a></span>
		</td></tr>
		<tr><td class="admin_cell"><span class='info'>
		<?php if(!empty($web_path)) {  ?>
			<a onclick="return confirm('<?php echo $l_confirm_del2;?>');" href="index.php?del=1&amp;path=<?php echo rawurlencode($web_path);?>"><?php echo $l_del_folder;?> <?php echo $trash;?></a><br /><br />
		<?php } ?>
		<a href="index.php?cache=1&amp;path=<?php echo rawurlencode($web_path);?>"><?php echo $l_gen_cache2;?></a><br />
		<a href="index.php?archive=1&amp;path=<?php echo rawurlencode($web_path);?>"><?php echo $l_gen_zip2;?></a><br />
		<a href="index.php?flatten=1&amp;path=<?php echo rawurlencode($web_path);?>"><?php echo $l_flatten;?></a><br />
		
		</span></td><td class="admin_cell">
		<?php if(!empty($web_path)) { 
			$array =split("/",$web_path);
			$filename = array_pop($array);
			$rename_path = implode("/",$array);
			$curdir = array_pop($array);
			$aswego="";
			?>
			
			<form action="index.php" method="get">
			<input type="hidden" name="renamewhat" value="2" />
			<input type="hidden" name="path" value="<?php echo htmlspecialchars($rename_path)?>" />
			
			<?php if(!empty($rename_path)) { ?>
			<span class='info'><?php echo $l_move_to;?>: </span>
			<select name="movepath">
			<option value=""><?php echo $l_move_top;?></option>
			<?php foreach($array as $p) { 
				if(!empty($aswego)) $aswego.="/".htmlspecialchars($p); else $aswego=htmlspecialchars($p);
			?>
			<option value="<?php echo $aswego?>"><?php echo htmlspecialchars($p)?></option>
			<?php } ?>
			<option value="<?php echo $aswego."/".htmlspecialchars($curdir)?>" selected="selected"><?php echo $l_no_move;?></option>
			</select>
			<?php } else { ?>
			<input type="hidden" name="movepath" value="<?php echo htmlspecialchars($rename_path)?>" />
			<?php } ?>
			
			<input type="hidden" name="file" value="<?php echo htmlspecialchars($filename)?>" />
			<span class='info'><?php echo $l_rename_fold;?>: </span><input type="text" name="rename" size="32" class="form_text" value="<?php echo htmlspecialchars($filename);?>" />
			<input type="submit" value="Rename &amp; Move" class='form_btn' />
			</form>
		<?php } else { ?>
			<span class="info_bold"><?php echo $l_add_friend;?></span><br /><br />
			
			<form action="index.php" method="get">
			<span class='info'><?php echo $l_url;?>: </span>
			<input type="text" name="friend" size="32" class='form_text' value="http://" />
			<input type="submit" value="<?php echo $l_add_friend2;?>" class='form_btn' />
			</form>
		<?php } ?>
		
			</td></tr></table>
	<?php 
	}
?>
<?php if(file_exists("_cache/".$web_path.".tgz")) echo "<br /><div class='tag'><a href='_cache/".$web_path.".tgz'>".$l_download_zip."</a></div>"; ?>

<br /><br />
<!-- Please leave this tag here so other people can find Fotopholder.  Thanks. -->
<div class="tag"><?php echo $l_generated;?> <a href="http://www.jakeo.com/software/fotopholder/index.php" class="tag_lnk">Fotopholder <?php echo $version?></a>

<?php
//This checkes the version number to see if there are any updates that you can install.  
//It also sends the number of photos that you are using this script to manage.  The sole purpose
//of this is so that we can display the total number of users and photos on the fotopholder website.
//This information cannot be linked back to you and no personally identifable information is sent from your computer.
//If you choose to enable the gallery link setting this will send the URL of your site so we can link to you on the FotoPholder website.
$non_personal_identifier = md5($_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"]);
if($gallery_link==1 && !empty($gallery_link_url)) $extra = "&site=".$gallery_link_url;

if(file_exists("./_cache/info.txt")) {
	$handle = @fopen("./_cache/info.txt", "rb");
	$total_photos = trim(@fgets($handle,1024));
	@fclose($handle);
}	
$handle = @fopen("http://www.jakeo.com/software/fotopholder/icons/version.php?id=".$non_personal_identifier."&photos=".$total_photos.$extra, "rb");
$current_version = trim(@fgets($handle,10));

if($version!=$current_version) {
	$whats_new = @fgets($handle,1024);
	echo "<br />".$whats_new;
}
@fclose($handle);
?>
</div>
<br />
</body></html>
<?php } ?>