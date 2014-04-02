<?php  
  
    /* 
	########################
	MODIFICATEUR D'EXETENTION D'IMAGE VERS DU PNG
	########################
	*
    * - $img_src_chemin : Le chemin de l'image source, l'image qui va être transphormée.
	* - $img_src_destination : Le chemin où sera placer l'image.
    * - $extension : Extention de l'image de base. Afin de tester celle-ci 
	*
	* - $img_src_resource : On charge l'image en fonction de son extention pour modification.
    */

 function test_extention($img_src_chemin,$img_src_destination){
	 
	$extension = strtolower(  substr(  strrchr($img_src_chemin, '.')  ,1)  );
	 
	switch ( $extension ) {
	
		case "jpg":
			$img_src_resource = imagecreatefromjpeg( $img_src_chemin );
			unlink($img_src_chemin);
			modif_upload_png($img_src_resource,$img_src_destination);
			break;
		case "jpeg": //pour le cas où l'extension est "jpeg"
			$img_src_resource = imagecreatefromjpeg( $img_src_chemin );
			unlink($img_src_chemin);
			modif_upload_png($img_src_resource,$img_src_destination);
			break;
	
		case "gif":
			$img_src_resource = imagecreatefromgif( $img_src_chemin );
			unlink($img_src_chemin);
			modif_upload_png($img_src_resource,$img_src_destination);
			break;
	
		case "png":
			break;
	
		// On peut également ouvrir les formats wbmp, xbm et xpm (vérifier la configuration du serveur)
	
		default:
			echo "L'image n'est pas dans un format reconnu. Extensions autorisées : jpg/jpeg, gif, png";
			echo "veuillez réessayer";
			unlink($img_src_chemin);
			break;
	}
 }
 
 function modif_upload_png($img_src_resource,$img_src_destination){
  		imagepng($img_src_resource,$img_src_destination);
		imagedestroy($img_src_resource);
  }
?>