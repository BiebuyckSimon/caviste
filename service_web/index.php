<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;


// Récupère tous les vins contenus dans la DB
$app->get( '/api/wines', function (Request $request, Response $response ) {

	// Connection à la DB
	$pdo = database();

	// On tente de récupérer dans la DB le nom et l'id de tous les vins
	$resultat = $pdo->query( 'SELECT id, name FROM wine' );


	// Si la récupération s'est bien passée, encodage du résultat au format JSON	
	if ( $resultat !== false ) {
		return json_encode( $resultat->fetchAll( PDO::FETCH_ASSOC ) );
	}
	
});

// Récupère tous les vins dont le nom contient la valeur recherchée
$app->get( '/api/wines/search/{elementSearch}', function (Request $request, Response $response, $args ) {

	// Connection à la DB
	$pdo = database();

	// On tente de récupérer dans la DB tous les vins contenant dans leur nom la valeur recherchée
	$stmt = $pdo->prepare( 'SELECT * FROM wine WHERE name LIKE :elementSearch' ) ;
	$resultat = $stmt->execute( array( ':elementSearch' => '%'.$args['elementSearch'].'%' ) );

	// Si la récupération s'est bien passée, encodage du résultat au format JSON
	if ( $resultat !== false ) {
		return json_encode( $stmt->fetchAll( PDO::FETCH_ASSOC ) );
	}
    
});

// Récupère le vin dont on possède l'id
$app->get( '/api/wines/{id}', function ( Request $request, Response $response, $args ) {

    // Connection à la DB
	$pdo = database();

	// On tente de récupérer dans la DB le vin dont on possède l'id
	$stmt = $pdo->prepare( 'SELECT * FROM wine WHERE id=:id' ) ;
	$resultat = $stmt->execute( array( ':id' => $args['id'] ) );

	// Si la récupération s'est bien passée, encodage du résultat au format JSON
	if ( $resultat !== false ) {
		return json_encode( $stmt->fetchAll( PDO::FETCH_ASSOC ) );
	}

});

// Ajoute un vin dans la DB
$app->post( '/api/wines', function ( Request $request, Response $response ) {

    // Connection à la DB
	$pdo = database();

	// On tente d'ajouter un vin dans la DB   
	$stmt = $pdo->prepare( 'INSERT INTO wine (name, grapes, country, region, year, description, picture) 
							VALUES (:name, :grapes, :country, :region, :year, :description, :picture)' );

	$resultat = $stmt->execute( array( ':name' => strtoupper($_POST['name']), 
							':grapes' => $_POST['grapes'],
							':country' => $_POST['country'],
							':region' => $_POST['region'],
							':year' => $_POST['year'],
							':description' => $_POST['description'], 
							':picture' => 'default.jpg' 
						));

	// Si l'ajout s'est bien passé, on retourne un message de réussite
	if ( $resultat !== false ) {
		return json_encode ( ['reponse' => 'Le vin a bien été ajouté'] );
	}

});

// Modifie les données du vin dont on possède l'id
$app->put( '/api/wines/{id}', function ( Request $request, Response $response, $args ) {

	// Connection à la DB
	$pdo = database();

	// On tente de modifier dans la DB le vin dont on possède l'id
	$stmt = $pdo->prepare( 'UPDATE wine 
							SET name=:name, 
							grapes=:grapes, 
							country=:country, 
							region=:region, 
							year=:year, 
							description=:description 
							WHERE id=:id'
						);

	$resultat = $stmt->execute( array( ':name' => strtoupper($_REQUEST['name']), 
							':grapes' => $_REQUEST['grapes'],
							':country' => $_REQUEST['country'],
							':region' => $_REQUEST['region'],
							':year' => $_REQUEST['year'],
							':description' => $_REQUEST['description'], 
							':id' => $args['id'] 
						));

	// Si la modification s'est bien passée, on retourne un message de réussite
	if ( $resultat !== false ) {
		return json_encode ( ['reponse' => 'Le vin a bien été modifié'] );
	}
    
});

// Supprime le vin dont on possède l'id
$app->delete( '/api/wines/{id}', function ( Request $request, Response $response, $args ) {

	// Connection à la DB
	$pdo = database();

	// On tente de supprimer dans la DB le vin dont on possède l'id
	$stmt= $pdo->prepare( 'DELETE FROM wine WHERE id = :id' ) ;
	$resultat = $stmt->execute( array(':id' => $args['id']) );

	// Si la suppression s'est bien passée, on retourne un message de réussite
	if ( $resultat !== false ) {
		return json_encode ( ['reponse' => 'Le vin a bien été supprimé'] );
	}
	
});


// Fonction permettant de se connecter à la DB
function database() {
	try {	
        return new PDO( 'mysql:host=localhost;dbname=cavavin', 'root', 'root' );
    } catch ( PDOException $e ) {
        die ('Erreur de connection à la base de données !' );  	
    }
}


$app->run();