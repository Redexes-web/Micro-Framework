<?php
use Controller as C;
// Charge le Kernel
require_once 'app/kernel.class.php';
$kernel = Kernel::getInstance();

// Trouve le nom de la page demandée dans l'url
// Exemple : dans "www.monsite.com/profil" la page demandée est la page "profil"
$baseDir            = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$pageController     = str_replace($baseDir, '', $_SERVER['REDIRECT_URL']);

// Charge le layout
$layout = new Layout();

// Charge le contenu principal de la page demandée dans le layout
// Il faut qu'un controller existe pour cette page, sinon, on renvoie sur la 404
if (class_exists($pageController))
    $layout->content = new $pageController;
else 
    $layout->content = new Controller('404');

// Affiche le résultat du layout avec tous les contenus chargés
echo $layout;