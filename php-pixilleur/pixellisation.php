<?php

	//variables d'arrivé
	$url_image_base = $_GET['url_img_base'];
	$url_repertoire_img = $_GET['url_repertoire'];
	$code_reference = $_GET['code_ref'];
	$numero_img  = $_GET['numero_img'];
	$nbr_grospixel = $_GET['nbrpixel'];

	//variable de retour
	$url_image_pixilisee = "";

	//taille d'un gros pixel
	$dimention_grospixel = 0;

	//variable qui vont servir de pointeur
	$traceur_x = 0;
	$traceur_y = 0;

	//creation du nom et du chemin futur de l'image de traitement
	$url_image_pixilisee = $url_repertoire_img."/pixilleur-img-".$numero_img."-".$code_reference.".png";

<<<<<<< Updated upstream

	//creation de l'image virtuel de traitement
	$image = imagecreatefrompng($url_image_base);


	// CALCUL DE LA LONGUEUR LA PLUS GRANDE ET DE LA TAILLE DU GROSPIXEL
	list($width, $height) = getimagesize($url_image_base);
	
	if ($width < $height){
		$dimention_grospixel = (int)($height/$nbr_grospixel);
	} else {
		$dimention_grospixel= (int)($width/$nbr_grospixel);
	}

	//POUR : la recuperation d'une couleur clé sur chaques colonne
	for ($y = $dimention_grospixel/2 ; $y < $height ; $y = $y+$dimention_grospixel){
		//POUR : la recuperation d'une couleur clé sur toute la ligne
		for ($x = $dimention_grospixel/2 ; $x < $width ; $x = $x+$dimention_grospixel){

			//recuperation de la couleur du pixel ciblé
			$index_couleur_pixel = imagecolorat($image,$x,$y);
			$array_couleur_pixel = imagecolorsforindex($image, $index_couleur_pixel);
			$couleur_pixel = imagecolorresolve($image, $array_couleur_pixel["red"],$array_couleur_pixel["green"],$array_couleur_pixel["blue"]);


			//POUR : chaques colonne d'un grospixel a remplir
			for($yy=0 ; $yy < $dimention_grospixel ; $yy++){
				//POUR : chaque ligne d'un grospixel à remplir			
				for($xx=0 ; $xx < $dimention_grospixel ; $xx++){
					//modification de la couleur du pixel
					imagesetpixel($image, $xx+$traceur_x, $yy+$traceur_y ,$couleur_pixel);
				}	
			}
			//modifier la localisation du pointeur
			$traceur_x = $traceur_x+$dimention_grospixel;
			
		}
		//changement de ligne, modification des pointeurs
		$traceur_y = $traceur_y+$dimention_grospixel;	
		$traceur_x = 0;
	}

=======
	$typeTraitement = "carre";

	$pixelleur = new Pixellisation();
	$image = $pixelleur->TraitementImage($url_image_base,$taille_grospixel,$typeTraitement);
>>>>>>> Stashed changes

	//###################
	// ENREGISTREMENT 
	//###################
	$reponse = "erreur : l'image ne c'est pas enregistrée";
	if(imagepng( $image, $url_image_pixilisee)){
		$reponse = "taille du pixel : "+$dimention_grospixel; 
	}
	
	imagedestroy($image); //détruit l'image, libérant ainsi de la mémoire
	
	//###################
	// RETOURNER LES VALEURS NESSESSAIRES
	//###################
	$retour = array(
		'resultat'      => $reponse,
		'url_image_pixilisee'	=> $url_image_pixilisee
	);
	 
	// Envoi du retour (on renvoi le tableau $retour encodé en JSON)
	header('Content-type: application/json');
	echo json_encode($retour);
?>