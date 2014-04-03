$(document).ready(function() {
	
	// ###############
	// VARIABLES
	// ############### 
	
	//variables de retour du téléchargement

	var code = "" //le code pour un traitement unique

	var url_repertoire = ""; //prend le code reference du traitement de l'image.
	var url_image_base = "";

	var img_width = 0; // dimention de l'image traitée en X.
	var img_height = 0; // dimention de l'image traitée en Y.

	var tbl_diviseurs_commun = new Array();

	//variables de detection verification
	var click_telechargement = false;
	var image_deja_telechargee = false;
	var image_visible =  "";

	//varaibles pour l'affichage
	var width_affichage = 0;
	var height_affichage = 0;

	//variable pour chargement
	var chargement_size = 0;

	// ###############
	// INIT
	// ###############

	$.get("php-pixilleur/netoyage.php");	

	// ###############
	// ECOUTEURS
	// ###############


	//ECOUTEURS ZONE DE TELECHARGEMENT

	var zones = document.getElementsByClassName("drop-zone");

	for (i = 0 ; i < zones.length ; i++){
		zones[i].addEventListener("dragleave",sortieZone,false);
		zones[i].addEventListener("dragover",survolZone,false);
		zones[i].addEventListener("drop",depot,false);
		zones[i].addEventListener("change",parcourir,false);
	}

	//ECOUTEURS PIXELLISATION
	$('#radio_pix_desactive').click(function(){
		image_visible = $('#image-0');
		animation_affichage(0,0,0,true);
	});
	
	$('#pix_tfaible').click(function(){
		image_visible = $('#image-1')
		animation_affichage(0,0,0,true);
	});
	
	$('#pix_faible').click(function(){
		image_visible = $('#image-2');
		animation_affichage(0,0,0,true);
	});
	
	$('#pix').click(function(){
		image_visible = $('#image-3');
		animation_affichage(0,0,0,true);
	});
	
	$('#pix_fort').click(function(){
		image_visible = $('#image-4');
		animation_affichage(0,0,0,true);
	});
	
	$('#pix_tfort').click(function(){
		image_visible = $('#image-5');
		animation_affichage(0,0,0,true);
	});

	//BOUTON TELECHARGEMENT
	$("#btn_telechargement").click(function(){
		click_telechargement = true;
		window.location.href="php-pixilleur/envoi_image.php?file=../"+image_visible.attr('src');
	});

	//BOUTONS SHARE
	$("#btn_share_facebook").click(function(){
		window.open(
			'https://www.facebook.com/sharer/sharer.php?u=http://www.jeremieboulay.fr/pixilleur/'+image_visible.attr('src'),
			'facebook_share',
			 'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no'
		);
	});

	$("#btn_share_twitter").click(function(){
		window.open(
			"http://twitter.com/share?url=../"+image_visible.attr('src')
		);
	});


	// ###############
	// DEPART
	// ###############


	
	

	
	// ###############
	// FONCTIONS
	// ###############

	
	function sortieZone(event){
		$(".visuel-drop-zone").css("border","solid #222222");
		//event.target.style.border = "solid #222222";
		event.preventDefault();
	}

	function survolZone(event){
		//event.target.style.border = "dashed white";
		event.preventDefault();
		event.dataTransfer.dropEffect = "copy";	
		$(".visuel-drop-zone").css("border","dashed white");
	}

	function depot(event){

		$(".visuel-drop-zone").css("border","solid #222222");
		//event.target.style.border = "solid #222222";
		event.preventDefault();

		testReception(event.dataTransfer.files);
		
		
	}

	function parcourir(event){
		testReception(event.target.files);
	}

	function testReception(fichier){

		if(fichier.length != 1) {
			alert("Erreur : Vous avez déposé plusieurs fichiers");
		} else {

			//TEST du poid de l'image 1Mo max 
			if( fichier[0].size < 1048576 ){

				// TEST du format de l'image
				if( fichier[0].type == "image/jpeg" || fichier[0].type == "image/png" || fichier[0].type == "image/gif"){

					//ENVOIS DU FICHIER AU TELECHARGEMENT
					var fichierImage = new FormData(); //API HTML5
					fichierImage.append("fichier-image",fichier[0]);
					telechargement(fichierImage);

				} else {
					alert("La photo envoyée n'est pas au bon format. (accepté : .jpg .png .gif");
				}
			} else {
				alert("La photo envoyée est trop volumineuse. 1Mo max");
			}
		}

	}

	//FONCTION QUI TRAITE L'IMAGE ENREGISTEE
	function telechargement(fichierImage){

		//UPLOAD DE L IMAGE EN AJAX
		var xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function(){

			if(this.readyState==4){

				//retour du fichier PHP en JSON
				var retour = JSON.parse(this.responseText);

				//sauvegarde de données sur l'image
				url_repertoire = retour.url_repertoire;
				url_image_base = retour.url_image_base;
				code = retour.code_ref;
				
				if(retour.erreur != ""){

					alert("Erreur : "+retour.erreur);

				} else {
					
					gestionAffichage();

					redimentionnementImage();
				}		
				
			}
		}

		xhr.open("POST","php-pixilleur/telechargement.php",true);
		xhr.send(fichierImage);
	}

	function gestionAffichage(){

		//SI : il ya déjà une image.
		if(image_deja_telechargee){
			//suppression du dossier déjà téléchargé 
			supp_dossier();

			//animation pour cacher l'image et les fonctionnalitées
			animation_affichage(0,0,0,false);
			$('.fonctionnalites')
				.animate({'opacity':0},800)
			;

		} else {
			//SINON : Mise en place de la présentation pour l'image.
			
			$('.presentation div, .presentation p').animate({'opacity':0},500, function(){	
					
				//animation de mise en place
				$(this).css("display","none");
				$('.presentation h1').animate({'padding-top':($(window).height()/2)-180},500);

			});
		}

		//afficher le chargement
		$('.wrapper-chargement-bar')
			.css("margin-top", ($(window).height()/2)+20)
			.animate({'opacity':1},500)
		;
		
		MiseAJourBarreChargement(5,'calcul redimentionnement');
	}

	function redimentionnementImage(){

		//CALCUL DES DIMENTIONS
		$.getJSON("php-pixilleur/nombres_divisible_commun.php",{ url_img_base : url_image_base },function(retour){

			img_width = retour.img_width;
			img_height = retour.img_height;

			tbl_diviseurs_commun = retour.tbl_diviseurs_commun;

			MiseAJourBarreChargement(5,'redimentionnement');

			//REDIMENTIONNEMENT
			$.get("php-pixilleur/redimentionnement.php",{ url_img_base : url_image_base, width : img_width, height : img_height },function(){


				//PIXELLISATION
				pixellisation(1);

				//on recoche "desactiver"
				$(".form_pix input:checked").attr('checked', false);
				$('#radio_pix_desactive').attr('checked', true);


				//modification affichage image
				$('#image img').css('display','none');
				image_visible = $("#image-0");

				image_visible
					.attr('src', url_image_base.substr(3))
					.css('display' , 'block')
				;

				dimention_affichage_image();

			});	
		});
	}


	//FONCTION RECURCIVE POUR FAIRE LES IMAGES PIXELLISEE
	function pixellisation(nbr_pixellisation){

		if(nbr_pixellisation < 6){

			MiseAJourBarreChargement(14,'pixellisation'+nbr_pixellisation);

			//AJAX : pixellisation de l'image en fonction d'un diviseur donné
			$.getJSON("php-pixilleur/pixellisation.php",
				{ nbrpixel : tbl_diviseurs_commun[5-nbr_pixellisation],
				  url_img_base : url_image_base,
				  url_repertoire : url_repertoire,
				  numero_img : nbr_pixellisation,
				  code_ref : code
			    },function(retour){

					//ajouter la src à l'image correspondante
					$('#image-'+nbr_pixellisation).attr('src', retour.url_image_pixilisee.substr(3));
					//rappel de la fonction
					pixellisation(nbr_pixellisation+1);
				}
			);
		} else {

			MiseAJourBarreChargement(10,"preparation fonctionnalitées");

			image_deja_telechargee = true;

			//apparition des fonctionnalitées
			$('.fonctionnalites').animate({'opacity':1},800,function(){

				MiseAJourBarreChargement(10,"ok");

				$('.fonctionnalites')
						.css("display","block")
						.animate({'opacity':1},800)
				;

				//check auto
				$('#radio_pix_desactive').attr('checked', true);

				//Fin du chargement
				$('.wrapper-chargement-bar').animate({"margin-top":0},1000,function(){
					$(this).animate({'opacity':0},500,function(){
						chargement_size = 0;
						MiseAJourBarreChargement(0,"");
					});
				});
			});
		}
	}	

	function MiseAJourBarreChargement(ajout,texte){
		chargement_size = chargement_size+ajout;
		$('.chargement-bar div').css('width', chargement_size+'%');
		$('.wrapper-chargement-bar p').html(texte);
	}

	//Fonction pour animer l'apparition de l'image au centre de la page et bien cadrée.
	function animation_affichage(w,h,o,reaparition){

		$('#wrapper-image').animate({'opacity':o},500);



		$('#image')
			.animate(
				{'width': w, 'height': h, 'opacity':o, 'margin-top':(($(window).height()/2) - h/2) },
				{
					duration:500,
					queue:false,
					complete:function(){

						if(reaparition == true){
							$('#image img').css('display','none');
							//faire le changement d'image affiche
							image_visible.css('display', 'block');

							animation_affichage(width_affichage,height_affichage,1,false);
						}
					}
				}
			)
		;	
	}

	//Fonction qui calcul les dimentions pour afficher l'image correstement sur la page.
	function dimention_affichage_image(){
		
		var w_limite = $(window).width()-100;
		var h_limite = $(window).height()-150;
		
		//Si : l'image est trop grande
		if ( img_width > w_limite || img_height > h_limite ){
			//alors une des deux dimentions est plus grande que la dimention max autorisée.
			if ( w_limite - img_width < h_limite - img_height ){
				//alors "w" a le plus grand écart il faut le réduire en brut
				
				height_affichage = ( w_limite * img_height ) / img_width;
				width_affichage = w_limite;
			} else {
				//alors "h" a le plus grand écart il faut le réduire en brut
				width_affichage =  ( img_width * h_limite ) / img_height;
				height_affichage = h_limite;	
				
			}	
		} else {
			width_affichage = img_width;
			height_affichage = img_height;
		}
		
		animation_affichage(width_affichage,height_affichage,1,false);
	}


	//Fontion de suppression d'un dossier d'image
	function supp_dossier(){
		//On supprime le dossier precedent
		$.get("php-pixilleur/supp_dossier.php",{ url_dossier : url_repertoire });
	}
	
});
