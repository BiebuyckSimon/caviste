<?php

// Route de base
$app->get('/', function ($request, $response ) use ($twig) {

	// On affiche le template "index.phtml" (template par défaut)
    echo $twig->render( 'index.phtml' );

});


// Afficher la liste des vins
$app->get('/allwines', function ($request, $response ) use ($twig) {

	// Récupération des vins au moyen de la fonction "recupWines"
	$tabWines = recupWines();

	// On affiche le template "allwines.twig.html"
    echo $twig->render( 'allwines.twig.html', ["tabWines" => $tabWines] );
});


// Afficher les vins sous forme d'une grille avec leur photo
$app->get('/pictureswines', function ($request, $response ) use ($twig) {

	// Récupération des vins au moyen de la fonction "recupWines"
	$tabWines = recupWines();

	// On affiche le template "pictureswines.twig.html"
    echo $twig->render( 'pictureswines.twig.html', ["tabWines" => $tabWines] );
});


// Afficher les vins triés par pays
$app->get('/countrieswines', function ($request, $response ) use ($twig) {

	// Récupération des vins au moyen de la fonction "recupWines"
	$tabWines = recupWines();

	// Récupération des différents pays dans un tableau
	$tabCountries = array();

	foreach( $tabWines as $wine ) {
		if ( ! in_array( $wine->country, $tabCountries ) ) {
			$tabCountries[] = $wine->country;
		}
	}

	// On affiche le template "countrieswines.twig.html"
    echo $twig->render( 'countrieswines.twig.html', ["tabWines" => $tabWines, "tabCountries"=> $tabCountries] );
});


// Fonction permettant de récupérer puis renvoyer la liste des vins
function recupWines() {

	// Récupération sous forme d'un tableau de tous les vins dans la DB au moyen de l'ORM RedBean
	$tabWines = R::findAll('wine');

	// On retourne le tableau "tabWines"
	return $tabWines;
}