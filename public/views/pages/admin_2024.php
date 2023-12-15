<?php
session_start ();

use entity\userEntity;
require_once ('../../../entity/userEntity.php');
require_once ('../../../config/DatabaseManager.php');
require_once ('../../../controller/participationController.php');

$database = DatabaseManager::getInstance();

$user = new userEntity(
    databaseManager: $database
);

if (!isset($_SESSION['user_info'])) {
    header("Location: index.php");
    exit();
}

$user->recoverUserData($_SESSION['user_info']);

if ($user->loggedInUser[0]['role'] != 'ROLE_ADMIN') {
    header("Location: connexion.php");
    exit();
}

$particpations = recoverParticipation(
    databaseManager: $database
);

if (isset($_POST['participationpdf'])) {
    foreach ($particpations as $particpation) {
        if ($particpation['ID_Insription'] == $_POST['participationID']) {
            createPDF ($particpation);
            break;
        }
    }
}

if (isset($_POST['participationdelet'])) {
    deleteParticipation (
        databaseManager: $database,
        user: $user,
        idParticipation: $_POST['participationID']
    );
    foreach ($particpations as $key => $particpation) {
        if ($particpation['ID_Insription'] == $_POST['participationID']) {
            unset($particpations[$key]);
            break;
        }
    }
}

$displayParticipation = "";
foreach ($particpations as $particpation) {
    $displayParticipation .= <<<HTML
    <div>
        <p>{$particpation['Prenom_Enfant']} - Dossier n°{$particpation['ID_Insription']}</p>
        
        <form method="post" action="admin_2024.php">
            <input type="hidden" name="participationID" value="{$particpation['ID_Insription']}">
            <button type="submit" name="participationpdf">infos</button>
            <button type="submit" name="participationdelet">supprimer</button>
        </form>
    </div>
HTML;

}

?>


<?php

echo $displayParticipation;