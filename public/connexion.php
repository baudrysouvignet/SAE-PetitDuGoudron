<?php
session_start ();


use entity\userEntity;
$isError = false;
$isCreated = null;

/*Function For Link Between Controller And Entity*/
function loginBtn(
    userEntity $user
): void
{
    global $isError;

    if (isset($_SESSION['user_info'])) {
        header ("Location: reglage.php");
    } else {
        connection(
            user: $user,
            mail: $_POST['mail'],
            password: $_POST['password']
        );

        if (!$user->loggedInUser) {
            $isError = true;
        } else {
            header ("Location: reglage.php");
        }
    }
}

function inscriptionBtn(
    userEntity $user,
    DatabaseManager $database
): void
{
    global $isCreated;
    $isCreated = inscription(
        user: $user,
        database: $database,
        mail: $_POST['mail'],
        password: $_POST['password'],
    );
}

/*Condition For Login Or Register*/
if (isset($_POST['mail']) && isset($_POST['password'])){
    require_once '../config/DatabaseManager.php';

    require_once '../controller/userController.php';
    require_once '../entity/userEntity.php';

    $database = DatabaseManager::getInstance();
    $user = new userEntity(
        databaseManager: $database
    );

    if (isset($_POST['login'])) {
        loginBtn ($user);
    } else if (isset($_POST['register'])) {
         inscriptionBtn (
            user: $user,
            database: $database
        );
    }
}

if (isset($_SESSION['user_info'])) {
    header ("Location: reglage.php");
}
?>

<form action="connexion.php" method="post">
    <label for="mail">Mail</label>
    <input type="text" name="mail" id="mail" placeholder="mail">

    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="password">

    <input type="submit" name="login" value="Se connecter">
    <input type="submit" name="register" value="S'inscire">
</form>

<?php
if ($isError) {
   echo "<p>Identifiants ou mot de passe incorrect</p>";
}
?>

<?php
if ($isCreated) {
    echo "<p>Le compte à bien été crée</p>";
} else if ($isCreated === false) {
    echo "<p>Le compte n'a pas pu être crée</p>";
}
?>
