<?php
$current_page = basename($_SERVER['PHP_SELF']);
$beforLink = '../';
if ($current_page === 'index.php') {
    $beforLink = 'views/';
}
?>

<div class="container">
    <div class="navbar">
        <div class="menu-toggle">
            <div id="menu-toggle-btn">
                <span></span>
            </div>
        </div>
    </div>
    <div id="nav-container">
        <div class="nav-responsive">
            <div class="col flex">
                <div class="col">
                    <div class="nav-link">
                        <a href="#"><img src="<?= $beforLink.'img/nav/home.svg'?>" alt="Accueil">Accueil</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="#"><img src="<?= $beforLink.'img/nav/forum.svg'?>" alt="Forum">Forum</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="#"><img src="<?= $beforLink.'img/nav/account.svg'?>" alt="Compte">Compte</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="#"><img src="<?= $beforLink.'img/nav/account.svg'?>" alt="Compte">Participer</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
