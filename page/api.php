<?php

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/?q=';
$code = uniqid();

// 1) ********* version de base ***********

// $query = $database->prepare('INSERT INTO urls (code, target) VALUES (:code, :target)');
// $query->execute([
// 	':code' => $code,
// 	':target' => $_GET['target'],
// ]);

// 2) ********* version corrigée ***********

// ajout de htmlspecialchars pour sécuriser l'url saisie (on enlève les balises et les caractères spéciaux)
$filteredUrl = htmlspecialchars($_GET['target'], ENT_QUOTES, 'UTF-8');

// ajout de filter_var pour n'accepter que les urls (sinon : effacement de la saisie => string vide)
$filteredUrl = filter_var($filteredUrl, FILTER_VALIDATE_URL);

// si filter_val a effacé la saisie, remplacement par un message plus explicite signalant l'erreur
if ($filteredUrl == ""){
	$filteredUrl = "Attention, tentative de malveillance : la saisie n'est pas une URL !";
}

$query = $database->prepare('INSERT INTO urls (code, target) VALUES (:code, :target)');
$query->execute([
	':code' => $code,
	':target' => $filteredUrl,
]);

// On retourne une url constituée de l'adresse de base + le code généré
// en cliquant sur celle-ci, on peut accéder au site

header('Content-Type: application/json');

if ($filteredUrl == "Attention, tentative de malveillance : la saisie n'est pas une URL !") {
	die(json_encode([
		'url' => "La saisie n'est pas une URL !"
	]));
} else {
	die(json_encode([
		'url' => $baseUrl . $code
	]));
}
