<?php
require_once "globals.php";
require_once "outils.php";
require_once "view/Web_Page.php";
require_once "db_pdo.php";

require_once "Products_Type.php";
require_once "users.php";
require_once "Customers.php";
require_once "Professeurs.php";


session_start();

//test DB
// $DB = new db_pdo();
// $DB->connect();


/**
 * CONTROLEUR
 */
function main()
{
    if (!isset($_REQUEST['op'])) {
        $op = 'homepage';
    } else {
        $op = $_REQUEST['op'];
    }
    switch ($op) {
        case 'homepage':
            $unePage = new Web_Page;
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            //dans ce cas $lang est égale à 'en'
            if ($lang == 'en') {

                //cookie timestamp actul, valide pour 1 an  (solution Taoufik)

                setcookie("w12-server", "Accueil", time() + 31536000); //time en seconds
                if (!isset($_COOKIE["w12-server"])) {
                    $unePage->title = "Home page";
                    $unePage->description = "Welcome - Home page";
                    $unePage->content = 'Welcome to ' . COMPANY_INFO['NAME'];
                } else {
                    $unePage->title = "Home page";
                    $unePage->description = "Welcome back - Home page";
                    $unePage->content = 'Welcome back to ' . COMPANY_INFO['NAME'];
                }
            } elseif ($lang == 'fr') {
                $unePage->title = "Page d'accueil";
                $unePage->description = "Bienvenue - Page d'accueil";

                //cookie timestamp actul, valide pour 1 an  (solution du prof)
                setcookie("timestampDerniereVisite", time(), time() + (365 * 24 * 60 * 60));

                if (isset($_COOKIE["timestampDerniereVisite"])) {
                    $unePage->content =  'Re-bienvenue chez ' . COMPANY_INFO['NAME'] . '! Votre derniere visite etait le ';
                    $unePage->content .= date("D d-M-Y h:i:s", $_COOKIE["timestampDerniereVisite"]);
                } else {
                    $unePage->content =  'Bienvenue chez ' . COMPANY_INFO['NAME'] . ', c\'est votre premiere visite';
                }
            } elseif ($lang == 'ar') {
                $unePage->title = "الصفحة الرئيسية";
                $unePage->description = "مرحبا بكم  - الصفحة الرئيسية";
                $unePage->content =  'مرحبا بكم في ' . COMPANY_INFO['NAME'];
            } else {
                $unePage->title = "Choisir la langue - Select language";
                $unePage->description = "Choisir la langue - Select language";
                $unePage->content =  'Choisir français ou anglais ect.. ' . COMPANY_INFO['NAME'];
                // afficher formulaire pour choisir
            }

            $unePage->countviews = $unePage->viewCount("log/accueil.txt");
            $unePage->render();
            datePageAccueil(); // enregiste la date et l'heur de visite de la page d'accueil
            break;

        case 'page_produits':
            $unePage = new Web_Page;
            $unePage->title = "Table des produits";
            $unePage->content = Products_Type::productList();
            $unePage->countviews = $unePage->viewCount("log/product_list.txt");
            $unePage->render();
            break;

        case 'catalogue_produits':
            $unePage = new Web_Page;
            $unePage->title = "Catalogue des produits";
            $unePage->content = Products_Type::productsCatalog();
            $unePage->countviews = $unePage->viewCount("log/product_catalogue.txt");
            $unePage->render();
            break;
        case '1':
            $unePage = new Web_Page;
            $unePage->title = "Page Login";
            $unePage->content = users::login();
            $unePage->render();
            break;
        case '2':
            $unePage = new Web_Page;
            $unePage->content = users::loginVerify();
            break;
        case '3':
            $unePage = new Web_Page;
            $unePage->title = "Register";
            $unePage->content = users::register();
            $unePage->render();
            break;
        case '4':
            $unePage = new Web_Page;
            $unePage->content = users::registerVerify();
            break;
        case '5':
            $unePage = new Web_Page;
            $unePage->title = "Register";
            $unePage->content = users::logout();
            $unePage->render();
            break;
        case '50':
            $unePage = new Web_Page;
            $unePage->title = "download un fichier PDF";
            $unePage->content = '<h2>download un fichier PDF</h2>';

            // le type de fichier, dans ce cas PDF, voir lien ci-dessous pour autres types
            header('Content-Type: application/pdf');
            // le nom du fichier sera un_fichier.pdf, navigateur peut demander permission
            header('Content-Disposition: attachment; filename="un_fichier.pdf"');
            // ok envoyer le fichier, lire et envoyer directement avec readfile()
            readfile('download/un_fichier.pdf');

            $unePage->render();
            break;
        case '51':
            header('location: http://www.timhortons.ca');
            break;

            // CUSTOMERS 400-499
        case '400':
            Customers::list();
            break;

        case '401':
            Customers::display();
            break;

        case '402':
            Customers::edit();
            break;

        case '403':
            Customers::save();
            break;

        case '404':
            Customers::delete();
            break;

        case '405':
            Customers::listJSON();
            break;

        case '1200':
            Professeurs::list();
            break;

        default:
            $unePage = new Web_Page;
            $unePage->title = "Erreur operation inconnue";
            $unePage->content = '<h2>Erreur operation inconnue</h2>';

            //Exemple pour envoyer un code d’erreur avec un message personnalisé en français
            header("HTTP/1.0 404 ce code d'opération est inconnu"); // affiché dans console client
            // exit("<h1>" . "ce code d'opération est inconnu" . "</h1>"); // affiché à l’écran client
            $unePage->render();
            break;
    }
}

//demare le programme
main();
