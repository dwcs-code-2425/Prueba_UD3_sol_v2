<?php
require "util.php";


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
        error_log("Ha ocurrido una excepción en login $email: " . $ex->getTraceAsString());
        addError("Error en la consulta a la base de datos.");
    } finally {
        $conProyecto = null;
        $stmt = null;
    }
    return false;

}

function getUsuarios(): array|null
{

    try {
        $con = getConnection();
        $stmt = $con->prepare("select * from usuario order by email");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    } catch (Exception $exception) {
        addError("Non se puideron recuperar os usuarios");
        return null;
    }


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

function countProductosByCatId(int $catId): int|false
{

    try {
        $con = getConnection();
        $stmt = $con->prepare("select count(*) from products_categories where CategoryID = ?");
        $stmt->execute([$catId]);
        $count = $stmt->fetch(PDO::FETCH_NUM);

        //OJO, QUE SI NO HAY RESULTADOS DEVUELVE FALSE Y HAY QUE DISTINGUIRLO DE QUE HAYA UN ERROR
        if ($count !== false)
            return $count[0];
        else{
            return 0;
        }
    } finally {
        $con = null;
        $stmt = null;
    }


}



/**
 * Recupera todos os datos da táboa usuarios pola clave primaria.
 * @param int $id id do usuario a recuperar.
 * @return array|false Devolve un array asociativo se se atopa o usuario por id. Devolve false en caso contrario.
 */
function getUserById(int $id): array|false
{
    try {
        $consulta = "select * from usuario where id = ?";
        $conProyecto = getConnection();
        $stmt = $conProyecto->prepare($consulta);



        $stmt->bindValue(1, $id);

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


        return $usuario;
    } catch (Exception $ex) {
        error_log("No se ha podido recuperar el usuario con id: $id " . $ex->getTraceAsString());
        addError("No se ha podido recuperar el usuario");
        return false;
    }

}

function updatePass(int $id, string $newpwd1): bool
{

    try {
        $con = getConnection();
        $stmt = $con->prepare("UPDATE usuario set pwdhash= ? where id = ?");
        $newpwd1_hashed = password_hash($newpwd1, PASSWORD_BCRYPT);
        $stmt->execute([$newpwd1_hashed, $id]);
        return $stmt->rowCount() == 1;
    } catch (Exception $ex) {
        error_log("No se ha podido actualizar la contraseña del usuario con email: $email " . $ex->getTraceAsString());
        addError("No se ha podido actualizar la contraseña del usuario");
        return false;
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



function cerrarSesion()
{
    //Tal y como se recomienda en https://www.php.net/manual/es/function.session-destroy.php
    iniciarSesion();

    //Vaciamos el array
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        //obtenemos los parámetros de creación de la cookie de sesión
        $params = session_get_cookie_params();
        //borramos la cookie de sesión
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    //Eliminamos los datos relacionados con la sesión en el almacenamiento servidor 
    session_destroy();

    //Eliminamos la cookie de noMostrar
    setcookie("noMostrar", "", time() - 1000);

}

/* Funcións para ser creadas na proba
 */




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



