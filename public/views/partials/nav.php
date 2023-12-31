<?php
$current_page = basename($_SERVER['PHP_SELF']);
$beforLink = '../';
$beforLinkNav = '';
if ($current_page === 'index.php') {
    $beforLink = 'views/';
    $beforLinkNav = 'views/pages/';
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
                        <a href="<?= $current_page === 'index.php'? '#' : '../../index.php'?>"><img src="<?= $beforLink.'img/nav/home.svg'?>" alt="Accueil">Accueil</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="<?= $beforLinkNav.'forum.php' ?>"><img src="<?= $beforLink.'img/nav/forum.svg'?>" alt="Forum">Forum</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="<?= $beforLinkNav.'connexion.php'?>"><img src="<?= $beforLink.'img/nav/account.svg'?>" alt="Compte">Compte</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                    <div class="nav-link">
                        <a href="<?= $beforLinkNav.'participer.php'?>"><img src="<?= $beforLink.'img/nav/add_notes.svg'?>" alt="Perticiper">Participer</a>
                        <div class="nav-item-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
