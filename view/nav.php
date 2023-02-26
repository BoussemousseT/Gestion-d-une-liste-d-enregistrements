    <!-- PAGE NAVIGATION -->
    <nav>
        <a href='index.php'>Accueil</a>
        |
        <a href='index.php?op=page_produits'>Product List</a>
        |
        <a href='index.php?op=catalogue_produits'>Product Catalogue</a>
        |
        <a href='index.php?op=50'>Download PDF</a>
        |
        <a href='index.php?op=51'>Redirect</a>
        |
        <?php
        if (!isset($_SESSION['email'])) {
            echo "<a href='index.php?op=1'>Login</a>";
            echo " | ";
            echo "<a href='index.php?op=3'>Register</a>";
        } else {
            echo "<a href='index.php?op=400'>Customers</a>";        //affiche la table customers au moment du login
            echo " | ";
            echo "<a href='index.php?op=1200'>Professeurs</a>";        //affiche la table professeurs au moment du login
            echo " | ";
            echo "<a href='index.php?op=5'>Logout</a><span>" . $_SESSION['email'] . "</span>";
            echo "<img class='imageProfil' src='users_images\\" . $_SESSION['picture'] . "' alt='photo usager'>";
        }
        ?>



    </nav>