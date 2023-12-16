<?php
session_start ();
require_once ('../controller/eventCalendar.php');
require_once ('../config/DatabaseManager.php');
?>


<?php
$database = DatabaseManager::getInstance();

$calendar = recoverAllEvents(
    database: $database
);
?>


<h1>Calendar</h1>
<?php
foreach ($calendar as $event) {
    echo <<<HTML
    <div>
        <p>{$event['Title']}</p>
        <p>{$event['Time']}</p>
        <p>{$event['Description']}</p>
    </div>
HTML;
}