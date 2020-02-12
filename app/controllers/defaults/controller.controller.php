<?php

/**
 * Controleur par défaut
 * 
 * Ce controleur n'est pas encore spécialisé.
 * Il peut être utilisé pour charger puis afficher tout type de données simples
 * en utilisant son constructeur par défaut.
 * Pour des données plus complexes il sera préférable de créer un controleur spécialisé, héritant de ce contrôleur de base.
 */
class Controller {

    protected $templateFile = '';
    public $content = '';

    /**
     * Construit le controlleur
     * 
     * @param string $templateName Nom d'un template spécifique à charger (si aucun, le moteur de template cherchera un template portant le même nom que le controlleur)
     */
    public function __construct($templateName = null){

        // vérifie si on a précisé un nom de template
        $templateName = $templateName ? $templateName : strtolower(get_class($this));
       
        // cherche le fichier du template
        $this->findTemplate($templateName);
    }

    /**
     * Fonction créant une représentation textuelle du controleur
     * Automatiquementy appelé si on fait un écho du controleur.
     * Cette fonction est responsable de l'affichage du HTML contenu dans le template.
     * Toutes les variables à utiliser dans le template doivent être initialisées avant
     * l'appel à cette fonction.
     */
    public function __toString(){

        // si pas de template, quitter
        if (!$this->templateFile)
            return '';
        
        // charge le contenu du template
        $content = file_get_contents($this->templateFile);

        // recherche dans le template d'éventuels "sous"-controleurs à charger
        preg_match_all('/<\?=\$([a-zA-Z]*)\?>/', $content, $matches);
        
        // charge tous les controleurs
        if (isset($matches[1])){
            foreach ($matches[1] as $controllerName){

                // si une classe existe pour ce controleur, il faut l'utiliser
                if (class_exists($controllerName)) 
                    $$controllerName = new $controllerName;
                // sinon, il faut utiliser le controleur par défaut "Controller" en précisant le template à utiliser
                else
                    $$controllerName = new Controller($controllerName);
            }
        }

        // charge le fichier de template
        require_once $this->templateFile;

        // la fonction __toString() doit absolument renvoyer une string renvoit une chaine nulle
        return '';
    }

    /**
     * Recherche un fichier de template par son nom
     * 
     * @param string $templateName Nom du template à rechercher
     */
    private function findTemplate($templateName){
        $templatesDir = 'app/views';
        $this->templateFile = recursiveFileSearch($templateName.'.phtml', $templatesDir);
    }
}