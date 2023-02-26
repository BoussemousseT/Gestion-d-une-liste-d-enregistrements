
<?php
require_once "outils.php";
class users
{
    // const USERS = [
    //     ['id' => 0, 'email' => 'Yannick@gmail.com', 'pw' => '12345678'],
    //     ['id' => 1, 'email' => 'Victor@test.com', 'pw' => '11111111'],
    //     ['id' => 2, 'email' => 'Christian@victoire.ca', 'pw' => '22222222'],
    // ];
    const PAYS = [
        [1, 'CA', 'Canada'],
        [2, 'US', 'États-Unis'],
        [3, 'MX', 'Mexique'],
        [4, 'FR', 'France'],
        [5, 'AU', 'Autre']
    ];

    public static function login($errMsg = "", $data = [])
    {
        //verifier nombre de tentatives , maximum 3

        $pageLogin = "";

        if (!isset($_SESSION['compteurLogin'])) {
            $_SESSION['compteurLogin'] = 0;
            $pageLogin = '<form action="index.php?op=2" method="post">';
            $pageLogin .= '<h1>Connectez-vous</h1>';
            $pageLogin .= '<h2 class="errMsg">' . $errMsg . '</h2>';

            if ($data == []) {
                //premier affichage, pas de donnees precedentes
                //valeur par defaut
                $data['email'] = "";
                $data['password'] = "";
            }
            $pageLogin .= '<div>';
            $pageLogin .= '<label for="email"></label>';
            $pageLogin .= '<input type="email" name="email" value="' . $data['email'] . '" placeholder="Email" maxlength="126" size="40" autofocus required/>';
            $pageLogin .= '</div><div>';
            $pageLogin .= '<label for="password"></label>';
            $pageLogin .= '<input type="password" name="password" value="' . $data['password'] . '" placeholder="Mot de passe" size="40" maxlength="8" required/>';
            $pageLogin .= '</div>';

            $pageLogin .= '<input type="submit" value="Continuez">';
            $pageLogin .= '</form>';
            return $pageLogin;
        } elseif ($_SESSION['compteurLogin'] < 3) {
            //affiche formulaire
            $pageLogin = '<form action="index.php?op=2" method="post">';
            $pageLogin .= '<h2>Connectez-vous</h2>';
            $pageLogin .= '<h2 class="errMsg">' . $errMsg . '</h2>';

            if ($data == []) {
                //premier affichage, pas de donnees precedentes
                //valeur par defaut
                $data['email'] = "";
                $data['password'] = "";
            }
            $pageLogin .= '<div>';
            $pageLogin .= '<label for="email"></label>';
            $pageLogin .= '<input type="email" name="email" value="' . $data['email'] . '" placeholder="Email" maxlength="126" size="40" autofocus required/>';
            $pageLogin .= '</div><div>';
            $pageLogin .= '<label for="password"></label>';
            $pageLogin .= '<input type="password" name="password" value="' . $data['password'] . '" placeholder="Mot de passe" size="40" maxlength="8" required/>';
            $pageLogin .= '</div>';

            $pageLogin .= '<input type="submit" value="Continuez">';
            $pageLogin .= '</form>';
            return $pageLogin;
        } else {
            $unePage = new Web_Page;
            $unePage->title = "Ressayer plus tard";
            $unePage->content = '<h2 class="errMsg">Ressayer plus tard, Vous êtes atteint le nombre tentative</h2>';
            $unePage->render();
        }
    }

    public static function loginVerify()
    {


        //verifer email

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            header("HTTP/1.0 400 Erreur email manquant dans formulaire login, class users.php");
            die("Erreur email manquant dans formulaire login, class users.php");
        }
        if (strlen($email) > 126 or strlen($email) == 0) {

            header("HTTP/1.0 400 Erreur email entre 0 et 126 char dans formulaire login, class users.php");
            die("Erreur email entre 0 et 126 char dans formulaire login, class users.php");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            header("HTTP/1.0 400 Erreur email format invalide dans formulaire login, class users.php");
            die("Erreur email format invalide dans formulaire login, class users.php");
        }

        //verifer mot de passe

        if (isset($_POST['password'])) {
            $pw_encoded = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $pw = password_verify($_POST['password'], $pw_encoded);
        } else {
            header("HTTP/1.0 400 Erreur pw manquant dans formulaire login, class users.php");
            die("Erreur pw manquant dans formulaire login, class users.php");
        }
        if (strlen($pw) > 8 or strlen($pw) == 0) {

            header("HTTP/1.0 400 Erreur pw entre 0 et 8 char dans formulaire login, class users.php");
            die("Erreur pw entre 0 et 8 char dans formulaire login, class users.php");
        }


