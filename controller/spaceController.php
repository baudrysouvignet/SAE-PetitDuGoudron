<?php

use entity\spaceEntity;
use entity\postEntity;
use entity\userEntity;


require_once('../../../entity/spaceEntity.php');
require_once('../../../entity/userEntity.php');
require_once('../../../entity/postEntity.php');


/**
 * @param spaceEntity $space
 * @param array $postList
 * @param DatabaseManager $databaseManager
 * @return void
 *
 * This function is used to set each post in the space
 */
function setEachPost(
    spaceEntity $space,
    array $postList,
    DatabaseManager $databaseManager
):void
{
    foreach ($postList as $post) {
        $user = new userEntity(
            databaseManager: $databaseManager
        );
        $user->recoverUserData(userID: $post['ID_Utilisateur']);

        $post = new postEntity (
            databaseManager: $databaseManager,
            id: $post['ID_Post'],
            content: $post['Content'],
            createdAt: $post['CreatedAt'],
            updatedAt: $post['UpdatedAt'],
            user: $user
        );

        $space->savePost(
            post: $post
        );
    }
}

/**
 * @param DatabaseManager $databaseManager
 * @return array
 *
 * This function is used to recover all the spaces
 */
function recoverAllSpace (
    DatabaseManager $databaseManager
):array
{
    $querySpace = $databaseManager->select ('SELECT * FROM Space order by ID_Space desc');
    $queryPost = $databaseManager->select ('SELECT * FROM Post order by CreatedAt desc');

    $spaceList = [];

    foreach ($querySpace as $space) {
        $user = new userEntity(
            databaseManager: $databaseManager
        );
        $user->recoverUserData(userID: $space['ID_Utilisateur']);

        $spaceList[$space['ID_Space']] = new spaceEntity (
            databaseManager: $databaseManager,
            id: $space['ID_Space'],
            title: $space['Title'],
            description: $space['Description'],
            user: $user
        );

        $postList = array_filter($queryPost, function($post) use ($space) {
            return $post['ID_Forum'] == $space['ID_Space'];
        });

        setEachPost (
            databaseManager: $databaseManager,
            space: $spaceList[$space['ID_Space']],
            postList: $postList
        );

    }

    return $spaceList;
}

/**
 * @param spaceEntity $space
 * @param string $comment
 * @param userEntity $user
 * @return void
 *
 * This function is used to add a post on a space
 */
function addPostOnSpace (
    spaceEntity $space,
    string $comment,
    userEntity $user
):void
{
    if ($user->loggedInUser[0]['role'] != 'ROLE_BANNED') {
        $space->addPost(
            user: $user,
            comment: $comment
        );
    }
}
