<?php

// Connection à la base
require 'config.php';

// Vérification si un code est présent dans l'url en GET (?q= + code)
if(isset($_GET['q'])){

	// si oui, on redirige vers l'url associée au code dans la table urls de la bdd
	$query = $database->prepare('select target from urls where code = :code');
	$query->execute(['code' => $_GET['q']]);
	$url = $query->fetchAll(PDO::FETCH_COLUMN, 0);

	header('Location: '.$url[0]);
	die('Redirect to '.$url[0].'...');
}

// Si pas de code dans l'url, affichage de l'accueil
include 'page/'.($_GET['page'] ?? 'front.php');
