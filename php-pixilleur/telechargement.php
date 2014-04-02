<?php

$_FILES['fichier-image']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_fichier-image.png).
$_FILES['fichier-image']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
$_FILES['fichier-image']['size'];     //La taille du fichier en octets.
$_FILES['fichier-image']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
$_FILES['fichier-image']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.

include ("modifpng.php");

//###################
//PARAMETRES
//###################

//variables limites
$maxsize = 1048576;
$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );

//variables de donnée de l'image
$extension_upload = "";

//variables alternatives
$code_reference = "";
$nom_image_base = "pixilleur-base";

//variables pour retour
$url_repertoire_img = "../depot-images-pixilleur/";
$url_image_base = "";
$erreur = "";



//###################
//TEST ET TELECHARGEMENT DE L'IMAGE
//###################

//si une erreur est détectée pendant le transfert.
if ($_FILES['fichier-image']['error'] > 0) $erreur = "Erreur lors du transfert, l'image est certainement trop volumineuse. ERREUR :".$_FILES['fichier-image']['name'];
else {

	//1. strrchr renvoie l'extension avec le point (« . »).
	//2. substr(chaine,1) ignore le premier caractère de chaine.
	//3. strtolower met l'extension en minuscules.
	$extension_upload = strtolower(  substr(  strrchr($_FILES['fichier-image']['name'], '.')  ,1)  );
	
	//si l'image est trop lourde.
	if ($_FILES['fichier-image']['size'] > $maxsize) $erreur = "Le fichier est trop gros";
	else {

		//si l'extension du fichier est bonne.
		if ( in_array($extension_upload,$extensions_valides) ){
			
			//creation d'un code unique pour l'enssemble des manipulations
			$code_reference = md5(uniqid(rand(), true));

			//creation d'un répertoire
			$url_repertoire_img = $url_repertoire_img."pixilleur-".$code_reference;
			if(!@mkdir($url_repertoire_img,0777)) $erreur = "Erreur du programme, le repertoire n'a pas pu être crée";
			else {
				
				//deplacement de l'image
				$nom_image_base = $nom_image_base."-".$code_reference;
				$url_image_base = $url_repertoire_img."/".$nom_image_base;
	
				$deplacement = move_uploaded_file($_FILES['fichier-image']['tmp_name'],$url_image_base.".".$extension_upload);
				if ($deplacement){
	
					//modification de l'extention du fichier et suite du programme (appel fonction dans 'modifjpg.php')
					test_extention($url_image_base.".".$extension_upload,$url_image_base.".png");
					//completer l'url de l'image de base
					$url_image_base = $url_image_base.".png";
			
				} else {
					$erreur = "Le transfert de l'image à échoué...";
				}
			}
		} else {
			$erreur = "Exetension incorrecte, essayez avec une autre image (.jpg, .png, .gif accepté)";
		}	
	}	
}


//###################
// RETOUR
//###################

$retour = array(
	'erreur'      => $erreur,
	'url_repertoire'	=>$url_repertoire_img,
	'url_image_base'	=>$url_image_base,
	'code_ref'	=>$code_reference
);
	
header('Content-type: text/html');
echo json_encode($retour); 


?>