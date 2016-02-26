<?php

// Route de base
$app->get('/', function ($request, $response ) use ($twig) {

	// On affiche le template "index.phtml" (template par défaut)
    echo $twig->render( 'index.phtml' );

});


// Afficher les vins sous forme d'une grille avec leur photo
$app->get('/catalogue', function ($request, $response ) use ($twig) {

	// Récupération des 6 premiers vins
	$tabWines = R::findAll('wine', 'LIMIT 0, 6');

	// Récupération du nombre de vins présents dans la DB
	$nbWines = R::count('wine');	

	// Récupération des différents pays dans un tableau
	$tabCountries = recupPays();

	// On affiche le template "catalogue.twig.html"
    echo $twig->render( 'catalogue.twig.html', ["tabWines" => $tabWines, "nbWines" => $nbWines, "tabCountries"=> $tabCountries]);

});


// Récupèrer les vins de la page donnée
$app->get( '/{numeroPage:[0-9]+}', function ($request, $response, $args ) use ($twig) {

	// Récupération du numéro de la page
    $numeroPage = $args['numeroPage'];

	// Récupération des vins de la page choisie
    $debut = $numeroPage * 6 - 6;
	$tabWines = R::findAll('wine', 'LIMIT '.$debut.', 6');

	// Récupération du nombre de vins présents dans la DB
	$nbWines = R::count('wine');

	// Récupération des différents pays dans un tableau
	$tabCountries = recupPays();

	// On affiche le template "catalogue.twig.html"
    echo $twig->render( 'catalogue.twig.html', ["tabWines" => $tabWines, "nbWines" => $nbWines, "tabCountries"=> $tabCountries]);

});


// Trier les vins selon le type de tri demandé
$app->get( '/tri/{typeTri}', function ($request, $response, $args ) use ($twig) {
	
	// Récupération du type de tri
    $typeTri = $args['typeTri'];

    // Récupération des vins triés selon le type de tri demandé
	if ( $typeTri == 'nom' ) {
		$tabWines = R::findAll('wine', 'ORDER BY name LIMIT 0, 6');
	} else if ( $typeTri == 'annee'  ) {
		$tabWines = R::findAll('wine', 'ORDER BY year LIMIT 0, 6');
	} else {
		$tabWines = R::findAll('wine', 'LIMIT 0, 6');
	}

	// Transformation des données en un tableau permettant de former du JSON par la suite
	$tabWinesForJSON = array(); 
	$nb = 0;

	foreach( $tabWines as $wine ) {
		$tabWinesForJSON[$nb]['id'] = $wine->id; 
		$tabWinesForJSON[$nb]['name'] = $wine->name; 
		$tabWinesForJSON[$nb]['year'] = $wine->year; 
		$tabWinesForJSON[$nb]['grapes'] = $wine->grapes; 
		$tabWinesForJSON[$nb]['country'] = $wine->country; 
		$tabWinesForJSON[$nb]['region'] = $wine->region; 
		$tabWinesForJSON[$nb]['description'] = $wine->description; 
		$tabWinesForJSON[$nb]['picture'] = $wine->picture; 
		$nb++;
	}

	// On renvoie les données des vins récupérés
    return json_encode( ["tabWines" => $tabWinesForJSON] );

});


// Filtrer les vins selon le pays choisi
$app->get( '/filtre/{pays}', function ($request, $response, $args ) use ($twig) {
	
	// Récupération du pays choisi
    $pays = $args['pays'];

    // Récupération des vins appartenant au pays choisi
	$tabWines = R::find('wine', 'country = :pays LIMIT 0, 6', [':pays'=>$pays]);

	// Transformation des données en un tableau permettant de former du JSON par la suite
	$tabWinesForJSON = array(); 
	$nb = 0;

	foreach( $tabWines as $wine ) {
		$tabWinesForJSON[$nb]['id'] = $wine->id; 
		$tabWinesForJSON[$nb]['name'] = $wine->name; 
		$tabWinesForJSON[$nb]['year'] = $wine->year; 
		$tabWinesForJSON[$nb]['grapes'] = $wine->grapes; 
		$tabWinesForJSON[$nb]['country'] = $wine->country; 
		$tabWinesForJSON[$nb]['region'] = $wine->region; 
		$tabWinesForJSON[$nb]['description'] = $wine->description; 
		$tabWinesForJSON[$nb]['picture'] = $wine->picture; 
		$nb++;
	}

	// On renvoie les données des vins récupérés
    return json_encode( ["tabWines" => $tabWinesForJSON] );

});


// Afficher les vins triés par pays
$app->get('/liste', function ($request, $response ) use ($twig) {

	// Récupération de tous les vins dans la DB
	$tabWines = R::findAll('wine');

	// Récupération des différents pays dans un tableau
	$tabCountries = recupPays();

	// On affiche le template "liste.twig.html"
    echo $twig->render( 'liste.twig.html', ["tabWines" => $tabWines, "tabCountries"=> $tabCountries] );
});


// Fonction permettant de récupérer un tableau contenant les différents pays présents dans la DB
function recupPays() {

	// Récupération de tous les vins dans la DB
	$tabWines = R::findAll('wine');

	// Déclaration d'un tableau vide	
	$tabCountries = array();

	// On insère dans le tableau les différents pays trouvés
	foreach( $tabWines as $wine ) {
		if ( ! in_array( strToUpper( $wine->country ), $tabCountries ) ) {
			$tabCountries[] = strToUpper( $wine->country);
		}
	}

	// On retourne le tableau
	return $tabCountries;
}