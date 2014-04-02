<?php

	$url_repertoire = $_GET['url_dossier'];

	include("netoyage.php");


	//pour ne pas risquer de supprimer 
	if ($url_repertoire != "../depot-images-pixilleur/"){

		suppDossier($url_repertoire);

	}

?>