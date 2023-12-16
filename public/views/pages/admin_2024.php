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
        
        <form method="post" action="admin_2024.php">
            <input type="hidden" name="participationID" value="{$particpation['ID_Insription']}">
            <button type="submit" name="participationpdf">infos</button>
            <button type="submit" name="participationdelet">supprimer</button>
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

    $btn = "<button type='submit' name='banMan'>bannir</button>";
    if ($uniqueUser->loggedInUser[0]['role'] == 'ROLE_BANNED') {
        $btn = "<button type='submit' name='unBanMan'>débannir</button>";
    }


    $dispayUser .= <<<HTML
    <div>
        <p>{$uniqueUser->loggedInUser[0]['mail']}</p>
        <form method="post" action="admin_2024.php">
            <input type="hidden" name="userId" value="{$uniqueUser->loggedInUser[0]['ID_Utilisateur']}">
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
        <h3>{$event['Title']}</h3>
        <p>{$event['Time']}</p>
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


<div>
    <h1>Les inscrits</h1>
    <?= $displayParticipation ?>
</div>

<div>
    <h1>Les utilisateurs</h1>
    <?= $dispayUser ?>
</div>

<div>
    <h1>Les évènements</h1>
    <?= $dispayEvent ?>
    <form action="admin_2024.php" method="post">
        <label for="eventSelect">Selectionner l'evenement à modifier</label>
        <input type="date" name="eventSelect" id="eventSelect" placeholder="Date de l'event" min="2024-09-01" value="2024-09-01" required>


        <label for="eventTitle">Titre</label>
        <input type="text" name="eventTitle" id="eventTitle" placeholder="Nouveau titre" required>

        <label for="eventDate">Date</label>
        <input type="text" name="eventDescription" id="eventDate" placeholder="Nouvelle description" required>

        <button type="submit" name="eventEdit">Modifier/Ajouter</button>
        <button type="submit" name="eventDelete">Supprimer</button>
    </form>
</div>