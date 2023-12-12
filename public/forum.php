<?php
session_start();

use entity\userEntity;

require_once '../config/DatabaseManager.php';
require_once '../entity/userEntity.php';
require_once '../controller/spaceController.php';


$database = DatabaseManager::getInstance();
$user = new userEntity(
    databaseManager: $database
);

$user = new userEntity(
    databaseManager: $database
);
if (isset($_SESSION['user_info'])){
    $user->recoverUserData ($_SESSION['user_info']);
}
$spaceList = recoverAllSpace (databaseManager: $database);

if (isset($_POST['post'])) {
    addPostOnSpace (
        space: $spaceList[$_POST['spaceID']],
        comment: $_POST['comment'],
        user: $user
    );
    header ("Location: forum.php");
    exit();
} else if (isset($_POST['connexion'])) {
    header ("Location: connexion.php");
    exit();
}

if (isset($_POST['delete'])) {
    $postIdToDelete = $_POST['postID'];

    foreach ($spaceList as $space) {
        $posts = $space->postList;
        foreach ($posts as $post) {
            if ($post->getData()['id'] == $postIdToDelete) {
                $post->deletePost();
                break 2;
            }
        }
    }
    header ("Location: forum.php");
    exit();
}

?>


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
            if ($post->getData()['user'][1] == $user->loggedInUser[0]['ID_Utilisateur']){
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