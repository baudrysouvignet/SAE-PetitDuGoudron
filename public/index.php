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
<?php include ('views/partials/preloader.php') ?>
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
                <div class="h1">les petits du goudron</div>
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

<div class="container-advantages-section">
    <div class="header-advantages-section">
        <h1>Les petits du goudron <br />c'est quoi ? </h1>
    </div>
    <div class="cards">
        <div class="card">
            <div class="card-cta">
                <p>04 71 04 11 36</p>
            </div>
            <div class="card-fg">
                <p class="case-study">Petits</p>
                <p class="review">
                    Une colonie de vacances exceptionnelle, où l'esprit d'équipe et l'amusement sont au rendez-vous.
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-cta">
                <p>04 71 04 11 36</p>
            </div>
            <div class="card-fg">
                <p class="case-study">Du</p>
                <p class="review">
                    Les participants auront l'opportunité de vivre des moments uniques, entre découvertes ludiques et activités
                    enrichissantes.
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-cta">
                <p>04 71 04 11 36</p>
            </div>
            <div class="card-fg">
                <p class="case-study">Goudron</p>
                <p class="review">
                    Les Petits du Goudron célèbrent l'esprit d'aventure, favorisent l'amitié et créent des souvenirs qui
                    dureront toute une vie.
                </p>
            </div>
        </div>
    </div>
</div>

<h1 class="title-team-section">Notre équipe.</h1>

<div class="container-team-section">

    <div class="overlay">
        <div class="bg-1 nom-wrapper">
            <h1 class="h1-team-section">Gaspard Michel Gaspard Michel Gaspard Michel</h1>
        </div>
        <div class="bg-2 nom-wrapper">
            <h1 class="h1-team-section">Baudry Souvignet Baudry Souvignet Baudry Souvignet</h1>
        </div>
        <div class="bg-3 nom-wrapper">
            <h1 class="h1-team-section">Pierrick Boutte Pierrick Boutte Pierrick Boutte</h1>
        </div>
        <div class="bg-4 nom-wrapper">
            <h1 class="h1-team-section">Mathis Oudin Mathis Oudin Mathis Oudin</h1>
        </div>
    </div>
    <div class="modal">
        <div class="modal-images">
            <div class="img" id="bg-1">
                <img src="views/img/index/team-1.jpg" alt="Gaspard Michel le bg" />
            </div>

            <div class="img" id="bg-2">
                <img src="views/img/index/team-2.jpg" alt="Baudry Souvignet le magnifique" />
            </div>

            <div class="img" id="bg-3">
                <img src="views/img/index/team-3.jpg" alt="Pierrick Boutte le splendide" />
            </div>

            <div class="img" id="bg-4">
                <img src="views/img/index/team-4.jpg" alt="Mathis Oudin le jolie" />
            </div>

        </div>

        <div class="info">
            <p class="name">Gaspard Michel</p>
            <p class="role">aka Atlassian god</p>
        </div>
    </div>
</div>

<div class="cursor">
    <i class="ph-light ph-arrow-left"></i>
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
