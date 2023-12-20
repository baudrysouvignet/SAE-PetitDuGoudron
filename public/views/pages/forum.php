<?php
session_start();

use entity\spaceEntity;
use entity\userEntity;

require_once ('../../../config/DatabaseManager.php');
require_once ('../../../entity/userEntity.php');
require_once ('../../../controller/userController.php');
require_once ('../../../entity/spaceEntity.php');
require_once ('../../../controller/spaceController.php');

//----------------
// User management
//----------------

$database = DatabaseManager::getInstance();

$user = new userEntity(
    databaseManager: $database
);
if (isset($_SESSION['user_info'])){
    $user->recoverUserData ($_SESSION['user_info']);
}

//----------------
// Space management
//----------------

$spaceList = recoverAllSpace (databaseManager: $database);

if (isset($_POST['addSpace'])) {
    $space = new spaceEntity (
        databaseManager: $database,
        id: null,
        title: $_POST['title'],
        description: $_POST['description'],
        user: $user
    );

    $space->saveSpace();
    header ("Location: forum.php");
    exit();
}

if (isset($_POST['spaceDelete'])) {
    foreach ($spaceList as $space) {
        if ($space->getData()['id'] == $_POST['spaceID']) {
            $space->deleteSpace();
            break;
        }
    }
    header ("Location: forum.php");
    exit();
}

//----------------
// Post management
//----------------

// Add post in space and bddd
if (isset($_POST['post'])) {
    addPostOnSpace (
        space: $spaceList[$_POST['spaceID']],
        comment: $_POST['comment'],
        user: $user
    );
} else if (isset($_POST['connexion'])) {
    header ("Location: connexion.php");
    exit();
}

// Delete post
if (isset($_POST['delete'])) {
    foreach ($spaceList as $space) {
        $posts = $space->postList;
        foreach ($posts as $post) {
            if ($post->getData()['id'] == $_POST['postID']) {

                $space->deletePost(
                    post: $post
                );
                break 2;
            }
        }
    }
}

// Ban user

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

?>

<!doctype html>
<html lang="fr">
<head>
    <?php include_once ('../partials/head.php'); ?>
    <link rel="stylesheet" href="../../styles/pages/forum.css">
    <title>Forum</title>
</head>
<body>
<?php include ('../partials/nav.php'); ?>

<div class="forum">
    <h1>Votre voix compte ici !</h1>
    <p>Rencontrez la communauté des Petits du Goudron et partagez vos expériences</p>
    <form action="forum.php" method="post">
        <label for="title">Question</label>
        <input type="text" name="title" id="title" placeholder="Titre" maxlength="250">

        <label for="description">Description</label>
        <input type="text" name="description" id="description" placeholder="Description">

        <input class="button" type="submit" name="addSpace" value="Ajouter un espace">

        <script src="../../scripts/pages/forum.js" defer></script>
    </form>
</div>

<main>
<?php
foreach ($spaceList as $space) {
    $name = 'connexion';
    $field = 'Connectez-vous';

    if (isset($_SESSION['user_info']) && $user->loggedInUser[0]['role'] == 'ROLE_BANNED') {
        $name = 'banni';
        $field = 'Vous êtes banni';
    } else if (isset($_SESSION['user_info'])) {
        $name = 'post';
        $field = 'Poster';
    }

    $posts = $space->postList;

    $nameAccount = strstr($space->getData()['user'], '@', true);

    /*Html for question part*/
    $html = <<<HTML
    <article class="space">
        <div class="post">
            <div class="info">
                <div class="nameInfo">
                    <img src="https://images.unsplash.com/photo-1695653422853-3d8f373fb434?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxfHx8ZW58MHx8fHx8" alt="" srcset="">
                    <p>@{$nameAccount}</p>
                </div>
                <div class="contentInfo">
                    <h3>{$space->getData()['title']}</h3>
                    <p>{$space->getData()['description']}</p>
                </div>
            </div> 
            <form class="formAnswer" action="forum.php" method="post">
                <input type="hidden" name="spaceID" value="{$space->getData()['id']}">
                <label for="comment" style="display: none">Commentaire</label>
                <input type="text" name="comment" id="comment" placeholder="Votre commentaire">
                <input class="button" type="submit" name="{$name}" value="{$field}">
            </form>
            <div class="management">
    HTML;

        if (isset($user->loggedInUser[0]['ID_Utilisateur']) && ($space->getData()['userId'] == $user->loggedInUser[0]['ID_Utilisateur'] || $user->loggedInUser[0]['role'] == 'ROLE_ADMIN')) {
            $html .= <<<HTML
            <form action="forum.php" method="post">
                <input type="hidden" name="spaceID" value="{$space->getData ()['id']}">
                <input class="" type="submit" name="spaceDelete" value="Supprimer">
            </form>
HTML;
    }

    if (count($posts) > 0) {
        $idSpace= $space->getData()['id'];
        $html .= "<button name=".$idSpace." class='more input'>Voir les réponses (".count($posts).")</button></div></div>";
        $html .= "<div class='postsContent' id='.$idSpace.'>";

        foreach ($posts as $post) {
            $userPost = new userEntity(
                databaseManager: $database
            );
            $userPost->recoverUserData(userID: $post->getData()['user'][1]);

            $banFields = '';
            if (isset($_SESSION['user_info']) && $user->loggedInUser[0]['role'] == 'ROLE_ADMIN' && $userPost->loggedInUser[0]['role'] != 'ROLE_ADMIN') {
                if ($userPost->loggedInUser[0]['role'] != 'ROLE_BANNED') {
                    $banFields = <<< HTML
                    <form action="forum.php" method="post">
                        <input type="hidden" name="userId" value="{$post->getData()['user'][1]}">
                        <input type="submit" name="banMan" value="Bannir">
                    </form>
HTML;
                } else if ($userPost->loggedInUser[0]['role'] == 'ROLE_BANNED') {
                    $banFields = <<< HTML
                    <form action="forum.php" method="post">
                        <input type="hidden" name="userId" value="{$post->getData()['user'][1]}">
                        <input type="submit" name="unBanMan" value="Débanir">
                    </form>
HTML;
                }
            }


            $nameAccount = strstr($post->getData()['user'][0], '@', true);

            if ($userPost->loggedInUser[0]['role'] == 'ROLE_ADMIN') {
                $nameAccount = $nameAccount . ' (Admin)';
            }

                /*HTML for post part*/
            $html .= <<<HTML
            <div class="post">
                <div class="info">
                    <div class="nameInfo">
                        <img src="https://images.unsplash.com/photo-1695653422853-3d8f373fb434?w=800&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxfHx8ZW58MHx8fHx8" alt="" srcset="">
                        <p>@{$nameAccount} - {$post->getData()['createdAt']}</p>
                    </div>
                    <div class="contentInfo">
                        <p>{$post->getData()['content']}</p>
                    </div>
                </div>
            <div class="management">
                {$banFields}
HTML;
            if (isset($user->loggedInUser[0]['ID_Utilisateur']) && ($post->getData()['user'][1] == $user->loggedInUser[0]['ID_Utilisateur'] || $user->loggedInUser[0]['role'] == 'ROLE_ADMIN')){
                $html .= <<<HTML
                    <form action="forum.php" method="post">
                        <input type="hidden" name="postID" value="{$post->getData()['id']}">
                        <input type="submit" name="delete" value="Supprimer">
                    </form>
HTML;
            }
            $html .= "</div></div>";
        }

        $html .= "</div>";
    } else {
        $html .= "</div></div>";
    }

    $html .= "</article>";

    echo $html;
}?>
</main>

</body>
</html>

