<?php 
/* CLASS :
	Permet de redimentionner deux longueurs pour qu'ils aient au moin 5 tailles commun.
*/
class TaillesCommunes{

	private $tbl_tailles_communes;
	private $longueur_reference;
	private $longueur_seconaire;
	private $tbl_tailles;



	/* FONCTION CONSTRUCTEUR: 
		Passage de paramètre
		Appelle la fonction principale
	 */
	function TaillesCommunes($l_ref,$l_sec){

		$this->tbl_tailles_communes = array();
		$this->tbl_tailles = array();

		$this->longueur_reference = $l_ref;
		$this->longueur_secondaire = $l_sec;

		$this->TrouverTaillesCommunes();

	}

	/* FONCTION PRINCIPALE: 
		Appelle la fonction RemplirTableauTaille
		Test si au moin 5 tailles trouvées par la fonction donnent un entier apr_ès division avec la longueur secondaire
		Si il n'y a pas assez de taille, la fonction réduit la longueur secondaire
		Si la longueur secondaire est trop réduide (-50px) alors on réduit la longueur de référence
		   et on recommence avec un nouveau tableau de Diviseurs.
	 */
	private function TrouverTaillesCommunes(){

		//variables parametre
		$longueur_secondaire_min = $this->longueur_secondaire - 50;
		if ($longueur_secondaire_min < 0) $longueur_secondaire_min = 0; 
		$nbr_tailles_communes = 5;

		//variables de traitement
		$longueur_seconaire_traitement = $this->longueur_secondaire;

		//APPEL : pour remplir le tableau de div de la grande longueur
		$this->RemplirTableauTaille();

		//TANT QUE : il n'y a pas 5 tailles communes
		while(count($this->tbl_tailles_communes) < $nbr_tailles_communes){

			//initialisation du tableau
			$this->tbl_tailles_communes  = array();

			//SI : après plusieurs passage la longueur secondaire est devenue trop petite
			if($longueur_seconaire_traitement < $longueur_secondaire_min ){
				//ALORS : 
				//on remet la petite longueur initiale
				$longueur_seconaire_traitement =  $this->longueur_secondaire;
				//on réduit la grande longueur et on recommence avec un nouveau tableau
				$this->longueur_reference--;
				$this->RemplirTableauTaille();
			}
			//POUR : chaques tailles compris dans le tableau
			foreach ($this->tbl_tailles as $taille) {

				//SI : la longueur secondaire divisée par la taille donne un entier
				//     ET qu'il n'y a pas encore assez de tailles communes
				if($longueur_seconaire_traitement%($taille) == 0 && count($this->tbl_tailles_communes) < $nbr_tailles_communes ){
					//ALORS : Le nombre est bon et on peut le sauvegarder
					array_push($this->tbl_tailles_communes, $taille);
				}
			}

			//SI : il n'y a pas assez de tailles, on réduit la longueur secondaire pour recommencer.
			if(count($this->tbl_tailles_communes) < $nbr_tailles_communes) 
				$longueur_seconaire_traitement--;
		}

		//FIN : le tableau est bon
		$this->longueur_secondaire = $longueur_seconaire_traitement;
	}

	/* FONCTION DE REMPLISSAGE: 
		Remplis un tableau de 5 à 10 tailles qui donnent un entier en les divisant par la longueur principale.
	    - Une taille doit être comprise entre 4 et 65.
	    - S'il n'y a pas assez de tailles, la fonction réduit la longueur et ce rappelle.
	 */
	private function RemplirTableauTaille(){

		//initialisation du tableau
		$this->tbl_tailles = array();

		//variables compteur
		$valeur = 4; //car au minimum je veux un 4

		//variables parametre
		$valeur_max = 65;
		$nbr_min = 5;
		$nbr_max = 10;

		//TANT QUE : pas + de 10 tailles ET la valeur pas + grande que valeur max
		while( count($this->tbl_tailles) < $nbr_max && $valeur < $valeur_max){

			//SI : la division de la longueur et de la valeur donne un nombre entier   
			if($this->longueur_reference%$valeur == 0){
				//ALORS : on enregistre la valeur dans le tableau
				array_push($this->tbl_tailles, $this->longueur_reference/$valeur);
			}
			$valeur++;
		}

		//SI : il n'y a pas au moin le nbr de taille
		if(count($this->tbl_tailles) < $nbr_min ){
			//ALORS : on réduit la longueur qui ne peut pas être utilisée et on recommence
			$this->longueur_reference--;
			//On rappelle la fonction
			$this->RemplirTableauTaille();
		}
	}

	public function GetTbl(){
		return $this->tbl_tailles_communes;
	}

	public function GetLongueurRef(){
		return $this->longueur_reference;
	}

	public function GetLongueurSec(){
		return $this->longueur_secondaire;
	}
}

?>