<?php

	//variables d'arrivé
	$url_image_base = $_GET['url_img_base'];
	$url_repertoire_img = $_GET['url_repertoire'];
	$code_reference = $_GET['code_ref'];
	$numero_img  = $_GET['numero_img'];
	$taille_grospixel = $_GET['nbrpixel'];

	include('class-pixilleur/Pixellisation.class.php');

	//variable de retour
	$url_image_pixilisee = "";

	//taille d'un gros pixel
	$dimention_grospixel = 0;

	//variable qui vont servir de pointeur
	$traceur_x = 0;
	$traceur_y = 0;

	//creation du nom et du chemin futur de l'image de traitement
	$url_image_pixilisee = $url_repertoire_img."/pixilleur-img-".$numero_img."-".$code_reference.".png";


	$typeTraitement = "carre";

	$pixelleur = new Pixellisation();


	$image = $pixelleur->TraitementImage($url_image_base,$taille_grospixel,$typeTraitement);

	//###################
	// ENREGISTREMENT 
	//###################
	$reponse = "erreur : l'image ne c'est pas enregistrée";
	if(imagepng( $image, $url_image_pixilisee))
		$reponse = "taille du pixel : "+$dimention_grospixel; 
	
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