<?php 
/* CLASS :
	Fonction mère. Permet la transformation des images.
	Reçois en paramètre l'image et le type de tranformation voulu.
*/

class Pixellisation{

	private $image;

	//taille d'un gros pixel
	private $dimention_grospixel;


	/* FONCTION CONSTRUCTEUR: 
		Passage de paramètre type de traitement
		Appelle la fonction principale
	 */


	public function traitementImage($url_image_base,$taillePixel,$typeTraitement){

		//creation de l'image virtuel de traitement
		$this->image = imagecreatefrompng($url_image_base);

		$this->dimention_grospixel = $taillePixel;

		list($width, $height) = getimagesize($url_image_base);

		//variable qui vont servir de pointeur
		$traceur_x = 0;
		$traceur_y = 0;

		//POUR : la recuperation d'une couleur clé sur chaques colonne
		for ($y = $this->dimention_grospixel/2 ; $y < $height ; $y = $y+$this->dimention_grospixel){
			//POUR : la recuperation d'une couleur clé sur toute la ligne
			for ($x = $this->dimention_grospixel/2 ; $x < $width ; $x = $x+$this->dimention_grospixel){


				switch($typeTraitement){

					case 'carre':
						$this->traitementCarre($x,$y,$traceur_x,$traceur_y);
						break;
					case '2triangles':
						$this->traitement2Triangles($x,$y,$traceur_x,$traceur_y);
						break;
					case '3triangles':
						# code...
						break;
					case '4triangles':
						# code...
						break;
					case 'croix':
						# code...
						break;
					default:
						$this->traitementCarre($x,$y,$traceur_x,$traceur_y);
						break;
				}

				//modifier la localisation du pointeur
				$traceur_x = $traceur_x+$this->dimention_grospixel;
				
			}
			//changement de ligne, modification des pointeurs
			$traceur_y = $traceur_y+$this->dimention_grospixel;	
			$traceur_x = 0;
		}

		return $this->image;

	}

	//Colorisation de tout le carre en une couleur unique
	private function traitementCarre($x,$y,$traceur_x,$traceur_y){

		$couleur_pixel = $this->getColor($x,$y);

		//POUR : chaques colonne d'un grospixel a remplir
		for($yy=0 ; $yy < $this->dimention_grospixel ; $yy++){
			//POUR : chaque ligne d'un grospixel à remplir			
			for($xx=0 ; $xx < $this->dimention_grospixel ; $xx++){
				//modification de la couleur du pixel
				imagesetpixel($this->image, $xx+$traceur_x, $yy+$traceur_y ,$couleur_pixel);
			}	
		}
	}

	private function traitement2Triangles($x,$y,$traceur_x,$traceur_y){

		$couleur_pixel_g = $this->getColor($x-($this->dimention_grospixel/4),$y);
		$couleur_pixel_d = $this->getColor($x+($this->dimention_grospixel/4),$y);

		// Si sens reste a zero de gauche a droite, sinon de droite a gauche
		$gestion_sens = 0;
		if ( rand(0,1) == 1){
			$gestion_sens = $this->dimention_grospixel;
		}

		//POUR : chaques colonne d'un grospixel a remplir
		for($yy=0 ; $yy < $this->dimention_grospixel ; $yy++){
			//POUR : chaque ligne d'un grospixel à remplir			
			for($xx=0 ; $xx < $this->dimention_grospixel ; $xx++){

				if ($xx < abs($gestion_sens-$yy)){
					imagesetpixel($this->image, $xx+$traceur_x, $yy+$traceur_y ,$couleur_pixel_g);
				} else {
					imagesetpixel($this->image, $xx+$traceur_x, $yy+$traceur_y ,$couleur_pixel_d);
				}
			}	
		}


	}

	private function getColor($x,$y){

		//recuperation de la couleur du pixel ciblé
		$index_couleur_pixel = imagecolorat($this->image,$x,$y);
		$array_couleur_pixel = imagecolorsforindex($this->image, $index_couleur_pixel);
		$couleur_pixel = imagecolorresolve($this->image, $array_couleur_pixel["red"],$array_couleur_pixel["green"],$array_couleur_pixel["blue"]);

		return $couleur_pixel;
	}

	
}

?>