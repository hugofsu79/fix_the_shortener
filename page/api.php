<?php

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/?q=';
$code = uniqid();

// 1) ********* version de base ***********


// On retourne une url constituée de l'adresse de base + le code généré
// en cliquant sur celle-ci, on peut accéder au site

header('Content-Type: application/json');

die(json_encode([
	'url' => $baseUrl . $code
]));
