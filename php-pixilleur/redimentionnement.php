<?php

	$url_image_base = $_GET['url_img_base'];
	$width_img = $_GET['width'];
	$height_img = $_GET['height'];

	redimentionnment($url_image_base,$width_img,$height_img);
    
    /* REDIMENTIONNE L'IMAGE EN SUPPRIMANT LE TROP

    * - $url_image : Le chemin de l'image source, l'image qui va être recadrée
    * - $width : La largeur du cadre
    * - $height : La hauteur du cadre
    */
    
	function redimentionnment($url_image, $width, $height){

		//variable pour recuperer les dimentions originales
		list($width_orig, $height_orig) = getimagesize($url_image);

		//variables pour recentrer l'image
		$dest_x = ($width_orig - $width)/2;
		$dest_y = ($height_orig - $height)/2;

		//dest
		$image=imagecreatetruecolor($width,$height);
		imagealphablending($image,false);
 		imagesavealpha($image,true)		;

 		//gestion de l'alpha
 		$transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
		imagefilledrectangle($image, 0, 0, imagesx($image)-1, imagesy($image)-1, $transparent);
		imagealphablending($image, true);
		
		// Création de l'image de destination.
		$image_dest = imagecreatefrompng($url_image);
	 	imagecopyresampled($image, $image_dest, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		
		//enregistrement
		imagepng( $image, $url_image );
	
		imagedestroy($image); //détruit l'image, libérant ainsi de la mémoire
		imagedestroy($image_dest);
	}
?>