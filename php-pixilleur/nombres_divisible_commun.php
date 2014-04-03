<?php

	//variables d'arrivé
	$url_image_base = $_GET['url_img_base'];

	include('class-pixilleur/TaillesCommunes.class.php');

	//recuperer la taille de l'image.
	$image_sizes = getimagesize($url_image_base);

	$width = $image_sizes[0];
	$height = $image_sizes[1];

	$tbl_tailles_communes = array();

	//Définition de la valeur de référence
	//La plus petite longueur est référete car elle doit être le moin modifiée possible
	if($width > $height){
		$CalculateurTC = new TaillesCommunes($height,$width);
		$width = $CalculateurTC->GetLongueurSec();
		$height = $CalculateurTC->GetLongueurRef();
	} else {
		$CalculateurTC = new TaillesCommunes($width,$height);
		$width = $CalculateurTC->GetLongueurRef();
		$height = $CalculateurTC->GetLongueurSec();
	}

	//###################
	// RETOURNER LES VALEURS NESSESSAIRES
	//###################
	$retour = array(
		'tbl_diviseurs_commun'	=> $CalculateurTC->GetTbl(),
		'img_width'      => $width,
		'img_height'	=> $height
	);

	// Envoi du retour (on renvoi le tableau $retour encodé en JSON)
	header('Content-type: application/json');
	echo json_encode($retour);
	
?>