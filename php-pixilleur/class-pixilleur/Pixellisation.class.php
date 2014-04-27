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

		//POUR : la recuperation d'une couleur clé sur chaques colonne
		for ($y = 0 ; $y < $height ; $y = $y+$this->dimention_grospixel){
			//POUR : la recuperation d'une couleur clé sur toute la ligne
			for ($x = 0 ; $x < $width ; $x = $x+$this->dimention_grospixel){

				switch($typeTraitement){

					case 'carre':
						$this->traitementCarre($x,$y);
						break;
					case '2triangles':
						$this->traitement2Triangles($x,$y);
						break;
					case '4triangles':
						$this->traitement4Triangles($x,$y);
						break;
					case '4carres':
						$this->traitement4Carres($x,$y);
						break;
					case 'ramdom':
						$ramdomTraitement = rand(0,3);
						switch($ramdomTraitement){
							case 0:
								$this->traitementCarre($x,$y);
								break;
							case 1:
								$this->traitement2Triangles($x,$y);
								break;
							case 2:
								$this->traitement4Triangles($x,$y);
								break;
							case 3:
								$this->traitement4Carres($x,$y);
								break;
							default:
								$this->traitementCarre($x,$y);
								break;
						}
						break;
					default:
						$this->traitementCarre($x,$y);
						break;
				}
			}
		}

		return $this->image;

	}

	//Colorisation de tout le carre en une couleur unique
	private function traitementCarre($x,$y){

		$couleur_pixel = $this->getColor($x+$this->dimention_grospixel/2,$y+$this->dimention_grospixel/2);

		$lim_x = $x+$this->dimention_grospixel;
		$lim_y = $y+$this->dimention_grospixel;
		$sauv_x = $x;

		//POUR : chaque colonne d'un grospixel à remplir
		for($y ; $y < $lim_y ; $y++){
			//POUR : chaque ligne d'un grospixel à remplir			
			for($x ; $x < $lim_x ; $x++){
				//modification de la couleur du pixel
				imagesetpixel($this->image, $x, $y ,$couleur_pixel);
			}
			$x = $sauv_x;	
		}
	}

	private function traitement2Triangles($x,$y){

		$couleur_pixel_g = $this->getColor($x+($this->dimention_grospixel/4),$y+$this->dimention_grospixel/2);
		$couleur_pixel_d = $this->getColor($x+(($this->dimention_grospixel/4)*3),$y+$this->dimention_grospixel/2);

		$lim_x = $x+$this->dimention_grospixel;
		$lim_y = $y+$this->dimention_grospixel;
		$sauv_x = $x;

		if ( rand(0,1) == 1){

			//POUR : chaque colonne d'un grospixel a remplir
			for($y ; $y < $lim_y ; $y++){
				//POUR : chaque ligne d'un grospixel à remplir			
				for($x ; $x < $lim_x ; $x++){

					if ($lim_x-$x > $lim_y-$y){
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_g);
					} else {
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_d);
					}
				}
				$x = $sauv_x;	
			}

		} else {

			for($y ; $y < $lim_y ; $y++){		
				for($x ; $x < $lim_x ; $x++){

					if ($lim_x-$x > $this->dimention_grospixel-($lim_y-$y)){
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_g);
					} else {
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_d);
					}
				}
				$x = $sauv_x;
			}
		}
	}

	private function traitement4Triangles($x,$y){

		$couleur_pixel_g = $this->getColor($x+($this->dimention_grospixel/4),$y+$this->dimention_grospixel/2);
		$couleur_pixel_d = $this->getColor($x+(($this->dimention_grospixel/4)*3),$y+$this->dimention_grospixel/2);
		$couleur_pixel_h = $this->getColor($x+$this->dimention_grospixel/2,$y+($this->dimention_grospixel/4));
		$couleur_pixel_b = $this->getColor($x+$this->dimention_grospixel/2,$y+(($this->dimention_grospixel/4)*3));

		$lim_x = $x+$this->dimention_grospixel;
		$lim_y = $y+$this->dimention_grospixel;
		$sauv_x = $x;

		//POUR : chaque colonne d'un grospixel a remplir
		for($y ; $y < $lim_y ; $y++){
			//POUR : chaque ligne d'un grospixel à remplir			
			for($x ; $x < $lim_x ; $x++){

				if ($lim_x-$x > $lim_y-$y){
					if ($lim_x-$x > $this->dimention_grospixel-($lim_y-$y)){
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_g);
					} else {
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_b);
					}					
				} else {
					if ($lim_x-$x > $this->dimention_grospixel-($lim_y-$y)){
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_h);
					} else {
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_d);
					}
				}
			}
			$x = $sauv_x;	
		}
	}

	private function traitement4carres($x,$y){

		$couleur_pixel_hg = $this->getColor($x+($this->dimention_grospixel/4),$y+($this->dimention_grospixel/4));
		$couleur_pixel_hd = $this->getColor($x+(($this->dimention_grospixel/4)*3),$y+($this->dimention_grospixel/4));
		$couleur_pixel_bd = $this->getColor($x+(($this->dimention_grospixel/4)*3),$y+(($this->dimention_grospixel/4)*3));
		$couleur_pixel_bg = $this->getColor($x+($this->dimention_grospixel/4),$y+(($this->dimention_grospixel/4)*3));

		$couleur_pixel = $this->getColor($x+$this->dimention_grospixel/2,$y+$this->dimention_grospixel/2);

		$lim_x = $x+$this->dimention_grospixel;
		$lim_y = $y+$this->dimention_grospixel;
		$sauv_x = $x;

		//POUR : chaque colonne d'un grospixel a remplir
		for($y ; $y < $lim_y ; $y++){
			//POUR : chaque ligne d'un grospixel à remplir			
			for($x ; $x < $lim_x ; $x++){

				if( $lim_x-$x > $this->dimention_grospixel/2 ){
					if( $lim_y-$y > $this->dimention_grospixel/2 ){
						//HAUT GAUCHE
						
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_hg);

					} else {
						//BAS GAUCHE
						imagesetpixel($this->image, $x, $y ,$couleur_pixel_bg);
					}

				} else {
					if( $lim_y-$y > $this->dimention_grospixel/2 ){
						//HAUT DROITE
							imagesetpixel($this->image, $x, $y ,$couleur_pixel_hd);

					} else {
						//BAS DROITE*
							imagesetpixel($this->image, $x, $y ,$couleur_pixel_bd);
					}

				}

				// if(($lim_y-$y)/2 < $lim_x-$x && $lim_x-$x < $this->dimention_grospixel-($lim_y-$y)/2 ){
				// 	imagesetpixel($this->image, $x, $y ,$couleur_pixel_c);
				// 	imagesetpixel($this->image, $x+($lim_x-$x), $y+($lim_y-$y) ,$couleur_pixel_c);
				// }
			}
			$x = $sauv_x;	
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