// Une fois le DOM chargé, les instructions jQuery peuvent être exécutées
$( function () {
	
	/**************************************************/
	/*   Action par défaut - Afficher tous les vins   */
	/**************************************************/
	affichageVins("GET", "http://localhost/service_web/index.php/api/wines"); 


	/**************************************/
	/*   Rechercher un/plusieurs vin(s)   */
	/**************************************/
	$('#frmSearch').submit( function(e) {

		// Empêcher le comportement par défaut du navigateur
		e.preventDefault();

		// Vider les messages d'affichage
		viderAffichage();

		// Récupération de la valeur entrée dans la barre de recherche
		var value = $('#search').val();    
		
		// Suppression des élements de la liste "listeVins"
		$('.itemVin').remove();

		// En fonction de la valeur récupérée, lancement d'une requête Ajax en appelant la fonction "affichageVins"
		if ( value == '' ) {
			affichageVins("GET", "http://localhost/service_web/index.php/api/wines");
		} else {
			affichageVins("GET", "http://localhost/service_web/index.php/api/wines/search/" + value);
		}
	});


	$('#search').on('blur', function() {

		// Récupération de la valeur entrée dans la barre de recherche
		var value = $('#search').val();    

		// Si aucune valeur n'a été entrée, on réaffiche tous les vins en lancant une requête Ajax
		if ( value == '') {
	
			// Vider les messages d'affichage
			viderAffichage();

			// Suppression des élements de la liste "listeVins"
			$('.itemVin').remove();

			// Appel à la fonction "affichageVins"
			affichageVins("GET", "http://localhost/service_web/index.php/api/wines");
		} 
	});


	/****************************/
	/*   Créer un nouveau vin   */
	/****************************/
	$('#btAdd').on('click', function() {

		// Vider les messages d'affichage
		viderAffichage();

		// On vide les champs du formulaire
		viderFormulaire();

	});


	/*********************************/
	/*   Modifier / Ajouter un vin   */
	/*********************************/
	$('#btSave').on('click', function(e) {
		
		// Empêcher le comportement par défaut du navigateur
		e.preventDefault();

		// Vider les messages d'affichage
		viderAffichage();

		// Suppression des élements de la liste "listeVins"
		$('.itemVin').remove();

		// Réafficher tous les vins après suppression 
		affichageVins("GET", "http://localhost/service_web/index.php/api/wines");

		// Récupération des valeurs présentes dans le formulaire
		var formu = $('#frmChange');
		var id = $('#id').val();
		var name = $('#name').val();
		var grapes = $('#grapes').val();
		var country = $('#country').val();
		var region = $('#region').val();
		var year = $('#year').val();
		var description = $('#description').val();

		// Avant de lancer la requête Ajax, on vérifie que toutes les données sont bien présentes
		if( name == '' || grapes == '' || country == '' || region == '' || year == '' || description == '' ){

			// On indique à l'utilisateur que tous les champs doivent être remplis
			$('#affichageChange').text('Veuillez entrer tous les champs');

		} else {

			if (id != '' ) {

				/********************/
				/*   Modification   */
				/********************/	

				// Lancement d'une requête Ajax permettant de modifier un vin			
				$.ajax({
					method: "POST",
					url: "http://localhost/service_web/index.php/api/wines/" + id, 
					headers: {"X-HTTP-Method-Override": "PUT"}, 
					dataType: "json",
					data: formu.serialize()
				})
				.done( function(data) {

					// On affiche un message de réussite à l'utilisateur
					$('#affichageChange').text( data.reponse );

					// Suppression des élements de la liste "listeVins"
					$('.itemVin').remove();

					// Réafficher tous les vins après modification 
					affichageVins("GET", "http://localhost/service_web/index.php/api/wines"); 

				})
				.fail( function() {

					// On affiche un message d'erreur à l'utilisateur
					$('#affichageChange').text('Le vin n\'a pu être modifié');

				});

			} else {

				/*************/
				/*   Ajout   */
				/*************/

				// Lancement d'une requête Ajax permettant d'ajouter un vin			
				$.ajax({
					method: "POST",
					url: "http://localhost/service_web/index.php/api/wines", 
					dataType: "json",
					data: formu.serialize()
				})
				.done( function(data) {

					// On vide les champs du formulaire
					viderFormulaire();

					// On affiche un message de réussite à l'utilisateur
					$('#affichageChange').text( data.reponse );

					// Suppression des élements de la liste "listeVins"
					$('.itemVin').remove();

					// Réafficher tous les vins après ajout 
					affichageVins("GET", "http://localhost/service_web/index.php/api/wines"); 

				})
				.fail( function() {

					// On affiche un message d'erreur à l'utilisateur
					$('#affichageChange').text('Le vin n\'a pu être ajouté');

				});


			}
		}	
	});


	/************************/
	/*   Supprimer un vin   */
	/************************/
	$('#btDelete').on('click', function(e) {	 

		// Empêcher le comportement par défaut du navigateur
		e.preventDefault();

		// Vider les messages d'affichage
		viderAffichage();

		// Suppression des élements de la liste "listeVins"
		$('.itemVin').remove();

		// Réafficher tous les vins après suppression 
		affichageVins("GET", "http://localhost/service_web/index.php/api/wines");

		// Récupération de l'id du vin à supprimer
		var id = $('#id').val(); 

		// Si un id est bien présent dans le champs "id" du formulaire, on lance une requête ajax
		if ( id != '') {

			$.ajax( {
				method: "DELETE",
				url: "http://localhost/service_web/index.php/api/wines/" + id,
				dataType: "json"
			})
			.done( function(data) {

				// On vide les champs du formulaire
				viderFormulaire();

				// On affiche un message de réussite à l'utilisateur
				$('#affichageChange').text( data.reponse );

				// Suppression des élements de la liste "listeVins"
				$('.itemVin').remove();

				// Réafficher tous les vins après suppression 
				affichageVins("GET", "http://localhost/service_web/index.php/api/wines");

			})
			.fail( function() {
				
				// On affiche un message d'erreur à l'utilisateur
				$('#affichageChange').text('Le vin n\'a pu être supprimé');

			}) 

		}
	});




	/*****************/
	/*   Fonctions   */
	/*****************/

	/* Fonction permettant 
	    	- d'afficher tous les vins dans une liste "<ul><li></li></ul>"
	    	- d'afficher les vins répondant à une recherche dans une liste "<ul><li></li></ul>"
	    	- d'afficher au click d'un vin les infos concernant celui-ci dans le formulaire d'ajout/modification/suppression
	*/
	function affichageVins(method, url) {
		$.ajax( {
			method: method,
			url: url,
			dataType: "json", 
		})
		.done( function(data) {

			if( (typeof(data) == "object") && (data != '') ) {

				// Création des items de la liste 'listeVins'
				for ( var i = 0; i < data.length; i++ ){
					$('#listeVins').append('<li class="itemVin" id="'+ data[i].id +'">' + data[i].name + '</li>');
				}


				/*************************************/
				/*   Afficher les données d'un vin   */
				/*************************************/				
				$('#listeVins').selectable( {
					tolerance: "fit",
					selected: function(event, ui) {
					
						// On vide les messages d'affichage
						viderAffichage();

						// Récupération de l'id du vin cliqué
						var id = ui.selected.id;

						// Lancement d'une requête Ajax récupérant les infos du vin cliqué
						$.ajax( {
							method: "GET",
							url: "http://localhost/service_web/index.php/api/wines/" + id,
							dataType: "json"
						})
						.done( function(data) {

							// En fonction de la réponse de la requête Ajax, on affiche les infos du vin préalablement cliqué
							if ( (typeof(data) == "object") && (data != '') ) {
								
								// Insertion des données dans le formulaire
								$('#id').val(data[0].id);
								$('#name').val(data[0].name);
								$('#grapes').val(data[0].grapes);
								$('#country').val(data[0].country);
								$('#region').val(data[0].region);
								$('#year').val(data[0].year);
								$('#picture').attr('src', 'pics/'+data[0].picture);
								$('#description').val(data[0].description);
							
							} else { 

								// On indique à l'utilisateur que l'id mentionné est inexistant
								$('#affichageSearch').text('Id inexistant');

							} 
							
						})
						.fail( function() {

							// On affiche un message d'erreur à l'utilisateur
							$('#affichageSearch').text('Un problème est survenu');

						});
						
					}

				});
			} else {

				// On indique à l'utilisateur qu'aucun résultat n'a été trouvé
				$('#affichageSearch').text('Aucun résultat trouvé');

			}

		})
		.fail( function(data) {

			// On affiche un message d'erreur à l'utilisateur
			$('#affichageSearch').text('Un problème est survenu');	

		});	
	}


	// Fonction permettant de vider les différentes zones acceuillant des messages pour l'utilisateur
	function viderAffichage(){
		$('#affichageSearch').text('');		
		$('#affichageChange').text('');		
	}


	// Fonction permettant de vider le formulaire d'ajout/modification/suppression
	function viderFormulaire() {
		$('#id').val('');
		$('#name').val('');
		$('#grapes').val('');
		$('#country').val('');
		$('#region').val('');
		$('#year').val('');
		$('#description').val('');	
		$('#picture').attr('src', 'pics/default.jpg');
	}
			
});