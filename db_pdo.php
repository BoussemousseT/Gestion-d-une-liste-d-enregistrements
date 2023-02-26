<?php
class db_pdo
{
    // //local
    private const DB_SERVER_TYPE = 'mysql'; // MySQL or MariaDB server
    private const DB_HOST = '127.0.0.1';    // local server on my laptop
    private const DB_PORT = 3307;           // optional, default 3306, use 3307 for MariaDB
    private const DB_NAME = 'classicmodels'; // for Database classicmodels
    private const DB_CHARSET = 'utf8mb4';  // pour français correct

    private const DB_USER_NAME = 'site_web_classic_models';    // if not root it must have been previously created on DB server
    private const DB_PASSWORD = '12345678';


    // // public
    // private const DB_SERVER_TYPE = 'mysql'; // MySQL or MariaDB server
    // private const DB_HOST = 'sql306.epizy.com';    // local server on my laptop
    // private const DB_PORT = 3306;           // optional, default 3306, use 3307 for MariaDB
    // private const DB_NAME = 'epiz_33462408_classicmodels'; // for Database classicmodels
    // private const DB_CHARSET = 'utf8mb4';  // pour français correct

    // private const DB_USER_NAME = 'epiz_33462408';    // if not root it must have been previously created on DB server
    // private const DB_PASSWORD = 'Ylm7sDRkYe7HBf';

    // PDO connection options
    private const DB_OPTIONS = [
        // throw exception on SQL errors
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // return records with associative keys only, no numeric index
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    private $connection = null;
    public function connect()
    {
        $DSN = self::DB_SERVER_TYPE . ':host=' . self::DB_HOST . ';port=' . self::DB_PORT . ';dbname=' . self::DB_NAME . ';charset=' . self::DB_CHARSET;
        try {
            $this->connection = new PDO($DSN, self::DB_USER_NAME, self::DB_PASSWORD, self::DB_OPTIONS);
            // echo 'OK connecté a la DB';
        } catch (PDOException $e) {
            //envoit email a responsable ...  fonctionne pas qvec WAMP car pas de serveur de email SMTP !!
            // mail(
            //     'boussemoussetaoufik@gmail.com',
            //     'Dude to serveur SQL est crash',
            //     'Cher Taoufik, je ne sais pas si tu es au courant mais nous somme dans la merde!!!'
            // );
            http_response_code(500); // internal server error
            exit('DB connection Error : ' . $e->getMessage());    //mettre cette ligne en comment si tu vx continu normal
        }
    }
    public function DB_disconnect()
    {
        $this->connection = null; // closing DB connection
    }

    /**
     * Pour les requetes INSER, UPDATE ou DELETE
     * Retourne un objet PDOstatement ou null si erreur
     */
    public function query($sql)
    {
        try {
            $result = $this->connection->query($sql);
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Erreur requete SQL: ' . $e->getMessage();
            exit();
        }
        return $result;
    }

    /**
     * Pour les requetes SELECT retourne le tableau des resultat
     * Retourne un objet PDOstatement ou null si erreur
     */
    public function querySelect($sql)
    {
        try {
            $result = $this->connection->query($sql);
            return $result->fetchAll();
        } catch (PDOException $e) {
            http_response_code(500);
            echo 'Erreur requete SQL: ' . $e->getMessage();
            exit();
        }
    }

    /**
     * queryParam() for INSERT, UPDATE, DELETE returning no records.
     * with parameters for protection against SQL injection.
     */
    public function queryParam($sql_str, $params)
    {
        try {
            $stmt = $this->connection->prepare($sql_str);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            // SQL syntax error for example
            http_response_code(500);
            exit("SQL Query Error : " . $e->getMessage());
        }
        return $stmt;
    }

    /**
     * querySelectParam() for SELECT query returning records
     * with parameters for protection against SQL injection.
     */
    public function querySelectParam($sql_str, $params)
    {
        try {
            $stmt = $this->connection->prepare($sql_str);
            $stmt->execute($params);
            $records = $stmt->fetchAll();
        } catch (\PDOException $e) {
            // SQL syntax error for example
            http_response_code(500);
            exit("SQL Query Error : " . $e->getMessage());
        }
        return $records;
    }
}
