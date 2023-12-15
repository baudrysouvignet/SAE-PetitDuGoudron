<?php
session_start();

use entity\spaceEntity;
use entity\userEntity;

require_once ('../../../config/DatabaseManager.php');
require_once ('../../../entity/userEntity.php');
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

?>


    <form action="forum.php" method="post">
        <label for="title">Question</label>
        <input type="text" name="title" id="title" placeholder="Titre" maxlength="250">

        <label for="description">Description</label>
        <input type="text" name="description" id="description" placeholder="Description">

        <input type="submit" name="addSpace" value="Ajouter un espace">
    </form>

<?php
foreach ($spaceList as $space) {
    $name = 'connexion';
    $field = 'Connectez-vous';

    if (isset($_SESSION['user_info'])) {
        $name = 'post';
        $field = 'Poster';
    }

    $posts = $space->postList;

    /*Html for question part*/
    $html = <<<HTML
    <article class="space">
        <div class="info">
            <h3>{$space->getData()['title']}</h3>
            <p>{$space->getData()['description']}</p>
            <p>{$space->getData()['user']}</p>
        </div> 
        <form action="forum.php" method="post">
            <input type="hidden" name="spaceID" value="{$space->getData()['id']}">
            <label for="comment" style="display: none">Commentaire</label>
            <input type="text" name="comment" id="comment" placeholder="Votre commentaire">
            <input type="submit" name="{$name}" value="{$field}">
        </form>
HTML;

    if (isset($user->loggedInUser[0]['ID_Utilisateur']) && ($space->getData()['userId'] == $user->loggedInUser[0]['ID_Utilisateur'] || $user->loggedInUser[0]['role'] == 'ROLE_ADMIN')) {
        $html .= <<<HTML
        <form action="forum.php" method="post">
            <input type="hidden" name="spaceID" value="{$space->getData ()['id']}">
            <input type="submit" name="spaceDelete" value="Supprimer la question">
        </form>
HTML;
    }

    if (count($posts) > 0) {
        $html .= "<button class='more'>Voir les réponses</button>";
        $html .= "<div class='postsContent'>";

        foreach ($posts as $post) {

            /*HTML for post part*/
            $html .= <<<HTML
            <div class="post">
                <div class="info">
                    <p>{$post->getData()['content']}</p>
                    <p>{$post->getData()['createdAt']}</p>
                    <p>{$post->getData()['user'][0]}</p>
                </div>
            </div>
HTML;
            if (isset($user->loggedInUser[0]['ID_Utilisateur']) && ($post->getData()['user'][1] == $user->loggedInUser[0]['ID_Utilisateur'] || $user->loggedInUser[0]['role'] == 'ROLE_ADMIN')){
                $html .= <<<HTML
                    <form action="forum.php" method="post">
                        <input type="hidden" name="postID" value="{$post->getData()['id']}">
                        <input type="submit" name="delete" value="Supprimer">
                    </form>
HTML;
            }
        }

        $html .= "</div>";
    }

    $html .= "</article>";

    echo $html;
}