<?php
class Customers
{
    static public function list()
    {
        $DB = new db_pdo();
        $DB->connect();
        // $customers = $DB->querySelect("SELECT * FROM customers");
        $web_page = new Web_Page;
        $web_page->title = "Liste des clients";
        // $web_page->content = tableHTML($customers);

        if (isset($_REQUEST['id']) and $_REQUEST['id'] !== "") {
            $customers = $DB->querySelectParam("SELECT * FROM customers WHERE id=?", [$_REQUEST['id']]);
        } else {
            //tous les clients par defaut
            $customers = $DB->querySelect("SELECT * FROM customers");
        }
        $html = "";

        $html .= '<div class="addSearchResult">';

        //ajouter un nouveau client
        $html .= '<span><a href="index.php?op=402"><img src="button_images/AddNewContact.png" alt="Add new contact"></a></span>';

        //formulaire de recherche
        $html .= '<div class="searchBar">';
        $html .= '<form action="index.php?op=400" method="POST">';
        $html .= '<input type ="text" name="id" min="0" step="1" placeholder="Entrez id recherchée">';
        $html .= '<input type="submit" value="Recherchez">';
        $html .= '</form>';
        $html .= '</div>';

        //nombre de resultat trouver
        $html .= '<span>Nombre d\'enregistrement trouver: ' . count($customers) . '</span>';
        $html .= '</div>';

        //table customers
        $html .= '<div class="listClient">';

        $html .= '<table >';
        $html .= '<tr><th>Id</th><th>Name</th><th>Last Name</th><th>First Name</th><th>Phone</th><th>Country</th><th>Actions</th></tr>';

        foreach ($customers as $unCustomer) {
            $html .= '<tr>';
            $html .= '<td>' . $unCustomer['id'] . '</td>';
            $html .= '<td class="nameColonne">' . $unCustomer['name'] . '</td>';
            $html .= '<td>' . $unCustomer['contactLastName'] . '</td>';
            $html .= '<td>' . $unCustomer['contactFirstName'] . '</td>';
            $html .= '<td>' . $unCustomer['phone'] . '</td>';
            $html .= '<td>' . $unCustomer['country'] . '</td>';
            $html .= '<td class="actionsColonne"><a href="index.php?op=401&id=' . $unCustomer['id'] . '"><img src="button_images/DisplayContact.png" alt="Afficher le client"></a>';
            $html .= '<a href="index.php?op=402&id=' . $unCustomer['id'] . '"><img src="button_images/ModifieContact.png" alt="Modifie le client"></a>';
            $html .= '<a href="index.php?op=404&id=' . $unCustomer['id'] . '"><img src="button_images/SupprimerContact.png" alt="Supprimer le client"></a></td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        $web_page->content = $html;
        $web_page->render();
    }



    static public function display($data = [])
    {
        if ($data == []) {
            if (isset($_REQUEST['id'])) {
                $DB = new db_pdo();
                $DB->connect();
                $data = $DB->querySelectParam("SELECT * FROM customers WHERE id =?", [$_REQUEST['id']]);

                if ($data == []) {
                    // user inexistant
                    http_response_code(404);
                    exit("erreur id inexistant");
                }
                $data = $data[0];
            }
        }
        $web_page = new Web_Page;
        $web_page->title = "Affichage d'informations du client";

        $html = "";
        $html .= "<div class=formulaireInfo>";
        $html .= "<div class=display>";

        //formulaire
        $html .= '<form action="index.php?op=402" method="POST">';

        $html .=  '<div><span> ID:</span><input class="id" type="number" name="id" maxlength="11" readonly required value =\'' . $data['id'] . '\'  ></div>';
        $html .=  '<div>';

        $html .=  '<fieldset>';
        $html .=  '<div><span> NAME:</span><input type="text" name="name" maxlength="50"  required value =\'' . $data['name'] . '\'></div>';
        $html .=  '<div><span> LAST NAME:</span><input type="text" name="contactLastName" readonly maxlength="50"  required value =\'' . $data['contactLastName'] . '\'></div>';
        $html .=  '<div><span> FIRST NAME:</span><input type="text" name="contactFirstName" readonly maxlength="50"  required value =\'' . $data['contactFirstName'] . '\'></div>';
        $html .=  '<div><span> PHONE:</span><input type="text" name="phone" maxlength="50"  readonly required value =\'' . $data['phone'] . '\'></div>';
        $html .=  '<div><span> ADRESSE 1:</span><input type="text" name="addressLine1" maxlength="50" readonly required value =\'' . $data['addressLine1'] . '\'></div>';
        $html .=  '<div><span> ADRESSE 2:</span><input type="text" name="addressLine2" maxlength="50" readonly value =\'' . $data['addressLine2'] . '\'></div>';
        $html .=  '</fieldset>';
        $html .=  '<fieldset>';
        $html .=  '<div><span> CITY:</span><input type="text" name="city" maxlength="50" maxlength="50" readonly required value =\'' . $data['city'] . '\'></div>';
        $html .=  '<div><span> STATE:</span><input type="text" name="state" maxlength="50" readonly value =\'' . $data['state'] . '\'></div>';
        $html .=  '<div><span> POSTAL CODE:</span><input type="text" name="postalCode" maxlength="15" readonly value =\'' . $data['postalCode'] . '\'></div>';
        $html .=  '<div><span> COUNTRY:</span><input type="text" name="country" maxlength="50" min="0" readonly required value =\'' . $data['country'] . '\'></div>';
        $html .=  '<div><span> SALES EMPLOYEE NUMBER:</span><input type="number" name="salesRepEmployeeNumber" maxlength="11" min="0" readonly value =\'' . $data['salesRepEmployeeNumber'] . '\'></div>';
        $html .=  '<div><span> CREDIT LIMIT:</span><input type="number" name="creditLimit" min="0" max="1000000" step="0.01" readonly value =\'' . $data['creditLimit'] . '\'></div>';
        $html .=  '</fieldset>';
        $html .= "</div>";

        $html .= '<button>Modifie</button> <button type="button" onclick="history.back();">Annuler</button> ';

        $html .= '</form>';
        $html .= "</div>";

        $html .= "</div>";


        $web_page->content = $html;
        $web_page->render();
    }

    static function edit($errMsg = "", $data = [])
    {

        $unePage = new Web_Page;
        $DB = new db_pdo;
        $DB->connect();


        if ($data == []) {
            if (isset($_REQUEST['id'])) {
                // edit un existant
                $unePage->title = "Modification des informations";
                $buttonName = "Sauvegarder";
                $idMsg = "*";   //au momemt de modifie client
                $data = $DB->querySelectParam("SELECT * FROM customers WHERE id=?", [$_REQUEST['id']]);
                if ($data == []) {
                    // user inexistant
                    http_response_code(404);
                    exit("erreur id inexistant");
                }
                $data = $data[0]; //premiere ligne de la table car juste 1 de toute façon
            } else {
                // ajoute un nouveau usager
                $unePage->title = "Ajoute un nouveau client";
                $buttonName = "Ajouter";
                $idMsg = "(Generated automatically)*"; //au momemt d'ajouter un client
                $data = [
                    'id' => -1, //nouveau
                    'name' => "",
                    'contactLastName' => "",
                    'contactFirstName' => "",
                    'phone' => "",
                    'addressLine1' => '',
                    'addressLine2' => '',
                    'city' => '',
                    'state' => '',
                    'postalCode' => '',
                    'country' => '',
                    'salesRepEmployeeNumber' => '',
                    'creditLimit' => '0'
                ];
            }
        }

        $employees = $DB->querySelect("SELECT id FROM employees");
        $salesRepEmployeeNumber = '<select name="salesRepEmployeeNumber" >';
        if (isset($data['salesRepEmployeeNumber']) && $data['salesRepEmployeeNumber'] !== "") {
            //pour affichier salesRepEmployeeNumber qui appartient au client sinon il va afficher la premiere ligne par defaut
            $salesRepEmployeeNumber .= '<option  value="' . $data['salesRepEmployeeNumber'] . '">' . $data['salesRepEmployeeNumber'] . '</option>';
        }
        //pour afficher la liste des id de la table employees

        foreach ($employees as $unEmployee) {
            $salesRepEmployeeNumber .= '<option  value="' . $unEmployee['id'] . '">' . $unEmployee['id'] . '</option>';
        }
        $salesRepEmployeeNumber .= '</select>';


        $html = "";
        $html .= "<div class=formulaireInfo>";

        //error msg
        $html .= '<div class="errMsg">' . $errMsg . '</div>';

        //formulaire
        $html .= '<form action="index.php?op=403" method="POST">';

        $html .=  '<div><span> ID:<span>' . $idMsg . '</span></span><input class="id" type="number" name="id" maxlength="11" readonly required value =\'' . $data['id'] . '\'  ></div>';
        $html .=  '<div>';

        $html .=  '<fieldset>';
        $html .=  '<div><span> NAME:<span>*</span></span><input type="text" name="name" maxlength="50"  required value =\'' . $data['name'] . '\'></div>';
        $html .=  '<div><span> LAST NAME:<span>*</span></span><input type="text" name="contactLastName" maxlength="50"  required value =\'' . $data['contactLastName'] . '\'></div>';
        $html .=  '<div><span> FIRST NAME:<span>*</span></span><input type="text" name="contactFirstName" maxlength="50"  required value =\'' . $data['contactFirstName'] . '\'></div>';
        $html .=  '<div><span> PHONE:<span>*</span></span><input type="text" name="phone" maxlength="50"  required value =\'' . $data['phone'] . '\'></div>';
        $html .=  '<div><span> ADRESSE 1:<span>*</span></span><input type="text" name="addressLine1" maxlength="50"  required value =\'' . $data['addressLine1'] . '\'></div>';
        $html .=  '<div><span> ADRESSE 2:</span><input type="text" name="addressLine2" maxlength="50"  value =\'' . $data['addressLine2'] . '\'></div>';
        $html .=  '</fieldset>';
        $html .=  '<fieldset>';
        $html .=  '<div><span> CITY:<span>*</span></span><input type="text" name="city" maxlength="50" maxlength="50"  required value =\'' . $data['city'] . '\'></div>';
        $html .=  '<div><span> STATE:</span><input type="text" name="state" maxlength="50"  value =\'' . $data['state'] . '\'></div>';
        $html .=  '<div><span> POSTAL CODE:</span><input type="text" name="postalCode" maxlength="15"  value =\'' . $data['postalCode'] . '\'></div>';
        $html .=  '<div><span> COUNTRY:<span>*</span></span><input type="text" name="country" maxlength="50" min="0"  required value =\'' . $data['country'] . '\'></div>';
        $html .=  '<div class="employeeNumber" ><span>EMPLOYEE NUMBER:</span>' . $salesRepEmployeeNumber . '</div>';
        $html .=  '<div><span> CREDIT LIMIT:</span><input type="number" name="creditLimit" min="0" max="1000000" step="0.01" value =\'' . $data['creditLimit'] . '\'></div>';
        $html .=  '</fieldset>';
        $html .= "</div>";

        $html .= '<button>' . $buttonName . '</button> <button type="button" onclick="history.back();">Annuler</button> ';

        $html .= '</form>';
        $html .= "</div>";

        $unePage->content = $html;
        $unePage->render();
    }


    static function save()
    {

        $errMsg = '';
        /**
         * verifier les informations reçu et nettoyer
         * */
        //NAME:
        $name = htmlspecialchars($_REQUEST['name']);
        if (!isset($name)) {
            $errMsg .= 'Remplir le champ "NAME"!';
        } elseif (strlen($name) > 50) {
            $errMsg .= 'Le text entrer dans le champ "NAME" est trop long, max 50 caractères.';
        }

        //LAST NAME:
        $contactLastName = htmlspecialchars($_REQUEST['contactLastName']);
        if (!isset($contactLastName)) {
            $errMsg .= 'Remplir le champ "LAST NAME"!';
        } elseif (strlen($contactLastName) > 50) {
            $errMsg .= 'Le text entrer dans le champ "LAST NAME" est trop long, max 50 caractères.';
        }

        //First NAME:
        $contactFirstName = htmlspecialchars($_REQUEST['contactFirstName']);
        if (!isset($contactFirstName)) {
            $errMsg .= 'Remplir le champ "First NAME"!';
        } elseif (strlen($contactFirstName) > 50) {
            $errMsg .= 'Le text entrer dans le champ "First NAME" est trop long, max 50 caractères.';
        }

        //PHONE:
        $phone = htmlspecialchars($_REQUEST['phone']);
        if (!isset($phone)) {
            $errMsg .= 'Remplir le champ "PHONE"!';
        } elseif (strlen($phone) > 50) {
            $errMsg .= 'Le text entrer dans le champ "PHONE" est trop long';
        }

        //ADRESSE 1:
        $addressLine1 = htmlspecialchars($_REQUEST['addressLine1']);
        if (!isset($addressLine1)) {
            $errMsg .= 'Remplir le champ "ADRESSE 1"!';
        } elseif (strlen($addressLine1) > 50) {
            $errMsg .= 'Le text entrer dans le champ "ADRESSE 1" est trop long, max 50 caractères.';
        }

        //ADRESSE 2:
        $addressLine2 = htmlspecialchars($_REQUEST['addressLine2']);
        if (strlen($addressLine2) > 50) {
            $errMsg .= 'Le nom entrer dans le champ "ADRESSE 2" est trop long, max 50 caractères.';
        }

        //CITY:
        $city = htmlspecialchars($_REQUEST['city']);
        if (!isset($city)) {
            $errMsg .= 'Remplir le champ "CITY"!';
        } elseif (strlen($city) > 50) {
            $errMsg .= 'Le text entrer dans le champ "CITY" est trop long, max 50 caractères.';
        }

        //STATE:
        $state = htmlspecialchars($_REQUEST['state']);
        if (strlen($state) > 50) {
            $errMsg .= 'Le text entrer dans le champ "STATE" est trop long, max 50 caractères.';
        }

        //POSTAL CODE:
        $postalCode = htmlspecialchars($_REQUEST['postalCode']);
        if (strlen($postalCode) > 15) {
            $errMsg .= 'Le text entrer dans le champ "POSTAL CODE" est trop long, max 15 caractères.';
        }

        //COUNTRY:
        $country = htmlspecialchars($_REQUEST['country']);
        if (!isset($country)) {
            $errMsg .= 'Remplir le champ "COUNTRY"!';
        } elseif (strlen($country) > 50) {
            $errMsg .= 'Le text entrer dans le champ "COUNTRY" est trop long, max 50 caractères.';
        }


        //SALES EMPLOYEE NUMBER:
        $salesRepEmployeeNumber = $_REQUEST['salesRepEmployeeNumber'];
        if (strlen($salesRepEmployeeNumber) > 11) {
            $errMsg .= 'Le chiffre entrer dans le champ "SALES EMPLOYEE NUMBER" est trop long, max 11 caractères.';
        }

        //CREDIT LIMIT:
        $creditLimit = $_REQUEST['creditLimit'];
        if ($creditLimit > 1000000) {
            $errMsg .= 'Le chiffre entrer dans le champ "CREDIT LIMIT" est plus grand que 1000000.';
        }

        if ($errMsg != '') {
            // reaffiche le formulaire
            self::edit($errMsg, $_REQUEST);
        } else {
            // TOUT OK
            if ($_REQUEST['id'] == -1) {

                // inérer un nouvel usager
                $DB = new db_pdo;
                $DB->connect();

                //trouve le prochain id
                $max = $DB->querySelect("SELECT MAX(id) as max FROM customers");
                $max = $max[0]['max'];
                $id = $max + 1;

                $DB->queryParam(
                    "INSERT INTO customers (id,name,contactLastName,contactFirstName,phone,addressLine1,addressLine2,city,state,postalCode,country,salesRepEmployeeNumber,creditLimit) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
                    [
                        $id,
                        $name,
                        $contactLastName,
                        $contactFirstName,
                        $phone,
                        $addressLine1,
                        $addressLine2,
                        $city,
                        $state,
                        $postalCode,
                        $country,
                        $salesRepEmployeeNumber,
                        $creditLimit
                    ]
                );

                $une_page = new Web_Page;
                // $une_page->title = "Votre compte a été créé";
                $une_page->content = header('location: index.php?op=400');

                $une_page->render();
            } else {
                $DB = new db_pdo;
                $DB->connect();
                $DB->queryParam("UPDATE customers SET name ='" . $name . "',
                    contactLastName ='" . $contactLastName . "',
                    contactFirstName ='" . $contactFirstName . "',
                    phone ='" . $phone . "',
                    addressLine1 ='" . $addressLine1 . "',
                    addressLine2 ='" . $addressLine2 . "',
                    city ='" . $city . "',
                    state ='" . $state . "',
                    postalCode ='" . $postalCode . "',
                    country ='" . $country .  "',
                    salesRepEmployeeNumber ='" . $salesRepEmployeeNumber . "',
                    creditLimit ='" . $creditLimit . "'
                    WHERE id =?", [$_REQUEST['id']]);
                $une_page = new Web_Page;
                // $une_page->title = "Votre profile a été modifié";
                $une_page->content = header('location: index.php?op=400');
                $une_page->render();
            }
        }
    }

    static public function delete()
    {
        $DB = new db_pdo();
        $DB->connect();
        //Delete client
        $DB->query("DELETE FROM customers WHERE id =" . $_REQUEST['id']);
        $une_page = new Web_Page;
        $une_page->content = header('location: index.php?op=400');
    }

    static public function listJSON()
    {
        $DB = new db_pdo();
        $DB->connect();
        $customers = $DB->querySelect('SELECT * FROM customers');
        $customersJson = json_encode($customers, JSON_PRETTY_PRINT);
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(200); // ou autre au besoin
        echo $customersJson; // output the data in JSON format
    }
}
