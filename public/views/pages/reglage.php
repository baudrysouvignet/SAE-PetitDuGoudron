<?php
session_start ();


function disconnect (): void
{
    session_destroy();
    header ("Location: connexion.php");
    exit();
}

if (isset($_POST['deconnexion']) || !isset($_SESSION['user_info'])) {
    disconnect();
}

use entity\userEntity;
require_once ('../../../entity/userEntity.php');
require_once ('../../../config/DatabaseManager.php');

$database = DatabaseManager::getInstance();
$user = new userEntity(
    databaseManager: $database
);
$user->recoverUserData ($_SESSION['user_info']);

if (isset($_POST['changePassword'])) {
    $passwordIsChanged = $user->updatePassword (
        oldPassword: $_POST['oldPassword'],
        newPassword: $_POST['newPassword']
    );
}

if (isset($_POST['changeMail'])) {
    $mailIsChanged = $user->updateMail (
        password: $_POST['password'],
        newMail: $_POST['newMail']
    );
}

?>

<h1><?= $user->loggedInUser[0]['mail'] ?></h1>

<form action="reglage.php" method="post">
    <label for="oldPassword">Ancien mot de passe</label>
    <input type="password" name="oldPassword" id="oldPassword" placeholder="Ancien mot de passe">

    <label for="newPassword">Nouveau mot de passe</label>
    <input type="password" name="newPassword" id="newPassword" placeholder="Nouveau mot de passe">

    <input type="submit" name="changePassword" value="Changer le mot de passe">
</form>

<?php
if (isset($passwordIsChanged)) {
    if ($passwordIsChanged) {
        echo "<p>Le mot de passe à bien été changé</p>";
    } else {
        echo "<p>Le mot de passe n'a pas pu être changé</p>";
    }
}
?>

<form action="reglage.php" method="post">
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Mot de passe">

    <label for="newMail">Nouveau mail</label>
    <input type="text" name="newMail" id="newMail" placeholder="Nouveau mail" autocomplete="off">

    <input type="submit" name="changeMail" value="Changer le mail">
</form>

<?php
if (isset($mailIsChanged)) {
    if ($mailIsChanged) {
        echo "<p>Le mail à bien été changé</p>";
    } else {
        echo "<p>Le mail n'a pas pu être changé</p>";
    }
}
?>

<form action="reglage.php" method="post">
    <input type="submit" name="deconnexion" value="Déconnexion">
</form>
