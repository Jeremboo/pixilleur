<?php

$dossier_image = new DirectoryIterator('../depot-images-pixilleur/');

foreach($dossier_image as $dossier){
	if($dossier->isDir() && (time() - $dossier->getMTIME() > 1800) && $dossier != ".." && $dossier != "."){
		suppDossier($dossier->getPathname());
	}
}

function suppDossier($url_repertoire){

		$dir=opendir($url_repertoire);
		//2)Tant que le dossier n'est pas vide
		while ($fichier = readdir($dir))
		{
				//3) Sans compter . et ..
				if ($fichier != "." && $fichier != "..")
				{
						//On selectionne le fichier et on le supprime
						$vidage= $url_repertoire."/".$fichier;
						unlink($vidage);
				}
		}
		//Fermer le dossier vide
		closedir($dir);
		//Supprimer le dossier
		rmdir($url_repertoire);

	}

?>