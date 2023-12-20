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

<?php include ('views/partials/head.php') ?>

<body>
<?php include ('views/partials/nav.php') ?>

<div class="container-hero-section">
    <div class="hero-section">
        <div class="copy">
            <div class="row row-1">
                <div class="h1">Colonie de vacances</div>
            </div>
            <div class="row row-2">
                <div></div>
                <div class="h1">P'tits du goudron</div>
                <div></div>
            </div>
            <div class="row row-3">
                <div></div>
                <div class="span">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INSCRIVEZ VOTRE ENFANT EN LIGNE EN CRÉANT UN COMPTE ET EN COMPLÉTANT LES
                    DOCUMENTS NÉCESSAIRES.
                </div>
            </div>
        </div>
    </div>
</div>
<div class="spline">
    <iframe src="https://my.spline.design/snowman-1dc179c3becd50305885dabd8e7a5e90/" frameborder="0"
            width="100%" height="100%"></iframe>
</div>



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
?>
</body>