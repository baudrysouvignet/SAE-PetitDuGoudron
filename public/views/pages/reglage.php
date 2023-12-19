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
<!doctype html>
<html lang="fr">
<head>
    <?php include '../partials/head.php'?>
    <title>Réglages</title>
    <link rel="stylesheet" href='../../styles/pages/reglage.css'>
    <link rel="stylesheet" href='../../styles/partials/participationForm.css'>
</head>
<body>
<?php include '../partials/nav.php'?>

<main>
    <?php
    if (isset($passwordIsChanged)) {
        if ($passwordIsChanged) {
            echo "<p class='info ok'>Le mot de passe à bien été changé</p>";
        } else {
            echo "<p class='info no'>Le mot de passe n'a pas pu être changé</p>";
        }
    }
    ?>
    <h1>Paramètre du compte</h1>
    <h2><?= $user->loggedInUser[0]['mail'] ?> <?= $user->loggedInUser[0]['role'] == 'ROLE_ADMIN' ? '(admin)' : '' ?></h2>

    <form class="disconnect" action="reglage.php" method="post">
        <input class="button" type="submit" name="deconnexion" value="Déconnexion">
    </form>

    <form action="reglage.php" method="post">
        <div>
            <label for="oldPassword">Ancien mot de passe</label>
            <input type="password" name="oldPassword" id="oldPassword" placeholder="Ancien mot de passe">
        </div>

        <div>
            <label for="newPassword">Nouveau mot de passe</label>
            <input type="password" name="newPassword" id="newPassword" placeholder="Nouveau mot de passe">
        </div>
        <input class="button" type="submit" name="changePassword" value="Modifier">
    </form>

    <form action="reglage.php" method="post">
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe">
        </div>
        <div>
            <label for="newMail">Nouveau mail</label>
            <input type="text" name="newMail" id="newMail" placeholder="Nouveau mail" autocomplete="off">
        </div>
        <input class="button"  type="submit" name="changeMail" value="Modifier">
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

    <?php
    if (count ($formData) > 0) {
        echo "<h2 class='part'>Vos dossiers d'inscription </h2>";
    }
    ?>

    <?php
    foreach ($formData as $key => $form) {
        $infoForm = $form;
        $id = $formData[$key]['ID_Insription'];
        $btnName = "Enregistrer (".$infoForm['firstName'].")";
        $field = 'reglage.php';

        echo '<div class="itemsInsc '.$id.'"><h3>Inscription N° '.$id.'</h3>';
        include ('../../../partials/participationForm.php');
        echo '</div>';
    }
    ?>
</main>
<?php include '../partials/footer.php'?>
</body>

