$(document).foundation();

$( function () {

	/**************************************/
	/*   Création du menu de pagination   */
	/**************************************/
	pagination();
	

	/**********************************************/
	/*   Trier les vins selon leur id/nom/année   */
	/**********************************************/
	$('#menuTri li').on('click', function(e) {
			
		// Empêcher le comportement par défaut du navigateur
		e.preventDefault();

		// Récupération du type de tri
		var typeTri = $(this).attr('id');

		// Envoie de la requête Ajax permettant de récupérer les vins triés comme demandé
		$.ajax( {
			method: "GET",
			url: "../public/index.php/tri/" + typeTri,
			dataType: "json"
		})
		.done( function(data) {

			// Modification du catalogue
			modificationCatalogue(data);

 		})
		.fail( function() {

			// On affiche un message d'erreur à l'utilisateur
			$('#affichage').text('Un problème est survenu');

		});

	});


	/**********************************************/
	/*   Filtrer les vins selon le pays demandé   */
	/**********************************************/
	$('#pays').on('change', function() {

		// Récupération du pays choisi
		var pays = $(this).val();
		
		// On vérifie qu'un pays a bien été choisi
		if ( pays != '0' ) {

			// Envoie de la requête Ajax permettant de récupérer les vins appartenant au pays choisi
			$.ajax( {
				method: "GET",
				url: "../public/index.php/filtre/" + pays,
				dataType: "json"
			})
			.done( function(data) {

				// Modification du catalogue
				modificationCatalogue(data);
				
	 		})
			.fail( function() {

				// On affiche un message d'erreur à l'utilisateur
				$('#affichage').text('Un problème est survenu');

			});
		}
		
	});
	

	/********************************/
	/*   Définition des fonctions   */
	/********************************/
	
	// Fonction permettant de mettre en place le système de pagination	
	function pagination() {
		var nbVins = $('#pagination').data('nb-wines');
		var nbPages = Math.ceil( nbVins / 6 );
		var currentPage = $('#pagination').data('current-page');

		for ( var i = 1; i < nbPages+1; i++ ) {
			$('#pagination').append('<li><a href="'+ i +'">'+ i +'</a></li>');
		}
		
		$('#pagination a').removeClass('currentPage');
		$('#pagination a[href='+currentPage+']').addClass('currentPage');
	}		

	// Fonction permettant de réaliser les différentes opérations nécessaires suite à un tri/filtre
	function modificationCatalogue (data) {

		// Modification des vins et des lightbox
		$('#itemsWines').empty();
		$('.reveal').remove();

		for (var i = 0; i < data.tabWines.length; i++ ) {
			$('#itemsWines').append('<div class="itemWines column thumbnail" data-open="'+data.tabWines[i].id+'" href="#" ><img src="img/'+data.tabWines[i].picture+'" alt="'+data.tabWines[i].name+'" /><div class="region" >'+data.tabWines[i].region+'</div><div class="name" >'+data.tabWines[i].name+'</div></div>');
			$('#pagination').before('<div class="reveal" id="'+data.tabWines[i].id+'" data-reveal><h1>'+data.tabWines[i].name+'</h1><img src="img/'+data.tabWines[i].picture+'" alt="'+data.tabWines[i].name+'" /><div class="idLightBox" ><span class="bold">Id: </span>'+data.tabWines[i].id+'</div><div class="nameLightBox" ><span class="bold">Name: </span>'+data.tabWines[i].name+'</div><div class="yearLightBox" ><span class="bold">Year: </span>'+data.tabWines[i].year+'</div><div class="grapesLightBox" ><span class="bold">Grapes: </span>'+data.tabWines[i].grapes+'</div><div class="countryLightBox" ><span class="bold">Country: </span>'+data.tabWines[i].country+'</div><div class="regionLightBox" ><span class="bold">Region: </span>'+data.tabWines[i].region+'</div><div class="descriptionLightBox" ><span class="bold">Description: </span>'+data.tabWines[i].description+'</div><button class="close-button" data-close type="button" ><span>&times;</span></button></div>');		
		}

		// Modification du menu de pagination
		$('#pagination').data('nb-wines', data.nbWines);
		$('#pagination').empty();
		pagination();

		// On simule un retour sur la première page
		$('#pagination a').removeClass('currentPage');
		$('#pagination a[href=1]').addClass('currentPage');

		// On réapplique le code de Foundation
		$(document).foundation();
	}

});