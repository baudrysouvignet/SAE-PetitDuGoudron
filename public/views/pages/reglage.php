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
require_once ('../../../controller/participationController.php');

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

//----------------
// Partciaptaion Gestion
//----------------

$form = recoverParticipation(
        databaseManager: $database,
        user:$user
);

//mettre en forme les data de la participation dans un tableau pres pour editParticipation
$formData = [];
foreach ($form  as $value) {
    $formData[] = [
            "firstName" => $value['Prenom_Enfant'],
            "date" => $value['Date_Naissance'],
            "sexe" => $value['Sexe'],
            "adress" => $value['Adresse'],
            "phone" => $value['Telephone'],
            "email" => $value['Email'],
            "allergy" => $value['Allergies'],
            "health" => $value['Conditions_Medicales'],
            "drug" => $value['Medicaments'],
            "doctorPhone" => $value['Telephone_Medecin'],
            "secu" => $value['Num_Secu'],
            "autorisation_parentale" => $value['Autorisation_Parentale'],
            "id_utilisateur" => $value['ID_Utilisateur'],
            "ID_Insription" => $value['ID_Insription']
    ];
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

if (isset($_POST['participer'])) {
    $data = [
        "firstName" => $_POST['firstName'],
        "date" => $_POST['date'],
        "sexe" => (int)$_POST['sexe'],
        "adress" => $_POST['adresse'],
        "phone" => $_POST['phone'],
        "email" => $_POST['email'],
        "allergy" => $_POST['allergy'],
        "health" => $_POST['health'],
        "drug" => $_POST['drug'],
        "doctorPhone" => (int)$_POST['doctorPhone'],
        "secu" => (int)$_POST['secu'],
        "autorisation_parentale" => $_POST['autorisation_parentale'],
        "id_utilisateur" => $_POST['id_utilisateur'],
        "id_form" => $_POST['id_form']
    ];

    editParticipation(
        databaseManager: $database,
        data: $data,
        user: $user,
        idParticipation: $_POST['id_form']
    );

    header ("Location: reglage.php");
    exit();
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


<?php
foreach ($formData as $key => $form) {
    $infoForm = $form;
    $id = $formData[$key]['ID_Insription'];
    $btnName = "Enregistrer (".$infoForm['firstName'].")";
    $field = 'reglage.php';

    echo '<div class="'.$id.'"><h3>Inscription N° '.$id.'</h3>';
    include ('../../../partials/participationForm.php');
    echo '</div>';
}
?>