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




	/* 
	 * Permet de calculer un longueur et largeur proche de celle donnée avec minimum 5 nombres divisible par la longueur et largeur.
	 * 
	 * -- $nombre_donne_x/$nombre_donne_y : Les nombres de départ. longueur:x largeur:y Ils sont modifié par la fonction.
	 * - $tableau_diviseur : tableau contenant les nombres divisibles par les deux longueurs résultantes.
	 */

	function nombres_divisible_commun(&$tableau_diviseur,&$nombre_donne_x,&$nombre_donne_y){

		$tableau_diviseur = array(); //initialisation

		// diviseurs_commun == la fonction qui va modifier les valeurs X et Y.
		if($nombre_donne_y < $nombre_donne_x){
			//alors la longueur X va servir de référence
			diviseurs_commun($tableau_diviseur,$nombre_donne_x,$nombre_donne_y);
		} else {
			//alors la longueur X va servir de référence
			diviseurs_commun($tableau_diviseur,$nombre_donne_y,$nombre_donne_x);
		}
		//FIN : le tableau de diviseur est prêt et les valeurs aussi.	
	}

	function diviseurs_commun(&$tab_div_commun,&$grande_longueur,&$petite_longueur){	
		//variable parametre
		$petite_longueur_min = $petite_longueur - 50;
		$nbr_diviseurs_commun = 5;

		//variable de traitement
		$tab_div_traitement = array();
		$petite_longueur_traitement = $petite_longueur;


		//APPEL : pour remplir le tableau de div de la grande longueur
		lister_diviseurs_grande_longuer($grande_longueur,$tab_div_traitement);
		//TANT QUE : il n'y a pas 5 diviseurs commun avec 
		//           la liste de diviseur de la longueur principale.
		while(count($tab_div_commun) < $nbr_diviseurs_commun){
			//initialisation de valeurs
			$tab_div_commun = array();

			//SI : après plusieurs passage la petite longueur est devenur + petite de 50px
			if($petite_longueur_traitement == $petite_longueur_min || $petite_longueur_traitement == 0){
				//ALORS : 
				//on remet la petite longueur initiale
				$petite_longueur_traitement = $petite_longueur;
				//on réduit la grande longueur et on recommence
				$grande_longueur--;
					
				lister_diviseurs_grande_longuer($grande_longueur,$tab_div_traitement);
			}

			//POUR : chaques diviseurs compris dans le tableau
			foreach ($tab_div_traitement as $diviseur) {
				//SI : la petite longueur est divisible par la même valeur
				//     que la division de la grande longueur par le nombre
				if($petite_longueur_traitement%($grande_longueur/$diviseur) == 0 && count($tab_div_commun) != $nbr_diviseurs_commun ){
					//ALORS : Le nombre est bon on le sauvegarde
					array_push($tab_div_commun, $diviseur);
				}
			}

			//SI : aucun diviseur n'était bon on essaie une plus petite longueur.
			if(count($tab_div_commun) < $nbr_diviseurs_commun) $petite_longueur_traitement--;
		}
		//FIN : le tableau est bon
		$petite_longueur = $petite_longueur_traitement;
	}


	//Fonction qui remplis un tableau de 10 diviseurs max pas plus grand que 80.
	function lister_diviseurs_grande_longuer(&$longueur,&$tbl_div){

		//initialisation du tableau
		$tbl_div = array();

		//variables parametre
		$valeur_max = 65;
		$nbr_diviseur_min = 5;
		$nbr_diviseur_max = 10;

		//variables compteur
		$valeur = 4; //car au minimum je veux un 4

		//TANT QUE : pas + de 10 diviseurs ET pas de valeurs + grande que valeur max
		while ( count($tbl_div) < $nbr_diviseur_max && $valeur < $valeur_max) {
			
			//SI : on obtien un diviseur absolu 
			if($longueur%$valeur == 0){
				//ALORS : on enregistre la valeur dans le tableau
				array_push($tbl_div, $valeur);
			}
			$valeur++;
		}
		//SI : il n'y a pas de valeur OU moin du minimum
		if(empty($tbl_div) || count($tbl_div) < $nbr_diviseur_min ){
			//ALORS : on réduit la longueur qui ne peut pas être utilisée et on recommence
			$longueur--;
			//On rappelle la fonction
			lister_diviseurs_grande_longuer($longueur,$tbl_div);
		}
	}
	
?>