        //chercher la liste des users dans la BD
        $DB = new db_pdo();
        $DB->connect();
        $users = $DB->querySelect("SELECT * FROM users");
        // var_dump($users);
        // $customers = $DB->list("SELECT * FROM customers");
        // var_dump($customers);

        // echo 'Nombre de customers:' . count($customers);

        // foreach ($customers as $unCustomers) {
        //     echo "<div>" . $unCustomers['id'] . " " . $unCustomers['name'] . " " . $unCustomers['contactLastName'] . " " . $unCustomers['contactFirstName'] . " " . $unCustomers['phone'] . " " . $unCustomers['addressLine1'] . " " . $unCustomers['addressLine2'] . " " . $unCustomers['city'] . " " . $unCustomers['state'] . " " . $unCustomers['postalCode'] . " " . $unCustomers['country'] . " " . $unCustomers['salesRepEmployeeNumber'] . " " . $unCustomers['creditLimit'] . "</div>"; // affiche tout les information des colonnes de la table
        // }



        //verifer si usager dans la liste

        $i = 0;
        $found = false;
        do {
            if ($users[$i]['email'] == $email and $users[$i]['pw'] == $pw) {
                $found = true;
            }
            $i++;
        } while (!$found and $i < count($users));

        if ($found) {
            //Tout OK
            $_SESSION['email'] = $email;

            $photo = $DB->querySelect("SELECT picture FROM users WHERE email='" . $email . "'");
            $photo = $photo[0]['picture'];
            $_SESSION['picture'] = $photo;

            $unePage = new Web_Page;
            $unePage->title = "Vous êtes connecter";
            $unePage->content =  "<h2> Vous êtes connecté " . $_REQUEST['email'] . "!</h2>";
            $_SESSION['compteurLogin'] = null;

            $unePage->render();
        } else {
            //mauvais email ou mot de passe

            $_SESSION['compteurLogin']++;

            $unePage = new Web_Page;
            $unePage->title = "Vous êtes connecter";
            if ($_SESSION['compteurLogin'] >= 3) {
                $unePage->content = '<h2 class="errMsg">Ressayer plus tard, Vous êtes atteint le nombre tentative</h2>';
            } else {
                $unePage->content = self::login("Email ou mot de passe incorrect, il vous reste " . 3 - $_SESSION['compteurLogin'] . " tentatives", $_REQUEST);
            }
            $unePage->render();
        }
    }
    public static function logout()
    {
        $_SESSION['email'] = null;
        // $_SESSION['compteurLogin'] = null;

        header("location: index.php"); //retourner a la page d'accueil
    }


    public static function register($errMsgRegister = "", $data = [])
    {

        if ($data == []) {
            //premier affichage, pas de donnees precedentes
            //valeur par defaut
            $data['fullname'] = "";
            // $data['country'] = "";
            // $data['radioChoix'] = "";
            $data['email'] = "";
            $data['pw'] = "";
            $data['pw2'] = "";
        }

        $pageRegister = '<form action="index.php?op=4" method="POST" enctype="multipart/form-data" >
        <h2>Inscription</h2>';
        $pageRegister .= '<div class=\'errMsgRegister\'>' . $errMsgRegister . '<div>';

        $pageRegister .= '<div><label for="fullname"></label>
        <input type="text" name="fullname" value="' . $data['fullname'] . '" placeholder="nom et prenom" maxlength="50" autofocus required/></div>
        <div><select name="country" >';
        for ($i = 0; $i < 5; $i++) {
            $pageRegister .= '<option value="' . self::PAYS[$i][1] . '" >' . self::PAYS[$i][2] . '</option>';
        }

        $pageRegister .= <<<HTML
        </select></div>
        <div><label>Sélectionnez votre langue</label></div>
        <div><input type="radio" name="radioChoix" value="fr" required>
        <label for="fr">Français</label></div>
        <div><input type="radio" name="radioChoix" value="an" >
        <label for="an">English</label></div>
        <div><input type="radio" name="radioChoix" value="autre" >
        <label for="autre">Autre</label></div>
        HTML;
        $pageRegister .= '<label for="email"></label>
        <input type="email" name="email" value="' . $data['email'] . '" placeholder="Courriel" maxlength="126" size="40" autofocus required/>
        </div><div>
        <label for="pw"></label>
        <input type="password" name="pw" value="' . $data['pw'] . '" placeholder="mot de passe max 8 char" maxlength="8" required/>
        </div>
        <div>
        <label for="pw2"></label>
        <input type="password" name="pw2" value="' . $data['pw2'] . '" placeholder="répéter le mot de passe" maxlength="8" required/>
        </div>
        <div>
        <div>
        <input type="file" name="ma_photo">
        </div>
        <input type="checkbox" name="spam_ok" value="1" checked>
        <label for="spam_ok">Je désire recevoir périodiquement des informations au sujet des produits...</label>
        </div>
        <input type="submit" value="Continuez">
        <button type="button" onClick="history.back();">Annuler</button>
        </div>
        </form>';
        return $pageRegister;
    }

    public static function registerVerify()
    {
        //verifer email

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            header("HTTP/1.0 400 Erreur email manquant dans formulaire login, class users.php");
            die("Erreur email manquant dans formulaire login, class users.php");
        }
        if (strlen($email) > 126 or strlen($email) == 0) {

            header("HTTP/1.0 400 Erreur email entre 0 et 126 char dans formulaire login, class users.php");
            die("Erreur email entre 0 et 126 char dans formulaire login, class users.php");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            header("HTTP/1.0 400 Erreur email format invalide dans formulaire login, class users.php");
            die("Erreur email format invalide dans formulaire login, class users.php");
        }

        //verifer mot de passe

        if (isset($_POST['pw'])) {
            $pw = $_POST['pw'];
        } else {
            header("HTTP/1.0 400 Erreur pw manquant dans formulaire login, class users.php");
            die("Erreur pw manquant dans formulaire login, class users.php");
        }
        if (strlen($pw) > 8 or strlen($pw) == 0) {
            header("HTTP/1.0 400 Erreur pw entre 0 et 8 char dans formulaire login, class users.php");
            die("Erreur pw entre 0 et 8 char dans formulaire login, class users.php");
        }


        //chercher la liste des users dans la BD
        $DB = new db_pdo();
        $DB->connect();
        $users = $DB->querySelect("SELECT * FROM users");


        //verifer si usager dans la liste

        $i = 0;
        $found = false;
        do {
            if ($users[$i]['email'] == $email) {
                $found = true;
            }
            $i++;
        } while (!$found and $i < count($users));

        if ($found) {
            //email exist deja
            $unePage = new Web_Page;
            $unePage->title = "Inscription a échoué";
            $unePage->content = self::register("Email existe deja choisis un autre email!", $_REQUEST);
            $unePage->render();
        } elseif ($pw !== $_POST['pw2']) {
            //mot de passe est different
            $unePage = new Web_Page;
            $unePage->title = "Inscription a échoué";
            $unePage->content = self::register("les 2 de passe ne sont pas identique!", $_REQUEST);
            $unePage->render();
        } else {
            //l'email choisi est correcte
            $unePage = new Web_Page;
            $unePage->content =  self::newAccount();
        }
    }


    static public function newAccount() // ajouter un nouveau utilisateur a la db
    {

        // echo Picture_Uploaded_Save_File("ma_photo", "users_images/");
        $DB = new db_pdo();
        $DB->connect();
        $pw = $_REQUEST['pw'];
        // pour encrypte le mot de passe => password_hash($pw, PASSWORD_DEFAULT)

        //Verification si l'image n'est pas uploader, mettre l'image "pas_photo.png" par defaut
        $imageName = basename($_FILES['ma_photo']['name']);
        if (Picture_Uploaded_Save_File("ma_photo", "users_images/") == "Error picture upload: code=4") {
            $imageName = "pas_photo.png";
        }
        $DB->query("INSERT INTO users (email , pw, level, fullname,country,language,picture) VALUES ('" . $_REQUEST['email'] . "','" . password_hash($pw, PASSWORD_DEFAULT) . "','client','" . $_REQUEST['fullname'] . "','" . $_REQUEST['country'] . "','" . $_REQUEST['radioChoix'] . "','" . $imageName . "')");

        // $DB->query("INSERT INTO users (email , pw, level, fullname,country,language,picture) VALUES ('" . $_REQUEST['email'] . "','" . $pw . "','client','" . $_REQUEST['fullname'] . "','" . $_REQUEST['country'] . "','" . $_REQUEST['radioChoix'] . "','" . $imageName . "')");

        $unePage = new Web_Page;
        $unePage->title = "Votre compte a été créé";

        $unePage->content =  "<h2> Votre compte a été créé, connecter vous avec " . $_REQUEST['email'] . "!</h2>";
        $unePage->render();
    }
}
