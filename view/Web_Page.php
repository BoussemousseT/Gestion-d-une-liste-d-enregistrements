<?php

class Web_Page
{
    public const LANG = 'fr-CA';
    public const AUTHOR = 'Taoufik Boussemousse';
    public const ICON = 'button_images/Logo.png';
    public $title = 'ManchesterUnitedCanada.com';
    public $description = 'Manchester United products- great selection at bargain price';
    public $content = "ERREUR - CONTENU MANQUANT!";

    public $countviews = ""; //nb de vue de la page

    public function render()
    {
        require_once "view/head.php";
        require_once "view/header.php";
        require_once "view/nav.php";
        echo $this->content;
        require_once "view/footer.php";
    }

    public function viewCount($filename)
    {
        $contenu_fichier = file_get_contents("$filename");
        $contenu_fichier++;
        file_put_contents($filename, $contenu_fichier);
        return $contenu_fichier;
    }
}
