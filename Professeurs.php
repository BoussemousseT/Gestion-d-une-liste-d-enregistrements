<?php
class Professeurs
{
    static public function list()
    {
        $DB = new db_pdo();
        $DB->connect();
        $web_page = new Web_Page;
        $web_page->title = "Liste des professeurs";

        if (!isset($_GET['order'])) {
            $professeurs = $DB->querySelect("SELECT * FROM professeurs ORDER BY Nom");   //order by nom par defaut
        } elseif ($_GET['order'] == "id") {
            $professeurs = $DB->querySelect("SELECT * FROM professeurs ORDER BY id");   //order by id
        } elseif ($_GET['order'] == "College") {
            $professeurs = $DB->querySelect("SELECT * FROM professeurs ORDER BY College");   //order by college
        } elseif ($_GET['order'] == "Region") {
            $professeurs = $DB->querySelect("SELECT * FROM professeurs ORDER BY Region");   //order by region
        } elseif ($_GET['order'] == "Nom") {
            $professeurs = $DB->querySelect("SELECT * FROM professeurs ORDER BY Nom");   //order by nom
        }

        $html = "";
        //nombre de resultat trouver
        $html .= '<span>Nombre de professeurs trouvés: ' . count($professeurs) . '</span>';

        //table professeurs
        $html .= '<table border=1 >';
        $html .= '<tr><th><a href = "index.php?op=1200&order=id">id</a></th><th><a href ="index.php?op=1200&order=Nom">Nom</a></th><th><a href = "index.php?op=1200&order=College">College</a></th><th><a href = "index.php?op=1200&order=Region">Region</a></th></tr>';

        foreach ($professeurs as $unProfesseur) {
            $html .= '<tr>';
            $html .= '<td>' . $unProfesseur['id'] . '</td>';
            $html .= '<td>' . $unProfesseur['Nom'] . '</td>';
            $html .= '<td>' . $unProfesseur['College'] . '</td>';
            $html .= '<td>' . $unProfesseur['Region'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $web_page->content = $html;
        $web_page->render();
    }
}
