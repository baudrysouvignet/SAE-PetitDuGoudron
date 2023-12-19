<?php
session_start ();

use entity\userEntity;

require_once ('../../../config/DatabaseManager.php');
require_once ('../../../entity/userEntity.php');
require_once ('../../../controller/participationController.php');

$database = DatabaseManager::getInstance();
$user = new userEntity(
    databaseManager: $database
);
if (isset($_SESSION['user_info'])){
    $user->recoverUserData ($_SESSION['user_info']);
} else {
    header ("Location: connexion.php");
    exit();
}

if (isset($_POST['participer'])) {
    $data = [
            "firstName" => $_POST['firstName'],
            "date" => $_POST['date'],
            "sexe" => $_POST['sexe'],
            "adress" => $_POST['adresse'],
            "phone" => (int)$_POST['phone'],
            "email" => $_POST['email'],
            "allergy" => $_POST['allergy'],
            "health" => $_POST['health'],
            "drug" => $_POST['drug'],
            "doctorPhone" => (int)$_POST['doctorPhone'],
            "secu" => (int)$_POST['secu'],
            "autorisation_parentale" => $_POST['autorisation_parentale'],
            "id_utilisateur" => $_POST['id_utilisateur']
    ];

    addParticipation(
        databaseManager: $database,
        data: $data
    );


    header ("Location: reglage.php");
    exit();
}

$field = 'participer.php';
$btnName = "S'inscrire";

?>

<link rel="stylesheet" href="../../styles/partials/participationForm.css"">

<h2>Formulaire d'inscription</h2>

<?php
include "../../../partials/participationForm.php";
?>
