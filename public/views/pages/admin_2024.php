<?php
session_start ();

use entity\userEntity;
require_once ('../../../entity/userEntity.php');
require_once ('../../../controller/userController.php');
require_once ('../../../config/DatabaseManager.php');
require_once ('../../../controller/participationController.php');
require_once ('../../../controller/eventCalendar.php');

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
        
        <form method="post" action="admin_2024.php?field=inscManagement">
            <input type="hidden" name="participationID" value="{$particpation['ID_Insription']}">
            <button class="button" type="submit" name="participationpdf">infos</button>
            <button class="button secondary" type="submit" name="participationdelet">supprimer</button>
        </form>
    </div>
HTML;

}

/*Ban USer*/

if (isset($_POST['banMan'])) {
    banUser (
        database: $database,
        userID: (int)$_POST['userId']
    );
} elseif (isset($_POST['unBanMan'])) {
    unBanUser (
        database: $database,
        userID: (int)$_POST['userId']
    );
}

$users = recoverAllUser (
    database: $database
);


$dispayUser = "";
foreach ($users as $uniqueUser) {
    if ($uniqueUser->loggedInUser[0]['role'] == 'ROLE_ADMIN') {
        continue;
    }

    $btn = "<button class='button' type='submit' name='banMan'>bannir</button>";
    if ($uniqueUser->loggedInUser[0]['role'] == 'ROLE_BANNED') {
        $btn = "<button class='button' type='submit' name='unBanMan'>débannir</button>";
    }


    $dispayUser .= <<<HTML
    <div>
        <p>{$uniqueUser->loggedInUser[0]['mail']}</p>
        <form method="post" action='admin_2024.php?field=compteManagement'>
            <input  type="hidden" name="userId" value="{$uniqueUser->loggedInUser[0]['ID_Utilisateur']}">
            {$btn}
        </form>
    </div>
HTML;
}

/*Calendrier*/
$events = recoverAllEvents(
    database: $database
);

$dispayEvent = "";
foreach ($events as $event) {
    $dispayEvent .= <<<HTML
    <div>
        <p>{$event['Time']}</p>
        <p>{$event['Title']}</p>
        <p>{$event['Description']}</p>
    </div>
HTML;
}

if (isset($_POST['eventEdit'])) {
    addOrEditEvent (
        database: $database,
        title: $_POST['eventTitle'],
        time: $_POST['eventSelect'],
        description: $_POST['eventDescription']
    );
    header("Location: admin_2024.php");
    exit();
}

if (isset($_POST['eventDelete'])) {
    deleteEvent (
        database: $database,
        time: $_POST['eventSelect']
    );
    header("Location: admin_2024.php");
    exit();
}

?>


<!doctype html>
<html lang="fr">
<head>
    <?php include '../partials/head.php'?>
    <link rel="stylesheet" href="../../styles/pages/admin.css">
    <script src="../../scripts/pages/admin.js" defer></script>
    <title>PDG - Admin</title>
</head>
<body>

    <?php include '../partials/nav.php'?>
    <h1 class="title">Compte administrateur</h1>
    <main>
        <nav class="navManagement">
            <a id="inscManagement" class="active">
                <p>Les Inscriptions</p>
                <img src="../img/arrow_back.svg" alt="">
            </a>
            <a id="compteManagement">
                <p>Les Comptes</p>
                <img src="../img/arrow_back.svg" alt="">
            </a>
            <a id="calManagement">
                <p>Le Calendrier</p>
                <img src="../img/arrow_back.svg" alt="">
            </a>
        </nav>

        <div class="content">
            <div class="part activate">
                <h2>Les inscrits</h2>
                <?= $displayParticipation ?>
            </div>

            <div class="user">
                <h2>Les utilisateurs</h2>
                <?= $dispayUser ?>
            </div>

            <div class="cal">
                <h2>Les évènements</h2>

                <form action="admin_2024.php?field=calManagement" method="post">
                    <label for="eventSelect" class="labelInfo">Selectionner l'evenement à modifier</label>
                    <input type="date" name="eventSelect" id="eventSelect" placeholder="Date de l'event" min="2024-09-01" value="2024-09-01" required>


                    <label for="eventTitle">Titre</label>
                    <input type="text" name="eventTitle" id="eventTitle" placeholder="Nouveau titre" required>

                    <label for="eventDate">Description</label>
                    <input type="text" name="eventDescription" id="eventDate" placeholder="Nouvelle description" required>

                    <button type="submit" name="eventEdit" class="button">Modifier/Ajouter</button>
                    <button type="submit" name="eventDelete" class="button secondary">Supprimer</button>
                </form>

                <?= $dispayEvent ?>
            </div>
        </div>


    </main>
    <?php include '../partials/footer.php'?>
</body>
</html>
