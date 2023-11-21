<?php
// Fonction pour vérifier les informations d'identification
function authenticate($username, $password) {
    // Vous devriez mettre en œuvre une logique de vérification des informations d'identification ici
    // Pour cet exemple, nous comparons avec des valeurs statiques
    $validUsername = 'utilisateur';
    $validPassword = 'azerty';

    return $username === $validUsername && $password === $validPassword;
}

// Vérifier si les informations d'identification sont fournies
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    // Si non, envoyer une demande d'authentification
    header('WWW-Authenticate: Basic realm="Mon API"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentification requise.';
    exit;
}

// Vérifier les informations d'identification
$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

if (!authenticate($username, $password)) {
    // Si les informations d'identification ne sont pas valides, renvoyer une erreur
    header('WWW-Authenticate: Basic realm="Mon API"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentification échouée.';
    exit;
}

// Authentification réussie, vous pouvez maintenant répondre à la demande de l'API
echo 'Authentification réussie. Bienvenue, ' . $username;
?>