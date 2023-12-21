<?php
$current_page = basename($_SERVER['PHP_SELF']);
$beforLink = '../../';
if ($current_page === 'index.php') {
    $beforLink = '';
}
?>

<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="stylesheet" href="<?= $beforLink.'styles/global.css'?>">
<link rel="stylesheet" href="<?= $beforLink.'styles/partials/styles_preloader.css'?>">
<link rel="stylesheet" href="<?= $beforLink.'styles/partials/styles_nav.css'?>">
<link rel="stylesheet" href="<?= $beforLink.'styles/partials/styles_footer.css'?>">
<link rel="stylesheet" href="<?= $beforLink.'styles/pages/styles_index.css'?>">

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
<script src="https://unpkg.com/split-type"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.3/TextPlugin.min.js"></script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<script src="<?= $beforLink.'scripts/partials/preloader.js'?>"></script>
<script src="<?= $beforLink.'scripts/partials/nav.js'?>"></script>
<script src="<?= $beforLink.'scripts/pages/index.js'?>"></script>




