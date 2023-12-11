<?php
require_once '../entity/userEntity.php';
use entity\userEntity;

/**
 * @param userEntity $user
 * @param string $mail
 * @param string $password
 * @return void
 *
 * Function to connect a user after form validation and set session.
 */
function connection(
    userEntity $user,
    string $mail,
    string $password
): void
{
    $isConnect = $user->connect(
        mail: $mail,
        password: $password
    );

    if ($isConnect) {
        $_SESSION['user_info'] = $isConnect[0]['ID_Utilisateur'];
    }
}

/**
 * @param userEntity $user
 * @param DatabaseManager $database
 * @param string $mail
 * @param string $password
 * @return bool
 *
 * Function to register a user after form validation
 */
function inscription(
    userEntity $user,
    DatabaseManager $database,
    string $mail,
    string $password
): bool
{
    $isMailUsed = $database->select(
        request: "SELECT ID_Utilisateur FROM User WHERE mail = :mail",
        param: [
            "mail" => $mail,
        ]
    );

    if (empty($isMailUsed)){
        $user->register (
            mail: $mail,
            password: $password
        );
        return true;
    }
    return false;
}

