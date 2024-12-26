<?php


function login(string $user, string $pass): bool
{

    try {

        $consulta = "select * from usuario where email = ?";
        $conProyecto = getConnection();
        $stmt = $conProyecto->prepare($consulta);

        $stmt->bindValue(1, $user);

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario === false) {
            addError("Error, Nombre de usuario o password incorrecto");
            return false;
        } else {
            return password_verify($pass, $usuario["pwdhash"]);
        }
    } catch (Exception $ex) {
        error_log("Ha ocurrido una excepción en login $user: " . $ex->getTraceAsString());
        addError("Error en la consulta a la base de datos.");
    } finally {
        $conProyecto = null;
        $stmt = null;
    }
    return false;

}



function countProductosByCatId(int $catId): int|false
{

    try {
        $con = getConnection();
        $stmt = $con->prepare("select count(*) from products_categories where CategoryID = ?");
        $stmt->execute([$catId]);
        $count = $stmt->fetch(PDO::FETCH_NUM);
//Si no hay resultados devuelve false
       if ($count !== false)
            return $count[0];
        else {
            return 0;
        }
    } finally {
        $con = null;
        $stmt = null;
    }


}




/*
Función auxiliar para amosar datos de éxit con Bootstrap
*/

function mostrarMsg(string $msg, string $type)
{
    echo "<div class=\"alert alert-{$type}\" role=\"alert\">
  $msg
</div>";
}

/* Funcións relacionadas coa sesión */

function iniciarSesion(): bool
{
    $iniciada = true;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $iniciada = session_start();
    }
    return $iniciada;
}


/* Funcións para ser creadas na proba
 */

 function readIniFile($file = "db_settings.ini"): array
 {
     //https://www.php.net/manual/es/function.parse-ini-file.php
 //carga el fichero ini especificado en $file, y devuelve las configuraciones que hay en él a un array asociativo $settings 
 //o false si hay algún error y no consigue leer el fichero. 
     if (!$settings = parse_ini_file($file, TRUE))
         throw new exception('Unable to open ' . $file . '.');
     return $settings;
 }
 
 function getConnection(): PDO
 {
     //leemos datos del ini file en un array asociativo
     $settings = readIniFile();
 
     //Creamos cadena de conexión concatenando
     $dsn = $settings['database']['driver'] .
         ':host=' . $settings['database']['host'] .
         ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
         ';dbname=' . $settings['database']['schema'];
 
     //Creamos el objeto PDO
     $conn = new PDO($dsn, $settings['database']['username'], $settings['database']['password']);
     return $conn;
 
 }
 



 function getCategories(): array|null
 {
 
     try {
         $con = getConnection();
         $stmt = $con->prepare("select CategoryID, CategoryName from categories order by CategoryName");
         $stmt->execute();
         $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
         return $results;
     } catch (Exception $exception) {
         error_log("Ha surgido una excepción. No se han podido recuperar las categorías: " . $exception->getTraceAsString());
         addError("Non se puideron recuperar as categorías");
         return null;
     } finally {
         $con = null;
         $stmt = null;
     }
 
 
 }
 function addError(string $msg)
 {
     iniciarSesion();
     $_SESSION["error"][] = $msg;
 }
 

function mostrarError()
{
    if (isset($_SESSION['error'])) {
        echo "<div class=\"alert alert-danger\" role=\"alert\">";
        foreach ($_SESSION["error"] as $error) {
            echo $error;
        }

        unset($_SESSION['error']);
        echo "</div>";
    }
}









function register(string $email, string $pass): bool
{

    try {
        if (findUserByEmail($email) === true) {
            addError("El email ya existe");
            return false;
        }
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO usuario(email, pwdhash) VALUES (?, ?) ");

        $pwdhash = password_hash($pass, PASSWORD_BCRYPT);

        $stmt->execute([$email, $pwdhash]);
        return $stmt->rowCount() == 1;
    } catch (Exception $ex) {
        error_log("No se ha podido crear el usuario $email: " . $ex->getTraceAsString());
        addError("No se ha podido crear el usuario");
        return false;
    } finally {
        $conn = false;
        $stmt = false;
    }

}

/**
 * Identifica si ya existe un usuario con el email de entrada.
 * @param string $email email por el que se buscará el usuario.
 * @return bool Devuelve true si se encuentra al menos un usuario con ese email o si surge una excepción, false en caso contrario.
 */
function findUserByEmail(string $email): bool
{
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * from usuario where email=:email");



        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt->rowCount() >= 1 && $user !== false;

    } catch (Exception $ex) {
        error_log("No se ha podido recuperar el usuario $email: " . $ex->getTraceAsString());
        addError("No se ha podido recuperar el usuario");
        return true;
    } finally {
        $conn = false;
        $stmt = false;
    }
}
