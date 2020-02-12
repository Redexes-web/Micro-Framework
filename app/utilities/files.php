<?php

    /**
     * Recherche un fichier de manière récursive dans un dossier
     * c'est à dire dans le dossier et dans tous ces sous-dossiers.
     * 
     * Récuresive signifie que la fonction se rappelle elle-même indéfiniment tant que cela est nécessaire.
     * Dans le cas d'une recherche dans un dossier et ses sous-dossiers somme ici,
     * Cela veut dire que la fonction s'éxécute une première fois sur le dossier principal.
     * Puis, si le fichier n'est pas trouvé, la fonction se relance elle-même sur tous les sous-dossiers.
     * Si les sous-dossiers contiennent eux-même d'autres sous-dossiers, 
     * elle se relancera encore automatiquement jusqu'à ne plus trouver de sous-dossiers.
     */
    function recursiveFileSearch($fileName, $dirName){

        // vérifie que le dossier existe
        if (!is_dir($dirName))
            return null;

        // vérifie si on a trouvé le fichier dans ce dossier
        // dans quel cas on arrête la recherche
        if (file_exists($dirName.'/'.$fileName))
            return $dirName.'/'.$fileName;

        // Cherche dans les sous-dossiers du dossier actuel
        $dir = dir($dirName);
        $foundFile = null;
        while (false !== ($entry = $dir->read())) {

            // passe outre les dossiers '.' et '..'
            if ($entry == '.' || $entry == '..')
                continue;

            // si on a un sous-dossier, lancé la recherche dans celui-ci
            if (is_dir($dirName.'/'.$entry)){
                $foundFile = recursiveFileSearch($fileName, $dirName.'/'.$entry);

                // si on a trouvé le fichier, s'arrêté là
                if ($foundFile)
                    return $foundFile;
            }
        }
        // ferme le dossier
        $dir->close();

        // aucun fichier trouvé dans le dossier en cours ou dans ses sous-dossiers
        return null;        
    }