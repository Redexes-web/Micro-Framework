<?php
require_once 'app/utilities/files.php';

/**
 * Noyau de l'application
 */
class Kernel {
    private static $instance = null;
    private $classExtensions = ['controller', 'model', 'class'];

    /**
     * Le Kernel est un singleton.
     * Son constructeur est donc privé
     * pour empecher les "new Kernel()";
     */
    private function __construct(){
                
        // déclare l'autoloader de classes
        spl_autoload_register([$this,"loadClass"]);
    }

    /**
     * Pour récupérer l'instance du kernel, 
     * il faut passer par la fonction getInstance()
     * Celle-ci est static, il faudra donc l'utiliser avec le nom de la classe
     * comme ceci : 
     * $kernel = Kernel::getInstance();
     */
    public static function getInstance(){

        // vérifie si une instance existe déjà
        if (!self::$instance)
            self::$instance = new Kernel();

        // renvoie l'instance du Kernel
        return self::$instance;
    }

    /**
     * Fonction appelée par l'autoloader pour trouver automatiquement
     * le fichier d'une classe. Ceci nous évite de nombreux "require" inutiles
     */
    public function loadClass($class){

        // recherche parmis toutes les extensions de classes possibles
        foreach ($this->classExtensions as $extension){

            // déduit le nom du fichier php à partir du nom de la classe et de l'extension
            $wantedFile = strToLower($class).'.'.$extension.'.php';
            
            // recherche ce fichier dans le dossier "app"
            $fileName = recursiveFileSearch($wantedFile, 'app');

            // si la classe a été trouvée, on s'arrête là
            if ($fileName)
                break;

            // sinon on essaye avec une autre extension
        }

        // charge le fichier de la classe si on l'a trouvé
        if(file_exists($fileName)) 
            require_once $fileName;
        else
            return null;
    }
}